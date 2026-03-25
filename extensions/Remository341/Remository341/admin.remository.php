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

// Don't allow direct linking
if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( 'Direct Access to this location is not allowed.' );

//error_reporting(E_ALL);

// Include files that contain classes
$remository_dir = str_replace('\\','/',dirname(__FILE__));
require_once($remository_dir.'/remository.interface.php');
$interface =& remositoryInterface::getInstance();
require_once($interface->getCfg('absolute_path').'/administrator/includes/pageNavigation.php');
require_once( $interface->getPath( 'admin_html' ) );
require_once( $interface->getPath( 'class' ) );

class remositoryAdminManager {
	var $ReMOSver = '1.0';
	var $plugin_name = '';
	var $act = '';
	var $actname = '';
	var $task = '';
	var $limitstart = 0;
	var $limit = 0;
	var $cfid = 0;
	var $currid = 0;
	var $c_classes_path = '';
	var $v_classes_path = '';
	
	function remositoryAdminManager ($plugin_name, $ReMOSver) {
		$this->ReMOSver = $ReMOSver;
		$interface =& remositoryInterface::getInstance();
		// Include files that contain definitions
		$repository =& remositoryRepository::getInstance();
		// Need to set all the config variables in case any are used in the language file
		foreach (get_class_vars(get_class($repository)) as $k=>$v) $$k = $repository->$k;
		require_once ($interface->getCfg('absolute_path').'/components/com_remository/com_remository_constants.php');
		$mosConfig_absolute_path = $interface->getCfg('absolute_path');
		$mosConfig_lang = $interface->getCfg('lang');
		$mosConfig_sitename = $interface->getCfg('sitename');
		if(file_exists($interface->getCfg('absolute_path').'/components/com_remository/language/'.$interface->getCfg('lang').'.php')) require_once($interface->getCfg('absolute_path').'/components/com_remository/language/'.$interface->getCfg('lang').'.php');
		if($interface->getCfg('lang') != 'english' AND file_exists($interface->getCfg('absolute_path').'/components/com_remository/language/english.php')) require_once($interface->getCfg('absolute_path').'/components/com_remository/language/english.php');
		$this->plugin_name = $plugin_name;
		$this->c_classes_path = $this->v_classes_path = $interface->getCfg('absolute_path').'/components/com_remository/';
		$this->c_classes_path .= 'c-admin-classes/';
		$this->v_classes_path .= 'v-admin-classes/';
		$this->noMagicQuotes();
		if ($this->act = remositoryRepository::GetParam ($_REQUEST, 'act', 'cpanel'));
		else $this->act = 'cpanel';
		$_REQUEST['act'] = $this->act;
		$this->actname = strtoupper(substr($this->act,0,1)).strtolower(substr($this->act,1));
		if ($this->task = remositoryRepository::GetParam($_REQUEST, 'task', 'list'));
		else $this->task = 'list';
		$default_limit  = $interface->getUserStateFromRequest( "viewlistlimit", 'limit', 20 );
		$this->limit = intval( remositoryRepository::getParam( $_REQUEST, 'limit', $default_limit ) );
		$this->limitstart = intval( remositoryRepository::getParam( $_REQUEST, 'limitstart', 0 ) );
		$this->cfid = remositoryRepository::getParam($_REQUEST, 'cfid', array(0));
		if (is_array( $this->cfid )) {
			foreach ($this->cfid as $key=>$value) $this->cfid[$key] = intval($value);
			$this->currid=$this->cfid[0];
		}
		else $this->currid = intval($this->cfid);
		$control_class = $this->plugin_name.'Admin'.$this->actname;
		if (file_exists($this->c_classes_path.$control_class.'.php')) {
			require_once ($this->c_classes_path.$control_class.'.php');
			if (class_exists($control_class)) {
				$controller =& new $control_class($this);
				$task = $this->task.'Task';
				if (is_callable(array($controller,$task))) $controller->$task();
				else trigger_error(sprintf(_DOWN_METHOD_NOT_PRESENT, $this->plugin_name, $task, $control_class));
			}
			else trigger_error(sprintf(_DOWN_CLASS_NOT_PRESENT, $this->plugin_name, $control_class));
		}
		else {
			$view_class = 'list'.$this->actname.'HTML';
			$controller =& new remositoryAdminControllers($this);
			$view = $this->newHTMLClassCheck ($view_class, $controller, 0, '');
			if ($view AND $this->checkCallable($view, 'view')) $view->view($this->ReMOSver);
			else trigger_error(sprintf(_DOWN_CLASS_NOT_PRESENT, $this->plugin_name, $view_class));
		}
	}
	
	function noMagicQuotes () {
		// Is magic quotes on?
		if (get_magic_quotes_gpc()) {
			// Yes? Strip the added slashes
			$_REQUEST = $this->remove_magic_quotes($_REQUEST);
			$_GET = $this->remove_magic_quotes($_GET);
			$_POST = $this->remove_magic_quotes($_POST);
		}
		set_magic_quotes_runtime(0);
	}
	
	function remove_magic_quotes ($array) {
		foreach ($array as $k => $v) {
			if (is_array($v)) $array[$k] = $this->remove_magic_quotes($v);
			else $array[$k] = stripslashes($v);
		}
		return $array;
	}
	
	function check_selection ($text) {
		if (!is_array($this->cfid) OR count( $this->cfid ) < 1) {
			echo "<script> alert('".$text."'); window.history.go(-1);</script>\n";
			exit;
		}
	}
	
	function newHTMLClassCheck ($name, &$controller, $total_items, $clist) {
		$controller->makePageNav($this, $total_items);
		if (file_exists($this->v_classes_path.$name.'.php')) require_once ($this->v_classes_path.$name.'.php');
		if (class_exists($name)) return new $name ($controller, $this->limit, $clist);
		trigger_error(sprintf("Component $this->plugin_name error: attempt to use non-existent class ", $this->plugin_name, $name));
		return false;
	}
	
	function checkCallable ($object, $method) {
		if (is_callable(array($object, $method))) return true;
		$name = get_class($object);
		trigger_error(sprintf("Component $this->plugin_name error: attempt to use non-existent method $method in $name", $this->plugin_name, $method, $name));
		return false;
	}

}

class remositoryAdminControllers {
	var $ReMOSver = '3.40';
	var $remUser = '';
	var $repository = '';
	var $interface = '';
	var $admin = '';
	var $pageNav = '';
	
	function remositoryAdminControllers ($admin) {
		// global $my, $mainframe, $mosConfig_lang, $mosConfig_sitename;
		$this->admin = $admin;
		$this->repository =& remositoryRepository::getInstance();
		$this->interface =& remositoryInterface::getInstance();
		$this->remUser = $this->interface->getUser();
	}
	
	function makePageNav (&$admin, $total) {
		$this->pageNav =& $this->interface->makePageNav( $total, $admin->limitstart, $admin->limit );
	}

	function backTask() {
		$this->interface->redirect( "index2.php?option=com_remository");
	}

	function error_popup ($message) {
		echo "<script> alert('".$message."'); window.history.go(-1); </script>\n";
	}

}


$admin =& new remositoryAdminManager('remository', '3.40');

?>