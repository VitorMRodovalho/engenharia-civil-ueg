<?php
/******************************************************************************
*
* Mambo Guru Contact Component - sef_ext.php module.
*
*  SEF transformation code for the Mambo Guru contact component
*  This code is compatible with Mambo 4.6 and is effective
*  when SEO is switched on through the admin interface, Site,
*  Global Configuration, SEO tab.
*  This code should be placed on your web server in the
*  /components/com_mgcontact/ directory.
*
* Copyright (C) 2006 Martin Brampton, martin@mamboguru.com, http://www.mamboguru.com
* This is commercial freeware - that is it is made available without charge, but
* all distribution and copying rights are reserved.  It may only be obtained
* directly from the author for personal (not excluding for profit) uses and may
* not be incorporate into other software or re-distributed.
* Comments: for Mambo v 4.6 or compatible systems if any
*
*******************************************************************************/

DEFINE('_CONTACT_VIEW','view');

class sef_contact {
	var $contacts = array();

	function &getInstance () {
		static $instance;
		if (!is_a($instance, 'sef_contact')) $instance = new sef_contact();
		return $instance;
	}

	/**
	* Creates the SEF advance URL out of the Mambo request
	* Input: $string, string, The request URL (index.php?option=com_example&Itemid=$Itemid)
	* Output: $sefstring, string, SEF advance URL ($var1/$var2/)
	**/
	function create ($string) {
		// $string == "index.php?option=com_example&Itemid=$Itemid&var1=$var1&var2=$var2"
		sef_contact::getContactData();
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
				$sefstring .= _CONTACT_VIEW.'/';
			}
			unset($params['task']);
		}
		if ($isView AND isset($params['contact_id'])) {
			$contactID = $params['contact_id'];
			$sefstring .= sef_contact::contactName($contactID).'/';
			unset($params['contact_id']);
		}
		foreach ($params as $key=>$param) $sefstring .= "$key,$param/";
		return $sefstring;
	}
	
	function getContactData () {
		$mambosef = mosSef::getInstance();
		$sql = "SELECT id, name FROM #__contact_details";
		if (is_callable(array('mamboDatabase','getInstance'))) $database = mamboDatabase::getInstance();
		else global $database;
		$database->setQuery($sql);
		$results = $database->loadObjectList();
		if ($results) foreach ($results as $acontact) {
			$this->contacts[$acontact->id] = $mambosef->nameForURL($acontact->name);
		}
	}
	
	function contactName ($id) {
		$name = $this->contacts[$id];
		return $name;
	}

	function findContact ($name) {
		$id = array_search ($name, $this->contacts);
		return $id;
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
		sef_contact::getContactData();
		$QUERY_STRING = "";
		if (isset($url_array[$pos+2]) AND $url_array[$pos+2] != '') {
			// component/example/$var1/
			$task = $url_array[$pos+2];
			if ($task == _CONTACT_VIEW) $task = 'view';
			$_REQUEST['task'] = $_GET['task'] = $task;
			$QUERY_STRING .= "&task=$task";
		}
		if (isset($url_array[$pos+3])) {
			$id =  $this->findContact($pos+3);
			$_REQUEST['contact_id'] = $_GET['contact_id'] = $id;
			$QUERY_STRING .= "&contact_id=$id";
		}

		return $QUERY_STRING;
	}

}

?>
