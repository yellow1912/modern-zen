<?php
/**
 * functions_prices
 *
 * @package functions
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: functions_prices.php 6905 2007-09-01 20:05:11Z ajeh $
 */

////
//get specials price or sale price


////
// Display Price Retail
// Specials and Tax Included
  function ri_get_products_display_price($products_id, $info_page = array(), $is_pro_info = false) {
    global $db, $currencies;
	
    $free_tag = "";
    $call_tag = "";

// 0 = normal shopping
// 1 = Login to shop
// 2 = Can browse but no prices
    // verify display of prices
      switch (true) {
        case (CUSTOMERS_APPROVAL == '1' and $_SESSION['customer_id'] == ''):
        // customer must be logged in to browse
        return '';
        break;
        case (CUSTOMERS_APPROVAL == '2' and $_SESSION['customer_id'] == ''):
        // customer may browse but no prices
        return TEXT_LOGIN_FOR_PRICE_PRICE;
        break;
        case (CUSTOMERS_APPROVAL == '3' and TEXT_LOGIN_FOR_PRICE_PRICE_SHOWROOM != ''):
        // customer may browse but no prices
        return TEXT_LOGIN_FOR_PRICE_PRICE_SHOWROOM;
        break;
        case ((CUSTOMERS_APPROVAL_AUTHORIZATION != '0' and CUSTOMERS_APPROVAL_AUTHORIZATION != '3') and $_SESSION['customer_id'] == ''):
        // customer must be logged in to browse
        return TEXT_AUTHORIZATION_PENDING_PRICE;
        break;
        case ((CUSTOMERS_APPROVAL_AUTHORIZATION != '0' and CUSTOMERS_APPROVAL_AUTHORIZATION != '3') and $_SESSION['customers_authorization'] > '0'):
        // customer must be logged in to browse
        return TEXT_AUTHORIZATION_PENDING_PRICE;
        break;
        default:
        // proceed normally
        break;
      }

// show case only
    if (STORE_STATUS != '0') {
      if (STORE_STATUS == '1') {
        return '';
      }
    }

    // $new_fields = ', product_is_free, product_is_call, product_is_showroom_only';
    $product_check = $db->Execute("select products_tax_class_id, products_price, products_priced_by_attribute, product_is_free, product_is_call, products_type from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'" . " limit 1");

    // no prices on Document General
    if ($product_check->fields['products_type'] == 3) {
      return '';
    }

    $show_display_price = '';
    $display_normal_price = zen_get_products_base_price($products_id);
    $display_special_price = zen_get_products_special_price($products_id, true);
    $display_sale_price = zen_get_products_special_price($products_id, false);

    $show_sale_discount = '';
    if (SHOW_SALE_DISCOUNT_STATUS == '1' and ($display_special_price != 0 or $display_sale_price != 0)) {
      if ($display_sale_price) {
        if (SHOW_SALE_DISCOUNT == 1) {
          if ($display_normal_price != 0) {
            $show_discount_amount = number_format(100 - (($display_sale_price / $display_normal_price) * 100),SHOW_SALE_DISCOUNT_DECIMALS);
          } else {
            $show_discount_amount = '';
          }
          $show_sale_discount = '<div class="productPriceDiscount">' . PRODUCT_PRICE_DISCOUNT_PREFIX . '<span>' . $show_discount_amount . PRODUCT_PRICE_DISCOUNT_PERCENTAGE . '</span></div>';

        } else {
          $show_sale_discount = '<div class="productPriceDiscount">' . PRODUCT_PRICE_DISCOUNT_PREFIX . '<span>' . $currencies->display_price(($display_normal_price - $display_sale_price), zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . PRODUCT_PRICE_DISCOUNT_AMOUNT . '</span></div';
        }
      } else {
        if (SHOW_SALE_DISCOUNT == 1) {
          $show_sale_discount = '<div class="productPriceDiscount">' . PRODUCT_PRICE_DISCOUNT_PREFIX . '<span>' . number_format(100 - (($display_special_price / $display_normal_price) * 100),SHOW_SALE_DISCOUNT_DECIMALS) . PRODUCT_PRICE_DISCOUNT_PERCENTAGE . '</span></div>';
        } else {
          $show_sale_discount = '<div class="productPriceDiscount">' . PRODUCT_PRICE_DISCOUNT_PREFIX . '<span>' . $currencies->display_price(($display_normal_price - $display_special_price), zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . PRODUCT_PRICE_DISCOUNT_AMOUNT . '</span></div>';
        }
      }
    }

    if ($display_special_price) {
      $show_normal_price = '<div class="normalprice">' . $info_page['normalprice'] . "<span>" . $currencies->display_price($display_normal_price, zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . "</span></div>";
      if ($display_sale_price && $display_sale_price != $display_special_price) {
        $show_special_price = '<div class="productSpecialPriceSale">' . $currencies->display_price($display_special_price, zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . '</div>';
        if ($product_check->fields['product_is_free'] == '1') {
          $show_sale_price = '<div class="productSalePrice">' . PRODUCT_PRICE_SALE . '<span><s>' . $currencies->display_price($display_sale_price, zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . '</s></span>' . '</div>';
        } else {
          $show_sale_price = '<div class="productSalePrice">' . PRODUCT_PRICE_SALE . '<span>' . $currencies->display_price($display_sale_price, zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . '</span></div>';
        }
      } else {
        if ($product_check->fields['product_is_free'] == '1') {
          $show_special_price = '<div class="productSpecialPrice">' . $info_page['productSpecialPrice'] . '<span><s>' . $currencies->display_price($display_special_price, zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . '</s></span>' . '</div>';
        } else {
          $show_special_price = '<div class="productSpecialPrice">' . $info_page['productSpecialPrice'] . '<span>' . $currencies->display_price($display_special_price, zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . '</span></div>';
        }
        $show_sale_price = '';
      }
    } else {
      if ($display_sale_price) {
        $show_normal_price = '<div class="normalprice">' . $info_page['normalprice'] . '<span>' . $currencies->display_price($display_normal_price, zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . '</span></div>';
        $show_special_price = '';
        $show_sale_price = '<div class="productSalePrice">' . PRODUCT_PRICE_SALE . '<span>' . $currencies->display_price($display_sale_price, zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . '</span></div>';
      } else {
        if ($product_check->fields['product_is_free'] == '1') {
          $show_normal_price = '<div class="productsIsFree">' . $info_page['productsIsFree'] . '<span><s>' . $currencies->display_price($display_normal_price, zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . '</s></span></div>';
        } else {
          $show_normal_price = "<div class='itemNormalPrice'>" . $info_page['itemNormalPrice'] . "<span>" . $currencies->display_price($display_normal_price, zen_get_tax_rate($product_check->fields['products_tax_class_id'])) . "</span></div>";
        }
        $show_special_price = '';
        $show_sale_price = '';
      }
    }

/*    if ($display_normal_price == 0) {
      // don't show the $0.00
      $final_display_price = $show_normal_price . $show_special_price . $show_sale_price . $show_sale_discount;
    } else {
      $final_display_price = $show_normal_price . $show_special_price . $show_sale_price . $show_sale_discount;
    }*/
	
	if(!$is_pro_info){$show_sale_discount = '';}
	
    $final_display_price = $show_normal_price . $show_special_price . $show_sale_price . $show_sale_discount;
    
    // If Free, Show it
    if ($product_check->fields['product_is_free'] == '1') {
      if (OTHER_IMAGE_PRICE_IS_FREE_ON=='0') {
        $free_tag = '<div class="priceIsFree">' . PRODUCTS_PRICE_IS_FREE_TEXT . '</div>';
      } else {
        $free_tag = '<div class="priceIsFree">' . zen_image(DIR_WS_TEMPLATE_IMAGES . OTHER_IMAGE_PRICE_IS_FREE, PRODUCTS_PRICE_IS_FREE_TEXT) . '</div>';
      }
    }

    // If Call for Price, Show it
    if ($product_check->fields['product_is_call']) {
      if (PRODUCTS_PRICE_IS_CALL_IMAGE_ON=='0') {
        $call_tag = '<div class="callForPrice"><a href="' . zen_href_link(FILENAME_CONTACT_US) . '">' . PRODUCTS_PRICE_IS_CALL_FOR_PRICE_TEXT . '</a></div>';
      } else {
        $call_tag = '<div class="callForPrice"><a href="' . zen_href_link(FILENAME_CONTACT_US) . '">' . zen_image(DIR_WS_TEMPLATE_IMAGES . OTHER_IMAGE_CALL_FOR_PRICE, PRODUCTS_PRICE_IS_CALL_FOR_PRICE_TEXT) . '</a></div>';
      }
    }

    return $final_display_price . $free_tag . $call_tag . '<div class="clearBoth"></div>';
  }

////




////
// Return a products quantity minimum and units display
  function ri_get_products_quantity_min_units_display($product_id, $include_break = true, $shopping_cart_msg = false) {
    $check_min = zen_get_products_quantity_order_min($product_id);
    $check_units = zen_get_products_quantity_order_units($product_id);

    $the_min_units='';

    if ($check_min != 1 or $check_units != 1) {
      if ($check_min != 1) {
        $the_min_units .= PRODUCTS_QUANTITY_MIN_TEXT_LISTING . '&nbsp;' . $check_min;
      }
      if ($check_units != 1) {
        $the_min_units .= ($the_min_units ? ' ' : '' ) . PRODUCTS_QUANTITY_UNIT_TEXT_LISTING . '&nbsp;' . $check_units;
      }

// don't check for mixed if not attributes
      $chk_mix = zen_get_products_quantity_mixed((int)$product_id);
      if ($chk_mix != 'none') {
        if (($check_min > 0 or $check_units > 0)) {
          if ($include_break == true) {
            $the_min_units .= '<br />' . ($shopping_cart_msg == false ? TEXT_PRODUCTS_MIX_OFF : TEXT_PRODUCTS_MIX_OFF_SHOPPING_CART);
          } else {
            $the_min_units .= '&nbsp;&nbsp;' . ($shopping_cart_msg == false ? TEXT_PRODUCTS_MIX_OFF : TEXT_PRODUCTS_MIX_OFF_SHOPPING_CART);
          }
        } else {
          if ($include_break == true) {
            $the_min_units .= '<br />' . ($shopping_cart_msg == false ? TEXT_PRODUCTS_MIX_ON : TEXT_PRODUCTS_MIX_ON_SHOPPING_CART);
          } else {
            $the_min_units .= '&nbsp;&nbsp;' . ($shopping_cart_msg == false ? TEXT_PRODUCTS_MIX_ON : TEXT_PRODUCTS_MIX_ON_SHOPPING_CART);
          }
        }
      }
    }

    // quantity max
    $check_max = zen_get_products_quantity_order_max($product_id);

    if ($check_max != 0) {
      if ($include_break == true) {
        $the_min_units .= ($the_min_units != '' ? '<br />' : '') . PRODUCTS_QUANTITY_MAX_TEXT_LISTING . '&nbsp;' . $check_max;
      } else {
        $the_min_units .= ($the_min_units != '' ? '&nbsp;&nbsp;' : '') . PRODUCTS_QUANTITY_MAX_TEXT_LISTING . '&nbsp;' . $check_max;
      }
    }
	
	if('' != $the_min_units){
		$the_min_units = '<div class="minUnits">' . $the_min_units . '</div>';
	}
	
    return $the_min_units;
  }
	
	
	
  require_once(DIR_WS_FUNCTIONS . 'functions_prices.php');
?>