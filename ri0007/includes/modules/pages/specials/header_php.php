<?php
/**
 * Specials
 *
 * @package page
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header_php.php 3000 2006-02-09 21:11:37Z wilt $
 */

require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
$breadcrumb->add(NAVBAR_TITLE);
if (MAX_DISPLAY_SPECIAL_PRODUCTS > 0 ) {
	$disp_order_default = PRODUCT_ALL_LIST_SORT_DEFAULT;
	require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_LISTING_DISPLAY_ORDER));
	$order_by = isset($order_by) ? $order_by : 'ORDER BY s.specials_date_added DESC';
	if (MAX_DISPLAY_SPECIAL_PRODUCTS > 0 ) {
		$listing_sql = "SELECT p.products_id, p.products_image, pd.products_name,
                          p.master_categories_id
                         FROM (" . TABLE_PRODUCTS . " p
                         LEFT JOIN " . TABLE_SPECIALS . " s on p.products_id = s.products_id
                         LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
                         WHERE p.products_id = s.products_id and p.products_id = pd.products_id and p.products_status = '1'
                         AND s.status = 1
                         AND pd.language_id = :languagesID
                         ORDER BY s.specials_date_added DESC";

		$listing_sql = $db->bindVars($listing_sql, ':languagesID', $_SESSION['languages_id'], 'integer');
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
	}
}
?>