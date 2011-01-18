<?php
/**
 * Simple Category Tree
 * @Version: Beta 2
 * @Authour: yellow1912
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */ 

//define ('SCT_REBUILD_TREE','false');
define ('SCT_REBUILD_TREE','true');
class SimpleCategoriesTree{
	var $category_tree = array();
	var $is_deepest_cats_built = false;
	var $parent_html = '';
	var $child_html = '';
	var $current_id = -1;
	var $exceptional_list = array();
	var $new_id;
	var $is_attached = false;
	
	function init(){
		if(SCT_REBUILD_TREE != 'false' || count($this->category_tree) == 0){
			global $languages_id, $db;
			$categories_query = "select c.categories_id, cd.categories_name, c.parent_id
	                      from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
	                      where c.categories_id = cd.categories_id
	                      and c.categories_status=1
						  and cd.language_id = '" . (int)$_SESSION['languages_id'] . "'
	                      order by c.parent_id, c.sort_order, cd.categories_name";
			$categories = $db->Execute($categories_query);

			// reset the tree first
			$this->category_tree = array(); 
			$this->is_deepest_cats_built = false;
			while (!$categories->EOF) {
				$this->category_tree[$categories->fields['categories_id']]['name'] = $categories->fields['categories_name'];
				$this->category_tree[$categories->fields['categories_id']]['parent_id'] = $categories->fields['parent_id'];
				$this->category_tree[$categories->fields['categories_id']]['path'][] = $categories->fields['categories_id'];
				$this->category_tree[$categories->fields['parent_id']]['sub_cats'][] = $categories->fields['categories_id'];
				$categories->MoveNext();
			}
			
			// walk through the array and build sub/cPath and other addtional info needed
			foreach($this->category_tree as $key => $value){
				// add sub 'class' for print-out purpose
				$this->category_tree[$key]['sub'] = isset($this->category_tree[$key]['sub_cats']) ? 'has_sub' : 'no_sub';
				// only merge if parent cat is not 0
				if(isset($this->category_tree[$key]['parent_id']) && $this->category_tree[$key]['parent_id'] > 0){
					if(is_array($this->category_tree[$this->category_tree[$key]['parent_id']]['path']) && count($this->category_tree[$this->category_tree[$key]['parent_id']]['path'])> 0)
						$this->category_tree[$key]['path'] = array_merge($this->category_tree[$this->category_tree[$key]['parent_id']]['path'],$this->category_tree[$key]['path']);
				}
				$this->category_tree[$key]['nPath'] = $this->category_tree[$key]['cPath'] = isset($this->category_tree[$key]['path']) ? implode('_',$this->category_tree[$key]['path']) : $key;
			}
			// for debugging using super global mod
			// $_POST['category_tree'] = $this->category_tree;
		}
		// This special portion of code was added to catch the current category selected
		$this->current_id = $this->getCurrentNavId();
		$this->exceptional_list = array();
		
		if($this->current_id != -1){
			$cPath = $this->retrieveCpath($this->current_id);
			if(!empty($cPath)){
				$this->exceptional_list = explode('_', $cPath);
			}
		}
	}
	
	function getCurrentNavId(){
		$cPath = $_GET['cPath'];
		if(isset($_GET['nPath']))
			$cPath = $_GET['nPath'];
		if(empty($cPath))
			return -1;
		return $this->_getCategoriesId($cPath);
	}
	
	function retrieveCpath($categories_id){
		$categories_id = $this->_getCategoriesId($categories_id);
		return (isset($this->category_tree[$categories_id]['cPath']) ? $this->category_tree[$categories_id]['cPath'] : '');
	}
	
	function retrieveCategoriesTreeArray(){
		return $this->category_tree;
	}
	
	function startAttach(){
		if(SCT_REBUILD_TREE == 'true' || !$this->is_attached)
			return true;
		return false;
	}
	
	function endAttach(){
		$this->is_attached = true;
	}
	
