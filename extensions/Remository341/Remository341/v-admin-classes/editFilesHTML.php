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

class editFilesHTML extends remositoryAdminHTML {
	
	function editfileScript () {
		?>
		<script type="text/javascript">
			function clearshort(){
				if (document.adminForm.autoshort.checked==true){
					if (document.adminForm.description.value!=""){
						if (document.adminForm.description.value.length>=(<?php echo $this->repository->Small_Text_Len; ?>-4)){
							document.adminForm.smalldesc.value=document.adminForm.description.value.substr(0,<?php echo $this->repository->Small_Text_Len; ?>-4) + "...";
						} else {
							document.adminForm.smalldesc.value=document.adminForm.description.value;
						}
					} else {
						document.adminForm.smalldesc.value="";
					}
					document.adminForm.smalldesc.disabled=true;
				} else {
					document.adminForm.smalldesc.value="";
					document.adminForm.smalldesc.disabled=false;
				}
			}
		</script>
		<?php
	}

	function view ( &$file, $oldpath='' )
	{
		$interface =& remositoryInterface::getInstance();
		$orphanpath = remositoryRepository::getParam($_REQUEST, 'orphanpath', '');
		if (!realpath($orphanpath)) $orphanpath = '';
		$iconList = remositoryFile::getIcons();
		$this->commonScripts('description');
		$this->startEditHeader();
		$this->publishedBox($file);
		$this->fileInputBox(_DOWN_FILE_TITLE, 'filetitle', $file->filetitle, 50);
		$this->fileInputArea(_DOWN_DESC, _DOWN_DESC_MAX, 'description', $file->description, 50, 80, true);
		$this->editfileScript();
		$this->tickBoxField($file, 'autoshort', _DOWN_AUTO_SHORT);
		$this->fileInputArea(_DOWN_DESC_SMALL, _DOWN_DESC_SMALL_MAX, 'smalldesc', $file->smalldesc, 6, 75, false);
		$this->fileInputArea(_DOWN_LICENSE, _DOWN_DESC_MAX, 'license', $file->license, 6, 75, false);
		$this->tickBoxField($file, 'licenseagree', _DOWN_LICENSE_AGREE);
		if ($file->islocal) {
			if ($file->id) $this->fileInputBox(_DOWN_REAL_NAME,'realname',$file->realname(),75);
			if (!$orphanpath) $this->fileUploadBox(_SUBMIT_NEW_FILE, 50);
		}
		else $this->fileInputBox(_DOWNLOAD_URL,'url',$file->url(),75);
		$this->fileInputBox(_DOWN_KEYWORDS,'keywords',$file->keywords,50);
		$this->fileInputBox(_DOWN_WINDOW_TITLE,'windowtitle',$file->windowtitle,50);
		$this->fileInputBox(_DOWN_FILE_VER,'fileversion',$file->fileversion,25);
		$this->fileInputBox(_DOWN_FILE_AUTHOR,'fileauthor',$file->fileauthor,25);
		$this->fileInputBox(_DOWN_FILE_DATE,'filedate',$file->filedate,25);
		$this->fileInputBox(_DOWN_FILE_SIZE,'filesize',$file->filesize,25);
		$this->fileInputBox(_DOWN_FILE_TYPE,'filetype',$file->filetype,25);
		$this->fileInputBox(_DOWN_FILE_HOMEPAGE,'filehomepage',$file->filehomepage,75);
		if ($this->repository->Max_Thumbnails == 0) $this->fileInputBox(_DOWN_SCREEN,'screenurl',$file->screenurl,75);
		$this->displayIcons($file, $iconList);
		?>
			<input type="hidden" name="islocal" value="<?php echo $file->islocal; ?>" />
			<input type="hidden" name="oldid" value="<?php if (isset($file->oldid)) echo $file->oldid; ?>" />
			<input type="hidden" name="oldcontainerid" value="<?php if (isset($file->containerid)) echo $file->containerid; ?>" />
			<input type="hidden" name="filetempname" value="<?php if (isset($file->filetempname)) echo $file->filetempname; ?>" />
			<input type="hidden" name="orphanpath" value="<?php echo $orphanpath; ?>" />
			<input type="hidden" name="filetemp">
			<input type="hidden" name="filetemphash">
			<script type="text/javascript" src="<?php echo $interface->getCfg('live_site').'/components/com_remository/'; ?>wz_tooltip.js"></script>
		<?php
		$this->editFormEnd($file->id, $oldpath);
	}
}

?>