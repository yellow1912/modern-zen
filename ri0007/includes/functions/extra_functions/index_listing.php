<?php

function index_listing($products_query, $max_display, $show_product_info, $image_product_new_width, $image_product_new_height, $products_category_id, $table_heading_new_products, $show_product){
	global $db;
	if ($products_query != '') $products = $db->ExecuteRandomMulti($products_query, $max_display);
	$row = 0;
	$col = 0;
	$list_box_contents = array();
	$title = '';
	
	$num_products_count = ($products_query == '') ? 0 : $products->RecordCount();
	
	// show only when 1 or more
	if ($num_products_count > 0) {
	  if ($num_products_count < $show_product_info || $show_product_info == 0 ) {
	    $col_width = floor(100/$num_products_count);
	  } else {
	    $col_width = floor(100/$show_product_info);
	  }
	
	  while (!$products->EOF) {
	    $products_price = zen_get_products_display_price($products->fields['products_id']);
	    if (!isset($productsInCategory[$products->fields['products_id']])) $productsInCategory[$products->fields['products_id']] = zen_get_generated_category_path_rev($products->fields['master_categories_id']);
	
	    $list_box_contents[$row][$col] = array('params' => 'class="centerBoxContentsNew centeredContent back"' . ' ' . 'style="width:' . $col_width . '%;"',
	    'text' => (($products->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '<div class="itemImage"><a href="' . zen_href_link(zen_get_info_page($products->fields['products_id']), 'cPath=' . $productsInCategory[$products->fields['products_id']] . '&products_id=' . $products->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $products->fields['products_image'], $products->fields['products_name'], $image_product_new_width, $image_product_new_height) . '</a></div>') . '<h3 class="itemTitle"><a href="' . zen_href_link(zen_get_info_page($products->fields['products_id']), 'cPath=' . $productsInCategory[$products->fields['products_id']] . '&products_id=' . $products->fields['products_id']) . '">' . $products->fields['products_name'] . '</a></h3>' . '<div class="itemPrice">'.$products_price.'</div>');
	
	    $col ++;
	    if ($col > ($show_product_info - 1)) {
	      $col = 0;
	      $row ++;
	    }
	    $products->MoveNextRandom();
	  }
		
	  if ($products->RecordCount() > 0) {
	    if (isset($products_category_id) && $products_category_id != 0) {
	      $category_title = zen_get_categories_name((int)$products_category_id);
	      $title = '<h2 class="centerBoxHeading">' . sprintf($table_heading_new_products, strftime('%B')) . ($category_title != '' ? ' - ' . $category_title : '' ) . '</h2>';
	    } else {
	      $title = '<h2 class="centerBoxHeading">' . sprintf($table_heading_new_products, strftime('%B')) . '</h2>';
	    }
	    $zc_show_products = true;
	  }
	}
	
	return array('title' => $title, 'list_box_contents' => $list_box_contents, $show_product => $zc_show_products);
	
}
