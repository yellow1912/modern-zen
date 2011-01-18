<?php
/**
 * Common Template - tpl_header.php
 *
 * this file can be copied to /templates/your_template_dir/pagename<br />
 * example: to override the privacy page<br />
 * make a directory /templates/my_template/privacy<br />
 * copy /templates/templates_defaults/common/tpl_footer.php to /templates/my_template/privacy/tpl_header.php<br />
 * to override the global settings and turn off the footer un-comment the following line:<br />
 * <br />
 * $flag_disable_header = true;<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_header.php 4813 2006-10-23 02:13:53Z drbyte $
 */
?>

<?php
  // Display all header alerts via messageStack:
  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }
  if (isset($_GET['error_message']) && zen_not_null($_GET['error_message'])) {
  echo htmlspecialchars(urldecode($_GET['error_message']));
  }
  if (isset($_GET['info_message']) && zen_not_null($_GET['info_message'])) {
   echo htmlspecialchars($_GET['info_message']);
} else {

}
?>

<!--bof-header logo and navigation display-->
<?php
if (!isset($flag_disable_header) || !$flag_disable_header) {
?>

<div id="headerWrapper">

<div id="logoWrapper" class="back">
    <div id="logo"><div id="logoContent" class="unitPng"><?php echo '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '" title="'.HEADER_ALT_TEXT.'"><span>&nbsp;</span></a>'; ?></div></div>
</div>

<!--bof header_content_wrapper-->
<div id="headerContentWrapper" class="forward">

<?php 
	$content = "";
  	$content .= zen_draw_form('quick_find_header', zen_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get');
  	$content .= zen_draw_hidden_field('main_page',FILENAME_ADVANCED_SEARCH_RESULT);
  	$content .= zen_draw_hidden_field('search_in_description', '1') . zen_hide_session_id();
    $content .= zen_draw_input_field('keyword', '', 'size="6" maxlength="30" style="width: 180px" value="' . HEADER_SEARCH_DEFAULT_TEXT . '" onfocus="if (this.value == \'' . HEADER_SEARCH_DEFAULT_TEXT . '\') this.value = \'\';" onblur="if (this.value == \'\') this.value = \'' . HEADER_SEARCH_DEFAULT_TEXT . '\';"');

  	$content .= "</form>";
?>

<div class="searchHeader forward unitPng">
	<?php echo $content; ?>
</div>
<br class="clearBoth" />
<div id="smallNaviHeader" class="forward">
	<ul>
	<?php if ($_SESSION['customer_id']) { ?>
		<li><a href="<?php echo zen_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><span class="colorOrangeLink"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></span></a></li>
		<li>|</li>
    	<li><a href="<?php echo zen_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><span class="colorBlackLink"><?php echo HEADER_TITLE_LOGOFF; ?></span></a></li>
	<?php
		} else {
        	if (STORE_STATUS == '0') {
	?>
			<li><a href="<?php echo zen_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><span class="colorOrangeLink"><?php echo HEADER_TITLE_REGISTER; ?></span></a></li>
			<li>|</li>
    		<li><a href="<?php echo zen_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><span class="colorBlackLink"><?php echo HEADER_TITLE_LOGIN; ?></span></a></li>
	<?php }} ?>
	<?php if ($_SESSION['cart']->count_contents() != 0) { ?>
		<li>|</li>
		<li><a href="<?php echo zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><span class="colorOrangeLink"><?php echo HEADER_TITLE_CHECKOUT; ?></span></a></li>
		<li>|</li>
    	<li class="shoppingCart unitPng">
			<a href="<?php echo zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'); ?>"><span class="colorBlackLink"><?php echo HEADER_TITLE_CART_CONTENTS; ?></span></a>
			<span class="colorBlack">(<?php echo $_SESSION['cart']->count_contents();?>):</span><span class="price colorOrange"><?php echo $currencies->format($_SESSION['cart']->show_total());?></span>
		</li>
	<?php }?>
	</ul>
</div>
<br class="clearBoth" />

</div>
<!--eof header_content_wrapper-->
<br class="clearBoth" />
<!--eof-branding display-->

<!--eof-header logo and navigation display-->
</div>
<?php } ?>

<!--bof-header ezpage links-->
<?php if (EZPAGES_STATUS_HEADER == '1' or (EZPAGES_STATUS_HEADER == '2' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR'])))) { ?>
<?php require($template->get_template_dir('tpl_ezpages_bar_header.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_ezpages_bar_header.php'); ?>
<?php } ?>
<!--eof-header ezpage links-->

