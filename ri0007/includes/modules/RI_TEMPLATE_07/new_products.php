<?php
/**
 * new_products.php module
 *
 * @package modules
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: new_products.php 6424 2007-05-31 05:59:21Z ajeh $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

//get function index_listing
include_once(DIR_WS_FUNCTIONS.'extra_functions/index_listing.php');

// initialize vars
$categories_products_id_list = '';
$list_of_products = '';
$new_products_query = '';

$display_limit = zen_get_new_date_range();

if ( (($manufacturers_id > 0 && $_GET['filter_id'] == 0) || $_GET['music_genre_id'] > 0 || $_GET['record_company_id'] > 0) || (!isset($new_products_category_id) || $new_products_category_id == '0') ) {
  $new_products_query = "select *
                           from " . TABLE_PRODUCTS_DESCRIPTION . " pd, ". TABLE_PRODUCTS . " p 
                           LEFT JOIN " . TABLE_MANUFACTURERS . " m ON (p.manufacturers_id = m.manufacturers_id)
                           where p.products_id = pd.products_id
                           and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                           and   p.products_status = 1 " . $display_limit;
} else {
  // get all products and cPaths in this subcat tree
  $productsInCategory = zen_get_categories_products_list( (($manufacturers_id > 0 && $_GET['filter_id'] > 0) ? zen_get_generated_category_path_rev($_GET['filter_id']) : $cPath), false, true, 0, $display_limit);

  if (is_array($productsInCategory) && sizeof($productsInCategory) > 0) {
    // build products-list string to insert into SQL query
    foreach($productsInCategory as $key => $value) {
      $list_of_products .= $key . ', ';
    }
    $list_of_products = substr($list_of_products, 0, -2); // remove trailing comma

    $new_products_query = "select *
                           from " . TABLE_PRODUCTS_DESCRIPTION . " pd, ". TABLE_PRODUCTS . " p 
                           LEFT JOIN " . TABLE_MANUFACTURERS . " m ON (p.manufacturers_id = m.manufacturers_id)
                           where p.products_id = pd.products_id
                           and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                           and p.products_status = 1
                           and p.products_id in (" . $list_of_products . ")";  
  }
}

if (isset($new_products_category_id) && $new_products_category_id != 0) {
  $category_title = zen_get_categories_name((int)$new_products_category_id);
  $index_title = '<h2 class="centerBoxHeading">' . sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')) . ($category_title != '' ? ' - ' . $category_title : '' ) . '</h2>';
} else {
  $index_title = '<h2 class="centerBoxHeading">' . sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')) . '</h2>';
}
if(!empty($new_products_query)){
	if(INDEX_NEW_USE_PRODUCT_LISTING == '2')
		extract(index_listing($new_products_query, MAX_DISPLAY_NEW_PRODUCTS, SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS, IMAGE_PRODUCT_NEW_WIDTH, IMAGE_PRODUCT_NEW_HEIGHT, $new_products_category_id, TABLE_HEADING_NEW_PRODUCTS,'zc_show_new_products'));
	elseif(INDEX_NEW_USE_PRODUCT_LISTING == '3'){
		$listing_sql = $new_products_query;
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
		$productListingId = 'newProducts';
		$is_index_listing = true;
		$max_index_listing = MAX_DISPLAY_NEW_PRODUCTS;
		require($template->get_template_dir('tpl_modules_product_listing.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_product_listing.php');
		$is_index_listing = false;
	}
	elseif(INDEX_NEW_USE_PRODUCT_LISTING == '1'){
		if ($new_products_query != '') $new_products = $db->ExecuteRandomMulti($new_products_query, MAX_DISPLAY_NEW_PRODUCTS);
		
		$row = 0;
		$col = 0;
		$list_box_contents = array();
		
		$num_products_count = ($new_products_query == '') ? 0 : $new_products->RecordCount();
		
		// show only when 1 or more
		if ($num_products_count > 0) {
		  if ($num_products_count < SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS || SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS == 0 ) {
		    $col_width = floor(100/$num_products_count);
		  } else {
		    $col_width = floor(100/SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS);
		  }
		
		  while (!$new_products->EOF) {
		    $products_price = ri_get_products_display_price($new_products->fields['products_id']);
		    if (!isset($productsInCategory[$new_products->fields['products_id']])) $productsInCategory[$new_products->fields['products_id']] = zen_get_generated_category_path_rev($new_products->fields['master_categories_id']);
		
		    $list_box_contents[$row][$col] = array('params' => 'class="centerBoxContentsNew centeredContent back"' . ' ' . 'style="width:' . $col_width . '%;"',
		    'text' => (($new_products->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '<a href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'cPath=' . $productsInCategory[$new_products->fields['products_id']] . '&products_id=' . $new_products->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $new_products->fields['products_image'], $new_products->fields['products_name'], IMAGE_PRODUCT_NEW_WIDTH, IMAGE_PRODUCT_NEW_HEIGHT) . '</a><br />') . '<a href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'cPath=' . $productsInCategory[$new_products->fields['products_id']] . '&products_id=' . $new_products->fields['products_id']) . '">' . $new_products->fields['products_name'] . '</a><br />' . $products_price);
		
		    $col ++;
		    if ($col > (SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS - 1)) {
		      $col = 0;
		      $row ++;
		    }
		    $new_products->MoveNextRandom();
		  }
		
		  if ($new_products->RecordCount() > 0) {
		    $zc_show_new_products = true;
		  }
		}
	}
}