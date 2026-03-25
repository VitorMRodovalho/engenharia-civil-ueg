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

// Don't allow direct linking
if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( 'Direct Access to this location is not allowed.' );

$remository_dir = str_replace('\\','/',dirname(__FILE__));
if (!class_exists('remositoryInterface')) require_once($remository_dir.'/remository.interface.php');
require_once ($remository_dir.'/com_remository_constants.php');


/**
* Abstract class for Remository classes that involve straightforward database tables
* Requires child classes to implement: tableName(), notSQL().
* tableName() must return the name of the database table, using #__ in the usual Mambo way
* notSQL() must return an array of strings, where each string is the name of a
* 	variable that is NOT in the database table, or is not written explicitly,
*   e.g. the auto-increment key.  If this is the ONLY non-SQL field, then the
*   child class need not implement it, as that it is already in the abstract class.
* Child classes may optionally implement: forcebools().
*/

class remositoryAbstract {
	/** @var int ID for file record in database */
	var $id=0;
	/** @var int Sequencing number for records */
	var $sequence=0;
	/** @var string Window Title */
	var $windowtitle='';
	/** @var string Keywords */
	var $keywords='';
	
	function remositoryAbstract () {
		die ('Cannot instantiate remositoryAbstract');
	}
	
	function addPostData () {
		foreach (get_class_vars(get_class($this)) as $field=>$value) {
			if ($field!='id' AND isset($_POST[$field])) {
				$this->$field = trim($_POST[$field]);
			}
		}
		$this->forceBools();
  	}

	function forceBools () {
	}

	function updateObjectDB () {
		remositoryRepository::doSQL($this->updateSQL());
	}
	
	function timeStampField () {
		return '';
	}

	function prepareValues () {
		$interface =& remositoryInterface::getInstance();
		foreach (get_class_vars(get_class($this)) as $field=>$value) {
			if (!is_numeric($this->$field)) $this->$field = $interface->getEscaped($this->$field);
		}
	}

	function updateSQL () {
		$interface =& remositoryInterface::getInstance();
		$tabname = $this->tableName();
		$sql = "UPDATE $tabname SET ";
		$exclude = $this->notSQL();
		foreach (get_class_vars(get_class($this)) as $field=>$value) {
			if (!in_array($field,$exclude)) {
				$data = is_numeric($this->$field) ? $this->$field : $interface->getEscaped($this->$field);
				$item[] = $field."='".$data."'";
			}
		}
		if ($this->timeStampField()) $item[] = $this->timeStampField()."='".date('Y-m-d H:i:s')."'";
		if (isset($item)) {
			$sql .= implode (', ', $item);
		}
		return $sql.' WHERE id='.$this->id;
	}

	function notSQL () {
		return array ('id');
	}

	function insertSQL () {
		$interface =& remositoryInterface::getInstance();
		$tabname = $this->tableName();
		$exclude = $this->notSQL();
		foreach (get_class_vars(get_class($this)) as $field=>$value) {
			if (!in_array($field,$exclude)) {
				$column[] = $field;
				$data = is_numeric($this->$field) ? $this->$field : $interface->getEscaped($this->$field);
				$item[] = "'".$data."'";
			}
		}
		$timestamp = $this->timeStampField();
		if ($timestamp) {
			$column[] = $timestamp;
			$item[] = "'".date('Y-m-d H:i:s')."'";
		}
		$columns = implode(',', $column);
		$datafields = implode(',', $item);
		return "INSERT INTO $tabname ($columns) VALUES($datafields)";
	}

	function setValues (&$anObject) {
		foreach (get_class_vars(get_class($this)) as $field=>$value) {
			if ($field != 'id' AND isset($anObject->$field)) $this->$field = $anObject->$field;
		}
	}

	function readDataBase($sql) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$database->setQuery( $sql );
		if (!$database->loadObject($this)) $this->id = 0;
	}
	
	function lastPart ($field, $separator, $lowercase=true) {
        $split_array = explode ( $separator, $field);
		$last = $split_array[count($split_array)-1];
		if ($lowercase) return strtolower($last);
		return $last;
	}
	
	function allButLast ($field, $separator) {
		$last = remositoryAbstract::lastPart($field,$separator);
		return substr($field,0,strlen($field)-strlen($last)-1);
	}

	function visibilitySQL (&$user, $see_objects) {
		$sql = '';
		if (!$user->isAdmin()) {
			$sql .= " AND f.published=1";
			if (!$see_objects) {
				$grouplist = remositoryGroup::getMembersGroupList ($user);
				if (strlen($grouplist)) $sql .= " AND ((f.registered & 2) OR ((f.userupload & 2) AND f.groupid IN ($grouplist)))";
				else $sql .= " AND (f.registered & 2)";
			}
		}
		return $sql;
	}

}

class remositoryFile extends remositoryAbstract {
	/** @var string File name on disk or as blob */
	var $realname='';
	/** @var bool Is the file in the local file system? */
	var $islocal='0';
	/** @var int Container ID */
	var $containerid=0;
	/** @var string File path if non-standard, derived from container */
	var $filepath='';
	/** @var string File size  */
	var $filesize='';
	/** @var string File extension */
	var $filetype='';
	/** @var string File Title for browser title bar */
	var $filetitle='';
	/** @var string File description */
	var $description='';
	/** @var string Short file description */
	var $smalldesc='';
	/** @var bool Is the short description automatically derived from the full description? */
	var $autoshort='';
	/** @var string License conditions for the file */
	var $license='';
	/** @var bool Does the user have to confirm the license conditions? */
	var $licenseagree='0';
	/** @var int Price in currency units with two decimal places */
	var $price=0;
	/** @var string Currency code e.g. GBP */
	var $currency='';
	/** @var int File download count */
	var $downloads=0;
	/** @var string URL to the file, if it is held elsewhere */
	var $url='';
	/** @var string Icon - not sure how this is used */
	var $icon='';
	/** @var bool Is this file published? */
	var $published=false;
	/** @var bool Is this file confined to registered users? */
	var $registered='2';
	/** @var User options 1=upload, 2=download, 3=both */
	var $userupload='3';
	/** @var bool Is this file recommended? */
	var $recommended=false;
	/** @var string Description of why recommended */
	var $recommend_text='';
	/** @var bool Is this file featured? */
	var $featured=false;
	/** @var date Start date for feature */
	var $featured_st_date='';
	/** @var date End date for feature */
	var $featured_end_date='';
	/** @var int Priority among featured files */
	var $featured_priority=0;
	/** @var int Sequencing number (calculated) */
	var $featured_seq=0;
	/** @var text Discussion of featured file */
	var $featured_text='';
	/** @var string Operating system for which file is intended */
	var $opsystem='';
	/** @var string Legal type - shareware, freeware, commercial, etc */
	var $legaltype='';
	/** @var text Requirements - what is the environment for running this file? */
	var $requirements='';
	/** @var Company name owning file */
	var $company='';
	/** @var date Release date */
	var $releasedate='';
	/** @var text Languages supported */
	var $languages='';
	/** @var string Company URL */
	var $company_URL='';
	/** @var string Translator name */
	var $translator='';
	/** @var string Version of this file */
	var $fileversion='';
	/** @var string Name of the author of the file */
	var $fileauthor='';
	/** @var string URL for web site of author of file */
	var $author_URL='';
	/** @var date The last modified date for the file */
	var $filedate=null;
	/** @var string Home page related to this file (URL) */
	var $filehomepage='';
	/** @var string Link to some kind of image referring to the file */
	var $screenurl='';
	/** @var bool Is this file in plain text? */
	var $plaintext=false;
	/** @var bool Is this file held in the database as a blob? */
	var $isblob=false;
	/** @var int Number of chunks for a file stored as blob in DB */
	var $chunkcount = 0;
	/** @var int Group of users that has access to this file */
	var $groupid=0;
	/** @var int Group of users who may edit this file */
	var $editgroup=0;
	/** @var string Information to be displayed during download */
	var $download_text = '';
	/** @var int The ID of the user who submitted this file */
	var $submittedby=0;
	/** @var date Date on which the file was submitted */
	var $submitdate='';
	/** @var string Custom field 1 */
	var $custom_1 = '';
	/** @var string Custom field 2 */
	var $custom_2 = '';
	/** @var string Custom field 3 */
	var $custom_3 = '';
	/** @var string Custom field 4 */
	var $custom_4 = 0;
	/** @var int Average rating of votes for this file */
	var $vote_value=0;
	/** @var int Count of votes for this file */
	var $vote_count=0;

	/**
	* File object constructor
	* @param int File ID from database or null
	*/
	function remositoryFile ( $id=0 ) {
		$repository =& remositoryRepository::getInstance();
		$this->id = $id;
		$this->fileversion = $repository->Default_Version;
	}
	
	function realName () {
		if ($this->islocal) return $this->realname;
		else return '';
	}
	
	function url () {
		if ($this->islocal) return '';
		else return $this->url;
	}

	function forceBools () {
		if ($this->published) $this->published=1;
		else $this->published=0;
		if ($this->licenseagree) $this->licenseagree=1;
		else $this->licenseagree=0;
		if ($this->autoshort) {
			$this->autoshort=1;
			$this->smalldesc='';
		} else $this->autoshort=0;
	}

	function notSQL () {
		return array ('id','vote_value', 'vote_count','submitdate');
	}
	
	function tableName () {
		return '#__downloads_files';
	}

	function timeStampField () {
		return 'submitdate';
	}
	
	function stripTagsFields () {
		return array ('smalldesc', 'keywords', 'filetitle', 'license', 'windowtitle', 'requirements', 'company',
		    'languages', 'company_URL', 'translator', 'fileversion', 'fileauthor', 'author_URL', 'filehomepage',
		    'screenurl');
	}

	function stripTags () {
		$fields = $this->stripTagsFields();
		foreach ($fields as $field) {
			$this->$field = strip_tags($this->$field);
		}
	}
	
	function validate ($extcheck=true) {
		$this->stripTags();
		$this->forceBools();
		$this->makeAutoshort();
		$this->checkLicenseagree();
		if ($extcheck) $this->checkExtensionOK();
	}
	
	function getPlainText () {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$sql = "SELECT filetext FROM #__downloads_text WHERE fileid=$this->id";
		$database->setQuery($sql);
		return $database->loadResult();
	}
	
	function addPostData ($adminside=false) {
		// Clear all tick boxes - will be sent by POST data if and only if tick is present
		$this->autoshort = 0;
		$this->licenseagree = 0;
		if ($adminside) {
			$this->published = 0;
			$this->featured = 0;
			$this->recommended = 0;
		}
		parent::addPostData();
	}
	
	function insertFileDB () {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		if ($this->plaintext OR $this->isblob) $id = null;
		else {
			if ($this->islocal) $sql = "SELECT id FROM #__downloads_files WHERE realname='$this->realname'";
			else $sql = "SELECT id FROM #__downloads_files WHERE url='$this->url'";
			$database->setQuery($sql);
			$id = $database->loadResult();
		}
		if ($id != null) {
			$this->id = $id;
			$this->updateObjectDB();
		}
		else {
			remositoryRepository::doSQL($this->insertSQL());
			$this->id = $database->insertid();
			if ($this->published) $this->incrementCounts('+1');
		}
	}

	function saveFile () {
		$default_icon = array (
		'txt' => 'document.gif',
		'exe' => 'executable.gif',
		'tar' => 'archive_tar.gif',
		'gz' => 'archive_gz.gif',
		'rar' => 'archive_rar.gif',
		'zip' => 'archive_zip.gif',
		'png' => 'pics.gif',
		'gif' => 'pics.gif',
		'jpg' => 'pics.gif',
		'pdf' => 'pdf1.gif',
		'doc' => 'word.gif',
		'rtf' => 'word.gif',
		'xls' => 'excel.gif'
		);
		if ($this->islocal) {
			$this->filetype = $this->lastPart($this->realname, '.');
			if (!$this->icon) {
				if (isset($default_icon [$this->filetype])) $this->icon = $default_icon [$this->filetype];
				else $this->icon = 'stuff1.gif';
			}
		}
		if ($this->id == 0) $this->insertFileDB();
		else $this->updateObjectDB();
	}

