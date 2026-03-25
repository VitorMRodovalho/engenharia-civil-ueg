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

class remositoryAdminMissing extends remositoryAdminControllers {

	function remositoryAdminMissing ($admin) {
		remositoryAdminControllers::remositoryAdminControllers ($admin);
	    $_REQUEST['act'] = 'missing';
	}

	function getMissingFiles(){
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$sql = 'SELECT * FROM #__downloads_files';
		$files = remositoryRepository::doSQLget($sql, 'remositoryFile');
		$missingFiles = array();
		foreach ($files as $file) {
			if ($file->islocal) {
				if ($file->isblob) {
					$database->setQuery("SELECT COUNT(chunkid) FROM #__downloads_blob WHERE fileid=$file->id");
					$chunks = $database->loadResult();
					if ($chunks == 0) {
						$missingFiles[]=$file;
					}
					elseif ($file->chunkcount == 0) {
						$database->setQuery("UPDATE #__downloads_files SET chunkcount=$chunks WHERE id=$file->id");
						$database->query();
					}
					elseif ($file->chunkcount != $chunks) {
						$missingFiles[]=$file;
					}
				}
				elseif ($file->plaintext) {
					$database->setQuery("SELECT COUNT(id) FROM #__downloads_text WHERE fileid=$file->id");
					$texts = $database->loadResult();
					if ($texts != 1) {
						$missingFiles[]=$file;
					}
				}
				else {
					if ($file->filepath) 
						if (!file_exists($file->filepath.$file->realname)) {
						$missingFiles[]=$file;
					}
				}
			}
		}
		foreach ($files as $file) {
			if (!$file->islocal) {
				$url = $file->url;
				if (!$url) $url = _DOWN_LOCAL_NO_URL;
				if (!eregi(_REMOSITORY_REGEXP_URL,$url) AND !eregi(_REMOSITORY_REGEXP_IP,$url)) {
						$missingFiles[]=$file;
				}
			}
		}
		return $missingFiles;
	}

	function listTask () {
		$MissingFiles = $this->getMissingFiles();
		$view = $this->admin->newHTMLClassCheck ('listMissingFilesHTML', $this, 0, '');
		$view->view($MissingFiles);
				}

	
	function editTask () {
		// Make a file object, with an ID from the form submitted
		$file =& new remositoryFile($this->admin->currid);
		// Fill the file object with data from the database
		$file->getValues($this->remUser);
		// Generate a list of possible containers in which the file could be located
		$clist = $file->getEditSelectList('containerid','class="inputbox"',$this->remUser);
		// Create and activate a View object
		$view = $this->admin->newHTMLClassCheck ('editFilesHTML', $this, 0, $clist);
		$view->view($file, $file->filepath );
			}
	
	function deleteTask () {
		$this->admin->check_selection(_DOWN_SEL_FILE_DEL);
		foreach ($this->admin->cfid as $id) {
			$file =& new remositoryFile ($id);
			$file->getValues($this->remUser);
			$file->deleteFile();
		}
		$this->repository->resetCounts();
		$this->listTask();
	}

}

?>