<?php
/**
 * init jscript auto_loaders
 *
 * @author yellow1912 (RubikIntegration.com)
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

$directory_array = $template->get_template_part($template->get_template_dir('.php',DIR_WS_TEMPLATE, $current_page_base,'auto_loaders'), '/^loader_/', '.php');
while(list ($key, $value) = each($directory_array)) {
/**
* include content from all site-wide jscript_*.php files from includes/templates/YOURTEMPLATE/jscript/auto_loaders, alphabetically.
* These .PHP files can be manipulated by PHP when they're called, and are copied in-full to the browser page
*/
  require($template->get_template_dir('.php',DIR_WS_TEMPLATE, $current_page_base,'auto_loaders') . '/' . $value);
}
if(!defined('MINIFY_STATUS'))
	define('MINIFY_STATUS', 'false');
	
if(MINIFY_STATUS == 'true'){
	$now = time();
	$minify_cache_time_latest = (int)MINIFY_CACHE_TIME_LATEST;
	if($now - $minify_cache_time_latest > (int)MINIFY_CACHE_TIME_LENGHT){
		$db->Execute("UPDATE ".TABLE_CONFIGURATION." SET configuration_value = $now WHERE configuration_key = 'MINIFY_CACHE_TIME_LATEST'");
		$minify_cache_time_latest = $now;
	}
}