	function attachToCategoryTree($new_node, $parent_id = 0){
		// we first need to find and assign a "fake" category id
		if(!isset($new_node['id']) || isset($this->category_tree[$new_node['id']])){
			if(!isset($this->new_id) && isset($this->category_tree[$parent_id]['sub_cats']) && count($this->category_tree[$parent_id]['sub_cats']) > 0)
				$this->new_id = end($this->category_tree[$parent_id]['sub_cats']);
			
			$current_id = ++$this->new_id;
		}
		else 
			$current_id = $new_node['id'];
			
		if(!is_numeric($this->category_tree[$parent_id]['nPath']) || $this->category_tree[$parent_id]['nPath'] != 0)
			$nPath = "{$this->category_tree[$parent_id]['nPath']}_{$current_id}";
		else 
			$nPath = $current_id;
			
		// we will then update its parent sub_cats. Since theese new add-on categories are "fake" and don't have
		// any product, we dont need to re-calculate the deepest_cats though.
		$this->category_tree[$parent_id]['sub_cats'][] = $current_id;

		if(isset($new_node['children']))
			$new_node['sub'] = 'has_sub'; 
		else 
			$new_node['sub'] = 'no_sub'; 
			
		$node = array('name' => $new_node['name'], 'parent_id' => $parent_id, 'path' => explode('_',$nPath), 'sub' => $new_node['sub'], 'cPath' => $new_node['cPath'], 'nPath' => $nPath);	
		$this->category_tree[$current_id] = $node;
		
		if(isset($new_node['children']))
			foreach($new_node['children'] as  $child)
				$this->attachToCategoryTree($child, $current_id);
	}
	
	function retrieveDeepestLevelChildren($categories_id){
		$categories_id = $this->_getCategoriesId($categories_id);
		return (isset($this->category_tree[$categories_id]['deepest_cats']) ? $this->category_tree[$categories_id]['deepest_cats'] : array());
	}
	
	function buildDeepestLevelChildren(){
		if(!$this->is_deepest_cats_built){
			$this->_buildDeepestLevelChildren(0);
			$this->is_deepest_cats_built = true;
		}
		// for debugging using super global mod
		// $_POST['category_tree'] = $this->category_tree;
	}
	
	// 9 is a ridiculous level already. If you go deeper than that, you have some problem with performance + structure
	// Max level should be around 3
	// when strict is set to true, the tree will NOT expand even if it can, it will stick to the set max level
	function buildCategoryString($parent_html = 'div', $child_html = 'span', $categories_id = 0, $default_current_id = 1,
									$max_level = 9, $include_root = false, $strict = false){	
		if($this->current_id == -1) $this->current_id = $default_current_id;
		$categories_id = $this->_getCategoriesId($categories_id);
		$result = '';
		// don't check if max_level = 0, since we assume store owners are not crazy enough to do that
		// --> less check = faster
		if(isset($this->category_tree[$categories_id])){
			//
			$level = 0;
			$max_level++;
			$this->parent_html = $parent_html;
			$this->child_html = $child_html;
			// check if we should include the root or only its branches
			if($include_root && $categories_id > 0){		
				$class = $this->_buildClass($categories_id, 0);
						
				$child_html = str_replace(array('{class}','{name}','{link}','{child}'), array("{class0}","{name0}","{link0}","{child0}"),$this->child_html);
				$result = str_replace(array("{class0}","{name0}","{link0}"), array($class, $this->category_tree[$categories_id]['name'], $this->_buildLink($categories_id)), $child_html);
				
				$level=1;
				$child = $this->__buildCategoryString($categories_id, $level, $max_level, $strict);			
				
				$result = str_replace("{child0}", $child, $result);	
				
				$result = str_replace(array("{class}","{child}"), array($class, $result), $this->parent_html);	
			}
			else{
				$result .= $this->__buildCategoryString($categories_id, $level, $max_level, $strict);
			}
		}
		return $result;
	}

	function __buildCategoryString($categories_id, $level, $max_level, $strict){
		$result = '';
		$is_allowed_to_go_deeper = (!$strict && in_array($categories_id, $this->exceptional_list));
		if(($level < $max_level) || $is_allowed_to_go_deeper){
			$class = $this->_buildClass($categories_id, $level);
			$parent_level = $level;

			if($level > 0){
				$child_html = str_replace(array('{class}','{name}','{link}','{child}'), array("{class$level}","{name$level}","{link$level}","{child$level}"),$this->child_html);
				$result = str_replace(array("{class$level}","{name$level}","{link$level}"), array($class, $this->category_tree[$categories_id]['name'], $this->_buildLink($categories_id)), $child_html);
			}
			else 
				$result = "{child$level}";
				
			if(isset($this->category_tree[$categories_id]['sub_cats'])){
					$level ++;
					$child = '';
					$count = count($this->category_tree[$categories_id]['sub_cats']);
					for($i=0; $i < $count; $i++){
						//$child_class = $this->_buildClass($this->category_tree[$categories_id]['sub_cats'][$i], $level);
						$child .= $this->__buildCategoryString($this->category_tree[$categories_id]['sub_cats'][$i],$level,$max_level,$strict);
					}
					if(($level < $max_level) || $is_allowed_to_go_deeper){
						$class = $this->_buildClass($categories_id, $level);
						$child = str_replace(array("{class}","{child}"), array($class, $child), $this->parent_html);
						$result = str_replace("{child$parent_level}", $child, $result);
					}
					else 
						$result = str_replace("{child$parent_level}", '', $result);
				}
			else 
				$result = str_replace("{child$parent_level}", '', $result);
			
		}
		return $result;
	}
	