	function &obtainPhysical () {
		$physical =& new remositoryPhysicalFile();
		$physical->setData($this->filepath.$this->realname, $this->id, $this->isblob, $this->plaintext);
		return $physical;
	}
	
	function storePhysicalFile (&$physical, $extensiontitle=true) {
		$this->url = '';
	    $this->islocal = '1';
		if ($this->filetitle == '') {
			if ($extensiontitle) $this->filetitle = $physical->proper_name;
			else $this->filetitle = remositoryRepository::allButLast($physical->proper_name, '.');
		}
		if ($this->filepath) {
			if ($this->onDiskCheckFail($physical)) {
				echo "<script> alert('"._ERR6."'); window.history.go(-1); </script>\n";
				exit;
			}
		}
		else {
			if (!$this->plaintext) $this->isblob = 1;
			$this->getPhysicalData($physical);
		}
		$this->saveFile();
		$newphysical =& $this->obtainPhysical();
		return $physical->moveTo($newphysical->file_path, $this->id, $newphysical->isblob, $newphysical->plaintext);
	}

	function downloadURL ($autodown) {
		if ($autodown) $function = 'download';
		else $function = 'showdown';
		$repository =& remositoryRepository::getInstance();
		$downURL = $repository->RemositoryBasicFunctionURL($function,$this->id);
		$downURL = "'".str_replace('&amp;','&',$downURL)."'";
		return $downURL;
	}

	function basicDownloadLink ($autodown) {
		if ($autodown) $function = 'download';
		else $function = 'startdown';
		if ($this->islocal AND $autodown) $fname = $this->realname;
		else $fname = null;
		$repository =& remositoryRepository::getInstance();
		$downlink = $repository->RemositoryBasicFunctionURL($function,$this->id, null, null, null, $fname);
		return $downlink;
	}
	
	function downloadLink ($autodown) {
		$downURL = $this->downloadURL ($autodown);
		if ($this->islocal) $addon = ' rel="nofollow">';
		elseif ($autodown == 2) $addon = ' target="_blank" rel="nofollow">';
		else $addon = ' onclick="download('.$downURL.')" rel="nofollow" target="_blank">';
		$downlink = $this->basicDownloadLink($autodown);
		$downlink = '<a href="'.$downlink.'"'.$addon;
		return $downlink;
	}

	function cloneFile () {
		$this->id = 0;
		$this->insertFileDB();
	}

	function deleteFileDB () {
		$sql = "DELETE FROM #__downloads_files WHERE id=$this->id";
		remositoryRepository::doSQL($sql);
		remositoryComment::deleteComments($this->id);
		remositoryLogEntry::deleteEntries($this->id);
		if ($this->published) $this->incrementCounts('-1');
	}

	function filePath () {
		if ($this->filepath) return $this->filepath.$this->realname;
		else return '';
	}

	function deleteFile () {
		$physical =& $this->obtainPhysical();
		$physical->delete();
		$this->deleteFileDB();
	}
	
	function setMetaData () {
		$interface =& remositoryInterface::getInstance();
		$interface->prependMetaTag('description', $this->smalldesc);
		if ($this->keywords) $interface->prependMetaTag('keywords', $this->keywords);
		else $interface->prependMetaTag('keywords', $this->filetitle);
	}
	
	function checkLicenseagree () {
		if ($this->licenseagree AND $this->license != '') $this->licenseagree = 1;
		else $this->licenseagree = 0;
	}
	
	function getValues (&$user) {
		$sql = "SELECT f.*, AVG(l.value) AS vote_value, COUNT(l.value) AS vote_count FROM #__downloads_files AS f LEFT JOIN #__downloads_log AS l ON l.type=3 AND l.fileid=f.id WHERE f.id = $this->id";
		if (!$user->isAdmin()) $sql .= " AND published=1";
		$sql .= ' GROUP BY f.id';
		$this->readDataBase($sql);
	}
	
	function getByName ($name) {
		$sql = "SELECT * FROM #__downloads_files WHERE islocal=1 AND realname='$name'";
		$this->readDataBase($sql);
	}

	function evaluateVote () {
		return round($this->vote_value);
	}

	function addVote (&$user, $vote) {
		$newvote =& new remositoryLogEntry(_REM_VOTE_USER_GENERAL,$user->id,$this->id,$vote);
		$newvote->insertEntry();
		$totalvalue = $this->vote_value * $this->vote_count + $vote;
		$this->vote_count++;
		$this->vote_value = $totalvalue/$this->vote_count;
	}

	function userVoted (&$user) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$sql = "SELECT COUNT(id) FROM #__downloads_log WHERE type=3 AND userid=$user->id AND fileid=$this->id";
		remositoryRepository::doSQL($sql);
		return $database->loadResult();
	}

	function userCommented (&$user) {

		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();

		$sql = "SELECT count(id) FROM #__downloads_reviews WHERE itemid = $this->id AND userid = $user->id";
		$database->setQuery ($sql);
		if ($database->loadResult() == 0) return false;
		echo '<h4>'._DOWN_ALREADY_COMM.'</h4>';
		return true;
	}

	function getComments () {
		return remositoryComment::getComments($this);
	}

	function &getContainer () {
		$manager =& remositoryContainerManager::getInstance();
		$container =& $manager->getContainer($this->containerid);
		return $container;
	}
	
	function memoContainer (&$container) {
		$this->registered = $container->registered;
		$this->userupload = $container->userupload;
		$this->groupid = $container->groupid;
		if ($this->plaintext = $container->plaintext) {
			$this->filepath = '';
			$this->isblob = 0;
		}
		else {
			if ($this->filepath = $container->filepath) $this->isblob = 0;
			else $this->isblob = 1;
		}
		$this->editgroup = $container->editgroup;
		if (!$this->filepath And !$this->plaintext) $this->isblob = 1;;
	}

	function getCategoryName () {
    	$parent = $this->getContainer();
    	return $parent->getCategoryName(true);
    }

    function getFamilyNames () {
    	$parent = $this->getContainer();
    	return $parent->getFamilyNames(true);
    }
    
	function incrementCounts ($by) {
		$container = $this->getContainer();
		while ($container != null) {
			$container->increment($by);
			$container=$container->getParent();
		}
	}

	function downloadForbidden (&$user, &$message) {
	    $message = '';
		if ($user->isAdmin() OR ($this->registered & 2)) return false;
		if ($user->isLogged()) {
			if (($this->userupload & 2) AND remositoryGroup::isUserMember($this->groupid,$user)) return false;
			else {
				$message = '<br/>&nbsp;<br/> '._DOWN_MEMBER_ONLY_WARN.remositoryGroup::getName($this->groupid);
				return true;
			}
		}
		$message = '<br/>&nbsp;<br/> '._DOWN_REG_ONLY_WARN;
		return true;
	}
	
	function updatePermitted (&$user) {
		if ($user->isAdmin()) return true;
		$repository =& remositoryRepository::getInstance();
		if ($repository->Allow_User_Edit AND $user->isLogged() AND ($user->id==$this->submittedby OR ($this->editgroup != 0 AND remositoryGroup::isUserMember($this->editgroup,$user)))) return true;
		else return false;
	}

	function getExtension () {
		if ($this->islocal) return $this->lastPart($this->realname, '.');
		else return $this->lastPart($this->url, '.');
	}
	
	function checkExtensionOK () {
		if ($this->islocal) {
			if ($this->isblob OR $this->plaintext) return;
			$repository =& remositoryRepository::getInstance();
			if ($repository->badExtension ($this->realname)) exit();
			else $this->filetype = $this->getExtension();
		}
	}

	function makeAutoShort () {
		if ($this->autoshort) {
			$this->autoshort = 1;
			$repository =& remositoryRepository::getInstance();
			$max = $repository->Small_Text_Len-3;
			$plain = strip_tags($this->description);
			$plain = str_replace('&nbsp;', ' ', $plain);
			if (strlen($plain) > $max) $this->smalldesc=substr($plain,0,$max).'...';
			else $this->smalldesc = $plain;
		}
		else $this->autoshort = 0;
	}
	
	function &getEditSelectList ($type, $parm, &$user) {
		$repository =& remositoryRepository::getInstance();
		$clist = $repository->getSelectList(false, $this->containerid, $type, $parm, $user);
		return $clist;
	}

	function getPhysicalData (&$physicalFile) {
		$this->realname = $physicalFile->proper_name;
		$this->filedate = $physicalFile->date;
		$this->filesize = $physicalFile->size;
		if (!$this->filetitle) $this->filetitle = $physicalFile->proper_name;
		$this->islocal = 1;
		$this->url = '';
	}
	
	function onDiskCheckFail (&$physicalFile) {
		$repository =& remositoryRepository::getInstance();
		$physicalFile->antiLeech();
		$this->getPhysicalData($physicalFile);
		$file_path = $this->filepath.$this->realname;
   		if (file_exists($file_path) AND !$repository->Allow_Up_Overwrite) return true;
		$this->checkExtensionOK();
		return false;
	}

	function isFieldHTML ($field) {
		return in_array($field, array('description', 'smalldesc', 'license'));
	}

	function fieldSizeLimit ($field) {
		$repository =& remositoryRepository::getInstance();
		$large = array ('description', 'license');
		if (in_array($field,$large)) return $repository->Large_Text_Len;
		else return $repository->Small_Text_Len;
	}

	function getIcons () {
		return remositoryRepository::getIcons ('file_icons');
	}

	function togglePublished (&$idlist, $value) {
		$cids = implode( ',', $idlist );
		$sql = "UPDATE #__downloads_files SET published=$value". "\nWHERE id IN ($cids)";
		remositoryRepository::doSQL($sql);
	}
	
	function sendAdminMail ($user_full) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		// Site name and live site are needed for evaluation of message text below
		// as is the user_full parameter 
		$mosConfig_live_site = $interface->getCfg('live_site');
		$mosConfig_sitename = $interface->getCfg('sitename');
		$superadmin = remositoryUser::superAdminMail();
		$repository = remositoryRepository::getInstance();
		if ($repository->Sub_Mail_Alt_Addr=='') $recipient = $superadmin;
	    else $recipient = $repository->Sub_Mail_Alt_Addr;
		$subject = $interface->getCfg('sitename').':'._DOWN_MAIL_SUB;
		if ($this->published) $message = _DOWN_MAIL_MSG_APP;
		else $message = _DOWN_MAIL_MSG;
		$message .= "\n"._DOWN_FILE_TITLE . $this->filetitle;
		eval ("\$message = \"$message\";");
		$containerid = intval($this->containerid);
		$sql = "SELECT u.email FROM `mos_downloads_containers` AS c, `mos_mbt_group_member` , `mos_users` AS u WHERE c.id = $containerid AND group_id = c.groupid AND u.id = member_id";
		$database->setQuery($sql);
		$notifiers = $database->loadObjectList();
		if ($notifiers) {
			$recipient = array ($recipient);
			foreach ($notifiers as $notifier) $recipient[] = $notifier->email;
		}
		mosMail ($superadmin, 'Administrator', $recipient, $subject, $message);
	}

	function resetDownloadCounts () {
		remositoryRepository::doSQL('UPDATE #__downloads_files SET downloads=0');
	}
	
	function storeMemoFields ($container) {
		$sql = "UPDATE #__downloads_files SET registered='$container->registered', userupload='$container->userupload', groupid='$container->groupid', editgroup='$container->editgroup' WHERE containerid=$container->id";
		remositoryRepository::doSQL($sql);
	}
	
	function getFilesSQL ($published, $count=false, $containerid=0, $descendants=false, $orderby=2, $search='', $limitstart=0, $limit=0) {
		$sorter = array ('', ' ORDER BY id', ' ORDER BY filetitle', ' ORDER BY downloads DESC', ' ORDER BY submitdate DESC');
		if (!isset($sorter[$orderby]) OR $orderby == 0) $orderby = 2;
		if ($count) $results = 'count(f.id)';
		else $results = 'f.*, AVG(l.value) AS vote_value, COUNT(l.value) AS vote_count';
		if ($descendants AND $containerid) {
			$sql = "SELECT $results FROM #__downloads_structure AS s, #__downloads_files AS f ";
			$where[] = 'f.containerid=s.item';
			$where[] = "s.container = $containerid";
		}
		else {
			$sql = "SELECT $results FROM #__downloads_files AS f ";
			if ($containerid) $where[] = "f.containerid = $this->id";
		}
		if (!$count) $sql .= ' LEFT JOIN #__downloads_log AS l ON l.type=3 AND l.fileid=f.id';
		if ($published) $where[] = 'f.published=1';
		if ($search) {
			$interface =& remositoryInterface::getInstance();
			$search = $interface->getEscaped($search);
			$where[] = "LOWER(f.filetitle) LIKE '%$search%'";
		}
		if (isset($where)) $sql .= ' WHERE '.implode(' AND ',$where);
		if (!$count) {
			$sql .= ' GROUP BY f.id';
			$sql .= $sorter[$orderby];
		}
		if ($limit) $sql .= " LIMIT $limitstart,$limit";
		return $sql;
	}
	
	function popularLoggedFiles ($category, $max, $days, $user) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$sql = 'SELECT f.id, f.filetitle, f.autoshort, f.description, f.smalldesc, f.filedate, f.icon, f.containerid, c.name, COUNT( l.fileid ) AS downloads FROM #__downloads_log AS l, #__downloads_files AS f, #__downloads_containers AS c';
		if ($category) $sql .= ', #__downloads_structure AS s';
		$sql .= ' WHERE c.id = f.containerid AND f.published=1 AND l.type=1 AND l.fileid=f.id';
		$repository =& remositoryRepository::getInstance();
		$sql .= remositoryAbstract::visibilitySQL ($user, $repository->See_Files_no_download);
		if ($category) $sql .= " AND f.containerid=s.item AND s.container=$category";
		$sql .= " AND DATE_SUB(CURDATE(),INTERVAL $days DAY ) <= l.date";
		$sql .= " GROUP BY l.fileid ORDER BY downloads DESC LIMIT $max";
		$database->setQuery($sql);
		$files = $database->loadObjectList();
		if ($files) return $files;
		else return array();
	}

	function popularDownloadedFiles ($category, $max, $user) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$sql = 'SELECT f.id, f.downloads, f.filetitle, f.autoshort, f.description, f.smalldesc, f.filedate, f.icon, f.containerid, c.name from #__downloads_files AS f, #__downloads_containers AS c';
		if ($category) $sql .= ', #__downloads_structure AS s';
		$sql .= ' WHERE f.containerid = c.id AND f.published=1';
		$repository =& remositoryRepository::getInstance();
		$sql .= remositoryAbstract::visibilitySQL ($user, $repository->See_Files_no_download);
		if ($category) $sql .= " AND f.containerid=s.item AND s.container=$category";
		$sql .= " ORDER BY downloads DESC LIMIT $max";
		$database->setQuery($sql);
		$files = $database->loadObjectList();
		if ($files) return $files;
		else return array();
	}
	
	function newestFiles ($category, $max, $user) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$sql = 'SELECT f.id, f.filetitle, f.autoshort, f.description, f.smalldesc, f.filedate, f.icon, f.containerid, c.name from #__downloads_files AS f, #__downloads_containers AS c';
		if ($category) $sql .= ', #__downloads_structure AS s';
		$sql .= ' WHERE f.containerid = c.id AND f.published=1';
		$repository =& remositoryRepository::getInstance();
		$sql .= remositoryAbstract::visibilitySQL ($user, $repository->See_Files_no_download);
		if ($category) $sql .= " AND f.containerid=s.item AND s.container=$category";
		$sql .= " ORDER BY f.filedate DESC LIMIT $max";
		$database->setQuery($sql);
		$files = $database->loadObjectList();
		if ($files) return $files;
		else return array();
	}
	
	function getCountInContainer ($id, $published, $search='') {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$sql = "SELECT COUNT(id) FROM #__downloads_files WHERE containerid = $id";
		if ($published) $sql .= ' AND published=1';
		if ($search) $sql .= " AND LOWER(filetitle) LIKE '%$search%'";
		$database->setQuery($sql);
		return $database->loadResult();
	}
	
	function searchFilesSQL($search_text, $seek_title, $seek_desc, &$user) {
		$interface =& remositoryInterface::getInstance();
		$search_text = $interface->getEscaped($search_text);
		$sql="SELECT id,containerid,filetitle,description,icon,filesize,downloads FROM #__downloads_files AS f";
		$searchspec = 0;
		if ($seek_title) $searchspec = 2;
		if ($seek_desc) $searchspec++;
		switch ($searchspec) {
			case '1':
			    $sql .= " WHERE description LIKE '%$search_text%'";
			    break;
			case '2':
			    $sql .= " WHERE filetitle LIKE '%$search_text%'";
				break;
			case '3':
			    $sql .= " WHERE (filetitle LIKE '%$search_text%' OR description LIKE '%$search_text%')";
			    break;
			default:
				echo '<br/>&nbsp;<br/>'._DOWN_SEARCH_ERR;
				exit;
		}
		$repository =& remositoryRepository::getInstance();
		$sql .= remositoryAbstract::visibilitySQL ($user, $repository->See_Files_no_download);
		return $sql;
	}
	
}

