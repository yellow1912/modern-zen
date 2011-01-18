<?php
/**
 * Featured Products
 *
 * @package page
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header_php.php 6912 2007-09-02 02:23:45Z drbyte $
 */
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
$breadcrumb->add(NAVBAR_TITLE);
// display order dropdown
$disp_order_default = PRODUCT_FEATURED_LIST_SORT_DEFAULT;

require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_LISTING_DISPLAY_ORDER));

$featured_products_array = array();

$listing_sql = "SELECT p.products_id, p.products_type, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, p.products_date_added, m.manufacturers_name, p.products_model, p.products_quantity, p.products_weight, p.product_is_call,
                                  p.product_is_always_free_shipping, p.products_qty_box_status,
                                  p.master_categories_id, m.manufacturers_id
                                  FROM (" . TABLE_PRODUCTS . " p
                                  LEFT JOIN " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " .
TABLE_PRODUCTS_DESCRIPTION . " pd
                                  LEFT JOIN " . TABLE_FEATURED . " f on pd.products_id = f.products_id )
                                  WHERE p.products_status = 1 and p.products_id = f.products_id and f.status = 1
                                  AND p.products_id = pd.products_id and pd.language_id = :languagesID " .
$order_by;

$listing_sql = $db->bindVars($listing_sql, ':languagesID', $_SESSION['languages_id'], 'integer');
$featured_products_split = new splitPageResults($featured_products_query_raw, MAX_DISPLAY_PRODUCTS_FEATURED_PRODUCTS);

//check to see if we are in normal mode ... not showcase, not maintenance, etc
  $show_submit = zen_run_normal();

$define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
  'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
  'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
  'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
  'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
  'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
  'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE);

  /*                         ,
  'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);
  */
  asort($define_list);
  reset($define_list);
  $column_list = array();
  foreach ($define_list as $key => $value)
  {
    if ($value > 0) $column_list[] = $key;
  }
?>