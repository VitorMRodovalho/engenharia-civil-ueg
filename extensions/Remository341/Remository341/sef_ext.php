<?php

/**************************************************************
* This file is part of Remository
* Copyright (c) 2006 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
* Remository started life as the psx-dude script by psx-dude@psx-dude.net
* It was enhanced by Matt Smith up to version 2.10
* Since then development has been primarily by Martin Brampton,
* with contributions from other people gratefully accepted
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$remository_dir = str_replace('\\','/',dirname(__FILE__));

require_once($remository_dir.'/sef.custom.php');
require_once($remository_dir.'/remository.class.php');

class sef_remository {

	/**
	* Creates the SEF advance URL out of the Mambo request
	* Input: $string, string, The request URL (index.php?option=com_example&Itemid=$Itemid)
	* Output: $sefstring, string, SEF advance URL ($var1/$var2/)
	**/
	function create ($string) {
		global $mosConfig_live_site;
		// $string == "index.php?option=com_example&Itemid=$Itemid&var1=$var1&var2=$var2"
		sef_remository::getRemositoryData();
		$sefstring = "";
		$isContainer = false;
		$string = strtolower(str_replace( '&amp;', '&', $string ));
		if (strpos($string, 'index.php?') === 0) $string = substr($string,10);
		parse_str($string,$params);
		if (isset($params['option'])) unset($params['option']);
		if (isset($params['itemid'])) unset($params['itemid']);
		if (isset($params['func'])) {
			if (substr($params['func'], 0, 5) == 'func,') $params['func'] = substr($params['func'], 5);
			if ($params['func'] == 'select') {
				$isContainer = true;
				$isFile = false;
				$sefstring .= _REMOSITORY_SELECT_FROM_CONTAINER.'/';
			}
			elseif ($params['func'] == 'fileinfo') {
				$isContainer = true;
				$isFile = true;
				$sefstring .= _REMOSITORY_SELECT_FROM_CONTAINER.'/';
			}
			else $sefstring .= $params['func']."/";
			unset($params['func']);
		}
		if (isset($params['os'])) {
			$sefstring .= $params['os']."/";
			unset($params['os']);
		}
		if (isset($params['id'])) {
			if ($isContainer) {
				if ($isFile) {
					$user = new remositoryUser();
					$user->admin = true;
					$file = new remositoryFile($params['id']);
					$file->getValues($user);
					$containerID = $file->containerid;
				}
				else $containerID = $params['id'];
				$sefstring .= sef_remository::containerName($containerID).'/';
				if ($isFile) $sefstring .= sef_remository::nameForURL($file->filetitle).'/';
			}
			else $sefstring .= $params['id']."/";
			unset($params['id']);
		}
		foreach ($params as $key=>$param) $sefstring .= "$key,$param/";
		return $sefstring;
	}
	
	function checkDatabase () {
		global $database, $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix;
		if (!isset($database)) $database = new database( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );
	}

	function getRemositoryData () {
		sef_remository::checkDatabase();
		if (!isset($GLOBALS['remository_containers']) OR !isset($GLOBALS['remository_links'])) {
			$sql = 'SELECT * FROM #__downloads_containers ORDER BY sequence, name';
			$GLOBALS['remository_containers'] = remositoryRepository::doSQLget($sql,'remositoryContainer');
			$GLOBALS['remository_links'] = array();
			foreach ($GLOBALS['remository_containers'] as $i=>$container) $GLOBALS['remository_links'][$container->id] = $i;
		}
	}
	
	function containerName ($id) {
		if (!isset($GLOBALS['remository_links'][$id])) {
			die( 'Error - please tell Martin, ID='.$id);
			return '';
		}
		$sub = $GLOBALS['remository_links'][$id];
		$name = sef_remository::nameForURL($GLOBALS['remository_containers'][$sub]->name);
		$parent = $GLOBALS['remository_containers'][$sub]->parentid;
		if ($parent) return sef_remository::containerName($parent).'/'.$name;
		else return $name;
	}

	function nameForURL ($name) {
		global $remository_sef_name_chars, $remository_sef_translate_chars;
		foreach ($remository_sef_name_chars as $i=>$char) $name = str_replace($char, $remository_sef_translate_chars[$i], $name);
		return $name;
	}

	function findContainer ($name, $parentid, &$string) {
		foreach ($GLOBALS['remository_containers'] as $cont) {
			$safename = sef_remository::nameForURL($cont->name);
			if ($cont->parentid == $parentid AND $safename == $name) return $cont->id;
		}
		$sql = "SELECT * FROM #__downloads_files WHERE containerid=$parentid";
		$files = remositoryRepository::doSQLget($sql,'remositoryFile');
		foreach ($files as $file) {
			if ($name == sef_remository::nameForURL($file->filetitle)) {
				$string = str_replace('select','fileinfo',$string);
				$_GET['func'] = $_REQUEST['func'] = 'fileinfo';
				return $file->id;
			}
		}
		return $parentid;
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
		sef_remository::getRemositoryData();
		$QUERY_STRING = "";
		$parentid = 0;
		$opsystems = array('All', 'Linux', 'Windows', 'Mac', 'Palm', 'Other');
		if (isset($url_array[$pos+2]) && $url_array[$pos+2]!="") {
			// component/example/$var1/
			$func = $url_array[$pos+2];
			if (substr($func,0,5) == 'func,') $func = substr($func,5);
			if ($func == _REMOSITORY_SELECT_FROM_CONTAINER) $func = 'select';
			$_REQUEST['func'] = $_GET['func'] = $func;
			$QUERY_STRING .= "&func=$func";
		}
		for ($i=$pos+3; $i<count($url_array); $i++) {
			$parm = $url_array[$i];
			$posparm = explode(',',$parm);
			if (count($posparm) > 1) {
				$subs = $posparm[0];
				$_GET[$subs] = $_REQUEST[$subs] = $posparm[1];
				$QUERY_STRING .= "&$subs=$posparm[1]";
			}
			elseif (is_numeric($parm)) {
				$_REQUEST['id'] = $_GET['id'] = $id = $parm;
				$QUERY_STRING .= "&id=$id";
			}
			elseif (in_array($parm,$opsystems)) {
				$_REQUEST['os'] = $_GET['os'] = $os = $parm;
				$QUERY_STRING .= "&os=$os";
			}
			elseif ($parm) {
				$parentid = sef_remository::findContainer($parm,$parentid,$QUERY_STRING);
			}
		}
		if ($parentid) {
			$_REQUEST['id'] = $_GET['id'] = $parentid;
			$QUERY_STRING .= "&id=$parentid";
		}
		if (isset($func) AND $func == 'rss' AND !isset($_REQUEST['no_html'])) {
				$_REQUEST['no_html'] = $_GET['no_html'] = 1;
				$QUERY_STRING .= "&no_html=1";
		}

		return $QUERY_STRING;
	}

}

?>