class remositoryTempFile extends remositoryFile {
	var $filetempname='';
	var $filetemphash='';
	var $oldid=0;

	function getValues () {
		$sql = "SELECT * FROM #__downloads_temp WHERE id = $this->id";
		$this->readDataBase($sql);
	}

	function &obtainPhysical () {
		$physical =& new remositoryPhysicalFile();
		$physical->setData($this->filePath());
		return $physical;
	}
	function getByName ($name) {
		$sql = "SELECT * FROM #__downloads_temp WHERE islocal=1 AND realname=$name";
		$this->readDataBase($sql);
	}

	function filePath () {
		if ($this->filetempname) {
			$temp_arr=explode(",",$this->filetempname);
			$repository =& remositoryRepository::getInstance();
			return $repository->Up_Path.'/'.$temp_arr[0].$temp_arr[1];
		}
		else return parent::filePath();
	}
	
	function tableName () {
		return '#__downloads_temp';
	}

	function deleteFileDB () {
		$sql = "DELETE FROM #__downloads_temp WHERE id=$this->id";
		remositoryRepository::doSQL($sql);
	}

	function inDataBase () {

		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();

		if ($this->filetempname) {
			$sql="SELECT COUNT(*) FROM #__downloads_temp WHERE filetempname = $this->filetempname";
			$database->setQuery($sql);
			if ($database->loadResult() != 0) return true;
		}
		return false;
	}

	function saveFile () {
		if ($this->inDataBase()) $sql = $this->updateSQL();
		else $sql = $this->insertSQL();
		remositoryRepository::doSQL($sql);
	}
	
}

class remositoryPhysicalFile {
	var $interface = '';
	var $database = '';
	var $error_message = '';
	var $file_path = '';
	var $proper_name = '';
	var $date = '';
	var $size = '';
	var $isblob = false;
	var $plaintext = false;
	var $fileid = 0;
	var $uploaded = false;
	
	function remositoryPhysicalFile () {
		$this->interface =& remositoryInterface::getInstance();
		$this->database =& $this->interface->getDB();
	}
	
	function isUpload () {
		return $this->uploaded;
	}
	
	function setData ($filepath, $fileid=0, $isblob=0, $plaintext=0) {
		$this->file_path = $filepath;
		$this->fileid = $fileid;
		$this->isblob = $isblob;
		$this->plaintext = $plaintext;
		$this->proper_name = basename($filepath);
		if (!$isblob AND !$plaintext AND file_exists($filepath)) {
			$this->date = date('Y-m-d H:i:s', filemtime($filepath));
			$this->size = number_format(filesize($filepath)/1024, 2).' Kb';
		}
	}
	
	function setFileID ($id) {
		$this->fileid = $id;
	}
	
	function setPerms () {
		$interface =& remositoryInterface::getInstance();
		if (!$this->uploaded AND $this->file_path) {
   			$origmask = @umask(0);
			if ($interface->getCfg('fileperms')) {
	    		$mode = octdec($interface->getCfg('fileperms'));
	    		$result = @chmod($this->file_path, $mode);
			}
			else $result = @chmod($this->file_path,0644);
			@umask($origmask);
		}
		else $result = true;
		return $result;
	}
	
	function delete () {
		if ($this->file_path) @unlink($this->file_path);
		else {
			if ($this->isblob) $sql = "DELETE FROM #__downloads_blob WHERE fileid = $this->fileid";
			elseif ($this->plaintext) $sql = "DELETE FROM #__downloads_text WHERE fileid = $this->fileid";
			if ($sql) {
				remositoryRepository::doSQL($sql);
			}
		}
	}

	function handleUpload ($suffix='') {
		$key = 'userfile'.$suffix;
		if (!isset($_FILES[$key]) OR $_FILES[$key]['tmp_name']=='none' OR $_FILES[$key]['tmp_name']==''){
			$this->error_message =_ERR1;
			return;
		}
		if ($_FILES[$key]['error']) {
		    $this->error_message = _ERR11;
			return;
		}
		$this->proper_name = $_FILES[$key]['name'];
		if ($_FILES[$key]['size'] == 0) {
		    $this->error_message = _ERR3;
		    return;
		}
		$this->size = $_FILES[$key]['size']/1024;
		$repository = remositoryRepository::getInstance();
		if($this->size > $repository->MaxSize) {
	    	$this->error_message =  _ERR5.$repository->MaxSize.' Kb';
	    	return;
	    }
	    $this->size = number_format($this->size,2).' Kb';
		if (!is_uploaded_file($_FILES[$key]['tmp_name'])) {
		    $this->error_message = _ERR2;
		    return;
	    }
	    else $this->file_path = $_FILES[$key]['tmp_name'];
		if (ini_get('safe_mode')) $this->date = date('Y-m-d H:i:s');
		else $this->date = date('Y-m-d H:i:s',filemtime($this->file_path));
		$this->uploaded = true;
	}
	
	function antiLeech () {
		$repository =& remositoryRepository::getInstance();
	    if ($repository->Anti_Leach){
	    	$this->proper_name = substr(md5($this->interface->getCfg('absolute_path')),0,8).$this->proper_name;
	    }
	}
	
	function makeUploadSafe () {
		if ($this->uploaded AND ini_get('safe_mode')) {
			$repository =& remositoryRepository::getInstance();
			$newfile = $repository->Up_Path.'/'.time().$this->proper_name;
			move_uploaded_file($this->file_path, $newfile);
			$this->file_path = $newfile;
		}
	}
	
	function moveTo ($filepath, $fileid, $isblob, $plaintext) {
		if ($fileid) $this->fileid = $fileid;
		if ($this->isblob) {
			if ($isblob) return true;
			elseif ($plaintext) return $this->blobToText();
			else {
				$repository =& remositoryRepository::getInstance();
				if ($repository->badExtension($filepath)) return false;
				$this->file_path = $filepath;
				return $this->blobToFile();
			}
		}
		elseif ($this->plaintext) {
			if ($isblob) return $this->textToBlob();
			elseif ($istext) return true;
			else {
				$repository =& remositoryRepository::getInstance();
				if ($repository->badExtension($filepath)) return false;
				$this->file_path = $filepath;
				return $this->textToFile();
			}
		}
		else {
			if ($isblob) return $this->fileToBlob();
			elseif ($plaintext) return $this->fileToText();
			else return $this->fileToFile($filepath);
		}
	}
	
