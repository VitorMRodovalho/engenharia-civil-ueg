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
if (!isset($GLOBALS['remositoryInterface'])) {

$GLOBALS['remositoryInterface'] = 1;

class remositoryInterface {

	var $mainframe;
	var $absolute_path;
	var $live_site;
	var $lang;
	var $sitename;

	function remositoryInterface () {
		$this->getMainFrame();
	}

	function getMainFrame () {
		if (!is_a($this->mainframe, 'mosMainFrame')) {
			if (is_callable(array('mosMainFrame', 'getInstance'))) $this->mainframe =& mosMainFrame::getInstance();
			else {
				global $mainframe;
				$this->mainframe =& $mainframe;
			}
		}
	}

	function &getInstance () {
        static $instance;
        if (!is_object($instance)) $instance = new remositoryInterface();
        return $instance;
    }

	function getCfg ($string) {
		if (is_a($this->mainframe,'mosMainFrame')) return $this->mainframe->getCfg($string);
		else $this->getMainFrame();
		if (is_a($this->mainframe,'mosMainFrame')) return $this->mainframe->getCfg($string);
		else {
			if (!isset($this->absolute_path)) $this->absolute_path = str_replace('\\', '/', substr(__FILE__, 0, -strlen('/components/com_remository/remository.interface.php')));
			if ($string == 'absolute_path') return $this->absolute_path;
			if ($string == 'live_site' AND isset($this->live_site)) return $this->live_site;
			if ($string == 'lang' AND isset($this->lang)) return $this->lang;
			if ($string == 'sitename' AND isset($this->sitename)) return $this->sitename;
			include ($this->absolute_path.'/configuration.php');
			$this->live_site = $mosConfig_live_site;
			$this->lang = $mosConfig_lang;
			$this->sitename = $mosConfig_sitename;
			$configitem = 'mosConfig_'.$string;
			return $$configitem;
		}
	}

	function &getDB () {
		if (class_exists('mamboDatabase')) $database =& mamboDatabase::getInstance();
		else global $database;
		return $database;
	}

	function getEscaped ($string) {
		$database =& $this->getDB();
		return $database->getEscaped($string);
	}

	function getUser () {
		if (is_callable(array('mamboCore','get'))) {
			if (mamboCore::is_set('currentUser')) $my = mamboCore::get('currentUser');
			else $my = aliroUser::getInstance();
		}
		else global $my;
		$user =& new remositoryUser ($my->id,$my);
		return $user;
	}

	function getIdentifiedUser ($id) {
		$database =& $this->getDB();
		$my =& new mosUser($database);
		$my->load($id);
		return $my;
	}

	function getCurrentItemid () {
		if (is_callable(array('mamboCore','get'))) $Itemid =& mamboCore::get('Itemid');
		else global $Itemid;
		return intval($Itemid);
	}

	function getUserStateFromRequest ($var_name, $req_name, $var_default=null) {
		$this->getMainFrame();
		$mainframe = $this->mainframe;
		if (isset($var_default) AND is_numeric($var_default)) $forcenumeric = true;
		else $forcenumeric = false;
		if (isset($_REQUEST[$req_name])) {
			if ($forcenumeric) $mainframe->setUserState($var_name, intval($_REQUEST[$req_name]));
			else $mainframe->setUserState($var_name, $_REQUEST[$req_name]);
		}
        elseif (isset($var_default) AND !isset($mainframe->userstate[$var_name])) $mainframe->setUserState($var_name, $var_default);
        return $mainframe->getUserState($var_name);
	}

	function getPath ($name, $option='') {
		$this->getMainFrame();
		return $this->mainframe->getPath($name, $option);
	}

	function setPageTitle ($title) {
		$this->getMainFrame();
		$canSetTitle = array($this->mainframe, 'SetPageTitle');
		if (is_callable($canSetTitle)) $this->mainframe->SetPageTitle($title);
	}

	function prependMetaTag ($tag, $content) {
		$this->getMainFrame();
		if (method_exists($this->mainframe, 'prependMetaTag')) $this->mainframe->prependMetaTag($tag, $content);
	}

	function addCustomHeadTag ($tag) {
		$this->getMainFrame();
		$this->mainframe->addCustomHeadTag($tag);
	}

    function redirect ($url, $msg='') {
    	mosRedirect($url, $msg);
    }

    function &makeTabs () {
    	$tabs =& new mosTabs(0);
    	return $tabs;
    }

    function &makePageNav ($total, $limitstart, $limit) {
    	$pagenav = new mosPageNav ($total, $limitstart, $limit);
    	return $pagenav;
    }

    function triggerMambots ($event, $args=null, $doUnpublished=false) {
    	global $_MAMBOTS;
    	if (class_exists('aliroRequest')) $handler = aliroMambotHandler::getInstance();
    	else $handler = $_MAMBOTS;
    	$handler->trigger($event, $args=null, $doUnpublished);
    }

}

}

?>