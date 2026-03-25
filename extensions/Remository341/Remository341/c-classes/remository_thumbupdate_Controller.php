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

class remository_thumbupdate_Controller extends remositoryUserControllers {
	
	function thumbupdate ($func) {
	    $file = $this->createFile ();
		if ($this->remUser->isAdmin() OR
		($this->repository->Allow_User_Edit AND $this->remUser->isLogged() AND
		($this->remUser->id==$file->submittedby OR ($file->editgroup != 0 AND remositoryGroup::isUserMember($file->editgroup,$this->remUser))))) {
	    	require_once ($this->admin->v_classes_path.'remositoryThumbUpdateHTML.php');
			$view =& new remositoryThumbUpdateHTML($this);
			$view->thumbUpdateHTML ($file);
		}
		else die('Access not permitted');
	}
	
}

?>