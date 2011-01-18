/** install single listing **/
delete from configuration where configuration_key in('PRODUCT_LISTING_LAYOUT_STYLE','PRODUCT_LISTING_COLUMNS_PER_ROW');

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Product Listing - Layout Style', 'PRODUCT_LISTING_LAYOUT_STYLE', 'rows', 'Select the layout style:<br />Each product can be listed in its own row (rows option) or products can be listed in multiple columns per row (columns option)', '8', '40', NULL, now(), NULL, 'zen_cfg_select_option(array("rows", "columns"),');

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Product Listing - Columns Per Row', 'PRODUCT_LISTING_COLUMNS_PER_ROW', '3', 'Select the number of columns of products to show in each row in the product listing. The default setting is 3.', '8', '41', NULL, now(), NULL, NULL);

INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES 
('Index New Listing Template', 'INDEX_NEW_USE_PRODUCT_LISTING', '1', 'Set to 1 to use the original listing template, 2 to use the shared listing template, 3 to use the product listing template.', 24, 1, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''1'', ''2'', ''3''),'),
('Index Special Listing Template', 'INDEX_SPECIAL_USE_PRODUCT_LISTING', '1', 'Set to 1 to use the original listing template, 2 to use the shared listing template, 3 to use the product listing template.', 24, 1, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''1'', ''2'', ''3''),'),
('Index Featured Listing Template', 'INDEX_FEATURED_USE_PRODUCT_LISTING', '1', 'Set to 1 to use the original listing template, 2 to use the shared listing template, 3 to use the product listing template.', 24, 1, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''1'', ''2'', ''3''),');

/** CJ Loader **/

SET @t4=0;
SELECT (@t4:=configuration_group_id) as t4 
FROM configuration_group
WHERE configuration_group_title= 'CSS/JS Loader';
DELETE FROM configuration WHERE configuration_group_id = @t4;
DELETE FROM configuration_group WHERE configuration_group_id = @t4;

INSERT INTO configuration_group VALUES ('', 'CSS/JS Loader', 'Set CSS/JS Loader Options', '1', '1');
UPDATE configuration_group SET sort_order = last_insert_id() WHERE configuration_group_id = last_insert_id();

SET @t4=0;
SELECT (@t4:=configuration_group_id) as t4 
FROM configuration_group
WHERE configuration_group_title= 'CSS/JS Loader';

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
('Enable Minify', 'MINIFY_STATUS', 'true', 'Minifying will speed up your site\'s loading speed by combining and compressing css/js files.', @t4, 1, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Max URL Lenght', 'MINIFY_MAX_URL_LENGHT', '2083', 'On some server the maximum lenght of any POST/GET request URL is limited. If this is the case for your server, you can change the setting here', @t4, 2, NOW(), NOW(), NULL, NULL),
('Minify Cache Time', 'MINIFY_CACHE_TIME_LENGHT', '31536000', 'Set minify cache time (in second). Default is 1 year (31536000)', @t4, 3, NOW(), NOW(), NULL, NULL),
('Latest Cache Time', 'MINIFY_CACHE_TIME_LATEST', '0', 'Normally you don\'t have to set this, but if you have just made changes to your js/css files and want to make sure they are reloaded right away, you can reset this to 0.', @t4, 4, NOW(), NOW(), NULL, NULL);

/** update configuration **/

UPDATE configuration SET configuration_value = '4' WHERE configuration_key = 'MAX_DISPLAY_NEW_PRODUCTS';
UPDATE configuration SET configuration_value = '1' WHERE configuration_key = 'MAX_MANUFACTURERS_LIST';
UPDATE configuration SET configuration_value = '250' WHERE configuration_key = 'MEDIUM_IMAGE_WIDTH';
UPDATE configuration SET configuration_value = '250' WHERE configuration_key = 'MEDIUM_IMAGE_HEIGHT';
UPDATE configuration SET configuration_value = '125' WHERE configuration_key = 'IMAGE_PRODUCT_LISTING_WIDTH';
UPDATE configuration SET configuration_value = '125' WHERE configuration_key = 'IMAGE_PRODUCT_LISTING_HEIGHT';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'IMAGE_PRODUCT_ALL_LISTING_WIDTH';
UPDATE configuration SET configuration_value = '160' WHERE configuration_key = 'IMAGE_PRODUCT_ALL_LISTING_HEIGHT';
UPDATE configuration SET configuration_value = '1' WHERE configuration_key = 'PRODUCT_LIST_IMAGE';
UPDATE configuration SET configuration_value = '2' WHERE configuration_key = 'PRODUCT_LIST_NAME';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'PRODUCT_LISTING_MULTIPLE_ADD_TO_CART';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'PRODUCT_LIST_DESCRIPTION';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'OTHER_IMAGE_PRICE_IS_FREE_ON';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'PRODUCT_INFO_PREVIOUS_NEXT';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'PRODUCT_INFO_CATEGORIES';
UPDATE configuration SET configuration_value = '220px' WHERE configuration_key = 'BOX_WIDTH_LEFT';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'COLUMN_RIGHT_STATUS';
UPDATE configuration SET configuration_value = '220px' WHERE configuration_key = 'COLUMN_WIDTH_LEFT';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'SHOW_CUSTOMER_GREETING';
UPDATE configuration SET configuration_value = '' WHERE configuration_key = 'SHOW_BANNERS_GROUP_SET6';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'SHOW_FOOTER_IP';
UPDATE configuration SET configuration_value = 'No' WHERE configuration_key = 'IMAGE_USE_CSS_BUTTONS';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'PRODUCT_NEW_LIST_IMAGE';
UPDATE configuration SET configuration_value = '2101' WHERE configuration_key = 'PRODUCT_NEW_LIST_NAME';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'PRODUCT_NEW_LISTING_MULTIPLE_ADD_TO_CART';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'PRODUCT_FEATURED_LIST_IMAGE';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'PRODUCT_ALL_LIST_IMAGE';
UPDATE configuration SET configuration_value = '1101' WHERE configuration_key = 'PRODUCT_ALL_LIST_NAME';
UPDATE configuration SET configuration_value = '0' WHERE configuration_key = 'PRODUCT_ALL_LISTING_MULTIPLE_ADD_TO_CART';
UPDATE configuration SET configuration_value = '1' WHERE configuration_key = 'SHOW_PRODUCT_INFO_MAIN_FEATURED_PRODUCTS';
UPDATE configuration SET configuration_value = '1' WHERE configuration_key = 'SHOW_PRODUCT_INFO_MAIN_SPECIALS_PRODUCTS';
UPDATE configuration SET configuration_value = '1' WHERE configuration_key = 'DEFINE_MAIN_PAGE_STATUS';
UPDATE configuration SET configuration_value = '1' WHERE configuration_key = 'EZPAGES_STATUS_FOOTER';
UPDATE configuration SET configuration_value = '3' WHERE configuration_key = 'INDEX_NEW_USE_PRODUCT_LISTING';
UPDATE configuration SET configuration_value = '3' WHERE configuration_key = 'INDEX_SPECIAL_USE_PRODUCT_LISTING';
UPDATE configuration SET configuration_value = '3' WHERE configuration_key = 'INDEX_FEATURED_USE_PRODUCT_LISTING';
UPDATE configuration SET configuration_value = 'columns' WHERE configuration_key = 'PRODUCT_LISTING_LAYOUT_STYLE';
UPDATE configuration SET configuration_value = '4' WHERE configuration_key = 'PRODUCT_LISTING_COLUMNS_PER_ROW';