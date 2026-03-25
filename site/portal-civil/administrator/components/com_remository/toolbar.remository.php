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

// ensure this file is being included by a parent file
if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( 'Direct Access to this location is not allowed.' );

// Include files that contain classes
if (!class_exists('aliroRequest')) {
	$remository_dir = str_replace('\\','/',dirname(__FILE__));
	require_once($remository_dir.'/remository.interface.php');
}
$interface =& remositoryInterface::getInstance();
$mosConfig_absolute_path = $interface->getCfg('absolute_path');
$mosConfig_lang = $interface->getCfg('lang');
$mosConfig_live_site = $interface->getCfg('live_site');
$mosConfig_sitename = $interface->getCfg('sitename');
$Large_Text_Len = 300;
$Small_Text_Len = 150;
if(file_exists($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php')) require_once($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php');
require_once($mosConfig_absolute_path.'/components/com_remository/language/english.php');
require_once($mosConfig_absolute_path.'/components/com_remository/remository.class.php');

class remositoryToolbar {
	var $act;
	var $task;
	// Create an instance, get the controlling parameters from the request
	function remositoryToolbar () {
		if ($this->act = remositoryRepository::GetParam ($_REQUEST, 'act', 'about'));
		else $this->act = 'about';
		if ($this->task = remositoryRepository::GetParam($_REQUEST, 'task', 'list'));
		else $this->task = 'list';
		$this->makeBar();
	}
	// create a toolbar based on the parameters found in $_REQUEST
	function makeBar () {
		$this->start();
		$act = $this->act;
		if (method_exists($this,$act)) $this->$act();
		$this->finish();
	}
	// Any initial actions
	function start () {
		mosMenuBar::startTable();
		if ('cpanel' != $this->act) mosMenuBar::custom ('cpanel', 'back.png', 'back_f2.png', _DOWN_CPANEL_RETURN, false );
	}
	// The following methods correspond exactly to the possible values
	// of 'act' in the request.  They in turn correspond to all the
	// possible options in the admin side drop down menu for Remository.
	function containers () {
		if ($this->task == 'add') $this->addMenu('Container');
		elseif ($this->task == 'edit') $this->editMenu('Container');
		else $this->listMenu('');
	}
	
	function files () {
		if (in_array($this->task, array('add','addfile','addurl'))) $this->addMenu('File');
		elseif ($this->task == 'edit') $this->editMenu('File');
		else $this->fileListMenu('File');
	}
	
	function groups () {
		if ($this->task == 'email') $this->emailGroupMenu();
		elseif ($this->task == 'edit' OR $this->task == 'add') $this->editGroupMenu();
		else $this->groupMenu();
	}
	
	function ftp () {
		mosMenuBar::publish( 'upload', 'Publish files' );
	}
	
	function uploads () {
		if ($this->task == 'edit') {
			mosMenuBar::custom ('approve1', 'apply.png', 'apply_f2.png', _APPROVE, false);
			$this->cancel_Button();
		}
		else {
			mosMenuBar::custom( 'approve', 'apply.png', 'apply_f2.png', _APPROVE, false );
			mosMenuBar::custom( 'approvep', 'publish.png', 'publish_f2.png', _DOWN_APPROVE_PUB, false );
			mosMenuBar::editList( 'edit', 'Edit Approval' );
			mosMenuBar::deleteList( '', 'delete', 'Delete Submision' );
		}
	}
	
	function counts () {
		$this->containers();
	}
	
	function downloads () {
		$this->files();
	}
	
	function unlinked () {
		if ($this->task == 'add') $this->addMenu('Orhpan');
		else {
			mosMenuBar::deleteList( '', 'delete', 'Delete Orphans' );
			$this->cancel_Button();
		}
	}
	
	function missing () {
		if ($this->task == 'add') $this->addMenu('Missing');
		else {
			mosMenuBar::deleteList( '', 'delete', 'Delete Missing Files' );
			$this->cancel_Button();
		}
	}
	
	function addstructure () {
		mosMenuBar::save( 'save', 'Save '.'File structure' );
	}
	
	function config () {
		mosMenuBar::save( 'save', 'Save Config' );
	}
	
	function prune () {
		mosMenuBar::save( 'save', 'Save - prune' );
	}
	
	// The cancel option is always formed the same way
	function cancel_Button () {
		mosMenuBar::custom( 'list', 'cancel.png', 'cancel_f2.png', _CANCEL, false );
	}
	
	function back_Button () {
		mosMenuBar::custom( 'back', 'back.png', 'back_f2.png', _DOWN_ABOUT, false );
	}
	// The menu for adding something is always the same apart from the text
	function addMenu ($entity) {
		mosMenuBar::save( 'save', 'Save '.$entity );
		$this->cancel_Button();
	}
	// The menu for editing something is always the same apart from the text
	function editMenu ($entity) {
		mosMenuBar::save( 'save', 'Save '.$entity );
		$this->cancel_Button();
	}
	// The menu for a list of items is always the same apart from the text
	function listMenu ($entity) {
		mosMenuBar::publishList( 'publish', 'Publish '.$entity );
		mosMenuBar::unpublishList( 'unpublish', 'UnPublish '.$entity );
		mosMenuBar::addNew( 'add', 'Add '.$entity );
		mosMenuBar::editList( 'edit', 'Edit '.$entity );
		mosMenuBar::deleteList( '', 'delete', 'Delete '.$entity );
	}
	// The menu for a list of files has two different "add" options
	function fileListMenu ($entity) {
		mosMenuBar::publishList( 'publish', 'Publish File' );
		mosMenuBar::unpublishList( 'unpublish', 'UnPublish File' );
		mosMenuBar::addNew( 'addfile', 'Add Local' );
		mosMenuBar::addNew( 'addurl', 'Add Remote' );
		mosMenuBar::editList( 'edit', 'Edit File' );
		mosMenuBar::deleteList( '', 'delete', 'Delete File' );
	}
    function editGroupMenu ()
    {
        mosMenuBar::startTable();
        mosMenuBar::save('save', 'Save');
		$this->cancel_Button();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    } 

    function groupMenu ()
    {
        mosMenuBar::startTable();
        mosMenuBar::addNew('add', 'Add');
        mosMenuBar::editList();
		mosMenuBar::deleteList( '', 'delete', 'Delete' );
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    } 

    function emailGroupMenu ()
    {
        mosMenuBar::startTable();
		$this->cancel_Button();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    } 	// Any concluding actions
    
	function finish () {
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

}

$toolbar = new remositoryToolbar();

?>
