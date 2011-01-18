<?php
/**
 * ch_categories_tree_generator
 * @Version: 
 * @Authour: 
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */ 
if (!defined('IS_ADMIN_FLAG')) {
	die('Illegal Access');
}
$autoLoadConfig[0][] = array('autoType'=>'class', 'loadFile'=> 'simple_categories_tree.php');
$autoLoadConfig[180][] = array('autoType'=>'classInstantiate',
								'className'=>'SimpleCategoriesTree',
								'objectName'=>'category_tree',
								'checkInstantiated'=>true,
								'classSession'=>true);
$autoLoadConfig[180][] = array('autoType'=>'objectMethod',
								'objectName'=>'category_tree',
								'methodName' => 'init');

?>