	function fileToFile ($filepath) {
		if ($this->uploaded) {
			if (move_uploaded_file($this->file_path, $filepath)) {
				$this->uploaded = false;
				$this->file_path = $filepath;
				$this->setPerms();
				return true;
			}
			else return false;
		}
		elseif ($this->file_path != $filepath) {
			$result = rename ($this->file_path, $filepath);
			if ($result) {
				$this->file_path = $filepath;
				$this->setPerms();
			}
			return $result;
		}
		else return true;
	}
			
	
	function fileToBlob () {
		$this->makeUploadSafe();
		if ($this->fileid AND $f = @fopen($this->file_path,'rb')) {
			$sql = "DELETE FROM #__downloads_blob WHERE fileid=$this->fileid";
			remositoryRepository::doSQL($sql);
			$chunkid = 0;
			$sql = "INSERT INTO #__downloads_blob (fileid, chunkid, datachunk) VALUES ('$this->fileid', '";
			while($f && !feof($f)) {
				$chunk = fread($f, 60000);
				$chunk = $this->interface->getEscaped($chunk);
				remositoryRepository::doSQL($sql."$chunkid', '$chunk')");
				$chunkid++;
			}
			fclose($f);
			$sql = "UPDATE #__downloads_files SET chunkcount=$chunkid WHERE id=$this->fileid";
			remositoryRepository::doSQL($sql);
			@unlink($this->file_path);
			return true;
		}
		else return false;
	}

	function fileToText () {
		$this->makeUploadSafe();
		if ($this->fileid AND $f = @fopen($this->file_path,'rb')) {
			$sql = "DELETE FROM #__downloads_text WHERE fileid=$this->fileid";
			remositoryRepository::doSQL($sql);
			$sql = "INSERT INTO #__downloads_text (fileid, filetext) VALUES ($this->fileid, '";
			while($f && !feof($f)) {
				$chunk = fread($f, 65535);
				$chunk = $this->interface->getEscaped($chunk);
				$sql .= $chunk;
			}
			fclose($f);
			remositoryRepository::doSQL($sql."')");
			@unlink($this->file_path);
			return true;
		}
		else return false;
	}
	
	function blobToFile () {
		$result = false;
		if (!file_exists($this->file_path) AND $f = @fopen($this->file_path, 'wb')) {
			$sql = "SELECT chunkid FROM #__downloads_blob WHERE fileid=$this->fileid ORDER BY chunkid";
			$this->database->setQuery($sql);
			$chunks = $this->database->loadResultArray();
			if ($chunks) foreach ($chunks as $chunkid) {
				$sql = "SELECT datachunk FROM #__downloads_blob WHERE fileid=$this->fileid AND chunkid=$chunkid";
				$this->database->setQuery($sql);
				$datachunk = $this->database->loadResult();
				if (fwrite ($f, $datachunk)) $result = true;
			}
			fclose($f);
		}
		if ($result) {
			$this->setPerms ();
			$sql = "DELETE FROM #__downloads_blob WHERE fileid=$this->fileid";
			remositoryRepository::doSQL($sql);
		}
		return $result;
	}
	
	function textToFile () {
		$result = false;
		if ($f = @fopen($this->file_path,'wb')) {
			$sql = "SELECT filetext FROM #__downloads_text WHERE fileid=$this->fileid";
			$this->database->setQuery($sql);
			$text = $this->database->loadResult();
			if ($text AND fwrite ($f, $text)) $result = true;
			fclose($f);
		}
		if ($result) {
			$this->setPerms ();
			$sql = "DELETE FROM #__downloads_text WHERE fileid=$this->fileid";
			remositoryRepository::doSQL($sql);
		}
		return $result;
	}
	
	function blobToText () {
		$text = '';
		$sql = "DELETE FROM #__downloads_text WHERE fileid=$this->fileid";
		remositoryRepository::doSQL($sql);
		$sql = "SELECT chunkid FROM #__downloads_blob WHERE fileid=$this->fileid ORDER BY chunkid";
		$this->database->setQuery($sql);
		$chunks = $this->database->loadResultArray();
		$isql = "INSERT INTO #__downloads_text (fileid, filetext) VALUES ($this->fileid, '";
		if ($chunks) foreach ($chunks as $chunkid) {
			$sql = "SELECT datachunk FROM #__downloads_blob WHERE fileid=$this->fileid AND chunkid=$chunkid";
			$this->database->setQuery($sql);
			$isql .= $this->interface->getEscaped($this->database->loadResult());
		}
		remositoryRepository::doSQL($isql."')");
		$sql = "DELETE FROM #__downloads_blob WHERE fileid=$this->fileid";
		remositoryRepository::doSQL($sql);
		return true;
	}
	
	function textToBlob () {
		$sql = "SELECT filetext FROM #__downloads_text WHERE fileid=$this->fileid";
		$this->database->setQuery($sql);
		$text = $this->database->loadResult();
		$sql = "DELETE FROM #__downloads_blob WHERE fileid=$this->fileid";
		remositoryRepository::doSQL($sql);
		$chunkid = 0;
		$sql = "INSERT INTO #__downloads_blob (fileid, chunkid, datachunk) VALUES ('$this->fileid', '";
		while ($chunkid*60000 < strlen($text)) {
			$chunk = substr($text,$chunkid*60000,60000);
			$chunk = $this->interface->getEscaped($chunk);
			remositoryRepository::doSQL($sql."$chunkid', '$chunk')");
			$chunkid++;
		}
		$sql = "DELETE FROM #__downloads_text WHERE fileid=$this->fileid";
		remositoryRepository::doSQL($sql);
		return true;
	}
	
}

class remositoryContainerManager {
	var $remository_links = array();
	var $remository_containers = array();
	
	function remositoryContainerManager () {
		$sql = 'SELECT * FROM #__downloads_containers ORDER BY sequence, name';
		$this->remository_containers = remositoryRepository::doSQLget($sql,'remositoryContainer');
		foreach ($this->remository_containers as $i=>$container) $this->remository_links[$container->id] = $i;
	}
	
    function &getInstance () {
        static $instance;
        if (!is_object($instance)) $instance = new remositoryContainerManager();
        return $instance;
    }
    
    function &getFromIDs (&$ids) {
    	$result = array();
    	foreach ($ids as $id) $result[] = $this->remository_containers[$this->remository_links[$id]];
    	return $result;
    }
	
	function &getChildren ($id, $published=true, $search='') {
		$children = array();
		foreach ($this->remository_containers as $i=>$container) {
			if ($container->parentid != $id) continue;
			if ($published AND $container->published == 0) continue;
			if ($search AND strpos($container->name, $search) === false) continue;
			$children[] =& $this->remository_containers[$i];
		}
		return $children;
	}
	
	function &getVisibleChildren ($id, &$user) {
		$children = array();
		$repository =& remositoryRepository::getInstance();
		foreach ($this->remository_containers as $i=>$container) {
			if ($container->parentid != $id) continue;
			if ($user->isAdmin()) {
				$children [] =& $this->remository_containers[$i];
			}
			else {
				if ($container->published == 0) continue;
				if (!$repository->See_Containers_no_download) {
					$grouplist = remositoryGroup::getMembersGroupList($user);
					$groups = explode (',', $grouplist);
					if (strlen($grouplist)) {
						if (($container->registered & 2) OR (($container->userupload & 2) AND (in_array($container->groupid, $groups)))) $children[] =& $this->remository_containers[$i];
					}
					else {
						if ($container->registered & 2) $children[] =& $this->remository_containers[$i];
					}
				}
				else $children[] =& $this->remository_containers[$i];
			}
		}
		return $children;
	}
	
	function &getContainer ($id) {
		if ($id AND isset($this->remository_links[$id])) $container =& $this->remository_containers[$this->remository_links[$id]];
		else $container = null;
		return $container;
	}
	
	function &getParent ($id) {
		if ($id AND isset($this->remository_links[$id])) $parent =& $this->remository_containers[$this->remository_links[$id]];
		else $parent = null;
		return $parent;
	}
	
	function delete ($id) {
		$sql = "DELETE FROM #__downloads_containers WHERE id=$id";
		remositoryRepository::doSQL($sql);
		if (isset($this->remository_links[$id]) AND isset($this->remository_containers[$this->remository_links[$id]])) {
			unset($this->remository_containers[$this->remository_links[$id]]);
			unset($this->remository_links[$id]);
		}
	}

	function makeSelectedList ($containers, $type, $parm) {
		$repository =& remositoryRepository::getInstance();
		$ids = explode(',', $containers);
		foreach ($ids as $id) {
			$id = intval($id);
			if (isset($this->remository_links[$id])) $selector[] = $repository->makeOption($id, $this->remository_containers[$this->remository_links[$id]]->name);
		}
		if (isset($selector)) return $repository->selectList ($selector, $type, $parm);
		else return '';
	}
	
	function &getFilePathData ($path='') {
		$repository = remositoryRepository::getInstance();
		$defaultdown = $repository->Down_Path.'/';
		$paths = array();
		foreach ($this->remository_containers as $i=>$container) {
			if ($path == '' OR ($path AND ($container->filepath == $path OR $container->filepath == ''))) {			
				$paths[] =& $this->remository_containers[$i];
			}
		}
		$containers = array();
		if ($paths) {
			foreach ($paths as $path) {
				if ($path->filepath) $containers[$path->filepath][] = $path->id;
				else $containers[$defaultdown][] = $path->id;
			}
		}
		return $containers;
	}
	
	function &getFolders ($search='') {
		$folders = array();
		foreach ($this->remository_containers as $i=>$container) {
			if ($search AND strpos(strtolower($container->name), $search) === false) continue;
			$folders[] =& $this->remository_containers[$i];
		}
		return $folders;
	}

	function getCategories ($published=false, $search='') {
		$categories = array();
		foreach ($this->remository_containers as $i=>$container) {
			if ($published AND $container->published == 0) continue;
			if ($container->parentid != 0) continue;
			if ($search AND strpos(strtolower($container->name), $search) === false) continue;
			$categories[] =& $this->remository_containers[$i];
		}
		return $categories;
	}
	
}

class remositoryContainer extends remositoryAbstract {
	/** @var int ID for container record in database */
	var $id=0;
	/** @var int ID of parent container in database if a folder */
	var $parentid=0;
	/** @var string Name of container */
	var $name='';
	/** @var string Path for storing files */
	var $filepath='';
	/** @var string Container description */
	var $description='';
	/** @var bool Is the container published? */
	var $published=false;
	/** @var int Count of contained folders */
	var $foldercount=0;
	/** @var int Files in the container count */
	var $filecount=0;
	/** @var string Icon - not sure how this is used */
	var $icon='';
	/** @var Visitor options 1=upload, 2=download, 3=both, 0=neither */
	var $registered='2';
	/** @var User options 1=upload, 2=download, 3=both, 0=neither */
	var $userupload='3';
	/** @var bool Is the file to be stored as a text string? */
	var $plaintext=0;
	/** @var int Group of users that has access to this container */
	var $groupid=0;
	/** @var int Editor group of users */
	var $editgroup=0;
	/** @var bool Auto-approve for Admin - Yes or No (Global applies) */
	var $adminauto=0;
	/** @var bool Auto-approve for user - Yes or No (Global applies)*/
	var $userauto=0;
	/** @var int Auto-approve group of users */
	var $autogroup=0;

