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

class remository_userupdate_Controller extends remositoryUserControllers {
	
	function userupdate ($func) {
		$file = $this->createFile ();
		if (!$file->updatePermitted($this->remUser)) $this->error_popup (_DOWN_NOT_AUTH);
		//Check for Upload amt limit
		if (!$this->remUser->isAdmin() AND $this->remUser->uploadsToday() >= $this->repository->Max_Up_Per_Day) {
			$this->error_popup (_ERR9);
			remositoryUserHTML::pathwayHTML( null );
			?><br/>&nbsp;<br/><?php echo _DOWN_ALL_DONE;
			return;
		}
		$clist = $this->repository->getSelectList(false,$file->containerid,'containerid','class="inputbox"',$this->remUser,true);
		require_once ($this->admin->v_classes_path.'remositoryAddFileHTML.php');
		$view =& new remositoryAddFileHTML($this);
		$view->addfileHTML($clist, $file);
	}
	
}

?>