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

class remository_search_Controller extends remositoryUserControllers {
	
	function search ($func)
	{
		$interface =& remositoryInterface::getInstance();
		$interface->SetPageTitle('Search for files');
		if (remositoryRepository::getParam($_POST,'submit','')) {
			$search_text = remositoryRepository::getParam($_POST,'search_text','');
			$seek_title = remositoryRepository::getParam($_POST,'search_filetitle',0);
			$seek_desc = remositoryRepository::getParam($_POST,'search_filedesc',0);
			$file_array = $this->repository->searchRepository($search_text, $seek_title, $seek_desc, $this->remUser);
			require_once ($this->admin->v_classes_path.'remositorySearchResultsHTML.php');
			$view =& new remositorySearchResultsHTML($this);
			$view->searchResultsHTML($file_array);
	  	}
		else {
			require_once ($this->admin->v_classes_path.'remositorySearchBoxHTML.php');
			$view =& new remositorySearchBoxHTML($this);
			$view->searchBoxHTML();
		}
	}
}

?>