<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_categories.php 4162 2006-08-17 03:55:02Z ajeh $
 */
  $content = "";
  
  $categories_tree = $_SESSION['category_tree']->retrieveCategoriesTreeArray();
/*  echo "<pre>";
  print_r($categories_tree);
  echo "</pre>";*/
  
  $content .= '<div id="categoriesContent" class="sideBoxContent">';
  $is_first = true; $cat_first = " catFirst";
  foreach($categories_tree[0]['sub_cats'] as $sub_cat){
  	if('no_sub' == $categories_tree[$sub_cat]['sub']){
		$content .= '<div class="parentCateLink'.$cat_first.'"><a href="'.zen_href_link(FILENAME_DEFAULT, 'cPath='.$categories_tree[$sub_cat]['path'][0]).'">' . $categories_tree[$sub_cat]['name'] . '</a></div>';
	}else{
  		$content .= '<div class="parentCate'.$cat_first.'"><a href="'.zen_href_link(FILENAME_DEFAULT, 'cPath='.$sub_cat) .'">' . $categories_tree[$sub_cat]['name'] . '</a></div>';
		$content .=  $_SESSION['category_tree']->buildCategoryString('<ul class="{class}">{child}</ul>', '<li class="{class}"><a class="{class} category-top" href="{link}"><span>{name}</span></a>{child}</li>', $sub_cat, 2, 1, false, true);
	}
	if($is_first){$is_first = false; $cat_first = "";}
  }
  
  $content .= '</div>';
  