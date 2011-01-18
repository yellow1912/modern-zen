<?php
/**
 * single_listing Class.
 *
 * @package classes
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: split_page_results.php 3041 2006-02-15 21:56:45Z wilt $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
/**
 * Split Page Result Class
 * 
 * An sql paging class, that allows for sql reslt to be shown over a number of pages using  simple navigation system
 * Overhaul scheduled for subsequent release
 *
 * @package classes
 */
class singleListing extends base {
  var $records, $number_of_rows;

  /* class constructor */
  function singleListing($query, $max_index_listing) {
    global $db;
		
   	$this->records = $db->ExecuteRandomMulti($query, $max_index_listing);
    
    $this->number_of_rows = $this->records->RecordCount();
  }
}