	/**
	* File object constructor
	* @param int Container ID from database or null
	*/
	function remositoryContainer ( $id=0 ) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$this->id = $id;
		if ($id) {
			$sql = "SELECT * FROM #__downloads_containers WHERE id = $this->id";
			$database->setQuery( $sql );
			$database->loadObject( $this );
		}
	}
	
	function tableName () {
		return '#__downloads_containers';
	}

	function delete () {
		$manager =& remositoryContainerManager::getInstance();
		$manager->delete($this->id);
	}
	
	function deleteAll () {
		$folders = $this->getChildren(false);
		foreach ($folders as $folder) $folder->deleteAll ();
		$files = $this->getFiles(true);
		foreach ($files as $file) $file->deleteFile();
		$tempfiles = $this->getTempFiles();
		foreach ($tempfiles as $file) $file->deleteFile();
		$this->delete();
	}

	function saveValues () {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$this->forceBools();
		if ($this->id == 0) {
			$sql = $this->insertSQL();
			remositoryRepository::doSQL ($sql);
			$this->id = $database->insertid();
		}
		else {
			$sql = $this->updateSQL();
			remositoryRepository::doSQL ($sql);
		}
	}
	
	function setMetaData () {
		$interface =& remositoryInterface::getInstance();
		$interface->prependMetaTag('description', strip_tags($this->name));
		if ($this->keywords) $interface->prependMetaTag('keywords', $this->keywords);
		else $interface->prependMetaTag('keywords', $this->name);
	}

	function isCategory () {
		if ($this->parentid == 0) return true;
		else return false;
	}
	
	function getCategoryName ($showself=false) {
		$category = $this->getCategory();
		if ($this->parentid OR $showself) return $category->name;
		return '*';
    }
    
    function &getCategory () {
		$container =& $this;
		while (is_object($container)) {
			$category = $container;
			$container =& $category->getParent();
		}
		return $category;
	}

    function getFamilyNames ($include=false) {
    	$names = '';
    	$parent = $this->getParent();
    	if ($parent AND $parent->parentid) {
    		$names .= '/'.$parent->name;
    		$grandparent = $parent->getParent();
    		if ($grandparent AND $grandparent->parentid) {
    			$names = '/'.$grandparent->name.$names;
				$greatgrandparent = $grandparent->getParent();
				if ($greatgrandparent->parentid) $names = '..'.$names;
			}
    	}
    	if ($include AND $this->id AND $this->parentid) $names = $names.'/'.$this->name;
    	if ($names) return $names;
    	return '-';
    }
	
	function downloadForbidden (&$user) {
		if ($user->isAdmin() OR ($this->registered & 2)) return false;
		if ($user->isLogged()) {
			if (($this->userupload & 2) AND remositoryGroup::isUserMember($this->groupid,$user)) return false;
			else {
				echo '<br/>$nbsp<br/> '._DOWN_MEMBER_ONLY_WARN.$this->name;
				return true;
			}
		}
		echo '<br/>&nbsp;<br/> '._DOWN_REG_ONLY_WARN;
		return true;
	}
	
	function &getChildren ($published=true, $search='') {
		$manager =& remositoryContainerManager::getInstance();
		$children =& $manager->getChildren($this->id, $published, $search);
		return $children;
	}
	
	function descendantSQL ($operation, $actions='') {
	    return "$operation #__downloads_containers AS c, #__downloads_structure AS s $actions WHERE s.item=c.id AND s.container=$this->id AND s.item!=s.container";
	}
	
	function getDescendants ($search='') {
		$manager =& remositoryContainerManager::getInstance();
		if ($this->id) {
			$ids =& $this->getDescendantIDs ($search);
			return $manager->getFromIDs($ids);
		}
		else return $manager->getFolders($search);
	}
	
	// Gives a null result if the container is not populated
	function &getDescendantIDs ($search='') {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$result = array();
		if ($this->id) {
			$search = $interface->getEscaped($search);
			$sql = $this->descendantSQL('SELECT c.id FROM');
			if ($search) $sql .= " AND LOWER(name) LIKE '%$search%'";
			$sql .= ' ORDER BY name';
			$database->setQuery($sql);
			$result = $database->loadResultArray();
		}
		return $result;
	}
	
	function makeDescendantsInherit () {
	    $fields = $this->inheritableFields();
	    foreach ($fields as $field) {
	    	$value = $this->$field;
	        $update[] = "c.$field='$value'";
		}
		$setter = 'SET '.implode(', ',$update);
	    $sql = $this->descendantSQL('UPDATE', $setter);
	    remositoryRepository::doSQL($sql);
	}
	
	function moveFilesAsNecessary ($inherit=false) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		if ($inherit) {
			$containerids =& $this->getDescendantIDs();
			array_push($containerids, $this->id);
		}
		else $containerids = array($this->id);
		$selector = implode(',', $containerids);
		if (!$this->filepath AND !$this->plaintext) $isblob = 1;
		else $isblob = 0;
		$sql = "SELECT id, filepath, realname, isblob, plaintext FROM #__downloads_files "
		." WHERE containerid IN($selector) AND (filepath != '$this->filepath' OR isblob != $isblob OR plaintext != $this->plaintext)";
		$database->setQuery($sql);
		$files = $database->loadObjectList();
		$physical =& new remositoryPhysicalFile();
		if ($files) foreach ($files as $file) {
			$physical->setData($file->filepath.$file->realname, $file->id, $file->isblob, $file->plaintext);
			if ($physical->moveTo($this->filepath.$file->realname, $file->id, $isblob, $this->plaintext)) {
				$database->setQuery("UPDATE #__downloads_files "
				." SET filepath='$this->filepath', isblob=$isblob, plaintext=$this->plaintext"
				." WHERE id = $file->id");
				$database->query();
			}
			else echo 'move failed';
		}
	}
	
	function inheritableFields () {
		return array ('registered','userupload','filepath','groupid','plaintext','editgroup','adminauto','userauto','autogroup');
	}

	function memoContainer (&$container) {
	    $fields = $this->inheritableFields();
	    foreach ($fields as $field) {
	        $this->$field = $container->$field;
	    }
	 }

	function isDownloadable (&$user) {
		if ($this->registered & 2) return true;
		if ($user->isLogged() AND ($this->userupload & 2) AND remositoryGroup::isUserMember($this->groupid,$user)) return true;
		return false;
	}

	function &getVisibleChildren (&$user) {
		$manager =& remositoryContainerManager::getInstance();
		$children = $manager->getVisibleChildren ($this->id, $user);
		return $children;
	}
	
	function checkFilePath () {
		if ($this->plaintext) $this->filepath = '';
		else {
			$this->filepath=trim(str_replace("\\","/",$this->filepath));
			if (!$this->filepath) {
				$repository = remositoryRepository::getInstance();
				if (!$repository->Use_Database) $this->filepath = $repository->Down_Path;
			}			
			if ($this->filepath) {
				$dir =& new remositoryDirectory($this->filepath, true);
				$this->filepath = $dir->path;
			}
		}
	}
	
	function &getParent () {
		$manager =& remositoryContainerManager::getInstance();
		$parent =& $manager->getParent($this->parentid);
		return $parent;
	}
	
	function increment ($by='0') {
		$parent = $this->getParent();
		if ($parent != null) $parent->increment($by);
		$this->filecount = $this->filecount+$by;
		$sql="UPDATE #__downloads_containers SET filecount=$this->filecount WHERE id=$this->id";
		remositoryRepository::doSQL($sql);
	}
	
	function areFilesVisible (&$user) {
		$repository =& remositoryRepository::getInstance();
		if ($repository->See_Files_no_download OR $user->isAdmin()) return true;
		return $this->isDownloadable($user);
	}


	function getFiles ($published, $orderby=2, $search='', $limitstart=0, $limit=0, $descendants=false) {
		$sql = remositoryFile::getFilesSQL($published, false, $this->id, $descendants, $orderby, $search, $limitstart, $limit);
		return remositoryRepository::doSQLget($sql, 'remositoryFile');
	}
	
	function getFilesCount ($search='', $remUser, $descendants=false) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		if ($remUser->isAdmin()) $published = false;
		else $published = true;
		$sql = remositoryFile::getFilesSQL($published, true, $this->id, $descendants, 2, $search);
		$database->setQuery( $sql );
		return $database->loadResult();
	}
	
	function setFileCount ($chain=null) {
		$this->filecount = 0;
		$this->foldercount = 0;
		if (is_array($chain)) {
			$sql = "DELETE FROM #__downloads_structure WHERE item=$this->id";
			remositoryRepository::doSQL($sql);
			$chain[] = $this->id;
			foreach ($chain as $containerid) {
				$sql = "INSERT INTO #__downloads_structure (container, item) VALUES ($containerid, $this->id)";
				remositoryRepository::doSQL($sql);
			}
		}
		$children = $this->getChildren(false);
		foreach ($children as $child) {
			$counts = $child->setFileCount($chain);
			$this->filecount = $this->filecount + $counts[0];
			$this->foldercount = $this->foldercount + $counts[1];
		}
		$this->filecount = $this->filecount + remositoryFile::getCountInContainer($this->id,true);
		$this->foldercount = $this->foldercount + count($children);
		$sql="UPDATE #__downloads_containers SET filecount=$this->filecount, foldercount=$this->foldercount WHERE id=$this->id";
		remositoryRepository::doSQL($sql);
		return array($this->filecount,$this->foldercount);
	}

	function getTempFiles ($search='') {
		$interface =& remositoryInterface::getInstance();
		if ($this->id == 0) return array();
		$sql = "SELECT * FROM #__downloads_temp WHERE containerid = $this->id";
		if ($search) {
			$search = $interface->getEscaped($search);
			$sql .= " AND LOWER(filetitle) LIKE '%$search%'";
		}
		return remositoryRepository::doSQLget($sql,'remositoryTempFile');
	}
	
	// This is used admin side, and wants all containers, whether they can accept files or not
	function getSelectList ($type, $parm, &$user, $notThis=0) {
		$repository =& remositoryRepository::getInstance();
		$selector[] = $repository->makeOption(0,_DOWN_NO_PARENT);
		$manager =& remositoryContainerManager::getInstance();
		foreach ($manager->getCategories() as $category) $category->addSelectList('',$selector,$notThis,$user);
		return $repository->selectList( $selector, $type, $parm, $this->id );
	}
	
	// This is used on user side for uploads, only want containers that can accept files
	function getPartialSelectList ($type, $parm, &$user, $notThis=0) {
		$repository =& remositoryRepository::getInstance();
		$selector = array();
		$this->addSelectList('', $selector, $notThis, $user, true);
		return (count($selector) ? $repository->selectList( $selector, $type, $parm, $this->id ) : '');
	}

	function addSelectList ($prefix, &$selector, $notThis, &$user, $usable=false) {
		$repository =& remositoryRepository::getInstance();
		$addthis = false;
		if ($user->isAdmin()) {
			$published = false;
			$addthis = true;
		}
		else {
			$published = true;
			if ($user->isLogged()) {
				if (($this->userupload & 1) AND remositoryGroup::isUserMember($this->groupid,$user)) $addthis = true;
			}
			elseif ($this->registered & 1) $addthis = true;
		}
		if ($usable AND $this->filepath AND (!file_exists($this->filepath) OR !is_writeable($this->filepath))) $addthis = false;
		if ($addthis AND (($notThis == 0) OR ($this->id != $notThis))) $selector[] = $repository->makeOption($this->id, $prefix.htmlspecialchars($this->name));
		foreach ($this->getChildren($published) as $container) $container->addSelectList($prefix.$this->name.'/',$selector,$notThis,$user);
	}

	function getURL () {
		return remositoryRepository::remositoryFunctionURL('select', $this->id);
	}
	
	function showPathway () {
		$interface =& remositoryInterface::getInstance();
		$parent = $this->getParent();
		if ($parent != null) $parent->showPathway();
		?>
		<img src="<?php echo $interface->getCfg('live_site') ?>/images/M_images/arrow.png" alt="arrow" />
		<?php
		echo $this->getURL();
		echo $this->name;
		echo '</a>';
	}
	
	function getIcons () {
		return remositoryRepository::getIcons ('folder_icons');
	}
	
	function togglePublished ($idlist, $value) {
		$cids = implode( ',', $idlist );
		$sql = "UPDATE #__downloads_containers SET published=$value". "\nWHERE id IN ($cids)";
		remositoryRepository::doSQL ($sql);
	}
	
}


