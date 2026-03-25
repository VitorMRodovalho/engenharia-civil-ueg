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

$remository_dir = str_replace('\\','/',dirname(__FILE__));
require_once($remository_dir.'/remository.interface.php');
$interface =& remositoryInterface::getInstance();
require_once( $interface->getPath( 'class' ) );
require_once( $interface->getPath( 'front_html' ) );

//error_reporting(E_ALL);

class remositoryUserAdmin {
	var $magic_quotes_value = 0;
	var $c_classes_path = '';
	var $v_classes_path = '';
	
	function remositoryUserAdmin ($component, $control_name, $alternatives, $default, $title) {
		$interface =& remositoryInterface::getInstance();
		// Is magic quotes on?
		if (get_magic_quotes_gpc()) {
		 	// Yes? Strip the added slashes
			$_REQUEST = $this->remove_magic_quotes($_REQUEST);
			$_GET = $this->remove_magic_quotes($_GET);
			$_POST = $this->remove_magic_quotes($_POST);
		}
		$this->magic_quotes_value = get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		$this->c_classes_path = $this->v_classes_path = $interface->getCfg('absolute_path').'/components/com_remository/';
		$this->c_classes_path .= 'c-classes/';
		$this->v_classes_path .= 'v-classes/';
		$interface->SetPageTitle($title);
		$func = remositoryRepository::getParam ($_REQUEST, $control_name, $default);
		if (isset($alternatives[$func])) $method = $alternatives[$func];
		else $method = $func;
		$classname = $component.'_'.$method.'_Controller';
		$classfile = $this->c_classes_path.$classname.'.php';
		if (file_exists($classfile)) require_once ($classfile);
		$no_html = remositoryRepository::getParam($_REQUEST, 'no_html', 0);
		if (!$no_html) {
			echo "\n<!-- Start of Remository HTML -->";
			echo "\n<div id='remository'>";
		}
		if (class_exists($classname)) {
			$controller =& new $classname($this);
			if (is_callable(array($controller,$method))) $controller->$method($func);
			else {
				header ('HTTP/1.1 404 Not Found');
				trigger_error("Component $component error: attempt to use non-existent method $method in $controller");
			}
		}
		else {
			header ('HTTP/1.1 404 Not Found');
			trigger_error("Component $component error: attempt to use non-existent class $classname");
		}
		if (!$no_html) {
			echo "\n</div>";
			echo "\n<!-- End of Remository HTML -->";
		}
		$this->restore_magic_quotes();
	}
	
	function &remove_magic_quotes (&$array) {
		foreach ($array as $k => $v) {
			if (is_array($v)) $array[$k] = $this->remove_magic_quotes($v);
			else $array[$k] = stripslashes($v);
		}
		return $array;
	}
	
	function restore_magic_quotes () {
		set_magic_quotes_runtime($this->magic_quotes_value);
	}

}

class remositoryUserControllers {
	var $ReMOSver = '3.40';
	var $remUser = '';
	var $repository = '';
	var $admin = '';
	var $idparm = 0;
	var $Itemid = 0;
	var $orderby = 2;
	var $submit_text = '';
	var $submitok = true;

	function remositoryUserControllers ($admin) {
		$interface =& remositoryInterface::getInstance();
		$mosConfig_absolute_path = $interface->getCfg('absolute_path');
		$mosConfig_lang = $interface->getCfg('lang');
		$mosConfig_sitename = $interface->getCfg('sitename');
		$this->admin = $admin;
		$this->idparm = remositoryRepository::getParam($_REQUEST, 'id', 0);
		$this->Itemid = remositoryRepository::getParam($_REQUEST, 'Itemid', 0);
		$this->orderby = remositoryRepository::getParam($_REQUEST, 'orderby', 2);
		$this->remUser = $interface->getUser();
		$this->repository =& remositoryRepository::getInstance();
		//Need config values for language files
		foreach (get_class_vars(get_class($this->repository)) as $k=>$v) $$k = $this->repository->$k;
		require_once ($mosConfig_absolute_path.'/components/com_remository/com_remository_constants.php');
		if(file_exists($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php')) require_once($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php');
		if($mosConfig_lang != 'english' AND file_exists($mosConfig_absolute_path.'/components/com_remository/language/english.php')) require_once($mosConfig_absolute_path.'/components/com_remository/language/english.php');
		$this->submit_text = _SUBMIT_FILE_BUTTON;
		$this->createSubmitText();
	}

	function &createFile () {
		if (is_numeric($this->idparm) AND ($this->idparm != 0)) {
			$file =& new remositoryFile ($this->idparm);
			$file->getValues($this->remUser);
			return $file;
		}
		die ('Fatal error - we should have had a valid file ID');
	}

	function &createContainer () {
		if (is_numeric($this->idparm) AND ($this->idparm != 0)) {
			$container =& new remositoryContainer ($this->idparm);
			return $container;
		}
		die ('Fatal error - we should have had a valid container ID='.$this->idparm);
	}
	
	function dirsize() {
   		$totalsize = 0;
		$manager =& remositoryContainerManager::getInstance();
	  	$filepaths = $manager->getFilePathData();
	  	foreach ($filepaths as $path=>$containers) {
	  		$directory =& new remositoryDirectory($path);
		  	$totalsize += $directory->getSize();
		}
		return $totalsize;
	}

	function createSubmitText () {
		if ($this->submitok AND !$this->repository->Allow_User_Sub AND !$this->remUser->isAdmin()){
			$this->submitok = false;
			$this->submit_text = _SUBMIT_FILE_NOUSER;
		}
		clearstatcache();
		if ($this->submitok AND $this->remUser->isUser() AND $this->repository->Max_Up_Per_Day > 0 AND $this->remUser->uploadsToday() >= $this->repository->Max_Up_Per_Day) {
		    $this->submitok = false;
		    $this->submit_text = _SUBMIT_FILE_NOLIMIT;
		}
	    if ($this->submitok) {
	    	$Curr_Up_Dir_Space = $this->dirsize()/1024;
	        $up_dir_space_avail=$this->repository->Max_Up_Dir_Space-$Curr_Up_Dir_Space-($this->repository->MaxSize/2);
	        if ($up_dir_space_avail<0) {
	         	$this->submitok = false;
	            $this->submit_text = _SUBMIT_FILE_NOSPACE;
			}
		}
	}

	function revertFullTimeStamp($timestamp) {
		$subs = array (5,8,11,14,17);
		$parts = array();
	    $parts[] = substr($timestamp,0,4);
	    foreach ($subs as $i) $parts[] = substr($timestamp,$i,2);
	    $newdate = mktime($parts[3],$parts[4],$parts[5],$parts[1],$parts[2],$parts[0]);
	    return $newdate;
	}
	
	function error_popup ($message) {
		echo "<script> alert('".$message."'); window.history.go(-1); </script>\n";
	}

}


$alternatives = array ('startdown' => 'fileinfo',
                'showdown' => 'fileinfo',
                'finishdown' => 'fileinfo');
                
$admin =& new remositoryUserAdmin('remository', 'func', $alternatives, 'select', 'Remository');

?>