<?php

class RICJLoader{
	/**
	 * required functions for jscript auto_loaders
	 *
	 * @author yellow1912 (RubikIntegration.com)
	 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
	 */
	
	function loadCssJsFiles(){
		global $current_page_base, $template, $Ajax, $page_directory, $this_is_home_page, $cPath;
		$jscript_files_to_load = $css_files_to_load = array();
		
		$ajax_status = (is_object($Ajax) && method_exists($Ajax, 'status')) ? $Ajax->status() : false;
	/**
	 * load all template-specific stylesheets, named like "style*.css", alphabetically
	 */
		if(!$ajax_status){
		  $directory_array = $template->get_template_part($template->get_template_dir('.css',DIR_WS_TEMPLATE, $current_page_base,'css'), '/^style/', '.css');
		  $load_order = -300;
		  while(list ($key, $value) = each($directory_array)) {
		    $css_files_to_load[$value] = $load_order++;
		  }
		
		  $directory_array = $template->get_template_part($template->get_template_dir('.php',DIR_WS_TEMPLATE, $current_page_base,'css'), '/^style/', '.php');
		  $load_order = -250;
		  while(list ($key, $value) = each($directory_array)) {
		    $css_files_to_load[$value] = $load_order++;
		  }
		}
	/**
	 * load stylesheets on a per-page/per-language/per-product/per-manufacturer/per-category basis. Concept by Juxi Zoza.
	 */
	  $manufacturers_id = (isset($_GET['manufacturers_id'])) ? $_GET['manufacturers_id'] : '';
	  $tmp_products_id = (isset($_GET['products_id'])) ? (int)$_GET['products_id'] : '';
	  $tmp_pagename = ($this_is_home_page) ? 'index_home' : $current_page_base;
	  $sheets_array = array('/' . $_SESSION['language'] . '_stylesheet', 
	                        '/' . $tmp_pagename, 
	                        '/' . $_SESSION['language'] . '_' . $tmp_pagename, 
	                        '/c_' . $cPath,
	                        '/' . $_SESSION['language'] . '_c_' . $cPath,
	                        '/m_' . $manufacturers_id,
	                        '/' . $_SESSION['language'] . '_m_' . (int)$manufacturers_id, 
	                        '/p_' . $tmp_products_id,
	                        '/' . $_SESSION['language'] . '_p_' . $tmp_products_id
	                        );
	  $load_order = 10000;                        
	  while(list ($key, $value) = each($sheets_array)) {
	    //echo "<!--looking for: $value-->\n";
	    $perpagefile = $template->get_template_dir('.css', DIR_WS_TEMPLATE, $current_page_base, 'css') . $value . '.css';
	    if (file_exists($perpagefile)) $css_files_to_load[trim($value, '/').'.css'] = $load_order++;
	    
	    $perpagefile = $template->get_template_dir('.php', DIR_WS_TEMPLATE, $current_page_base, 'css') . $value . '.php';
	    if (file_exists($perpagefile)) $css_files_to_load[trim($value, '/').'.php'] = $load_order++;
	    
	    $perpagefile = $template->get_template_dir('.js', DIR_WS_TEMPLATE, $current_page_base, 'jscript') . $value . '.js';
	    if (file_exists($perpagefile)) $jscript_files_to_load[trim($value, '/').'.js'] = $load_order++;
	    
	    $perpagefile = $template->get_template_dir('.php', DIR_WS_TEMPLATE, $current_page_base, 'jscript') . $value . '.php';
	    if (file_exists($perpagefile)) $jscript_files_to_load[trim($value, '/').'.php'] = $load_order++;
	  }
	
	/**
	 * load printer-friendly stylesheets -- named like "print*.css", alphabetically
	 */
		if(!$ajax_status){
		  $directory_array = $template->get_template_part($template->get_template_dir('.css',DIR_WS_TEMPLATE, $current_page_base,'css'), '/^print/', '.css');
		  sort($directory_array);
		  while(list ($key, $value) = each($directory_array)) {
		    echo '<link rel="stylesheet" type="text/css" media="print" href="' . $template->get_template_dir('.css',DIR_WS_TEMPLATE, $current_page_base,'css') . '/' . $value . '" />'."\n";
		  }
		  
		  	if (file_exists(DIR_WS_CLASSES . 'browser.php')) {
		    include(DIR_WS_CLASSES . 'browser.php');
		    $browser = new _Browser();
		    $browser_name = preg_replace("/[^a-zA-Z0-9s]/", "-", strtolower($browser->getBrowser()));
		    $browser_version = floor($browser->getVersion());
		    
		    // this is to make it compatible with the other ie css hack
		    if ($browser->getBrowser() == $browser->BROWSER_IE) {
		   		$browser_name = 'ie';
		    }
		     
		    // get the browser specific files
		    $directory_array = $template->get_template_part($template->get_template_dir('.css',DIR_WS_TEMPLATE, $current_page_base,'css'), "/^{$browser_name}-/", '.css');
		    $js_directory_array = $template->get_template_part($template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript'), "/^{$browser_name}-/", '.js');
		    
		    $load_order = -100;
		    while(list ($key, $value) = each($directory_array )) {
		      $css_files_to_load[$value] = $load_order++;									
		    }
		    
		    $load_order = -500;
		    while(list ($key, $value) = each($js_directory_array )) {
		      $jscript_files_to_load[$value] = $load_order++;	
		    }	
		    
		    // get the version specific files
		    $directory_array = $template->get_template_part($template->get_template_dir('.css',DIR_WS_TEMPLATE, $current_page_base,'css'), "/^{$browser_name}{$browser_version}-/", '.css');
		    $js_directory_array = $template->get_template_part($template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript'), "/^{$browser_name}{$browser_version}-/", '.js');
		    
		    $load_order = -100;
		    while(list ($key, $value) = each($directory_array )) {
		      $css_files_to_load[$value] = $load_order++;						
		    }
		    
		    $load_order = -500;
		    while(list ($key, $value) = each($js_directory_array )) {
		      $jscript_files_to_load[$value] = $load_order++;	
		    }	
		  }
	
	/**
	 * load all site-wide jscript_*.js files from includes/templates/YOURTEMPLATE/jscript, alphabetically
	 */
	  $directory_array = $template->get_template_part($template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript'), '/^jscript_/', '.js');
	  $load_order = -400;
	  while(list ($key, $value) = each($directory_array)) {
	    $jscript_files_to_load[$value] = $load_order++;	
	  }							
	}
	
	/**
	 * load all page-specific jscript_*.js files from includes/modules/pages/PAGENAME, alphabetically
	 */
	  $directory_array = $template->get_template_part($page_directory, '/^jscript_/', '.js');
	  $load_order = 9999;
	  while(list ($key, $value) = each($directory_array)) {
	    $jscript_files_to_load["$page_directory/$value"] = $load_order++;
	  }
	
	/**
	 * load all site-wide jscript_*.php files from includes/templates/YOURTEMPLATE/jscript, alphabetically
	 */
		if(!$ajax_status){
		  $directory_array = $template->get_template_part($template->get_template_dir('.php',DIR_WS_TEMPLATE, $current_page_base,'jscript'), '/^jscript_/', '.php');
		  $load_order = -200;
		  while(list ($key, $value) = each($directory_array)) {
			/**
			 * include content from all site-wide jscript_*.php files from includes/templates/YOURTEMPLATE/jscript, alphabetically.
			 * These .PHP files can be manipulated by PHP when they're called, and are copied in-full to the browser page
			 */
		    $jscript_files_to_load[$value] = $load_order++;
		  }
		}
		
	/**
	 * include content from all page-specific jscript_*.php files from includes/modules/pages/PAGENAME, alphabetically.
	 */
	  $directory_array = $template->get_template_part($page_directory, '/^jscript_/', '.php');
	  $load_order = 9999;
	  while(list ($key, $value) = each($directory_array)) {
	/**
	 * include content from all page-specific jscript_*.php files from includes/modules/pages/PAGENAME, alphabetically.
	 * These .PHP files can be manipulated by PHP when they're called, and are copied in-full to the browser page
	 */
	    $jscript_files_to_load["$page_directory/$value"] = $load_order++;
	  }
	  
	  return array($css_files_to_load, $jscript_files_to_load);
	}
	
	
	function processCssJsFiles($css_files, $js_files){
		global $loaders, $current_page_base, $minify_cache_time_latest, $Ajax;
		if(isset($loaders) && count($loaders) > 0){
			$_jscripts = $_css_files = array();
			foreach($loaders as $j){
				if(in_array('*', $j['conditions']['pages']) || in_array($current_page_base, $j['conditions']['pages'])){
					if(isset($j['jscript_files']))
						$_jscript_files[] = $j['jscript_files'];
					if(isset($j['css_files']))
						$_css_files[] = $j['css_files'];
				}
				else{
					$load = false;	
					if(isset($j['conditions']['call_backs']))
					foreach($j['conditions']['call_backs'] as $function){
						$f = explode(',',$function);
						if(count($f) == 2){
							$load = call_user_func(array($f[0], $f[1]));
						}
						else $load = $function();
						
						if($load){
							if(isset($j['jscript_files']))
								$_jscript_files[] = $j['jscript_files'];
							if(isset($j['css_files']))
								$_css_files[] = $j['css_files'];
							break;
						}
					}
				}
			}
		
			if(count($_css_files) > 0){
				$css_files = $this->loadFiles($_css_files, $css_files);	
			}
		
			if(count($_jscript_files) > 0){
				$js_files = $this->loadFiles($_jscript_files, $js_files);
			}
		}
		
		$files = array();
		if(MINIFY_STATUS == 'true'){
			if(is_object($Ajax) && $Ajax->status()){
				$files['js'] = $this->getMinifyfiles($js_files, "%s", 'jscript/');
				$files['css'] = $this->getMinifyfiles($css_files, "%s", 'css/');				
			}
			else{
				$files['js'] = $this->getMinifyfiles($js_files, "<script type=\"text/javascript\" src=\"min/?f=%s&amp;$minify_cache_time_latest\"></script>\n", 'jscript/');
				$files['css'] = $this->getMinifyfiles($css_files, "<link rel=\"stylesheet\" type=\"text/css\" href=\"min/?f=%s&amp;$minify_cache_time_latest\" />\n", 'css/');
			}
		}
		else{
			if(is_object($Ajax) && $Ajax->status()){
				$files['js'] = $this->getFiles($js_files, "%s", 'jscript/');
				$files['css'] = $this->getFiles($css_files, "%s", 'css/');				
			}
			else{
				$files['js'] = $this->getFiles($js_files, '<script type="text/javascript" src="%s"></script>' . "\n", 'jscript/');
				$files['css'] = $this->getFiles($css_files, '<link rel="stylesheet" type="text/css" href="%s" />' . "\n", 'css/');
			}
		}
		return $files;
	}
	
	function loadFiles($_jscripts, $jfiles){
			foreach($_jscripts as $files){
				foreach($files as $file=>$order)
				if(!isset($jfiles[$file]) || $jfiles[$file] > $order) $jfiles[$file] = $order;		
			}
			
			if(count($jfiles) > 0)
				asort($jfiles);	
			return $jfiles;
	}
	
	function getPath($file, $type='jscript'){
		$path_info = pathinfo($file);
		return array('extension' => $path_info['extension'], 'path' => DIR_WS_TEMPLATE.$type.'/'.$path_info['dirname'].$file_name);
	}
	
		
	function getMinifyfiles($files, $request_string, $folder){
		global $request_type, $current_page_base, $template;
		$relative_path = $request_type == 'NONSSL' ? DIR_WS_CATALOG : DIR_WS_HTTPS_CATALOG;
		$files_paths = '';$result = array();
		foreach($files as $file=>$order){
			$file_exists = false;
			// case 1: file is in server but full path not passed, assuming it is under corresponding template css/js folder
			if($file_exists = file_exists(DIR_FS_CATALOG.DIR_WS_TEMPLATE.$folder.$file)){
				$file_absolute_path = DIR_FS_CATALOG.DIR_WS_TEMPLATE.$folder.$file;
				$file_relative_path = $relative_path.DIR_WS_TEMPLATE.$folder.$file;
			}
			// case 2: file is in the default template
			elseif($file_exists = file_exists(DIR_FS_CATALOG.DIR_WS_TEMPLATES.'template_default/'.$folder.$file)){
				$file_absolute_path = DIR_FS_CATALOG.DIR_WS_TEMPLATES.'template_default/'.$folder.$file;
				$file_relative_path = $relative_path.DIR_WS_TEMPLATES.'template_default/'.$folder.$file;
			}
			// case 3: file is in the server, can be accessed via the same domain, full path passed
			elseif($file_exists = file_exists($file)){
				$file_absolute_path = DIR_FS_CATALOG.$file; 
				$file_relative_path = $relative_path.$file; 
			}
			// case 4: file is not even on the same domain
			elseif(substr($file, 0, 4) == 'http'){
				$file_relative_path = $file; 
				$file_exists = true;
			}
			
			if($file_exists === true){
				$ext = pathinfo($file, PATHINFO_EXTENSION);
				// if we encounter php, unfortunately we will have to include it for now
				// another solution is to put everything into 1 file, but we will have to solve @import
				if($ext == 'php'){
					if($files_paths != ''){
						$result[] = array('string' => sprintf($request_string, trim($files_paths, ',')), 'include' => false);	
						$files_paths = '';		
					}
				
					$result[] = array('string' => $file_absolute_path, 'include' => true);
				}
				else{
					if(substr($file_relative_path, 0, 4) == 'http'){
						$result[] = array('string' => "<script type='text/javascript' src='$file_relative_path'></script>\n", 'include' => false);		
					}
					elseif(strlen($files_paths) > ((int)MINIFY_MAX_URL_LENGHT - 20)){
						$result[] = array('string' => sprintf($request_string, trim($files_paths, ',')), 'include' => false);	
						$files_paths = $file_relative_path.',';		
					}
					else
						$files_paths .= $file_relative_path.',';
				}
			}
		}
		
		// one last time
		if($files_paths != '')
			$result[] = array('string' => sprintf($request_string, trim($files_paths, ',')), 'include' => false);
		return $result;
	}
	
	function getFiles($files, $request_string, $folder){
		global $request_type;
		$result = array();
		$relative_path = $request_type == 'NONSSL' ? DIR_WS_CATALOG : DIR_WS_HTTPS_CATALOG;
		foreach($files as $file=>$order){
			$file_exists = false;
			// case 1: file is in server but full path not passed, assuming it is under corresponding template css/js folder
			if($file_exists = file_exists(DIR_FS_CATALOG.DIR_WS_TEMPLATE.$folder.$file)){
				$file_absolute_path = DIR_FS_CATALOG.DIR_WS_TEMPLATE.$folder.$file;
				$file_relative_path = $relative_path.DIR_WS_TEMPLATE.$folder.$file;
			}
			// case 2: file is in the default template
			elseif($file_exists = file_exists(DIR_FS_CATALOG.DIR_WS_TEMPLATES.'template_default/'.$folder.$file)){
				$file_absolute_path = DIR_FS_CATALOG.DIR_WS_TEMPLATES.'template_default/'.$folder.$file;
				$file_relative_path = $relative_path.DIR_WS_TEMPLATES.'template_default/'.$folder.$file;
			}
			// case 3: file is in the server, can be accessed via the same domain, full path passed
			elseif($file_exists = file_exists($file)){
				$file_absolute_path = DIR_FS_CATALOG.$file; 
				$file_relative_path = $relative_path.$file; 
			}
			// case 4: file is not even on the same domain
			elseif(substr($file, 0, 4) == 'http'){
				$file_relative_path = $file; 
				$file_exists = true;
			}
			
			if($file_exists === true){
				$ext = pathinfo($file, PATHINFO_EXTENSION);
				// if we encounter php, unfortunately we will have to include it for now
				// another solution is to put everything into 1 file, but we will have to solve @import
				if($ext == 'php')
					$result[] = array('string' => $file_absolute_path, 'include' => true);
				else
					$result[] = array('string' => sprintf($request_string, $file_relative_path), 'include' => false);
			}
		}
		return $result;
	}	
}
