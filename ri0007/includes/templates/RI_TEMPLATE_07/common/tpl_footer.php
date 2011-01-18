<?php
/**
 * Common Template - tpl_footer.php
 *
 * this file can be copied to /templates/your_template_dir/pagename<br />
 * example: to override the privacy page<br />
 * make a directory /templates/my_template/privacy<br />
 * copy /templates/templates_defaults/common/tpl_footer.php to /templates/my_template/privacy/tpl_footer.php<br />
 * to override the global settings and turn off the footer un-comment the following line:<br />
 * <br />
 * $flag_disable_footer = true;<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_footer.php 4821 2006-10-23 10:54:15Z drbyte $
 */
require(DIR_WS_MODULES . zen_get_module_directory('footer.php'));
?>

<?php
if (!isset($flag_disable_footer) || !$flag_disable_footer) {
?>

<!--bof-navigation display -->
<div id="navSuppWrapper">
<div id="navsuppWrapperLeft" class="back">
	<div id="navsuppWrapperLeftContent"><h3>Lorem ipsum</h3><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultricies quam eget nunc mattis ultrices.</span></div>
</div>
<div id="navsuppWrapperRight" class="back">
	<div id="navSupp" class="back">
		<h3>About Us</h3>
		<div id="navSuppContent">
			<?php if (EZPAGES_STATUS_FOOTER == '1' or (EZPAGES_STATUS_FOOTER == '2' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR'])))) { ?>
			<?php require($template->get_template_dir('tpl_ezpages_bar_footer.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_ezpages_bar_footer.php'); ?>
			<?php } ?>
		</div>
	</div>
	<div id="navSuppRight" class="forward">
		<div id="navSuppRightLeft" class="back">
			<h3>Lorem ipsum</h3><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultricies quam eget nunc mattis ultrices.</span>
		</div>
		<div id="navSuppRightRight" class="forward">
			<div id="navSuppRightRightContent">
				<h3>Proud to Be</h3>
			</div>
		</div>
		<br class="clearBoth" />
	</div>
	<br class="clearBoth" />
</div>
<br class="clearBoth" />
</div>
<!--eof-navigation display -->

<!--bof-ip address display -->
<?php
if (SHOW_FOOTER_IP == '1') {
?>
<div id="siteinfoIP"><?php echo TEXT_YOUR_IP_ADDRESS . '  ' . $_SERVER['REMOTE_ADDR']; ?></div>
<?php
}
?>
<!--eof-ip address display -->

<!--bof-banner #5 display -->
<?php
  if (SHOW_BANNERS_GROUP_SET5 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET5)) {
    if ($banner->RecordCount() > 0) {
?>
<div id="bannerFive" class="banners"><?php echo zen_display_banner('static', $banner); ?></div>
<?php
    }
  }
?>
<!--eof-banner #5 display -->

<!--bof- site copyright display -->
<div id="siteinfoLegal" class="legalCopyright"><?php echo FOOTER_TEXT_BODY; ?></div>
<!--eof- site copyright display -->

<?php
} // flag_disable_footer
?>