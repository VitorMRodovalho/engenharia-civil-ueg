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

class remositoryAdminUnlinked extends remositoryAdminControllers {

	function remositoryAdminUnlinked ($admin) {
		remositoryAdminControllers::remositoryAdminControllers ($admin);
	    $_REQUEST['act'] = 'unlinked';
	}
	
	function &getOrphanDownloads () {
		$manager =& remositoryContainerManager::getInstance();
		$paths = $manager->getFilePathData();
		$OrphanDownloads = array();
		foreach ($paths as $dir_path=>$containers) {
			$directory =& new remositoryDirectory($dir_path);
			$neworphans = $directory->getOrphans();
			if (count($neworphans)) $OrphanDownloads = array_merge($OrphanDownloads, $neworphans);
		}
		return $OrphanDownloads;
	}
	
	function &getOrphanUploads () {
		$upload_path = $this->repository->Up_Path.'/';
		$upload_dir =& new remositoryDirectory($upload_path);
		$up_names = $upload_dir->listFiles();
		$temp_files = $this->repository->getTempFileNames();
		$OrphanUploads = array();
		foreach ($up_names as $upload_file) {
			$full_path = $upload_path.$upload_file;
	   		if (($upload_file != "index.html") AND (substr($upload_file,0,1) != ".") AND !is_dir($full_path) AND !in_array($upload_file,$temp_files)) {
				$OrphanUploads[$full_path] = true;
			}
		}
		return $OrphanUploads;
	}

	function listTask () {
		$OrphanDownloads =& $this->getOrphanDownloads();
		ksort($OrphanDownloads);
		$OrphanUploads =& $this->getOrphanUploads();
		ksort($OrphanUploads);
		$view = $this->admin->newHTMLClassCheck ('listUnlinkedHTML', $this, 0, '');
		if ($view AND $this->admin->checkCallable($view, 'view')) $view->view($OrphanDownloads, $OrphanUploads);
	}
	
	function editTask () {
		$file =& new remositoryFile();
		$file->fileversion = $this->repository->Default_Version;
		$filepath = remositoryRepository::getParam($_REQUEST, 'filepath', '');
		if ($filepath) {
			$filename = remositoryAbstract::lastPart($filepath, '/', false);
			$file->islocal = '1';
			$file->realname = $filename;
			$file->filetitle = $filename;
			$file->filetype = remositoryAbstract::lastPart($filename,'.');
			$file->filesize = number_format(filesize($filepath)/1024,2).' Kb';
			$file->filedate = date('Y-m-d H:i:s',filemtime($filepath));
			$file->fileversion = $this->repository->Default_Version;
			$manager =& remositoryContainerManager::getInstance();
			$clist = $manager->makeSelectedList($_REQUEST['containers'],'containerid','class="inputbox"');
			$oldpath = remositoryAbstract::allButLast($filepath,'/').'/';
			HTML_downloads::editfileHTML ( $file, $this->repository, $clist, $oldpath );
		}
		else die ('Should be impossible to attempt editorphan with no filepath');
	}
	
	function deleteTask () {
		$this->admin->check_selection(_DOWN_SEL_FILE_DEL);
		$OrphanDownloads =& $this->getOrphanDownloads();
		$OrphanUploads =& $this->getOrphanUploads();
		$cfid = remositoryRepository::getParam($_REQUEST, 'cfid', array());
	    foreach ($cfid as $file) {
			if (isset($OrphanDownloads[$file]) OR isset($OrphanUploads[$file])) @unlink($file);
	    }
	    $this->listTask();
	}

}

?>