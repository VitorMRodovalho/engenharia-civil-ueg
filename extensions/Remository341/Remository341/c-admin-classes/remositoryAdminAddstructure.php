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

class remositoryAdminAddstructure extends remositoryAdminControllers {

	function listTask () {
	    $containerID = 0;
		$clist = $this->repository->getSelectList(false,$containerID,'cfid','class="inputbox"',$this->remUser);
		$view = $this->admin->newHTMLClassCheck ('listAddstructureHTML', $this, 0, $clist);
		if ($view AND $this->admin->checkCallable($view, 'view')) $view->view();
	}
	
	function setFileCommonData (&$file) {
		$file->license = remositoryRepository::getParam($_POST, 'license', '');
		$file->licenseagree = remositoryRepository::getParam($_POST, 'licenseagree', '');
		$file->fileversion = remositoryRepository::getParam($_POST, 'fileversion', $this->repository->Default_Version);
		$file->fileauthor = remositoryRepository::getParam($_POST, 'fileauthor', '');
		$file->filehomepage = remositoryRepository::getParam($_POST, 'filehomepage', '');
		$file->icon = remositoryRepository::getParam($_POST, 'icon', '');
	}

	function mkSubFolder ($filepath) {
	    if (!file_exists($filepath)) $newdir =& new remositoryDirectory ($filepath, true);
	}

	function stdpath ($path) {
		if (substr($path,strlen($path)) != '/') $path .= '/';
		$filepath=str_replace("\\","/",$path); 
		$filepath=str_replace("\\","/",$path); 
		return $filepath;
	}

	function addOneLevel ($path, &$container, &$extensions, $extensiontitle, $delete) {
		if (substr($path,strlen($path)) != '/') $path .= '/';
		$directory =& new remositoryDirectory($path);
		$files = $directory->listFiles();
		$newfile =& new remositoryFile ();
		$newfile->containerid = $container->id;
		$newfile->memoContainer($container);
		$newfile->published = 1;
		$newfile->submittedby = $this->remUser->id;
		$this->setFileCommonData($newfile);
		$newfile->validate();
		foreach ($files as $file) {
			@set_time_limit(25);
			$ext = remositoryAbstract::lastPart($file,'.');
			if ($extensions != '*' AND !in_array($ext, $extensions)) continue;
			$filepath = $path.$file;
			$physical =& new remositoryPhysicalFile ();
			$physical->setData($filepath);
			$newfile->id = 0;
			$newfile->filetitle = '';
			$newfile->storePhysicalFile ($physical, $extensiontitle);
			if ($delete) @unlink($filepath);
		}
	    $this->repository->resetCounts(array());
		$directories = $directory->listFiles('','dir');
		foreach ($directories as $newdir) {
			$dirpath = $path.$newdir;
			$folder =& new RemositoryContainer ();
			$folder->parentid = $container->id;
			$folder->name = $newdir;
			$folder->plaintext = $container->plaintext;
			if ($container->filepath) $folder->filepath = $container->filepath.$newdir.'/';
		    if (!file_exists($folder->filepath))$newdir =& new remositoryDirectory ($folder->filepath, true);
			$folder->published = 1;
			$folder->saveValues();
			$this->addOneLevel ($dirpath, $folder, $extensions, $extensiontitle, false);
		}
		$this->repository->resetCounts(array());
	}

	function saveTask () {
	    $basedir = str_replace("'", '', remositoryRepository::getParam ($_REQUEST, 'basedir', ''));
	    $extensionlist = remositoryRepository::getParam($_REQUEST, 'extensionlist', '');
	    if (trim($extensionlist) == '*') $extensions = '*';
	    else {
			$extensions = explode(',', $extensionlist);
	    	$extensions = array_map('trim', $extensions);
	    }
		$extensiontitle = remositoryRepository::getParam($_POST, 'extensiontitle', '');
	    $container =& new remositoryContainer($this->admin->currid);
	    if ($basedir AND $this->admin->currid) $this->addOneLevel ($basedir, $container, $extensions, $extensiontitle, False);
		$this->interface->redirect( "index2.php?option=com_remository", _DOWN_STRUCTURE_ADDED );
	}

}

?>