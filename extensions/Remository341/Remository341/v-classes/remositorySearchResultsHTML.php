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

class remositorySearchResultsHTML extends remositoryUserHTML {
	var $tabcnt=0;

	function simpleTickBox ($title, $name) {
		echo "\n\t\t<p class='remositoryformentry'>";
		echo "\n\t\t\t<label for='$name'>$title</label>";
		echo "\n\t\t\t<input type='checkbox' name='$name' id='$name' value='1' checked='checked' />";
		echo "\n\t\t</p>";
	}

	function searchResultsHTML(&$files) {
		$formurl = remositoryRepository::RemositoryBasicFunctionURL('search');
		$this->pathwayHTML(null);
		echo "\n\t<form method='post' action='$formurl'>";
		echo "\n\t<div id='remositorysearch'>";
		echo "\n\t<h2>"._DOWN_SEARCH.'</h2>';
		echo "\n\t\t<p class='remositoryformentry'>";
		echo "\n\t\t\t<label for='search_text'>"._DOWN_SEARCH_TEXT."</label>";
		echo "\n\t\t\t<input class='inputbox' type='text' name='search_text' id='search_text' />";
		echo "\n\t\t\t<input class='button' type='submit' name='submit' value='"._DOWN_SUB_BUTTON."' />";
		echo "\n\t\t</p>";
		$this->simpleTickBox(_DOWN_SEARCH_FILETITLE, 'search_filetitle');
		$this->simpleTickBox(_DOWN_SEARCH_FILEDESC, 'search_filedesc');
		echo "\n\t\t\t<input type='hidden' name='submit' value='submit' />";
		echo "\n\t</div>";
		echo "\n\t</form>";
		if (count($files)) {
			echo "\n\t<div id='remositoryfilelisting'>";
			foreach ($files as $file) {
				$container =& new remositoryContainer($file->containerid);
				$this->fileListing ($file, $container, null, $this->remUser, true);
				$this->tabcnt = ($this->tabcnt+1) % 2;
			}
			echo "\n\t</div>\n";
		}
		$this->filesFooterHTML ();
	}
}

?>