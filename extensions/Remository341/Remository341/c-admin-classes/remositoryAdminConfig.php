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

class configSelector {
	var $description='';
	var $variablename='';

	function configSelector ($name, $desc) {
		$this->variablename = $name;
		$this->description = $desc;
	}
}

class remositoryAdminConfig extends remositoryAdminControllers {

	function remositoryAdminConfig ($admin) {
		remositoryAdminControllers::remositoryAdminControllers ($admin);
	    $_REQUEST['act'] = 'config';
	}

	function listTask (){
		// make a generic yes no list
		$yesno[] = $this->repository->makeOption( 0, _NO );
		$yesno[] = $this->repository->makeOption( 1, _YES );
		// build the html select lists
		$newlist[] =& new configSelector ('Use_Database', _DOWN_CONFIG39);
		$newlist[] =& new configSelector ('Allow_Up_Overwrite', _DOWN_CONFIG11);
		$newlist[] =& new configSelector ('Allow_User_Sub', _DOWN_CONFIG12);
		$newlist[] =& new configSelector ('Allow_User_Edit', _DOWN_CONFIG13);
		$newlist[] =& new configSelector ('Allow_User_Up', _DOWN_CONFIG14);
		$newlist[] =& new configSelector ('Allow_Comments', _DOWN_CONFIG15);
		$newlist[] =& new configSelector ('Allow_Votes', _DOWN_CONFIG25);
		$newlist[] =& new configSelector ('Send_Sub_Mail', _DOWN_CONFIG16);
		$newlist[] =& new configSelector ('Enable_Admin_Autoapp', _DOWN_CONFIG26);
		$newlist[] =& new configSelector ('Enable_User_Autoapp', _DOWN_CONFIG27);
		$newlist[] =& new configSelector ('Enable_List_Download', _DOWN_CONFIG28);
		$newlist[] =& new configSelector ('User_Remote_Files', _DOWN_CONFIG29);
		$newlist[] =& new configSelector ('See_Containers_no_download', _DOWN_CONFIG33);
		$newlist[] =& new configSelector ('See_Files_no_download', _DOWN_CONFIG34);
		$newlist[] =& new configSelector ('Allow_Large_Images', _DOWN_CONFIG38);

		$view = $this->admin->newHTMLClassCheck ('listConfigHTML', $this, 0, '');
		if ($view AND $this->admin->checkCallable($view, 'view')) $view->view($newlist, $yesno);
	}
	
	function saveTask () {
		$this->repository->addPostData();
		$this->repository->saveValues();
		$this->interface->redirect( "index2.php?option=com_remository&act=cpanel", _DOWN_CONFIG_COMP );
	}

}

?>