class remositoryUser {
	/** @var int ID for the user in the database */
	var $id=0;
	/** @var bool Is the current user of administrator status? */
	var $admin=false;
	/** @var bool Is the current user logged in?  */
	var $logged=false;
	/** @var string User name if loggged in */
	var $name='';
	/** @var string User full name if logged in */
	var $fullname='';
	/** @var string User type if logged in */
	var $usertype='';
	/** @var string User current IP address */
	var $currIP='';

	/**
	* File object constructor
	* @param int Directory full path
	*/
	function remositoryUser ( $id=0, $my=null ) {
		$interface =& remositoryInterface::getInstance();
		$this->id = $id;
		if ($id) {
			if (!$my) $my = $interface->getIdentifiedUser($id);
			if ($my->gid) {
				$this->name = $my->username;
				$this->fullname = $my->name;
				$this->usertype = $my->usertype;
				$this->logged = true;
				if ((strtolower($my->usertype)=='administrator') || (strtolower($my->usertype)=='superadministrator')
						|| (strtolower($my->usertype)=='super administrator')){
					$this->admin = true;
				}
			}
		}
		$this->currIP = getenv('REMOTE_ADDR');
	}

	function isAdmin () {
		return $this->admin;
	}
	function isUser () {
		if ($this->isAdmin()) return false;
		return $this->isLogged();
	}
	function isLogged () {
		return $this->logged;
	}
	function fullname () {
		return $this->fullname;
	}
	function uploadsToday () {
		
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		
		$today = date('Y-m-d');
		$sql="SELECT COUNT(*) from #__downloads_temp WHERE submittedby=".$this->id." AND submitdate LIKE '".$today."%'";
		$database->setQuery($sql);
		return $database->loadResult();
	}
	
	function allowUploadCheck ($container) {
		if ($this->isAdmin()) return;
		$repository =& remositoryRepository::getInstance();
		if (!$repository->Allow_User_Sub) {
			echo "<script> alert('"._DOWN_NOT_AUTH."'); window.history.go(-1); </script>\n";
			exit();
		}
		if ($this->logged) {
			if ($this->uploadsToday() > $repository->Max_Up_Per_Day) {
				?><SCRIPT>alert("<?php echo _ERR9; ?>")</SCRIPT><?php
				remositoryUserHTML::pathwayHTML( null );
				?><br/>&nbsp;<br/><?php echo _DOWN_ALL_DONE;
				exit();
			}
			if ($container->id AND $container->userupload & 1 AND remositoryGroup::isUserMember($container->groupid, $this)) return;
		}
		elseif ($container->registered & 1) return;
		echo "<script> alert('"._DOWN_NOT_AUTH."'); window.history.go(-1); </script>\n";
		exit();
		
	}
	
	function hasAutoApprove ($container) {
		if (!$this->isLogged()) return false;
		$repository = remositoryRepository::getInstance();
		if ($this->isAdmin()) {
			if ($container->adminauto) return true;
			if ($repository->Enable_Admin_Autoapp) return true;
		}
		else {
			if ($repository->Enable_User_Autoapp) return true;
			if ($container->userauto AND remositoryGroup::isUserMember($container->autogroup, $this)) return true;
		}
		return false;
	}

	function superAdminMail () {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$sql="select email, name from #__users where usertype='superadministrator' or usertype='super administrator'";
		$database->setQuery( $sql );
		$row=null;
		$database->loadObject( $row );
		if ($row) return $row->email;
    	else return null;
	}

}


class remositoryGroup {
	
	function isUserMember($groupid,&$user) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		if ($groupid == 0) return true;
		$sql = "SELECT COUNT(*) FROM #__mbt_group_member WHERE group_id = $groupid AND member_id=$user->id";
		$database->setQuery($sql);
		return $database->loadResult();
	}
	
	function getName($groupid) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		if ($groupid) {
			$sql = "SELECT group_name FROM #__mbt_group WHERE group_id=$groupid";
			$database->setQuery($sql);
			return $database->loadResult();
		}
		else return _DOWN_ALL_REGISTERED;
	}
	
	function getMembersGroupList ($user) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		if ($user->id == 0) return '';
		if (remositoryGroup::isInstalled()) {
			$sql = "SELECT group_id FROM `#__mbt_group_member` WHERE member_id=$user->id";
			$database->setQuery($sql);
			$groups = $database->loadResultArray();
			if ($groups) {
				$groups[] = 0;
				return implode(',',$groups);
			}
		}
		return '0';
	}

	function isInstalled () {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$tablename = '#__mbt%';
		$database->setQuery($tablename);
		$tablename = $database->_sql;
		$sql = "SHOW TABLES LIKE '$tablename'";
		$database->setQuery($sql);
		$tables = $database->loadObjectList();
		if ($tables AND count($tables)) return true;
		else return false;
	}

	function &getGroups () {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		if (remositoryGroup::isInstalled()) {
			$sql = "SELECT group_id, group_name FROM `#__mbt_group`";
			$database->setQuery($sql);
			$grouplist = $database->loadObjectList();
			if ($grouplist) $result = $grouplist;
		}
		else $result = array();
		return $result;
	}

	function getGroupSelector($selGroup, $selProperty){
		$repository =& remositoryRepository::getInstance();
		$selector[] = $repository->makeOption(0, 'All Registered users');
		if ($grouplist = remositoryGroup::getGroups()) {
			foreach ($grouplist as $group) $selector[] = $repository->makeOption($group->group_id, $group->group_name);
		}
		return $repository->selectList( $selector, $selProperty, 'class="inputbox"', $selGroup->$selProperty );
	}

}


class remositoryRepository extends remositoryAbstract {

	/** @var string Remository version number */
	var $version='';
	/** @var book Default to database to store files */
	var $Use_Database='1';
	/** @var string Table classes */
	var $tabclass=null;
	/** @var string Table headers */
	var $tabheader=null;
	/** @var string URL to header picture */
	var $headerpic=null;
	/** @var array Permitted file extensions */
	var $ExtsOk=null;
	/** @var string Download file path */
	var $Down_Path=null;
	/** @var string Upload file path */
	var $Up_Path=null;
	/** @var int Length of full description (maximum) */
	var $Large_Text_Len=null;
	/** @var int Length of short description (maximum) */
	var $Small_Text_Len=null;
	/** @var int Small Image width (pixels) */
	var $Small_Image_Width=null;
	/** @var int Small Image height (pixels) */
	var $Small_Image_Height=null;
	/** @var int Large Image width (pixels) */
	var $Large_Image_Width=null;
	/** @var int Large Image height (pixels) */
	var $Large_Image_Height=null;
	/** @var bool Allow Large images to be popped up */
	var $Allow_Large_Images=1;
	/** @var int Maximum file size in Kbytes */
	var $MaxSize=null;
	/** @var int Maximum uploads per user per day */
	var $Max_Up_Per_Day=null;
	/** @var int Maximum space allowed for files directory */
	var $Max_Up_Dir_Space=null;
	/** @var int Number of favourites to be marked by a registered user */
	var $Favourites_Max=null;
	/** @var int Maximum number of thumbnail image files, 0 = use URL in file data */
	var $Max_Thumbnails=0;
	/** @var string Default Version Number */
	var $Default_Version=null;
	/** @var string Date format string for PHP data function  */
	var $Date_Format=null;
	/** @var bool Anti Leach in effect */
	var $Anti_Leach=null;
	/** @var bool Allow uploads that overwrite an earlier file */
	var $Allow_Up_Overwrite=null;
	/** @var bool Allow users to submit files */
	var $Allow_User_Sub=null;
	/** @var bool Allow users to edit existing file information */
	var $Allow_User_Edit=null;
	/** @var bool Allow users to upload files */
	var $Allow_User_Up=null;
	/** @var bool Enable Auto approve and publish for admin */
	var $Enable_Admin_Autoapp=null;
	/** @var bool Enable Auto approve and publish for registered users */
	var $Enable_User_Autoapp=null;
	/** @var bool Allow comments on files */
	var $Allow_Comments=null;
	/** @var bool Allow votes on files */
	var $Allow_Votes=null;
	/** @var bool Enable downloads directly from a list of files */
	var $Enable_List_Download=null;
	/** @var bool Show pathway through filebase */
	var $User_Remote_Files=null;
	/** @var bool Let users see containers where download not permitted */
	var $See_Containers_no_download='1';
	/** @var bool Let users see files that are not permitted to be downloaded */
	var $See_Files_no_download='1';
	/** @var bool Send mail when a file is submitted */
	var $Send_Sub_Mail=null;
	/** @var string Submit Mail Alt Add */
	var $Sub_Mail_Alt_Addr=null;
	/** @var string Submit Mail Alt Name */
	var $Sub_Mail_Alt_Name=null;
	/** @var time Timestamp for authentication */
	var $Time_Stamp;
	/** @var string Information to be displayed during download */
	var $download_text = '';

	function remositoryRepository () {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		if (true OR $type=='GLOBAL') {
			$mosConfig_live_site = $interface->getCfg('live_site');
			$sql = "SELECT * FROM #__downloads_repository WHERE id=0";
			$database->setQuery($sql);
			if (!$database->loadObject($this)) {
				$mosConfig_absolute_path = $interface->getCfg('absolute_path');
				require ($mosConfig_absolute_path.'/components/com_remository/com_remository_settings.php');
				$this->id = 0;
				foreach (get_class_vars(get_class($this)) as $k=>$v) {
					if(isset($$k)) $this->$k = $$k;
					else $this->$k = '';
				}
				$this->saveValues();
			}
		}
		foreach (get_class_vars(get_class($this)) as $k=>$v) {
			$this->$k = str_replace ('{live_site}', $mosConfig_live_site, $this->$k);
		}
	}

    function &getInstance () {
        static $instance;
        if (!is_object($instance)) $instance = new remositoryRepository();
        return $instance;
    }

	function tableName () {
		return '#__downloads_repository';
	}

	function getVarText() {
		$txt = '';
		$this->Time_Stamp = time();
		foreach (get_class_vars(get_class($this)) as $k=>$v) {
			if (substr($k,0,1) != '_') {
				if (is_numeric($this->$k)){
					$txt .= "\$$k = ".intval($this->$k).";\n";
				} elseif (strlen($k) > 0) $txt .= "\$$k = \"".addslashes( $this->$k )."\";\n";
			}
		}
		return $txt;
	}

	function saveValues () {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$this->forceBools();
		$this->id = 0;
		$sql = 'SELECT COUNT(id) FROM #__downloads_repository WHERE id = 0';
		$database->setQuery($sql);
		if ($database->loadResult()) $sql = $this->updateSQL();
		else $sql = $this->insertSQL();
		remositoryRepository::doSQL ($sql);
	}

	function searchRepository($search_text, $seek_title, $seek_desc, &$user) {
		if (!$search_text) return array();
		$sql = remositoryFile::searchFilesSQL($search_text, $seek_title, $seek_desc, $user, $this);
		return $this->doSQLget($sql,'remositoryFile');
	}

	function badExtension ($filepath) {
		$ext = $this->lastPart($filepath, '.');
		if (trim($this->ExtsOk) != '*' AND !in_array($ext,$this->getExtensionsOK())) {
			echo "<script> alert('"._ERR4."'); window.history.go(-1); </script>\n";
			return true;
		}
		return false;
	}

