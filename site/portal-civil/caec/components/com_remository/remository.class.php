<?php
/**
* @version $Id: remository.class.php,v 2.50 2005/05/02 14:45:11 akede Exp $
* @package Remository 3.24
* @copyright (C) 2005 Martin Brampton
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Remository is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* Remository classes
*
* @package Remository 3.22
*/

require_once ($mosConfig_absolute_path.'/components/com_remository/com_remository_constants.php');

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
		return;
	}

	function updateObjectDB () {
		$this->prepareValues();
		remositoryRepository::doSQL($this->updateSQL());
	}
	
	function timeStampField () {
		return '';
	}

	function updateSQL () {
		$tabname = $this->tableName();
		$sql = "UPDATE $tabname SET ";
		$started = false;
		$exclude = $this->notSQL();
		foreach (get_class_vars(get_class($this)) as $field=>$value) {
			if (!in_array($field,$exclude)) {
				if ($started) $sql .= ', ';
				else $started = true;
				$sql .= $field."='".$this->$field."'";
			}
		}
		$timestamp = $this->timeStampField();
		if ($timestamp) {
			if ($started) $sql .= ', ';
			$sql .= $timestamp."='".date('Y-m-d H:i:s')."'";
		}
		return $sql.' WHERE id='.$this->id;
	}

	function notSQL () {
		return array ('id');
	}

	function insertSQL () {
		$tabname = $this->tableName();
		$sql = "INSERT INTO $tabname (";
		$values = ') VALUES (';
		$started = false;
		$exclude = $this->notSQL();
		foreach (get_class_vars(get_class($this)) as $field=>$value) {
			if (!in_array($field,$exclude)) {
				if ($started) {
					$sql .= ', ';
					$values .= ', ';
				}
				else $started = true;
				$sql .= $field;
				$values .= "'".$this->$field."'";
			}
		}
		$timestamp = $this->timeStampField();
		if ($timestamp) {
			if ($started) {
				$sql .= ', ';
				$values .= ', ';
			}
			$sql .= $timestamp;
			$values .= "'".date('Y-m-d H:i:s')."'";
		}
		return $sql.$values.')';
	}

	function setValues (&$anObject) {
		foreach (get_class_vars(get_class($this)) as $field=>$value) {
			if ($field != 'id' AND isset($anObject->$field)) $this->$field = $anObject->$field;
		}
	}

	function prepareValues () {
		global $database;
		foreach (get_class_vars(get_class($this)) as $field=>$value) {
			if (!is_numeric($this->$field)) $this->$field = $database->getEscaped($this->$field);
		}
	}

	function readDataBase($sql) {
		global $database;
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
			$sql .= ' AND published=1';
			if (!$see_objects) {
				$grouplist = remositoryGroup::getMembersGroupList ($user);
				if (strlen($grouplist)) $sql .= " AND ((registered & 2) OR ((userupload & 2) AND groupid IN ($grouplist)))";
				else $sql .= ' AND (registered & 2)';
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
	var $licenseagree=false;
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
	/** @var int Group of users that has access to this file */
	var $groupid=0;
	/** @var int The ID of the user who submitted this file */
	var $submittedby=0;
	/** @var date Date on which the file was submitted */
	var $submitdate='';
	/** @var object Votes for this file */
	var $votes=0;

	/**
	* File object constructor
	* @param int File ID from database or null
	*/
	function remositoryFile ( $id=0 ) {
		global $Default_Version;
		$this->id = $id;
		$this->fileversion = $Default_Version;
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
		return array ('id','votes','submitdate');
	}
	
	function tableName () {
		return '#__downloads_files';
	}

	function timeStampField () {
		return 'submitdate';
	}

	function insertFileDB () {
		global $database;
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
			$this->prepareValues();
			remositoryRepository::doSQL($this->insertSQL());
			$this->id = $database->insertid();
			if ($this->published) $this->incrementCounts('+1');
		}
	}

	function saveFile () {
		if ($this->id == 0) $this->insertFileDB();
		else $this->updateObjectDB();
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

	function filePath (&$repository) {
		if ($this->filepath) $path = $this->filepath;
		else $path = $repository->Down_Path.'/';
		return $path.$this->realname;
	}

	function deleteFile (&$repository) {
		if ($this->islocal) {
			unlink($this->filePath($repository));
		}
		$this->deleteFileDB();
	}

	function checkLicenseagree () {
		if ($this->licenseagree AND $this->license != '') $this->licenseagree = 1;
		else $this->licenseagree = 0;
	}

	function getValues (&$user) {
		$sql = "SELECT * FROM #__downloads_files WHERE id = $this->id";
		if (!$user->isAdmin()) $sql .= " AND published=1";
		$this->readDataBase($sql);
	}
	
	function getByName ($name) {
		$sql = "SELECT * FROM #__downloads_files WHERE islocal=1 AND realname='$name'";
		$this->readDataBase($sql);
	}

	function setVotes () {
		global $database;
		$this->votes=new remositoryVote(_REM_VOTE_USER_GENERAL,$this->id);
		$this->votes->getVote();
	}
	
	function evaluateVote () {
		if ($this->votes == 0) $this->setVotes();
		$val = $this->votes->evaluation;
		return round($val);
	}

	function addVote (&$user, $vote) {
		if ($this->votes == 0) $this->setVotes();
		return $this->votes->addVote($user, $vote);
	}

	function userVoted (&$user) {
		if ($this->votes == 0) $this->setVotes();
		return $this->votes->userVoted($user);
	}

	function userCommented (&$user) {

		global $database;

		$sql = "SELECT count(id) FROM #__downloads_reviews WHERE itemid = $this->id AND userid = $user->id";
		$database->setQuery ($sql);
		if ($database->loadResult() == 0) return false;
		echo '<table><tr><td><b>'._DOWN_ALREADY_COMM.'</b></td></tr></table>';
		return true;
	}

	function getComments () {
		return remositoryComment::getComments($this);
	}

	function getContainer () {
		if ($this->containerid) return new remositoryContainer ($this->containerid);
		else return null;
	}
	
	function memoContainer (&$container) {
		$this->registered = $container->registered;
		$this->userupload = $container->userupload;
		$this->filepath = $container->filepath;
		$this->groupid = $container->groupid;
		$this->plaintext = $container->plaintext;
	}

    function getFamilyNames () {
    	$parent = $this->getContainer();
    	if ($parent AND $parent->parentid) {
    		$names = '/'.$parent->name;
    		$grandparent = $parent->getParent();
    		if ($grandparent AND $grandparent->parentid) {
    			$names = '/'.$grandparent->name.$names;
				$greatgrandparent = $grandparent->getParent();
				if ($greatgrandparent->parentid) return '..'.$names;
			}
    		return $names;
    	}
    	return '-';
    }
    
	function incrementCounts ($by) {
		$container = $this->getContainer();
		while ($container != null) {
			$container->increment($by);
			$container=$container->getParent();
		}
	}

	function downloadForbidden (&$user) {
		if ($user->isAdmin() OR ($this->registered & 2)) return false;
		if ($user->isLogged()) {
			if (($this->userupload & 2) AND remositoryGroup::isUserMember($this->groupid,$user)) return false;
			else {
				echo '<br/>&nbsp;<br/> '._DOWN_MEMBER_ONLY_WARN.remositoryGroup::getName($this->groupid);
				return true;
			}
		}
		echo '<br/>&nbsp;<br/> '._DOWN_REG_ONLY_WARN;
		return true;
	}

	function getExtension () {
		if ($this->islocal) return $this->lastPart($this->realname, '.');
		else return $this->lastPart($this->url, '.');
	}
	
	function checkExtensionOK (&$repository) {
		if ($this->islocal) {
			$ext = $this->getExtension();
			if (!in_array($ext,$repository->getExtensionsOK())) {
				echo "<script> alert('"._ERR4."'); window.history.go(-1); </script>\n";
				exit();
			}
			else $this->filetype = $ext;
		}
	}

	function makeAutoShort (&$repository) {
		if ($this->autoshort) {
			$this->autoshort = 1;
			$max = $repository->Small_Text_Len-3;
			$plain = strip_tags($this->description);
			if (strlen($plain) > $max) $this->smalldesc=substr($plain,0,$max).'...';
			else $this->smalldesc = $plain;
		}
		else $this->autoshort = 0;
	}

	function isFieldHTML ($field) {
		return in_array($field, array('description', 'smalldesc', 'license'));
	}

	function fieldSizeLimit ($field, &$repository) {
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
	
	function resetDownloadCounts () {
		remositoryRepository::doSQL('UPDATE #__downloads_files SET downloads=0');
	}
	
	function getCountInContainer ($id, $published, $search='') {
		global $database;
		$sql = "SELECT COUNT(id) FROM #__downloads_files WHERE containerid = $id";
		if ($published) $sql .= ' AND published=1';
		if ($search) $sql .= " AND LOWER(filetitle) LIKE '%$search%'";
		$database->setQuery($sql);
		return $database->loadResult();
	}
	
	function searchFilesSQL($search_text, $seek_title, $seek_desc, $seek_author, &$user, &$repository) {
		global $database;
		$search_text = $database->getEscaped($search_text);
		$sql="SELECT id,containerid,filetitle,description,icon,filesize,downloads FROM #__downloads_files";
		if ($seek_desc) $where[] = "description LIKE '%$search_text%'";
		if ($seek_title) $where[] = "filetitle LIKE '%$search_text%'";
		if ($seek_author) $where[] = "fileauthor LIKE '%$search_text%'";
		$visibility = remositoryAbstract::visibilitySQL ($user, $repository->See_Files_no_download);
		if ($visibility OR isset($where)) $sql .= ' WHERE ';
		if (isset($where)) $sql .= '('.implode(' OR ', $where).')';
		else $sql .= 'id>0';
		if ($visibility) $sql .= $visibility;
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

	function getByName ($name) {
		$sql = "SELECT * FROM #__downloads_temp WHERE islocal=1 AND realname=$name";
		$this->readDataBase($sql);
	}

	function filePath (&$repository) {
		if ($this->filetempname) {
			$temp_arr=explode(",",$this->filetempname);
			return $repository->Up_Path.'/'.$temp_arr[0].$temp_arr[1];
		}
		else return parent::filePath($repository);
	}
	
	function tableName () {
		return '#__downloads_temp';
	}

	function deleteFileDB () {
		$sql = "DELETE FROM #__downloads_temp WHERE id=$this->id";
		remositoryRepository::doSQL($sql);
	}

	function inDataBase () {

		global $database;

		if ($this->filetempname) {
			$sql="SELECT COUNT(*) FROM #__downloads_temp WHERE filetempname = $this->filetempname";
			$database->setQuery($sql);
			if ($database->loadResult() != 0) return true;
		}
		return false;
	}

	function saveFile () {
		$this->prepareValues();
		if ($this->inDataBase()) $sql = $this->updateSQL();
		else $sql = $this->insertSQL();
		remositoryRepository::doSQL($sql);
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

	/**
	* File object constructor
	* @param int Container ID from database or null
	*/
	function remositoryContainer ( $id=0 ) {
		global $database;
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
		global $database;
		$sql = "DELETE FROM #__downloads_containers WHERE id=$this->id";
		remositoryRepository::doSQL($sql);
	}
	
	function deleteAll (&$repository) {
		$folders = $this->getChildren(false);
		foreach ($folders as $folder) $folder->deleteAll ($repository);
		$files = $this->getFiles(true);
		foreach ($files as $file) $file->deleteFile($repository);
		$this->delete();
	}

	function saveValues () {
		$this->forceBools();
		$this->prepareValues();
		if ($this->id == 0) $sql = $this->insertSQL();
		else $sql = $this->updateSQL();
		remositoryRepository::doSQL ($sql);
	}

	function isCategory () {
		if ($this->parentid == 0) return true;
		else return false;
	}
	
	function getCategoryName () {
		global $database;
	    $sql = "SELECT c.name FROM #__downloads_structure AS s, #__downloads_containers AS c WHERE item=$this->id AND s.container=c.id AND c.parentid=0";
	    $database->setQuery($sql);
	    $name = $database->loadResult();
	    if ($name) return $name;
	    else return '-';
    }
    
    function getFamilyNames () {
    	$parent = $this->getParent();
    	if ($parent AND $parent->parentid) {
    		$names = '/'.$parent->name;
    		$grandparent = $parent->getParent();
    		if ($grandparent AND $grandparent->parentid) {
    			$names = '/'.$grandparent->name.$names;
				$greatgrandparent = $grandparent->getParent();
				if ($greatgrandparent->parentid) return '..'.$names;
			}
    		return $names;
    	}
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
	
	function getChildren ($published=true, $search='') {
		$sql = "SELECT * FROM #__downloads_containers WHERE parentid = $this->id";
		if ($published) $sql .= ' AND published=1';
		if ($search) $sql .= " AND LOWER(name) LIKE '%$search%'";
		$sql .= ' ORDER BY name';
		return remositoryRepository::doSQLget($sql, 'remositoryContainer');
	}
	
	function isDownloadable (&$user) {
		if ($this->registered & 2) return true;
		if ($user->isLogged() AND ($this->userupload & 2) AND remositoryGroup::isUserMember($this->groupid,$user)) return true;
		return false;
	}

	function getVisibleChildren (&$user, &$repository) {
		$sql = "SELECT * FROM #__downloads_containers WHERE parentid = $this->id";
		$sql .= remositoryAbstract::visibilitySQL($user, $repository->See_Containers_no_download);
		$sql .= ' ORDER BY name';
		return remositoryRepository::doSQLget($sql, 'remositoryContainer');
	}
	
	function getParent () {
		
		if ($this->parentid != 0) {
			$parent = new remositoryContainer($this->parentid);
			return $parent;
		}
		else return null;
	}
	
	function increment ($by='0') {
		$parent = $this->getParent();
		if ($parent != null) $parent->increment($by);
		$this->filecount = $this->filecount+$by;
		$sql="UPDATE #__downloads_containers SET filecount=$this->filecount WHERE id=$this->id";
		remositoryRepository::doSQL($sql);
	}
	
	function areFilesVisible (&$user, &$repository) {
		if ($repository->See_Files_no_download OR $user->isAdmin()) return true;
		return $this->isDownloadable($user);
	}


	function getFiles ($published, $orderby=2, $search='', $limitstart=0, $limit=0) {
		if ($this->id == 0) return array();
		$sorter[1] = ' ORDER BY id';
		$sorter[2] = ' ORDER BY filetitle';
		$sorter[3] = ' ORDER BY downloads DESC';
		$sorter[4] = ' ORDER BY submitdate DESC';
		if (!isset($sorter[$orderby])) $orderby = 2;
		$sql = "SELECT * FROM #__downloads_files WHERE containerid = $this->id";
		if ($published) $sql .= ' AND published=1';
		if ($search) $sql .= " AND LOWER(filetitle) LIKE '%$search%'";
		$sql .= $sorter[$orderby];
		if ($limit) $sql .= " LIMIT $limitstart,$limit";
		return remositoryRepository::doSQLget($sql, 'remositoryFile');
	}
	
	function getFilesCount ($search='', $remUser=null) {
		if ($this->id == 0) return 0;
		if ($remUser AND !$remUser->isAdmin()) $published = true;
		else $published = false;
		return remositoryFile::getCountInContainer($this->id,$published,$search);
	}
	
	function setFileCount ($chain=null) {
		$this->filecount = 0;
		$this->foldercount = 0;
		if (is_array($chain)) {
			$sql = "DELETE FROM #__downloads_structure WHERE item=$this->id";
			remositoryRepository::doSQL($sql);
			$chain[] = $this->id;
			foreach ($chain as $i=>$containerid) {
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
		if ($this->id == 0) return array();
		$sql = "SELECT * FROM #__downloads_temp WHERE containerid = $this->id";
		if ($search) $sql .= " AND LOWER(filetitle) LIKE '%$search%'";
		return remositoryRepository::doSQLget($sql,'remositoryTempFile');
	}
	
	function addSelectList ($prefix, &$selector, &$notThis, &$user) {
		$addthis = false;
		if ($user->isAdmin()) {
			$published = false;
			$addthis = true;
		}
		else {
			$published = false;
			if ($user->isLogged()) {
				if (($this->userupload & 1) AND remositoryGroup::isUserMember($this->groupid,$user)) $addthis = true;
			}
			elseif ($this->registered & 1) $addthis = true;
		}
		if ($addthis AND (($notThis == null) OR ($this->id != $notThis->id))) $selector[] = mosHTML::makeOption($this->id, $prefix.htmlspecialchars($this->name));
		foreach ($this->getChildren($published) as $container) $container->addSelectList($prefix.$this->name.'/',$selector,$notThis,$user);
	}
	
	function getURL () {
		return remositoryRepository::remositoryFunctionURL('select', $this->id);
	}
	
	function showPathway () {
		global $mosConfig_live_site;
		$parent = $this->getParent();
		if ($parent != null) $parent->showPathway();
		?>
		<img src="<?php echo $mosConfig_live_site ?>/images/M_images/arrow.png" alt="arrow" />
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
	
	function getFilePaths () {
		global $database;
		$sql = "SELECT DISTINCT filepath FROM #__downloads_containers WHERE filepath!=''";
		$database->setQuery($sql);
		return $database->loadResultArray();
	}
	
	function getContainerCount () {
		global $database;
		$database->setQuery('SELECT COUNT(*) FROM #__downloads_containers WHERE parentid=0');
		return $database->loadResult();
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
		global $database;
		$this->id = $id;
		if ($id) {
			if (!$my) {
				$my = new mosUser($database);
				$my->load($id);
			}
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
		$this->currIP = $_SERVER['REMOTE_ADDR'];
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
		
		global $database;
		
		$today = date('Y-m-d');
		$sql="SELECT COUNT(*) from #__downloads_temp WHERE submittedby=".$this->id." AND submitdate LIKE '".$today."%'";
		$database->setQuery($sql);
		return $database->loadResult();
	}
	
	function allowUploadCheck (&$repository) {
		if ($this->isAdmin()) return;
		if (!$repository->Allow_User_Sub) {
			echo "<script> alert('"._DOWN_NOT_AUTH."'); window.history.go(-1); </script>\n";
			exit();
		}
		if ($this->logged AND $this->uploadsToday() > $repository->Max_Up_Per_Day) {
			?><SCRIPT>alert("<?php echo _ERR9; ?>")</SCRIPT><?php
			HTML_downloads::filesHeaderHTML( null );
			?><br/>&nbsp;<br/><?php echo _DOWN_ALL_DONE;
			exit();
		}
	}

	function superAdminMail () {
		global $database;
		$sql="select email, name from #__users where usertype='superadministrator' or usertype='super administrator'";
		$database->setQuery( $sql );
		$row=null;
		$database->loadObject( $row );
		if ($row) return $row->name.' <'.$row->email.'>';
		else return null;
	}

}


class remositoryGroup {
	
	function isUserMember($groupid,&$user) {
		global $database;
		if ($groupid == 0) return true;
		$sql = "SELECT COUNT(*) FROM #__mbt_group_member WHERE group_id = $groupid AND member_id=$user->id";
		$database->setQuery($sql);
		return $database->loadResult();
	}
	
	function getName($groupid) {
		global $database;
		if ($groupid) {
			$sql = "SELECT group_name FROM #__mbt_group WHERE group_id=$groupid";
			$database->setQuery($sql);
			return $database->loadResult();
		}
		else return _DOWN_ALL_REGISTERED;
	}
	
	function getMembersGroupList ($user) {
		global $database;
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
		global $database;
		$tablename = '#__mbt%';
		$database->setQuery($tablename);
		$tablename = $database->_sql;
		$sql = "SHOW TABLES LIKE '$tablename'";
		$database->setQuery($sql);
		$tables = $database->loadObjectList();
		if ($tables AND count($tables)) return true;
		else return false;
	}

	function getGroups ($names=true) {
	    global $database;
		if (remositoryGroup::isInstalled()) {
			$sql = "SELECT group_id, group_name FROM `#__mbt_group`";
			$database->setQuery($sql);
			$grouplist = $database->loadObjectList();
			if ($grouplist) return $grouplist;
		}
		return array();
	}

	function getGroupSelector($selGroup = 0){
		global $database;
		$selector[] = mosHTML::makeOption(0, 'All Registered users');
		$grouplist = remositoryGroup::getGroups();
		foreach ($grouplist as $group){
			$selector[] = mosHTML::makeOption($group->group_id, $group->group_name);
		}
		return mosHTML::selectList( $selector, 'groupid', 'class="inputbox"', 'value', 'text', $selGroup );
	}

}


class remositoryRepository extends remositoryAbstract {

	/** @var string Remository version number */
	var $version='';
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
	/** @var int Maximum file size in Kbytes */
	var $MaxSize=null;
	/** @var int Maximum uploads per user per day */
	var $Max_Up_Per_Day=null;
	/** @var int Maximum space allowed for files directory */
	var $Max_Up_Dir_Space=null;
	/** @var int Number of favourites to be marked by a registered user */
	var $Favourites_Max=null;
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

	function remositoryRepository ($type=null) {
		$this->id = 0;
		if ($type=='GLOBAL') {
			foreach (get_class_vars(get_class($this)) as $k=>$v) {
				if(isset($GLOBALS[$k])) {
					$this->$k = $GLOBALS[$k];
				} else {
					$this->$k = "";
				}
			}
		}
	}

	function tableName () {
		return '#__downloads_repository';
	}

	function getVarText() {
		$txt = '';
		$this->Time_Stamp = time();
		foreach (get_class_vars(get_class($this)) as $k=>$v) {
			if (substr($k,0,1) != '_') {
				if (!get_magic_quotes_gpc()) {
					if (is_numeric($this->$k)){
						$txt .= "\$$k = ".intval($this->$k).";\n";
					} else {
						if (strlen($k) > 0) $txt .= "\$$k = \"".addslashes( $this->$k )."\";\n";
					}
				} else {
					if (is_numeric($this->$k)){
						$txt .= "\$$k = ".intval($this->$k).";\n";
					} else {
						if (strlen($k) > 0) $txt .= "\$$k = \"".$this->$k."\";\n";
					}
				}
			}
		}
		return $txt;
	}

	function saveValues () {
		global $database;
		$this->prepareValues();
		$this->forceBools();
		$sql = 'SELECT COUNT(id) FROM #__downloads_repository';
		$database->setQuery($sql);
		if ($database->loadResult()) $sql = $this->updateSQL();
		else $sql = $this->insertSQL();
		remositoryRepository::doSQL ($sql);
	}

	function searchRepository($search_text, $seek_title, $seek_desc, $seek_author, &$user, &$repository) {
		$sql = remositoryFile::searchFilesSQL($search_text, $seek_title, $seek_desc, $seek_author, $user, $repository);
		return $this->doSQLget($sql,'remositoryFile');
	}


	function checkBeforeUpload (&$user) {
		$submit_text = null;
		if ($user->isAdmin) {if (!file_exists($this->Down_Path.'/')) $submit_text = _SUBMIT_NO_DDIR;}
		elseif ($user->isUser) {if (!file_exists($this->Up_Path.'/')) $submit_text = _SUBMIT_NO_UDDIR;}
	}

	function checkSpace () {
		 $Curr_Up_Dir_Space=(dirsize($this->Up_Path))/1024;
         $up_dir_space_avail=$this->maxDirectory-$Curr_Up_Dir_Space-$this->maxSize;
         if ($up_dir_space_avail<=0) return _SUBMIT_FILE_NOSPACE;
         else return null;
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
	
	function getSelectList ($allowAll, $default, $type, $parm, &$user, $notThis=null) {
		if ($allowAll) {
			$selector[] = mosHTML::makeOption( '0', _DOWN_SEL_LOC_PROMPT );
			$selector[] = mosHTML::makeOption( '0', _DOWN_ALL_LOC_PROMPT );
		}
		foreach ($this->getCategories() as $category) $category->addSelectList('',$selector,$notThis,$user);
		if (isset($selector)) return mosHTML::selectList( $selector, $type, $parm, 'value', 'text', $default );
		else return '';
	}

	function getIcons ($location) {
		
		global $mosConfig_absolute_path, $mosConfig_live_site;

		$iconList='';
		$handle=@opendir($mosConfig_absolute_path.'/components/com_remository/images/'.$location);
		if ($handle) {
			$ss = 0;
			while (($file = readdir($handle))!==false) {
				if ($file != "." && $file != "..") {
					$iconList.="<a href=\"JavaScript:paste_strinL('{$file}')\" onmouseover=\"window.status='{$file}'; return true\"><img src=\"{$mosConfig_live_site}/components/com_remository/images/{$location}/{$file}\" width=\"16\" height=\"16\" border=\"0\" alt=\"{$file}\" /></a>&nbsp;&nbsp;";
			        $ss++;
					if ($ss>=10) {
						$ss = 0;
						$iconList.="<br/>\n";
					}
				}
			}
   			closedir($handle);
			if ($iconList=='') $iconList="_DOWN_NOT_AUTH";
		}
		return $iconList;
	}
	
	function getFolders () {
		$sql = "SELECT * from #__downloads_containers WHERE parentid!=0 ORDER BY name";
		return remositoryRepository::doSQLget($sql, 'remositoryContainer');
	}

	function getCategories ($published = false) {
		if ($published) $ifpub = ' AND published=1';
		else $ifpub = '';
		$sql = "SELECT * from #__downloads_containers WHERE parentid=0$ifpub ORDER BY name";
		return remositoryRepository::doSQLget($sql, 'remositoryContainer');
	}
	
	function requireCategories () {
		$cats = $this->getCategories();
		if (count($cats)==0){
			echo "<script> alert('"._DOWN_NO_CAT_DEF."'); </script>\n";
			mosRedirect( "index2.php?option=com_remository" );
		}
		return $cats;
	}
	
	function resetCounts ($chain=null) {
		$categories = $this->getCategories();
		if (is_array($chain)) $this->doSQL('TRUNCATE TABLE #__downloads_structure');
		foreach ($categories as $category) $category->setFileCount($chain);
	}

	function getFiles ($search='', $limitstart=0, $limit=0) {
		$sql = "SELECT * FROM #__downloads_files";
		if ($search) $sql .= " WHERE LOWER(filetitle) LIKE '%$search%'";
		$sql .= " ORDER BY filetitle";
		if ($limit) $sql .= " LIMIT $limitstart,$limit";
		return $this->doSQLget($sql,'remositoryFile');
	}
	
	function getFilesCount ($search) {
		global $database;
		$sql = 'SELECT count(id) FROM #__downloads_files';
		if ($search) $sql .= " WHERE LOWER(filetitle) LIKE '%$search%'";
		$database->setQuery( $sql );
		return $database->loadResult();
	}

	function getTempFiles () {
		$sql = "SELECT * FROM #__downloads_temp ORDER BY id";
		return $this->doSQLget($sql,'remositoryTempFile');
	}
	
	function checkByName ($name) {
		$f = new remositoryFile();
		$f->getByName($name);
		if ($f->id != 0) return true;
		$f = new remositoryTempFile();
		$f->getByName($name);
		if ($f->id != 0) return true;
		return false;
	}

	function RemositoryFunctionURL ($func=null, $idparm=null, $os=null, $orderby=null, $item=null) {

		return '<a href="'.remositoryRepository::RemositoryBasicFunctionURL($func,$idparm,$os,$orderby,$item).'">';

	}

	function RemositoryBasicFunctionURL ($func=null, $idparm=null, $os=null, $orderby=null, $item=null ) {

		global $mosConfig_absolute_path, $Itemid;

		$url = 'index.php?option=com_remository&amp;Itemid=';
		if ($item) $url .= $item;
		else $url .= $Itemid;
		if ($func) $url .= '&amp;func='.$func;
		if ($idparm) $url .= '&amp;id='.$idparm;
		if (!$os) $os = remositoryRepository::getParam($_REQUEST,'os',null);
		if ($os AND $os != 'All') $url .= '&amp;os='.$os;
		if ($orderby) $url .= '&amp;orderby='.$orderby;
		if ($func == 'download') {
			$url .= '&amp;chk='.$this->makeCheck($idparm,$func);
		}

		$sefurl = sefRelToAbs($url);
		return $sefurl;

	}
	
	function wrongCheck ($chk, $id, $func) {
		
		global $mosConfig_absolute_path;
		
		$chk0 = md5($this->Time_Stamp.$mosConfig_absolute_path.date('md').$id.$func);
		if ($chk0 == $chk) return false;
		return true;
	}
	
	function makeCheck ($id, $func) {
		
		global $mosConfig_absolute_path;
		
		return md5($this->Time_Stamp.$mosConfig_absolute_path.date('md').$id.$func);
	}

	function RemositoryImageURL($imageName, $width=16, $height=16) {

		global $mosConfig_live_site;

		$element = '<img src="';
		$element .= $mosConfig_live_site.'/components/com_remository/images/'.$imageName;
		$element .= '" width="';
		$element .= $width;
		$element .= '" height="';
		$element .= $height;
		$element .= '" border="0" align="middle" alt="';
		$element .= $imageName;
		$element .= '"/>';
		return $element;

	}
	
	function doSQL ($sql) {
		global $database;
		$database->setQuery($sql);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}
	}
	
	function doSQLget ($sql, $classname) {
		global $database;
		$database->setQuery($sql);
		$rows = $database->loadObjectList();
		eval('$target = get_class_vars("'.$classname.'");');
		if ($rows) {
			foreach ($rows as $row) {
				eval('$next = new '.$classname.'(0);');
				foreach (get_object_vars($row) as $field=>$value) {
					if (isset($target[$field])) $next->$field = $value;
				}
				$result[] = $next;
			}
			return $result;
		}
		else return (array());
	}
	
	function getParam (&$array, $name, $default='') {
		if (isset($array[$name])) return $array[$name];
		else return $default;
	}

}

class remositoryVote {
	var $id='0';
	var $type=0;
	var $lastIP=null;
	var $count=0;
	var $evaluation=0;
	
	function remositoryVote ($type, $id) {
		$this->type = $type;
		$this->id = $id;
	}

	function getVote () {
		global $database;
		$sql = "SELECT AVG(value), COUNT(value), ipaddress FROM #__downloads_log WHERE type=$this->type AND fileid=$this->id GROUP BY type, fileid ORDER BY date DESC";
		$database->setQuery($sql);
		$row = $database->loadRow();
		$this->evaluation = $row[0];
		$this->count = $row[1];
		$sql = "SELECT ipaddress FROM #__downloads_log WHERE fileid=$this->id AND type=$this->type ORDER BY date DESC";
		$database->setQuery($sql);
		$this->lastIP = $database->loadResult();
	}

	function addVote (&$user, $vote) {
		$type = _REM_VOTE_USER_GENERAL;
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO #__downloads_log (type, date, userid, fileid, value, ipaddress) VALUES ('$this->type', '$date', $user->id, $this->id, $vote, '$user->currIP')";
		remositoryRepository::doSQL($sql);
		$this->lastIP = $user->currIP;
		$score = ($this->evaluation * $this->count);
		$this->count++;
		$this->evaluation = ($score + $vote)/$this->count;
	    return $this->count;
	 }
	 
	function userVoted (&$user) {
		if ($this->lastIP == $user->currIP) {
			echo '<table><tr><td><b>'._DOWN_ALREADY_VOTE.'</b></td></tr></table>';
			return true;
		}
		else return false;
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
		global $database, $Date_Format;
		$this->prepareValues();
		if ($this->date == null) $this->date = date('Y-m-d H:i:s');
		$sql="INSERT INTO #__downloads_reviews (component, itemid, userid, title, comment, date) VALUES ('$this->component', $file->id, $this->userid, '$this->title', '$this->comment', '$this->date')";
		remositoryRepository::doSQL($sql);
	}
	
	function getComments (&$file) {
		global $database;
		$sql = "SELECT c.title, c.comment, c.date, u.id as userid, u.name, u.username FROM #__downloads_reviews AS c, #__users AS u WHERE c.userid=u.id AND c.itemid=$file->id";
		$database->setQuery($sql);
		$rows = $database->loadObjectList();
		$comments = array();
		if ($rows) {
			foreach ($rows as $row) {
				$comments[] = new remositoryComment ($row->userid, $row->name, $row->username, $row->title, $row->comment, $row->date);
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
		$this->ipaddress = $_SERVER['REMOTE_ADDR'];
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
