<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_subscribe.php,v 1.1 2006/06/16 01:46:16 Owner Exp $
 */
   
   $content = '';
   $content .= zen_draw_form('subscribe', zen_href_link(FILENAME_SUBSCRIBE, '', 'SSL'), 'post', '');
   $content .= zen_draw_hidden_field('act', 'subscribe');
   $content .= zen_draw_hidden_field('main_page',FILENAME_SUBSCRIBE);
   $content .= (empty($subscribe_text) ? '' : $subscribe_text);
   $content .= '<br />' . zen_draw_input_field('email', '', 'size="14" maxlength="80" style="width: 180px" value="' . HEADER_SUBSCRIBE_DEFAULT_TEXT .
               '" onfocus="if (this.value == \'' . HEADER_SUBSCRIBE_DEFAULT_TEXT . '\') this.value = \'\';"');
   if(EMAIL_USE_HTML == 'true') {
    $content .= ' <br /> <label>' . zen_draw_radio_field('email_format', 'HTML', true) . ENTRY_EMAIL_HTML_DISPLAY . '</label>';
    $content .= ' <label style="white-space:nowrap">' . zen_draw_radio_field('email_format', 'TEXT', false) . ENTRY_EMAIL_TEXT_DISPLAY . '</label>';
   }

   $content .= '<div style="margin-top: 5px"><center>' . zen_image_submit (BUTTON_IMAGE_SUBSCRIBE,HEADER_SUBSCRIBE_BUTTON, 'value="' . HEADER_SUBSCRIBE_BUTTON . '" ') . '</center></div>';
   $content .= '</form>';
?>
