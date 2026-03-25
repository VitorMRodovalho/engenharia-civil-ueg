<?php

/**************************************************************
/*  SEF transformation code for the Joomla! content component
/*  This code is compatible with Joomla 1.0.x and is effective
/*  when Remosef for Joomla is installed, SEO is switched on 
/*  through the admin interface, Site, Global Configuration, 
/*  SEO tab.
/*
/*  This code should be placed on your web server in the
/*  /components/com_content/ directory.
/*
/*  Author: Martin Brampton
/*  Date: 1 November 2006
/*  Copyright (c) Martin Brampton - all rights reserved
/*  This code is released as commercial Freeware.  You may use it
/*  free of charge provided the author is relieved of all liability
/*  in respect of its use.  You may not use the code to derive other
/*  code, or redistribute the code to others.
/*******************************************************************/

class sef_content {

	function create ($task,$params) {
		$mambosef =& mosSef::getInstance();
		// $string == "index.php?option=com_example&Itemid=$Itemid&var1=$var1&var2=$var2"
		sef_content::getContentData();
		if (isset($params['Itemid'])) unset($params['Itemid']);
		$seftask = $mambosef->translateContentTask($task);
		$sefstring = "/$seftask/";
		if (strpos($task,'section') !== false) {
			if (isset($params['id']) AND $params['id']) $sefstring .= $mambosef->content_sections['name'][$params['id']].'/';
		}
		elseif (strpos($task,'category') !== false) {
			if (isset($params['id']) AND $params['id']) {
				$sectionid = $mambosef->content_categories['sectionid'][$params['id']];
				$sefstring .= ($sectionid ? $mambosef->content_sections['name'][$sectionid].'/' : 'x/');
				$sefstring .= $mambosef->content_categories['name'][$params['id']].'/';
			}
		}
		elseif (strpos($task,'view') !== false) {
			if (isset($params['id']) AND $params['id']) {
				$sectionid = $mambosef->content_items['sectionid'][$params['id']];
				$catid = $mambosef->content_items['catid'][$params['id']];
				if ($sectionid OR $catid) {
					$sefstring .= ($sectionid ? $mambosef->content_sections['name'][$sectionid].'/' : 'x/');
					$sefstring .= ($catid ? $mambosef->content_categories['name'][$catid].'/': 'x/');
				}
				$sefstring .= $mambosef->content_items['name'][$params['id']].'/';
			}
		}
		if (isset($params['limit']) AND isset($params['limitstart'])) $sefstring .= $params['limit'].','.$params['limitstart'].'/';
		return $sefstring;
	}

	function getContentData () {
		global $database;
		$mambosef =& mosSef::getInstance();
		if (!isset($mambosef->content_data)) {
			$mambosef->content_data = 'OK';
			$sql = 'SELECT id, sectionid, catid, title FROM #__content';
			$database->setQuery($sql);
			$items = $database->loadObjectList();
			if ($items) {
				foreach ($items as $item) {
					$mambosef->content_items['name'][$item->id] = $mambosef->nameForURL($item->title);
					$mambosef->content_items['sectionid'][$item->id] = $mambosef->nameForURL($item->sectionid);
					$mambosef->content_items['catid'][$item->id] = $mambosef->nameForURL($item->catid);
				}
			}
			$sql = 'SELECT id, title FROM #__sections';
			$database->setQuery($sql);
			$sections = $database->loadObjectList();
			if ($sections) {
				foreach ($sections as $section) $mambosef->content_sections['name'][$section->id] = $mambosef->nameForURL($section->title);
			}
			$sql = 'SELECT id, section, name FROM #__categories';
			$database->setQuery($sql);
			$categories = $database->loadObjectList();
			if ($categories) {
				foreach ($categories as $category) {
					if (is_numeric($category->section)) {
						$mambosef->content_categories['name'][$category->id] = $mambosef->nameForURL($category->name);
						$mambosef->content_categories['sectionid'][$category->id] = $mambosef->nameForURL($category->section);
					}
				}
			}
		}
	}

