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

class remositoryUtilities {
	
	function remos_get_module_parm ($params, $name, $default) {
		$value =  (method_exists($params,'get') ? $params->get($name,$default) : $params->$name);
		if ($value == '') return $default;
		$isnumeric = is_numeric($default);
		if ($isnumeric AND !is_numeric($value)) return $default;
		if ($isnumeric) return intval($value);
		return $value;
	}

	function visibilitySQL () {
		global $See_Files_no_download, $my;
		$sql = '';
		if ((strtolower($my->usertype)=='administrator') || (strtolower($my->usertype)=='superadministrator')
			|| (strtolower($my->usertype)=='super administrator')) return $sql;
		if (!$See_Files_no_download) {
			$grouplist = remositoryUtilities::getMembersGroupList ();
			if (strlen($grouplist)) $sql .= " AND ((f.registered & 2) OR ((f.userupload & 2) AND f.groupid IN ($grouplist)))";
			else $sql .= " AND (f.registered & 2)";
		}
		return $sql;
	}

	function getMembersGroupList () {
		global $See_Files_no_download, $my, $database;
		if (!$See_Files_no_download AND $my->id) {
			$tablename = '#__mbt%';
			$database->setQuery($tablename);
			$tablename = $database->_sql;
			$sql = "SHOW TABLES LIKE '$tablename'";
			$database->setQuery($sql);
			$tables = $database->loadObjectList();
			if ($tables AND count($tables)) {
				$sql = "SELECT group_id FROM `#__mbt_group_member` WHERE member_id=$my->id";
				$database->setQuery($sql);
				$groups = $database->loadResultArray();
				if ($groups) $groups[] = 0;
				else $groups = array(0);
			}
			else $groups = array(0);
			$grouplist = implode(',',$groups);
		}
		else $grouplist = null;
		return $grouplist;
	}

	function remos_module_CSS () {
		$content = '';
		$content .= "<style type='text/css'>\n";
		$content .= ".ontab {\n";
		$content .= "	font-family : Verdana, Arial, Helvetica, sans-serif;\n";
		$content .= "	font-size: 8pt;\n";
		$content .= "	background-color: #ffae00;\n";
		$content .= "	border-left: outset 2px #ff9900;\n";
		$content .= "	border-right: outset 2px #808080;\n";
		$content .= "	border-top: outset 2px #ff9900;\n";
		$content .= "	border-bottom: solid 1px #d5d5d5;\n";
		$content .= "	width: 14%;\n";
		$content .= "	text-align: center;\n";
		$content .= "	cursor: hand;\n";
		$content .= "	font-weight: normal;\n";
		$content .= "	color: #FFFFFF;\n";
		$content .= "}\n";
		$content .= ".offtab {\n";
		$content .= "	font-family : Verdana, Arial, Helvetica, sans-serif;\n";
		$content .= "	font-size: 8pt;\n";
		$content .= " background-color : #e5e5e5;\n";
		$content .= "	border-left: outset 2px #E0E0E0;\n";
		$content .= "	border-right: outset 2px #E0E0E0;\n";
		$content .= "	border-top: outset 2px #E0E0E0;\n";
		$content .= "	border-bottom: solid 1px #d5d5d5;\n";
		$content .= "	width: 14%;\n";
		$content .= "	text-align: center;\n";
		$content .= "	cursor: hand;\n";
		$content .= "	font-weight: normal;\n";
		$content .= "}\n";
		$content .= ".tabheading {\n";
		$content .= "	background-color: #ffae00;\n";
		$content .= "	color: #FFFFFF;\n";
		$content .= "	font-family : Verdana, Arial, Helvetica, sans-serif;\n";
		$content .= "	font-size: 8pt;\n";
		$content .= "	text-align: left;\n";
		$content .= "}\n";
		$content .= ".pagetext {\n";
		$content .= "	visibility: hidden;\n";
		$content .= "	display: none;\n";
		$content .= "	position: relative;\n";
		$content .= "	top: 0;\n";
		$content .= "}\n";
		$content .= ".number{\n";
		$content .= "	font: 8pt Verdana, Geneva, Arial, Helvetica, sans-serif;\n";
		$content .= "	text-align: center;\n";
		$content .= "	text-decoration: underline;\n";
		$content .= "	}\n";
		$content .= "</style>\n";
		$content .= "<table cellspacing=\"2\" cellpadding=\"1\" border=\"0\" width=\"100%\">\n";
		return $content;
	}
}

if (!is_callable('remos_getItemID')) {
	function remos_getItemID ($component_string) {
		global $database;
		if (isset($GLOBALS['remosef_itemids'][$component_string])) return $GLOBALS['remosef_itemids'][$component_string];
		$database->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=$component_string'");
		$GLOBALS['remosef_itemids'][$component_string] = $database->loadResult();
		return $GLOBALS['remosef_itemids'][$component_string];
	}
}

?>