	function getUploadLimit () {
		return $this->maxUploads;
	}

    function canUserSubmit () {
    	return $this->userSubmit;
    }

	function getTableClasses () {
		return explode(",",$this->tabclass);
	}

	function getExtensionsOK () {
		return explode(",",strtolower($this->ExtsOk));
	}
	
	function getSelectList ($allowTop, $default, $type, $parm, &$user, $usable=false) {
		if ($allowTop) $selector[] = $this->makeOption(0,_DOWN_NO_PARENT);
		$manager =& remositoryContainerManager::getInstance();
		foreach ($manager->getCategories() as $category) $category->addSelectList('', $selector, null, $user, $usable);
		if (isset($selector)) return $this->selectList( $selector, $type, $parm, $default );
		else return '';
	}

	function &getIcons ($location) {
		
		$interface =& remositoryInterface::getInstance();

		$mosConfig_live_site = $interface->getCfg('live_site');
		$iconList='';
		$handle=@opendir($interface->getCfg('absolute_path').'/components/com_remository/images/'.$location);
		if ($handle) {
			$ss = 0;
			while (($file = readdir($handle))!==false) {
				if ($file != "." && $file != "..") {
					$iconList.="\n\t\t\t\t<a href=\"JavaScript:paste_strinL('{$file}')\" onmouseover=\"window.status='{$file}'; return true\"><img src=\"{$mosConfig_live_site}/components/com_remository/images/{$location}/{$file}\" width=\"32\" height=\"32\" alt=\"{$file}\" /></a>&nbsp;&nbsp;";
					/*
			        $ss++;
					if ($ss>=10) {
						$ss = 0;
						$iconList.="<br/>\n";
					}
					*/
				}
			}
   			closedir($handle);
			if ($iconList=='') $iconList="_DOWN_NOT_AUTH";
		}
		return $iconList;
	}
	
	function &requireCategories () {
		$manager =& remositoryContainerManager::getInstance();
		$cats = $manager->getCategories();
		if (count($cats)==0){
			echo "<script> alert('"._DOWN_NO_CAT_DEF."'); </script>\n";
			$interface =& remositoryInterface::getInstance();
			$interface->redirect( "index2.php?option=com_remository" );
		}
		return $cats;
	}
	
	function resetCounts ($chain=null) {
		$manager =& remositoryContainerManager::getInstance();
		$categories = $manager->getCategories();
		if (is_array($chain)) $this->doSQL('TRUNCATE TABLE #__downloads_structure');
		foreach ($categories as $category) $category->setFileCount($chain);
	}

	function getFiles ($search='', $limitstart=0, $limit=0) {
		$sql = remositoryFile::getFilesSQL(true, false, 0, true, 2, $search, $limitstart, $limit);
		return $this->doSQLget($sql,'remositoryFile');
	}
	
	function getFilesCount ($search) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$sql = remositoryFile::getFilesSQL(true, true, 0, true, 2, $search);
		$database->setQuery( $sql );
		return $database->loadResult();
	}

	function getTempFiles () {
		$sql = "SELECT * FROM #__downloads_temp ORDER BY id";
		return $this->doSQLget($sql,'remositoryTempFile');
	}
	
	function &getTempFileNames () {
		$tempfiles = $this->getTempFiles();
		$tempfilenames = array();
		foreach ($tempfiles as $tempfile) {
			if ($tempfile->filetempname) {
				$currfile = explode(",",$tempfile->filetempname);
				$tempfilenames[] = $currfile[0].$currfile[1];
			}
		}
		return $tempfilenames;
	}

	function checkByName ($name) {
		$f =& new remositoryFile();
		$f->getByName($name);
		if ($f->id != 0) return true;
		$f =& new remositoryTempFile();
		$f->getByName($name);
		if ($f->id != 0) return true;
		return false;
	}

	function RemositoryFunctionURL ($func=null, $idparm=null, $os=null, $orderby=null, $item=null) {

		return '<a href="'.remositoryRepository::RemositoryBasicFunctionURL($func,$idparm,$os,$orderby,$item).'">';

	}

	function RemositoryBasicFunctionURL ($func=null, $idparm=null, $os=null, $orderby=null, $item=null, $fname=null ) {

		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		
		if (isset($GLOBALS['remosef_itemids']['com_remository'])) $Itemid = $GLOBALS['remosef_itemids']['com_remository'];
		else {
			$database->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_remository'");
			$GLOBALS['remosef_itemids']['com_remository'] = $Itemid = $database->loadResult();
		}

		$url = 'index.php?option=com_remository&Itemid=';
		if ($item) $url .= $item;
		else $url .= intval($Itemid);
		if ($func) $url .= '&func='.$func;
		if ($idparm) $url .= '&id='.$idparm;
		if (!$os) $os = remositoryRepository::getParam($_REQUEST,'os',null);
		if (!in_array($os,array('win','mac','linux','all'))) $os = null;
		if ($os AND $os != 'All') $url .= '&os='.$os;
		if ($orderby) $url .= '&orderby='.$orderby;
		if ($func == 'download') $url .= '&chk='.$this->makeCheck($idparm,$func).'&no_html=1';
		elseif ($func == 'rss') $url .= '&no_html=1';
		if ($fname) $url .= '&fname='.urlencode($fname);

		$sefurl = sefRelToAbs($url);
		$ampreg = '/\&([^amp;])/';
		$sefurl = preg_replace ($ampreg, '&amp;$1', $sefurl);
		return $sefurl;

	}
	
	function wrongCheck ($chk, $id, $func) {
		
		if ($chk == $this->makeCheck($id, $func)) return false;
		return true;
	}
	
	function makeCheck ($id, $func) {
		
		$interface =& remositoryInterface::getInstance();
		return md5($this->Time_Stamp.$interface->getCfg('absolute_path').date('md').$id.$func);
	}

	function sendAdminMail ($user_full, $filetitles) {
		$interface =& remositoryInterface::getInstance();
		// Site name and live site are needed for evaluation of message text below
		// as is the user_full parameter 
		$mosConfig_live_site = $interface->getCfg('live_site');
		$mosConfig_sitename = $interface->getCfg('sitename');
		$superadmin = remositoryUser::superAdminMail();
		if ($this->Sub_Mail_Alt_Addr=='') $recipient = 'Administrator <'.$superadmin.'>';
	    else $recipient = "$this->Sub_Mail_Alt_Name <$this->Sub_Mail_Alt_Addr>";
		$subject = $interface->getCfg('sitename').':'._DOWN_MAIL_SUB;
		$message = _DOWN_MAIL_MSG_APP."\n"._DOWN_FILE_TITLE."\n". $filetitles;
		eval ("\$message = \"$message\";");
		mosMail ($superadmin, 'Administrator', $recipient, $subject, $message);
	}

	function RemositoryImageURL($imageName, $width=32, $height=32) {

		$interface =& remositoryInterface::getInstance();

		$element = '<img src="';
		$element .= $interface->getCfg('live_site').'/components/com_remository/images/'.$imageName;
		$element .= '" width="';
		$element .= $width;
		$element .= '" height="';
		$element .= $height;
		$element .= '" alt="';
		$element .= $imageName;
		$element .= '"/>';
		return $element;

	}
	
	function doSQL ($sql) {
		if ($sql) {
			$interface =& remositoryInterface::getInstance();
			$database =& $interface->getDB();
			$database->setQuery($sql);
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
	}
	
	function &doSQLget ($sql, $classname) {
		if ($sql) {
			$interface =& remositoryInterface::getInstance();
			$database =& $interface->getDB();
			$database->setQuery($sql);
			$rows = $database->loadObjectList();
		}
		else $rows = null;
		$target = get_class_vars($classname);
		if ($rows) {
			foreach ($rows as $row) {
				$next =& new $classname(0);
				foreach (get_object_vars($row) as $field=>$value) {
					if (isset($target[$field])) $next->$field = $value;
				}
				$result[] = $next;
			}
		}
		else $result = (array());
		return $result;
	}
	
	function makeOption($value, $text='', $value_name='value', $text_name='text') {
		$obj = new stdClass;
		$obj->$value_name = $value;
		$obj->$text_name = trim($text) ? $text : $value;
		return $obj;
	}
	
	function selectList (&$arr, $tag_name, $tag_attribs='', $selected=NULL, $key='value', $text='text' ) {
		$html = "\n\t\t<select name=\"$tag_name\" $tag_attribs>";
		foreach ($arr as $option) {
			$picked = ($option->$key == $selected ? "selected='selected'" : '');
			$html .= "\n\t\t\t<option value='{$option->$key}' $picked>{$option->$text}</option>";
		}
		$html .= "\n\t\t</select>\n";
		return $html;
	}

	function getParam (&$array, $name, $default='') {
		if (isset($array[$name])) {
			if (is_numeric($default)) return intval($array[$name]);
			else return $array[$name];
		}
		else return $default;
	}

}

class remositoryDirectory {
	var $path='';
	
	function remositoryDirectory ($path, $make=false) {
		if (substr($path,strlen($path)-1,1) == '/') $this->path = $path;
		else $this->path = $path.'/';
		if ($make AND !is_dir($path)) $this->makePhysical();
	}
	
	function makePhysical () {
		if (@mkdir($this->path, 0755)) return $this->setPerms();
		else return false;
	}
	
	function setPerms () {
		$interface =& remositoryInterface::getInstance();
   		$origmask = @umask(0);
		if ($interface->getCfg('dirperms')) {
	    	$mode = octdec($interface->getCfg('dirperms'));
			$result = @chmod($this->path, $mode);
		}
		else $result = @chmod($this->path,0755);
		@umask($origmask);
		return $result;
	}

	function &listFiles ($pattern='', $type='file') {
		$results = array();
	  	if ($dir = @opendir($this->path)) {
			while ($file = readdir($dir)) {
	      		if (($file == 'index.html') OR (substr($file,0,1) == '.')) continue;
	      		if (is_dir($this->path.$file)) {
				  	if ($type == 'file') continue;
				}
	      		elseif ($type == 'dir') continue;
				if ($pattern) {
					if (substr($file,0,strlen($pattern)) == $pattern) $results[] = $file;
				}
				else $results[] = $file;
	    	}
		  	closedir($dir);
  		}
  		return $results;
  	}
  	
  	function &getOrphans () {
		$manager =& remositoryContainerManager::getInstance();
  		$data = $manager->getFilePathData($this->path);
  		$orphans = array();
  		if (count($data)) {
			$containers = $data[$this->path];
			$filenames = $this->listFiles();
			$repository =& remositoryRepository::getInstance();
			foreach ($filenames as $filename) {
	      		if (($filename != 'index.html') AND (substr($filename,0,1) != '.') AND (!is_dir($this->path.$filename)) AND !$repository->checkByName($filename)) {
					$orphans[$this->path.$filename] = $containers;
				}
			}
		}
		return $orphans;
	}

  	function getSize () {
  		$totalsize = 0;
  		$files = $this->listFiles();
  		foreach ($files as $file) $totalsize += filesize($this->path.$file);
  		return $totalsize;
  	}
  	
}


class remositoryThumbnails {
	var $fileid=0;
	var $maxcount=0;
	var $filepath='';
	var $count=0;
	var $thumb_paths=array();
	var $img_paths=array();
	var $thumb_URLs=array();
	var $img_URLs=array();
	var $allfree=array();
	var $freecount = 0;
	var $allow_large=1;
	
