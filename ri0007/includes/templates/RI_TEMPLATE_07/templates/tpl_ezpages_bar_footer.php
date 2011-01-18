<?php
/**
 * Page Template
 *
 * Displays EZ-Pages footer-bar content.<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_ezpages_bar_footer.php 4225 2006-08-24 01:42:49Z drbyte $
 */

   /**
   * require code to show EZ-Pages list
   */
  include(DIR_WS_MODULES . zen_get_module_directory('ezpages_bar_footer.php'));
?>
<?php if (sizeof($var_linksList) >= 1) { ?>
<?php for ($i=1, $n=sizeof($var_linksList); $i<=$n; $i++) {  ?>

<?php if(1 == $i){ ?>
	<div class="navSuppLeft back"><ul>
<?php } ?>
  <li><a href="<?php echo $var_linksList[$i]['link']; ?>"><?php echo $var_linksList[$i]['name']; ?></a></li>
<?php if(3 == $i){ ?>
	</ul></div><div class="navSuppLeft forward"><ul>
<?php } ?>
<?php if((sizeof($var_linksList) == $i)){ ?>
	</ul></div>
<?php }else if($i > 5){?>
	</ul></div>
<?php break; } ?>

<?php } // end FOR loop ?>
<br class="clearBoth" />
<?php } ?>