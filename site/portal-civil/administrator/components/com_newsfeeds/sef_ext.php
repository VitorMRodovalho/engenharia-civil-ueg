<?php
/*******************************************************************************
*
* Newsfeeds Component - sef_ext.php module.
*
*  SEF transformation code for the Mambo newsfeeds component
*  This code is compatible with Mambo 4.6 and is effective
*  when SEO is switched on through the admin interface, Site,
*  Global Configuration, SEO tab.
*  This code should be placed on your web server in the
*  /components/com_newsfeeds/ directory.
*
* Copyright (C) 2006 Martin Brampton, martin@mamboguru.com, http://www.mamboguru.com
* This is commercial freeware - that is it is made available without charge, but
* all distribution and copying rights are reserved.  It may only be obtained
* directly from the author for personal (not excluding for profit) uses and may
* not be incorporate into other software or re-distributed.
* Comments: for Mambo v 4.6 or compatible systems if any
*
*******************************************************************************/

DEFINE('_NEWSFEEDS_VIEW','view');

class sef_newsfeeds {
	var $newsfeeds = array();
	var $newscats = array();
	
	function sef_newsfeeds () {
		sef_newsfeeds::getNewsfeedsData();
	}

	function &getInstance () {
		static $instance;
		if (!is_a($instance, 'sef_newsfeeds')) $instance = new sef_newsfeeds();
		return $instance;
	}
	
	/**
	* Creates the SEF advance URL out of the Mambo request
	* Input: $string, string, The request URL (index.php?option=com_example&Itemid=$Itemid)
	* Output: $sefstring, string, SEF advance URL ($var1/$var2/)
	**/
	function create ($string) {
		// $string == "index.php?option=com_example&Itemid=$Itemid&var1=$var1&var2=$var2"
		$sefstring = "";
		$isView = false;
		$string = strtolower(str_replace( '&amp;', '&', $string ));
		if (strpos($string, 'index.php?') === 0) $string = substr($string,10);
		parse_str($string,$params);
		if (isset($params['option'])) unset($params['option']);
		if (isset($params['itemid'])) unset($params['itemid']);
		if (isset($params['task'])) {
			if ($params['task'] == 'view') {
				$isView = true;
				$sefstring .= _NEWSFEEDS_VIEW.'/';
			}
			unset($params['task']);
		}
		if ($isView AND isset($params['feedid'])) {
			$newsfeedID = $params['feedid'];
			$sefstring .= sef_newsfeeds::newsfeedName($newsfeedID).'/';
			unset($params['feedid']);
		}
		if (!$isView AND isset ($params['catid'])) {
			$newsfeedcat = $params['catid'];
			$sefstring .= sef_newsfeeds::newsfeedCatName($newsfeedcat);
			unset($params['catid']);
		}
		foreach ($params as $key=>$param) $sefstring .= "$key,$param/";
		return $sefstring;
	}
	
	function getNewsfeedsData () {
		if (is_callable(array('mamboDatabase','getInstance'))) $database = mamboDatabase::getInstance();
		else global $database;
		$mambosef = mosSef::getInstance();
		$sql = "SELECT id, name FROM #__newsfeeds";
		$database->setQuery($sql);
		$results = $database->loadObjectList();
		if ($results) foreach ($results as $anewsfeed) {
			$this->newsfeeds[$anewsfeed->id] = $mambosef->nameForURL($anewsfeed->name);
		}
		$sql = "SELECT id, name FROM #__categories WHERE section='com_newsfeeds'";
		$database->setQuery($sql);
		$results = $database->loadObjectList();
		if ($results) foreach ($results as $acategory) {
			$this->newscats[$acategory->id] = $mambosef->nameForURL($acategory->name);
		}
	}
	
	function newsfeedName ($id) {
		$name = $this->newsfeeds[$id];
		return $name;
	}
	
	function newsfeedCatName ($catid) {
		$name = $this->newscats[$catid];
		return $name;
	}

	function findNewsfeed ($name) {
		$id = array_search ($name, $this->newsfeeds);
		return $id;
	}
	
	function findCategory ($name) {
		$catid = array_search ($name, $this->newscats);
		return $catid;
	}

	/**
	* Reverts to the Mambo query string out of the SEF advance URL
	* Input:
	*    $url_array, array, The SEF advance URL split in arrays (first custom virtual directory beginning at $pos+1)
	*    $pos, int, The position of the first virtual directory (component)
	* Output: $QUERY_STRING, string, Mambo query string (var1=$var1&var2=$var2)
	*    Note that this will be added to already defined first part (option=com_example&Itemid=$Itemid)
	**/
	function revert ($url_array, $pos) {
		// define all variables you pass as globals - not required for Remository - uses super globals
 		// Examine the SEF advance URL and extract the variables building the query string
		$QUERY_STRING = "";
		if (isset($url_array[$pos+3]) AND $url_array[$pos+3] != '') {
			// component/example/$var1/
			$task = $url_array[$pos+2];
			if ($task == _NEWSFEEDS_VIEW) $task = 'view';
			$_REQUEST['task'] = $_GET['task'] = $task;
			$QUERY_STRING .= "&task=$task";
			if ($task == 'view' AND isset($url_array[$pos+3])) {
				$id =  $this->findNewsfeed($url_array[$pos+3]);
				$_REQUEST['feedid'] = $_GET['feedid'] = $id;
				$QUERY_STRING .= "&feedid=$id";
			}
		}
		elseif (isset($url_array[$pos+2]) AND $url_array[$pos+2] != '') {
			$id = $this->findCategory($url_array[$pos+2]);
			$_REQUEST['catid'] = $_GET['catid'] = $id;
			$QUERY_STRING .= "&catid=$id";
		}

		return $QUERY_STRING;
	}

}

?>