	function remositoryThumbnails (&$file) {
		$interface =& remositoryInterface::getInstance();
		srand((double)microtime()*1000000);
		$this->fileid=$file->id;
		$repository =& remositoryRepository::getInstance();
		$this->maxcount = $repository->Max_Thumbnails;
		$this->allow_large = $repository->Allow_Large_Images;
		$this->filepath = $this->baseFilePath().$this->dirPattern().$file->id.'/';
		if ($this->maxcount) $this->findFiles();
		elseif ($file->screenurl) {
			$this->thumb_URLs[] = $file->screenurl;
			$this->img_URLs[] = $file->screenurl;
			$filepath = str_replace($interface->getCfg('live_site'), $interface->getCfg('absolute_path'), $file->screenurl);
			$this->thumb_paths[] = $filepath;
			$this->img_paths[] = $filepath;
			$this->count = 1;
		}
		else $this->count = 0;
	}
	
	function baseFilePath () {
		return '/components/com_remository_files/';
	}
	
	function dirPattern () {
		return 'file_image_';
	}
	
	function findFiles () {
		$interface =& remositoryInterface::getInstance();
		$thumb_pattern = 'th_'.$this->fileid.'_';
		$dir =& new remositoryDirectory($interface->getCfg('absolute_path').$this->filepath, true);
		$thumbfiles = $dir->listFiles($thumb_pattern);
		$this->count = 0;
		foreach ($thumbfiles as $i=>$thumb) {
			$numberandext = remositoryAbstract::lastPart($thumb,'_');
			$k = intval(remositoryAbstract::allButLast($numberandext,'.'));
			$marker[$k] = 'X';
			$thumbpath = $this->filepath.$thumb;
			$this->thumb_paths[$i] = $interface->getCfg('absolute_path').$thumbpath;
			$this->thumb_URLs[$i] = $interface->getCfg('live_site').$thumbpath;
			$imagefile = str_replace('th_','img_',$thumbpath);
			if (file_exists($interface->getCfg('absolute_path').$imagefile)) {
				$this->img_paths[$i] = $interface->getCfg('absolute_path').$imagefile;
				$this->img_URLs[$i] = $interface->getCfg('live_site').$imagefile;
			}
			else {
				$this->img_paths[$i] = $this->thumb_paths[$i];
				$this->img_URLs[$i] = $this->thumb_URLs[$i];
			}
			$this->count = $i+1;
		}
		$freecount = 0;
		unset($this->allfree);
		for ($i=1; $i<=$this->maxcount AND ($this->count + $freecount) < $this->maxcount; $i++) {
			if (!isset($marker[$i])) {
				$this->allfree[] = substr($i+100,1);
				$freecount++;
			}
		}
		$this->freecount = $freecount;
	}
	
	function getNextFree () {
		return array_shift($this->allfree);
	}
	
	function addImage (&$physicalFile, $fileid) {
		$interface =& remositoryInterface::getInstance();
		if ($nextfree = $this->getNextFree()) {
			$filepath = $interface->getCfg('absolute_path').$this->filepath;
			$file_to_fix = $filepath.time().$physicalFile->proper_name;
			$physicalFile->fileToFile($file_to_fix);
			$file_ext = remositoryAbstract::lastPart($file_to_fix,'.');
			if ($file_ext != 'png' AND $file_ext != 'jpg' AND $file_ext != 'jpeg' AND $file_ext != 'gif') echo _DOWN_THUMB_WRONG_TYPE;
			elseif (file_exists($file_to_fix)) {
				$large_image = $filepath.'img_'.$fileid.'_'.$nextfree.'.'.$file_ext;
				$small_image = $filepath.'th_'.$fileid.'_'.$nextfree.'.'.$file_ext;
				$repository =& remositoryRepository::getInstance();
				$this->imgresize($file_to_fix, $large_image, $repository->Large_Image_Width, $repository->Large_Image_Height);
				$physicalimage =& new remositoryPhysicalFile();
				$physicalimage->setData($large_image);
				$physicalimage->setPerms();
				$this->imgresize($file_to_fix, $small_image, $repository->Small_Image_Width, $repository->Small_Image_Height);
				$physicalimage->setData($small_image);
				$physicalimage->setPerms();
				unlink ($file_to_fix);
			}
			else echo '<h3>'._ERR1.'</h3>';
		}
	}

	function displayThumbnail ($item, $link=true) {
		if ($this->count == 0) return '&nbsp;';
		$html = '';
		if ($link) {
		    if ($this->allow_large) {
		    	$alt_text = 'View Full Sized Screenshot';
		    	if ($this->maxcount) {
					$imginfo = getimagesize($this->img_paths[$item]);
					$imgw = $imginfo[0]+20;
					$imgh = $imginfo[1]+20;
		    	}
		    	else {
					$repository =& remositoryRepository::getInstance();
					$thw = $repository->Large_Image_Width;
					$thh = $repository->Large_Image_Height;
		    	}
				$imglink = $this->img_URLs[$item];
				//get the x and y size of the full sized image
				$html .= "\n\t\t\t\t<a href=\"javascript:void(0)\" onclick=\"window.open('$imglink','FullSize','width=$imgw,height=$imgh,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,copyhistory=0')\">";
			}
			else $alt_text = 'Full screenshots disabled';
		}
		else $alt_text = 'Thumbnail image';
		if ($this->maxcount) {
			$thinfo = getimagesize($this->thumb_paths[$item]);
			$thw = $thinfo[0];
			$thh = $thinfo[1];
		}
		else {
			$repository =& remositoryRepository::getInstance();
			$thw = $repository->Small_Image_Width;
			$thh = $repository->Small_Image_Height;
		}
		$thlink = $this->thumb_URLs[$item];
		$html .= "\n\t\t\t\t<img width=\"$thw\" height=\"$thh\" alt=\"$alt_text\" src='$thlink' />";
		//create a thumbnail image linked to full size image
		if ($link AND $this->allow_large) $html .= '</a>';
		return $html;
	}
	
	function displayOneThumbnail () {
		if ($this->count == 0) return '';
		if ($this->count > 1) $item = rand(0,$this->count-1);
		else $item = 0;
		if (isset($this->thumb_URLs[$item])) return $this->displayThumbnail($item);
		else {
			$repository =& remositoryRepository::getInstance();
			return $repository->RemositoryImageURL('blank.gif', $repository->Small_Image_Width, $repository->Small_Image_Height);
		}
	}
	
	function displayAllThumbnails () {
		if ($this->count == 0) return '';
		$html = "\n\t\t\t<div class='remositorythumbset'>";
		for ($item=0; $item<$this->count; $item++) {
			$html .= $this->displayThumbnail($item);
//			if (($item+1) % _THUMBNAILS_PER_COLUMN == 0) echo '</tr><tr><td>&nbsp;</td></tr><tr>';
		}
		$html .= "\n\t\t\t<!-- End of remositorythumbset -->";
		$html .= "\n\t\t\t</div>";
		return $html;
	}
	
	function displayAllThumbnailsDeletable () {
		$html = '';
		for ($item=0; $item<$this->count; $item++) {
			$html .= "\n\t\t\t<div class='remositorydelthumb'>";
			$html .= $this->displayThumbnail($item, false);
			$html .= "\n\t\t\t\t<p><input class='button' type='submit' name='delete_thumb_".$item."' value='"._DOWN_DELETE_THUMBNAIL."' /></p>";
			$html .= "\n\t\t\t</div>";
		}
		return $html;
	}
	
	function deleteThumbFile ($item) {
		@chmod($this->thumb_paths[$item], 0646);
		unlink($this->thumb_paths[$item]);
		@chmod($this->img_paths[$item], 0646);
		unlink($this->img_paths[$item]);
	}
	
		//This function will resize any PNG or JPG image to whatever size you specify
	//It will keep aspect ratios.
	//usage imgresize(/path/to/sourcefile.png,$destfilename,150,150);
	function imgresize($origfile,$newfile,$new_w,$new_h)
	{
		//determine starting type and create blank
		//you could also add gif and bmp in here
		$type=remositoryAbstract::lastPart($origfile,'.');
		if ($type=="jpg" || $type=="jpeg") $src_img=imagecreatefromjpeg($origfile);
		if ($type=="png") $src_img=imagecreatefrompng($origfile);
        if ($type=="gif") $src_img=imagecreatefromgif($origfile);

		//grab original sizes
		$old_x=imagesx($src_img);
		$old_y=imagesy($src_img);

		//math to figure aspect ratio
		/*
		if ($old_x > $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_h/$old_x);
		}
		if ($old_x < $old_y) {
			$thumb_w=$old_x*($new_w/$old_y);
			$thumb_h=$new_h;
		}
		if ($old_x == $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$new_h;
		}
		*/
		// new math to figure aspect ratio
		$ratio = min($new_w/$old_x, $new_h/$old_y);
		$thumb_h = $old_y * $ratio;
		$thumb_w = $old_x * $ratio;
		
		//generate a blank final image
		$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);

		//this resamples the original image and I think uses bicub to create a new image
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

		//unlink it if it already exists in destination
		if(file_exists($newfile)) {
			chmod($newfile, 0646);
			unlink($newfile);
		}

		//create the final file
		if ($type=="png") imagepng($dst_img,$newfile);
		elseif ($type=="gif") imagegif($dst_img,$newfile);
		else imagejpeg($dst_img,$newfile);

		//free up memory
		imagedestroy($dst_img);
		imagedestroy($src_img);
	}

}

class remositoryComment extends remositoryAbstract {

	var $component='com_remository';
	var $itemid=0;
	var $userid=0;
	var $name='';
	var $username='';
	var $userURL='';
	var $title='';
	var $comment='';
	var $fullreview='';
	var $date=null;
	
	function remositoryComment ($userid, $name, $username, $title, $comment, $date=null) {
		$this->userid = $userid;
		$this->name = $name;
		$this->username = $username;
		$this->title = $title;
		$this->comment = $comment;
		$this->date = $date;
	}
	
	function saveComment (&$file) {
		$interface =& remositoryInterface::getInstance();
		if ($this->date == null) $this->date = date('Y-m-d H:i:s');
		$comment = $interface->getEscaped($this->comment);
		$title = $interface->getEscaped($this->title);
		$sql="INSERT INTO #__downloads_reviews (component, itemid, userid, title, comment, date) VALUES ('$this->component', $file->id, $this->userid, '$title', '$comment', '$this->date')";
		remositoryRepository::doSQL($sql);
	}
	
	function &getComments (&$file) {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$sql = "SELECT c.title, c.comment, c.date, u.id as userid, u.name, u.username FROM #__downloads_reviews AS c, #__users AS u WHERE c.userid=u.id AND c.itemid=$file->id";
		$database->setQuery($sql);
		$rows = $database->loadObjectList();
		$comments = array();
		if ($rows) {
			foreach ($rows as $row) {
				$comments[] =& new remositoryComment ($row->userid, $row->name, $row->username, $row->title, $row->comment, $row->date);
			}
		}
		return $comments;
	}

	function deleteComments ($fileid) {
		$sql = "DELETE FROM #__downloads_reviews WHERE component='com_remository' AND itemid=$fileid";
		remositoryRepository::doSQL($sql);
	}

}

class remositoryLogEntry {
	var $id=0;
	var $type=0;
	var $date='';
	var $userid=0;
	var $fileid=0;
	var $value=0;
	var $ipaddress='';

	function remositoryLogEntry ($type, $userid, $fileid, $value) {
		$this->type = $type;
		$this->userid = $userid;
		$this->fileid = $fileid;
		$this->value = $value;
		$this->date = date('Y-m-d H:i:s');
		$this->ipaddress = getenv('REMOTE_ADDR');
	}

	function insertEntry () {
		$sql = "INSERT INTO #__downloads_log (type, date, userid, fileid, value, ipaddress) VALUES ('$this->type', '$this->date', '$this->userid', '$this->fileid', '$this->value', '$this->ipaddress')";
		remositoryRepository::doSQL($sql);
	}
	
	function deleteEntries ($fileid) {
		$sql = "DELETE FROM #__downloads_log WHERE fileid=$fileid";
		remositoryRepository::doSQL($sql);
	}
}


?>