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

class remositoryAdminUploads extends remositoryAdminControllers {
	var $containerid = 0;

	function remositoryAdminUploads ($admin) {
		remositoryAdminControllers::remositoryAdminControllers ($admin);
		$this->containerid = remositoryRepository::getParam($_REQUEST, 'containerid', 0);
	    $_REQUEST['act'] = 'uploads';
	}
	
	function listTask () {
		// Get the search string that will constrain the list of containers displayed
		$search = trim( strtolower( remositoryRepository::getParam( $_REQUEST, 'search', '' ) ) );
		// If user has specified a container, get the files for that one, otherwise get them all
		if ($this->containerid) {
			$container =& new remositoryContainer($this->containerid);
			$files = $container->getTempFiles($search);
		}
		else $files = $this->repository->getTempFiles();
		// Generate a select list so user can look at files for specific containers
		$clist = $this->repository->getSelectList(true, $this->containerid,'containerid','class="inputbox" size="1" onchange="document.adminForm.submit();"',$this->remUser);
		// Create and activate a View object
		$view = $this->admin->newHTMLClassCheck ('listUploadsHTML', $this, count($files), $clist);
		if ($view AND $this->admin->checkCallable($view, 'view')) $view->view($files, $search);
	}

	function approveTask ($forcepublish=false) {
		// Check that at least one file has been selected for approval
		$this->admin->check_selection(_DOWN_SEL_FILE_APPROVE);
		// Use the single file approval code on each selected file
		foreach ($this->admin->cfid as $id) {
			$tempfile =& new remositoryTempFile($id);
			if ($id) {
				$tempfile->getValues();
				$this->singleApprove($tempfile, $forcepublish);
			}
		}
		// List out the files still available for approval
		$this->listTask();
	}
	
	function approvepTask () {
		$this->approveTask(true);
	}
	
	function approve1Task () {
		// Create a temporary file object with ID submitted, should always be non zero
		$tempfile =& new remositoryTempFile($this->admin->currid);
		if ($tempfile->id) {
			// Get values, add information from form submitted, then use approve function
			$tempfile->getValues();
			$tempfile->addPostData();
			$tempfile->containerid = $_POST['containerid'];
			$this->singleApprove ($tempfile);
		}
		// List out the files still available for approval
		$this->listTask();
	}

	function singleApprove($tempfile, $forcepublish=false) {
		// Validation checks on the temporary file metadata
		$tempfile->validate();
		$container =& new remositoryContainer($tempfile->containerid);
		$tempfile->memoContainer($container);
		// Create a file object - get the old version of the metadata if exists
		$oldfile =& new remositoryFile ($tempfile->oldid);
		if ($oldfile->id != 0) {
			$oldfile->getValues($this->remUser);
			$oldphysical =& $oldfile->obtainPhysical();
		}
		// Create a new file object, as opposed to temporary file and transfer data
		$newfile =& new remositoryFile($tempfile->oldid);
		$newfile->setValues($tempfile);
		if ($forcepublish) $newfile->published = 1;
		//If there is no name for a temporary file, must just be updating metadata
		if (!$tempfile->filetempname) {
			if (isset($oldphysical)) $oldphysical->moveTo($newfile->filepath.$newfile->realname, $newfile->id, $newfile->isblob, $newfile->plaintext);
			$newfile->saveFile();
			$tempfile->deleteFileDB();
			return;
		}
		elseif (isset($oldphysical)) $oldphysical->delete();
		// Get the path of the new physical file
		$tempfilepath = $tempfile->filePath();
		// If it is going to be stored in file system, move it and save metadata
		$newfile->saveFile();
		$physical =& $tempfile->obtainPhysical();
		$physical->moveTo($newfile->filepath.$newfile->realname, $newfile->id, $newfile->isblob, $newfile->plaintext);
		$tempfile->deleteFileDB();
		// Now it is approved, log the upload
        $uploadsize = ($newfile->islocal ? $newfile->filesize : 0);
		$logentry =& new remositoryLogEntry(_LOG_UPLOAD, $newfile->submittedby, $newfile->id, $uploadsize);
		$logentry->insertEntry();
	}

	function editTask () {
		// Create a file object using the ID submitted and set its values
		$file =& new remositoryTempFile($this->admin->currid);
		$file->getValues($this->remUser);
		// Generate a list of possible containers for the file
		// If file is local, limit to writeable containers
		$clist = $this->repository->getSelectList(false,$file->containerid,'containerid','class="inputbox"',$this->remUser, $file->islocal);
		// Create and activate a View object
		$view = $this->admin->newHTMLClassCheck ('editFilesHTML', $this, 0, $clist);
		$view->view($file);
	}

	function saveTask () {
		// Should never be called?
		$this->listTask();
	}

	function deleteTask () {
		$this->admin->check_selection(_DOWN_SEL_FILE_DEL);
		foreach ($this->admin->cfid as $id) {
			$tempfile =& new remositoryTempFile ($id);
			$tempfile->getValues();
			$tempfile->deleteFile();
		}
		$this->listTask();
	}

}

?>