	function _buildTags($parent_tag, $child_tag){
		if(!empty($parent_tag)){
			$this->parent_open_tag = "<$parent_tag class='%s'>";
			$this->parent_close_tag = "</$parent_tag>";		
		}
		else{
			$this->parent_open_tag = $this->parent_close_tag = '';		
		}
		if(!empty($child_tag)){
			$this->child_open_tag = "<$child_tag class='%s'>";
			$this->child_close_tag = "</$child_tag>";		
		}
		else{
			$this->child_open_tag = $this->child_close_tag = '';
		}
	}
	
	function countSubCategories($categories_id){
		$categories_id = $this->_getCategoriesId($categories_id);
		return isset($this->category_tree[$categories_id]['sub_cats']) ? 
				count($this->category_tree[$categories_id]['sub_cats']) : 0;
	}
	
	function _buildClass($categories_id, $level){
		$class = "level_$level ".$this->category_tree[$categories_id]['sub'];
		if($this->current_id == -1)
			return $class;
			
		global $cPath;
		if(isset($_GET['nPath']))
			$cPath = $_GET['nPath'];
			
		$current_categories_array = array();
		if(!empty($cPath))
		$current_categories_array = explode('_', $cPath);
		
		if($categories_id == $this->current_id)
			$class .= ' current';
		elseif(in_array($categories_id, $current_categories_array))
			$class .= ' currentParent';
		
		return $class;
	}
	
	function _buildLink($categories_id){	
		//if(isset($this->category_tree[$categories_id]['link']))	
		//	return $this->category_tree[$categories_id]['link'];
			
		if(is_numeric($this->category_tree[$categories_id]['cPath']) || preg_match('/[0-9]+(_[0-9]+)+/', $this->category_tree[$categories_id]['cPath']))
			$link = zen_href_link(FILENAME_DEFAULT, 'cPath=' . $this->category_tree[$categories_id]['cPath']);
		else
			$link = $this->category_tree[$categories_id]['cPath'].(strpos($this->category_tree[$categories_id]['cPath'] , '?') !== false ? '&' : '?')."nPath={$this->category_tree[$categories_id]['nPath']}";
		
		//$this->category_tree[$categories_id]['link'] = $link;	
		return $link;
	}
	
	function _buildDeepestLevelChildren($categories_id){
		$parent_id = isset($this->category_tree[$categories_id]['parent_id']) ? $this->category_tree[$categories_id]['parent_id'] : -1;
		if(isset($this->category_tree[$categories_id]['sub_cats'])){
			foreach($this->category_tree[$categories_id]['sub_cats'] as $sub_cat){
					// we now need to loop thru these cats, and find if they have sub_cats
					$this->_buildDeepestLevelChildren($sub_cat);
			}
		}
		elseif($parent_id > 0){
			$this->category_tree[$parent_id]['deepest_cats'][] = $categories_id;
		}
		
		if($parent_id >= 0 && isset($this->category_tree[$categories_id]['deepest_cats'])){
			if(isset($this->category_tree[$parent_id]['deepest_cats']))
				$this->category_tree[$parent_id]['deepest_cats'] = array_merge($this->category_tree[$parent_id]['deepest_cats'],$this->category_tree[$categories_id]['deepest_cats']);
			else
				$this->category_tree[$parent_id]['deepest_cats'] = $this->category_tree[$categories_id]['deepest_cats'];
		}
	}
	
	function _getCategoriesId($categories_id){
		if(!is_int($categories_id)){
			$temp = explode('_',$categories_id);
			$categories_id = end($temp);
		}
		return $categories_id;
	}
}