	function revert ($url_array, $urlstart) {
		$mambosef =& mosSef::getInstance();
		if ($mambosef->register_globals AND !ini_get('register_globals')) {
			global $id, $section, $Itemid, $limit, $limitstart;
		}
		$foundit = false;
		$tasktest = $mambosef->untranslateContentTask($url_array[$urlstart+1]);
		if ($tasktest !== false AND $tasktest !== null) {
			$foundit = true;
			$_REQUEST['option'] = $_GET['option'] = $option = 'com_content';

			// language hook for content
			$lang = "";
			$parms = array();
			foreach($url_array as $key=>$value) {
				if ($key <= $urlstart+1) continue;
				if ( strcasecmp(substr($value,0,5),'lang,') == 0 ) {
					$parts = explode(",", $value);
					if (count($parts) > 1) {
						$lang = $_REQUEST['lang'] = $_GET['lang'] = $parts[1];
					}
				}
				elseif ($value) $parms[] = $value;
			}

			// $option/$task/$sectionid/$id/$Itemid/$limit/$limitstart
			$_REQUEST['task'] = $_GET['task'] = $task = $tasktest;
			$QUERY_STRING = "option=com_content&task=$task";
		}
		else return '';
		sef_content::getContentData();
		$pagerequest = '';
		$catid = 0;
		$sectionid = 0;
		if (count($parms) > 1) {
			$paging = $parms[count($parms)-1];
			$pageparts = explode(',',$paging);
			if (count($pageparts) == 2 AND is_numeric($pageparts[0]) and is_numeric($pageparts[1])) {
				$_REQUEST['limit'] = $_GET['limit'] = $limit = intval($pageparts[0]);
				$_REQUEST['limitstart'] = $_GET['limitstart'] = $limitstart = intval($pageparts[1]);
				$pagerequest = "&limit=$limit&limitstart=$limitstart";
				unset($parms[count($parms)-1]);
			}
		}

		if ($task == 'view' AND count($parms) == 1) {
			foreach ($mambosef->content_items['name'] as $i=>$item) {
				if ($parms[0] == $item AND $mambosef->content_items['sectionid'][$i] == 0 AND $mambosef->content_items['catid'][$i] == 0) {
					$_REQUEST['id'] = $_GET['id'] = $id = $i;
					$QUERY_STRING .= "&id=$id";
					unset($parms[0]);
					break;
				}
			}
		}
		else {
			if (isset($parms[0])) $idtest = array_search($parms[0],$mambosef->content_sections['name']);
			else $idtest = false;
			if ($idtest !== false) {
				$sectionid = $idtest;
				unset($parms[0]);
			}
			else $sectionid = 0;
			if (isset($parms[1])) {
				foreach ($mambosef->content_categories['name'] as $i=>$category) {
					if ($parms[1] == $category AND $mambosef->content_categories['sectionid'][$i] == $sectionid) {
						$catid = $i;
						unset($parms[1]);
						break;
					}
				}
	            if (isset($parms[2])) {
					foreach ($mambosef->content_items['name'] as $i=>$item) {
						if ($parms[2] == $item AND $mambosef->content_items['sectionid'][$i] == $sectionid AND $mambosef->content_items['catid'][$i] == $catid) {
							$_REQUEST['id'] = $_GET['id'] = $id = $i;
							$QUERY_STRING .= "&id=$id";
							unset($parms[2]);
							break;
						}
					}
				}
				else {
					$_REQUEST['sectionid'] = $_GET['sectionid'] = $sectionid;
					$_REQUEST['id'] = $_GET['id'] = $id = $catid;
					$QUERY_STRING .= "&sectionid=$sectionid&id=$id";
				}
			}
			else {
				$_REQUEST['id'] = $_GET['id'] = $section = $id = $sectionid;
				$QUERY_STRING .= "&id=$section";
			}
		}

		if (count($parms) != 0) return '';

		$Itemid = $mambosef->getItemid('index.php?'.$QUERY_STRING);
		if ($Itemid) {
			$_REQUEST['Itemid'] = $_GET['Itemid'] = $Itemid;
			$QUERY_STRING .= "&Itemid=$Itemid";
		}
		else return '';
		
		$QUERY_STRING .= $pagerequest;
		if ($lang!="") {
			$QUERY_STRING .= "&lang=$lang";
		}
		return $QUERY_STRING;
	}

}

?>