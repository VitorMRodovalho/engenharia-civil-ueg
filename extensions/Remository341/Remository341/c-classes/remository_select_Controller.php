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

class remository_select_Controller extends remositoryUserControllers {

	function select($func) {
		$interface =& remositoryInterface::getInstance();
		require_once ($this->admin->v_classes_path.'remositoryFileListHTML.php');
	    if ($this->idparm) $container = $this->createContainer ();
	    else $container =& new remositoryContainer();
	    if ($container->windowtitle) $interface->SetPageTitle($container->windowtitle);
		elseif ($container->name) $interface->SetPageTitle($container->name);
		$subfolders = $container->getVisibleChildren($this->remUser);
		if ($this->idparm == 0 AND count($subfolders) == 0) {
			$msg = _DOWN_NO_VISITOR_CATS;
			echo "<span class='remositorymessage>"._DOWN_NO_VISITOR_CATS.'</span>';
			return;
		}
		$page = remositoryRepository::getParam($_REQUEST, 'page', 1);
		$pagecontrol =& new remositoryPage ( $container, $this->remUser, _ITEMS_PER_PAGE, $page, $this->orderby );
		if ($this->idparm AND $container->areFilesVisible($this->remUser)) $files = $container->getFiles(!$this->remUser->isAdmin(), $this->orderby, null, $pagecontrol->startItem(), $pagecontrol->itemsperpage);
		else $files = array();
		$view =& new remositoryFileListHTML($this);
		$view->fileListHTML($container, $subfolders, $files, $pagecontrol);
	}

}

?>