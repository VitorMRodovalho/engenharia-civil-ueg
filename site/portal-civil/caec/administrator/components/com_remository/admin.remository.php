<?php
/**
* FileName: admin.remository.php
* Date: 05 March 2005
* License: GNU General Public License
* Script Version #: 3.22
* MOS Version #: 4.5+ (Tested on 4.5 (1.0.9 security fixed) and 4.5.2.1)
* Script TimeStamp: "05Mar2005 10:50AM"
* Original Script: psx-dude - psx-dude@psx-dude.net
* Enhancements & Integration: Matt Smith, Martin Brampton - martin@remository.com (http://www.remository.com)
**/

// Part of Remository, copyright (c) Martin Brampton 2005.
// For further information please refer to http://www.remository.com

require_once ($mosConfig_absolute_path.'/components/com_remository/com_remository_constants.php');

// Don't allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

//error_reporting(E_ALL);

// Is magic quotes on?
if (get_magic_quotes_gpc()) {
 // Yes? Strip the added slashes
 $_REQUEST = remove_magic_quotes($_REQUEST);
 $_GET = remove_magic_quotes($_GET);
 $_POST = remove_magic_quotes($_POST);
}
set_magic_quotes_runtime(0);

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );

require_once($mosConfig_absolute_path.'/administrator/includes/pageNavigation.php');

//include('configuration.php');
require_once($mosConfig_absolute_path.'/components/com_remository/com_remository_settings.php');
if(file_exists($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php')) require_once($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php');
else require_once($mosConfig_absolute_path.'/components/com_remository/language/english.php');

class configSelector {
	var $description='';
	var $variablename='';
	
	function configSelector ($name, $desc) {
		$this->variablename = $name;
		$this->description = $desc;
	}
}

$tabclass_arr = explode(",",$tabclass);
$remUser = new remositoryUser ($my->id,$my);

$ReMOSver='3.24';

$repository = new remositoryRepository ('GLOBAL');

$cfid = remositoryRepository::getParam( $_REQUEST, 'cfid', array(0) );
if (is_array( $cfid )) $currid=$cfid[0];
else $currid=$cfid;

$filepath = remositoryRepository::getParam($_REQUEST, 'filepath', '');
$oldpath = remositoryRepository::getParam($_REQUEST, 'oldpath', '');
$act = remositoryRepository::getParam($_REQUEST, 'act', '');
$task = remositoryRepository::getParam($_REQUEST, 'task', '');
$func = remositoryRepository::getParam($_POST, 'func', '');

//$default_limit  = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 20 );
$limit = intval( remositoryRepository::getParam( $_REQUEST, 'limit', 20 ) );
$limitstart = intval( remositoryRepository::getParam( $_REQUEST, 'limitstart', 0 ) );

if ($task<>'') $func = $task;
elseif ($act<>'') $func = $act;

if (is_integer(strpos(strtolower($func),'cancel')))	$func='cancel';

switch ($func) {
	
	case 'showfolders':
		showFolders( $repository, $remUser );
		break;
	  
	case 'showfiles':
		showfiles($repository,$remUser);
		break;
	  
	case 'addcat':
	    $container = new remositoryContainer();
		HTML_downloads::editContainerHTML ( $container, $repository );
		break;
	  
	case 'delcat':
		deletecontainer ( $cfid, $repository, $remUser, 'CATEGORY' );
		break;
	  
	case 'addfolder':
		$iconList = remositoryContainer::getIcons();
		$repository->requireCategories();
		$clist = $repository->getSelectList(false, '', 'parentid', 'class="inputbox"',$remUser);
		$container = new remositoryContainer();
		HTML_downloads::editContainerHTML ( $container, $repository, $clist );
		break;
	  
	case 'savecontainer':
	    $next = savecontainer($currid,$repository,$oldpath);
    	mosRedirect( "index2.php?option=com_remository&task=".$next );
		break;
		
	case 'addfile':
		$file = new remositoryFile();
		$file->fileversion = $repository->Default_Version;
		HTML_downloads::editfileHTML ( $file, $repository, $remUser, false );
		break;
	  
	case 'savefile':
		savefile ( $currid, $repository, $remUser, $oldpath );
		break;
			
	case 'delfile':
		delfile ( $cfid, $repository, $remUser );
		break;
	  	
	case 'delfolder':
		deletecontainer ( $cfid, $repository, $remUser, 'FOLDER' );
		break;
	  									
	case 'editcat':
		$iconList = remositoryContainer::getIcons();
		$category = new remositoryContainer($currid);
		$oldpath = $category->filepath;
		HTML_downloads::editContainerHTML ( $category, $repository, '', $oldpath );
		break;
		
	case 'editfolder':
		$iconList = remositoryContainer::getIcons();
		$folder = new remositoryContainer($currid);
		$oldpath = $folder->filepath;
		$clist = $repository->getSelectList(false, $folder->parentid,'parentid','class="inputbox"', $remUser, $folder);
		HTML_downloads::editContainerHTML ( $folder, $repository, $clist, $oldpath );
		break;
		
	case 'editfile':
		$file = new remositoryFile($currid);
		if ($currid!=0) {
			$file->getValues($remUser);
			$oldpath = $file->filepath;
			if ($oldpath == '') $oldpath = $repository->Down_Path.'/';
		}
		else $file->fileversion = $repository->Default_Version;
		HTML_downloads::editfileHTML ( $file, $repository, $remUser, false, $oldpath );
		break;
	  
	case 'publishcat':
		publishcontent($cfid, $repository, 'CATEGORY', 1 );
		break;  	
		
	case 'publishfolder':
		publishcontent($cfid, $repository, 'FOLDER', 1 );
		break; 
		
	case 'publishfile':
		publishcontent($cfid, $repository, 'FILE', 1 );
		break;
		
	case 'unpublishcat':
		publishcontent($cfid, $repository, 'CATEGORY', 0 );
		break;  
		
	case 'unpublishfolder':
		publishcontent($cfid, $repository, 'FOLDER', 0 );
		break; 
		
	case 'unpublishfile':
		publishcontent($cfid, $repository, 'FILE', 0 );
		break; 
		
	case 'delsubmit':
		delsubmit ( $cfid, $repository );
		break;
	  		
	case 'approve2':
		$tempfile = new remositoryTempFile($currid);
		if ($currid != 0) $tempfile->getValues();
		$tempfile->addPostData();
		$tempfile->containerid = $_POST['suggestloc'];
		singleApprove ( $tempfile, $remUser, $repository );
		mosRedirect( "index2.php?option=com_remository&task=showfiles" );
		break;
	  
	case 'bulkapprove':
		bulkApprove ( $cfid, $remUser, $repository );
		break;
	  
	case 'approve':
		showApprove ($repository, $remUser);
		break;
	  
	case 'editapprove':
		$file = new remositoryTempFile($currid);
		if ($currid!=0) {
			$file->getValues($remUser);
			$oldpath = $file->filepath;
			if ($oldpath == '') $oldpath = $repository->Down_Path.'/';
		}
		HTML_downloads::editfileHTML ( $file, $repository, $remUser, true, $oldpath );
		break;
	  
	case 'orphans':
		showorphans ($repository);
		break;
	  
	case 'addorphan':
		addorphan ($repository, $remUser, $filepath);
		break;
	  
	case 'delorphans':
	    foreach ($cfid as $file) @unlink($file);
		mosRedirect( "index2.php?option=com_remository&task=orphans" );
		break;
	  
	case 'resetfilecounts':
		$repository->resetCounts(array());
		mosRedirect( "index2.php?option=com_remository" );
		break;
		
	case 'resetdownloads':
		remositoryFile::resetDownloadCounts();
		break;
		
	case 'remosconfig':
		config($repository);
		break;
		
	case 'saveconfig':
		saveconfig();
		break;
		
	case 'stats':
	  	Stats ();
  		break;
  		
  	case 'missing':
  	    showmissing($repository);
  	    break;
  		
	case 'dbconvert':
	  	dbconvert($repository);
  		break;

	case 'about':
  		HTML_downloads::aboutHTML();
  		break;
  		
  	case 'cancel':
  		$cancelopt = array();
  		$cancelopt = explode("|", $task);
		mosRedirect( "index2.php?option=com_remository&task=$cancelopt[1]" );
  		break;
  		
	case 'showcats':
	default:
		$categories = $repository->getCategories();
		$pageNav = new mosPageNav( count($categories), $limitstart, $limit );
		HTML_downloads::showContainersHTML( array_slice($categories,$limitstart,$limit), $repository, $pageNav );
		break;
}


function showFolders (&$repository, &$remUser) {
	    global $limit, $limitstart;
		$fileloc = trim( strtolower( remositoryRepository::getParam( $_REQUEST, 'fileloc', '' ) ) );
		$search = trim( strtolower( remositoryRepository::getParam( $_REQUEST, 'search', '' ) ) );
		//Get Suggested Folder/Cat Location
		if ($fileloc) {
			$container = new remositoryContainer($fileloc);
			$folders = $container->getChildren (false, $search);
		}
		else $folders = $repository->getFolders();
		$clist = $repository->getSelectList(true, $fileloc, 'fileloc', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', $remUser);
		$pageNav = new mosPageNav( count($folders), $limitstart, $limit );
		HTML_downloads::showContainersHTML( array_slice($folders,$limitstart,$limit), $repository, $pageNav, $search, $clist );
}


function deletecontainer ( $cfid, &$repository, &$remUser, $type='FOLDER' )
{
	check_selection($cfid);
	foreach ($cfid as $id) {
		$container = new remositoryContainer($id);
		$container->deleteAll($repository);
	}
	$repository->resetCounts(array());
	if ($type == 'FOLDER') mosRedirect( "index2.php?option=com_remository&task=showfolders" );
	else mosRedirect( "index2.php?option=com_remository&task=showcats" );
}

function showFiles(&$repository, &$remUser) {
	global $limit, $limitstart;
	$fileloc = trim( strtolower( remositoryRepository::getParam( $_REQUEST, 'fileloc', '' ) ) );
	$search = trim( strtolower( remositoryRepository::getParam( $_REQUEST, 'search', '' ) ) );

	//Get Suggested Folder/Cat Location
	if ($fileloc) {
		$container = new remositoryContainer($fileloc);
		$files = $container->getFiles(false, 1, $search, $limitstart, $limit);
		$total = $container->getFilesCount($search);
	}
	else {
		$files = $repository->getFiles($search, $limitstart, $limit);
		$total = $repository->getFilesCount($search);
	}
	$clist = $repository->getSelectList(true, $fileloc,'fileloc','class="inputbox" size="1" onchange="document.adminForm.submit();"',$remUser);
	$pageNav = new mosPageNav( $total, $limitstart, $limit );
	HTML_downloads::showFilesHTML( $files, $repository, $clist, $pageNav, $search, $limit );
}

function savecontainer ($currid, &$repository, $oldpath) {
	    $container = new remositoryContainer($currid);
	    $container->addPostData();
	    if ($currid == 0) $container->published = 1;
	    $k = strlen($container->filepath);
	    if ($k > 0) {
	    	if (file_exists($container->filepath)) {
				if (substr($container->filepath,$k-1) != '/') $container->filepath = $container->filepath.'/';
			}
			else {
				echo "<script> alert('".'Specified file path does not exist - ignored '."$container->filepath'); </script>\n";
				$container->filepath = '';
			}
		}
		if ($container->filepath != $oldpath) {
			echo "<script> alert('".'Path for files changed - WARNING files not moved'."$container->filepath'); </script>\n";
		}
	    $container->saveValues ();
		$repository->resetCounts(array());
		$sql = "UPDATE #__downloads_files SET registered='$container->registered', userupload='$container->userupload', filepath='$container->filepath',groupid='$container->groupid' WHERE containerid=$container->id";
		remositoryRepository::doSQL($sql);
		$repository->resetCounts(array());
		if ($container->isCategory()) return 'showcats';
		else return 'showfolders';
}

function addorphan ( &$repository, &$remUser, $filepath )
{
	$file = new remositoryFile();
	$file->fileversion = $repository->Default_Version;
	if ($filepath) {
		$parts = explode('/',$filepath);
		$filename = $parts[count($parts)-1];
		$file->islocal = '1';
		$file->realname = $filename;
		$file->filetitle = $filename;
		$split = explode( '.', $filename);
		$file->filetype = strtolower($split[count($split)-1]);
		$file->filesize = number_format(filesize($filepath)/1024,2).'Kb';
		$file->filedate = date('Y-m-d H:i:s',filemtime($filepath));
		HTML_downloads::editfileHTML ( $file, $repository, $remUser, false, remositoryAbstract::allButLast($filepath,'/').'/' );
	}
	else die ('Should be impossible to attempt addorphan with no filepath');
}

function publishcontent( $cfid, &$repository, $type='', $publish=1 ) {
	if (!is_array( $cfid ) OR count( $cfid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('"._DOWN_PUB_PROMPT."$action'); window.history.go(-1);</script>\n";
		exit;
	}
	switch ($type) {
		case 'FILE':
		    remositoryFile::togglePublished($cfid,$publish);
			$repository->resetCounts();
			mosRedirect( "index2.php?option=com_remository&task=showfiles" );
		    break;
		case 'FOLDER':
		    remositoryContainer::togglePublished($cfid,$publish);
			$repository->resetCounts(array());
			mosRedirect( "index2.php?option=com_remository&task=showfolders" );
			break;
		default:
		    remositoryContainer::togglePublished($cfid,$publish);
			$repository->resetCounts(array());
			mosRedirect( "index2.php?option=com_remository" );
	}
}


function savefile ( $fileid, &$repository, &$remUser, $oldpath )
{
	$file = new remositoryFile($fileid);
	if ($file->id != 0) {
		$file->getValues($remUser);
		// Clear all tick boxes - will be set by POST data only if tick is present
		$file->published = 0;
		$file->autoshort = 0;
		$file->featured = 0;
	}
	else $file->submittedby = $remUser->id;
	$file->addPostData();
	$file->smalldesc = strip_tags($file->smalldesc);
	$file->checkExtensionOK($repository);
	$file->makeAutoshort($repository);
	$file->containerid = $_POST['suggestloc'];
	$container = new remositoryContainer($file->containerid);
	$file->memoContainer($container);
	if ($file->realname AND !$file->url) $file->islocal = '1';
	if ($file->filepath) $filelocation = $file->filepath;
	else $filelocation = $repository->Down_Path.'/';
	if ($file->islocal AND $filelocation != $oldpath) die('File updates that require the file to be moved not yet implemented');
	$file->checkLicenseagree();

	$file->saveFile();
	$repository->resetCounts();
	mosRedirect( "index2.php?option=com_remository&task=showfiles" );
}


function delfile ( $cfid, &$repository, &$remUser )
{
	check_selection($cfid);
	foreach ($cfid as $id) {
		$file = new remositoryFile ($id);
		$file->getValues($remUser);
		$file->deleteFile($repository);
	}
	$repository->resetCounts();
	mosRedirect( "index2.php?option=com_remository&task=showfiles" );
}

function delsubmit ( $cfid, &$repository )
{
	check_selection($cfid);
	$cfids = implode( ',', $cfid );
	foreach ($cfid as $id) {
		$tempfile = new remositoryTempFile ($id);
		$tempfile->getValues();
		$tempfile->deleteFile($repository);
	}
	mosRedirect( "index2.php?option=com_remository&task=approve" );
}


function showApprove(&$repository, &$remUser)
{
	global $limit, $limitstart;
	$fileloc = trim( strtolower( remositoryRepository::getParam( $_REQUEST, 'fileloc', '' ) ) );
	$search = trim( strtolower( remositoryRepository::getParam( $_REQUEST, 'search', '' ) ) );
	
	//Get Suggested Folder/Cat Location
	if ($fileloc) {
		$container = new remositoryContainer($fileloc);
		$files = $container->getTempFiles($search);
	}
	else $files = $repository->getTempFiles();
	$clist = $repository->getSelectList(true, $fileloc,'fileloc','class="inputbox" size="1" onchange="document.adminForm.submit();"',$remUser);

	$pageNav = new mosPageNav( count($files), $limitstart, $limit );

		
	HTML_downloads::approveHTML( $files, $repository, $pageNav, $search );
}


function bulkApprove ( $cfid=null, &$remUser, &$repository )
{
	if (count( $cfid ) < 1) {
		echo "<script> alert('"._DOWN_SEL_FILE_APPROVE."'); window.history.go(-1);</script>\n";
		exit;
	}
	foreach ($cfid as $id) {
		$tempfile = new remositoryTempFile($id);
		$tempfile->getValues();
		singleApprove($tempfile, $remUser, $repository);
	}
	mosRedirect( "index2.php?option=com_remository&task=showfiles" );
}

function singleApprove ( $tempfile, &$remUser, &$repository )
{
	$tempfile->checkExtensionOK($repository);
	$tempfile->makeAutoshort($repository);
	$tempfile->checkLicenseagree();
	$oldfile = new remositoryFile ($tempfile->oldid);
	if ($oldfile->id != 0) $oldfile->getValues($remUser);
	$newfile = new remositoryFile($tempfile->oldid);
	$newfile->setValues($tempfile);
	$newfile->forceBools();
	$newfile->downloads = $oldfile->downloads;
	$container = new remositoryContainer($newfile->containerid);
	$newfile->memoContainer($container);
	//Rename/Move/RePermission file
	$tempfilepath = $tempfile->filePath($repository);
	$destpath = $newfile->filePath($repository);
	if ($tempfilepath == $destpath) {
		$newfile->saveFile();
		$tempfile->deleteFileDB();
	}
	else {
		chmod($tempfilepath, 0644);
		if($result1 = copy($tempfilepath, $destpath)) {
			$newfile->saveFile();
			$tempfile->deleteFileDB();
			$result2 = unlink($tempfilepath);
		}
		chmod($destpath, 0644);
	}
	if ($oldfile->id != 0 AND $oldfile->url != $newfile->url) $oldfile->cloneFile($remUser);
	$logtype = _LOG_UPLOAD;
	if ($newfile->islocal) $uploadsize = $newfile->filesize;
	else $uploadsize = 0;
	$logentry = new remositoryLogEntry(_LOG_UPLOAD, $newfile->submittedby, $newfile->id, $uploadsize);
	$logentry->insertEntry();
}

function showmissing ($repository) {
	global $database;
	$sql = "SELECT id, islocal, url, realname, filepath, submitdate FROM #__downloads_files";
	$database->setQuery($sql);
	$files = $database->loadObjectList();
	if (count($files)==0) {
		echo 'No missing files <br/>';
		return;
	}
	$nomissing = true;
	foreach ($files as $file) {
		if ($file->islocal) {
			if ($file->filepath) $checkpath = $file->filepath.$file->realname;
			else $checkpath = $repository->Down_Path.'/'.$file->realname;
			if (!file_exists($checkpath)) {
				echo $checkpath.' - NOT FOUND AT THIS LOCATION<br/>';
				$nomissing = false;
			}
			else {
				$filedate = date('Y-m-d H:i:s', filemtime($checkpath));
				$filesize = number_format(filesize($checkpath)/1024,2).'Kb';
				$subdate = $file->submitdate;
				if (strcmp($subdate,$filedate) < 0) $subdate = $filedate;
				$sql = "UPDATE #__downloads_files SET filedate='$filedate', filesize='$filesize', submitdate='$subdate' WHERE id=$file->id";
				remositoryRepository::doSQL($sql);
			}
		}
	}
	foreach ($files as $file) {
		if (!$file->islocal) {
			$url = $file->url;
			if (!$url) $url = 'FILE NOT LOCAL, BUT NO URL SPECIFIED';
			if (!eregi(_REMOSITORY_REGEXP_URL,$url) AND !eregi(_REMOSITORY_REGEXP_IP,$url)) {
				echo $url.' - NOT VALIDATED<br/>';
				$nomissing = false;
			}
		}
	}
	if ($nomissing) echo 'No missing files<br/>';
}

function showorphans (&$repository) {
	
	function scanDirectory ($path, &$DelArrayDownPath, &$DelArrayDownFile, &$DelArrayOldPath, &$repository, $oldpath=false) {
	  	if ($dir = @opendir($path)) {
			while ($file = readdir($dir)) {
	      		if (($file != "index.html") AND (substr($file,0,1) != ".") AND (!is_dir($path.$file))) {
	     			if (!$repository->checkByName($file)) {
						$DelArrayDownPath[] = $path.$file;
						$DelArrayDownFile[] = $file;
						if ($oldpath) $DelArrayOldPath[] = $path;
						else $DelArrayOldPath[] = '';
					}
				}
	    	}
		  	closedir($dir);
  		}
  	}

	$DelArrayDownPath = array();
	$DelArrayDownFile = array();
	$DelArrayOldPath = array();
  	scanDirectory ($repository->Down_Path.'/', $DelArrayDownPath, $DelArrayDownFile, $DelArrayOldPath, $repository);
  	$directories = remositoryContainer::getFilePaths();
  	foreach ($directories as $directory) scanDirectory ($directory, $DelArrayDownPath, $DelArrayDownFile, $DelArrayOldPath, $repository, true);
	$DelArrayUpPath = array();
	$rows = $repository->getTempFiles();
  	$dirpass=$repository->Up_Path;
  	$dir = opendir($dirpass);
  	if (opendir('.')) {
		while ($file = readdir($dir)) {
      		if (($file != "index.html") AND (substr($file,0,1) != ".") AND (!is_dir($dirpass.'/'.$file))) {
				$fileUnlinked = true;
				foreach ($rows as $row) {
					if ($row->filetempname) {
						$currfile = explode(",",$row->filetempname);
						if ($currfile[0].$currfile[1]==$file) $fileUnlinked = false;
					}
				}
				if ($fileUnlinked) $DelArrayUpPath[] = $repository->Up_Path.'/'.$file;
			}
		}
	}

	HTML_downloads::orphansHTML( $DelArrayDownPath, $DelArrayUpPath, $DelArrayDownFile, $DelArrayDownPath );

}


function config (&$repository)
{
	// make a generic yes no list
	$yesno[] = mosHTML::makeOption( 0, _NO );
	$yesno[] = mosHTML::makeOption( 1, _YES );

	// build the html select lists
	$newlist[] = new configSelector ('Anti_Leach', _DOWN_CONFIG20);
	$newlist[] = new configSelector ('Allow_Up_Overwrite', _DOWN_CONFIG11);
	$newlist[] = new configSelector ('Allow_User_Sub', _DOWN_CONFIG12);
	$newlist[] = new configSelector ('Allow_User_Edit', _DOWN_CONFIG13);
	$newlist[] = new configSelector ('Allow_User_Up', _DOWN_CONFIG14);
	$newlist[] = new configSelector ('Allow_Comments', _DOWN_CONFIG15);
	$newlist[] = new configSelector ('Allow_Votes', _DOWN_CONFIG25);
	$newlist[] = new configSelector ('Send_Sub_Mail', _DOWN_CONFIG16);
	$newlist[] = new configSelector ('Enable_Admin_Autoapp', _DOWN_CONFIG26);
	$newlist[] = new configSelector ('Enable_User_Autoapp', _DOWN_CONFIG27);
	$newlist[] = new configSelector ('Enable_List_Download', _DOWN_CONFIG28);
	$newlist[] = new configSelector ('User_Remote_Files', _DOWN_CONFIG29);
	$newlist[] = new configSelector ('See_Containers_no_download', _DOWN_CONFIG33);
	$newlist[] = new configSelector ('See_Files_no_download', _DOWN_CONFIG34);

	HTML_downloads::configHTML( $repository, $newlist, $yesno );
	
}


function saveconfig(){
	
	global $mosConfig_absolute_path;
	
	$repository = new remositoryRepository ();
	$repository->addPostData();
	$repository->saveValues();
	$config = "<?php\n";
	$config .= $repository->getVarText();
	$config .= "?>";
	if ($fp = fopen($mosConfig_absolute_path.'/components/com_remository/com_remository_settings.php', "w")) {
		fputs($fp, $config, strlen($config));
		fclose ($fp);
		mosRedirect( "index2.php?option=com_remository", _DOWN_CONFIG_COMP );
	} else {
		mosRedirect( "index2.php?option=com_remository", _DOWN_CONFIG_ERR );
	}

}


function stats (){

	global $database;

		// Top 5 Downloads
		$sql="SELECT downloads, filetitle from #__downloads_files ORDER BY downloads DESC LIMIT 5";
		$database->setQuery( $sql );
		$downloads = $database->loadObjectList();

		// Top 5 Rated
		$logtype = _REM_VOTE_USER_GENERAL;
		$sql="SELECT CONCAT(filetitle,',',ROUND(AVG(value),1)), AVG(value) as average FROM #__downloads_log as l, #__downloads_files as f WHERE l.type=$logtype AND f.id = l.fileid GROUP BY f.id ORDER BY average DESC LIMIT 5";
		$database->setQuery( $sql );
		$ratings = $database->loadResultArray();

		// Top 5 Voted
		$sql="SELECT CONCAT(filetitle,',',COUNT(l.id)), COUNT(l.id) as counter FROM #__downloads_log as l, #__downloads_files as f WHERE l.type=$logtype AND f.id = l.fileid GROUP BY f.id ORDER BY counter DESC LIMIT 5";
		$database->setQuery( $sql );
		$votes = $database->loadResultArray();

	HTML_downloads::statsHTML( $downloads, $ratings, $votes );
}

function dbconvert (&$repository) {

	function convertfolder ($folder, $parent, &$containermap, &$container_reg) {
		global $database;
		foreach ($folder as $field=>$value) {
			if (!is_numeric($folder->$field)) $folder->$field = $database->getEscaped($folder->$field);
		}
		if ($folder->registered) $folder->registered = '0';
		else $folder->registered = '2';
		$sql = "INSERT INTO #__downloads_containers (parentid,name,published,description,filecount,icon,registered) VALUES ($parent, '$folder->name', $folder->published, '$folder->description', '$folder->files', '$folder->icon', $folder->registered)";
		$database->setQuery($sql);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$newid = $database->insertid();
		$containermap['folderid'][$folder->id] = $newid;
		$container_reg[$newid] = $folder->registered;
		$sql = "SELECT * FROM #__downloads_folders WHERE parentid=$folder->id";
		$database->setQuery($sql);
		$children = $database->loadObjectList();
		if ($children) {
			foreach ($children as $child) convertfolder ($child, $newid, $containermap, $container_reg);
		}
	}

	global $database, $mosConfig_live_site;
	foreach (array('containers','files','reviews','structure','log','temp') as $tablename) {
		$sql = "TRUNCATE TABLE #__downloads_$tablename";
		remositoryRepository::doSQL($sql);
	}
	$container_reg = array();
	$sql = "ALTER TABLE #__downloads_containers AUTO_INCREMENT=2";
	remositoryRepository::doSQL($sql);
	$containermap = array('catid'=>array(),'folderid'=>array());
	$sql = "SELECT * FROM #__downloads_category";
	$database->setQuery($sql);
	$rows = $database->loadObjectList();
	foreach ($rows as $row) {
		if ($row->registered) $row->registered = '0';
		else $row->registered = '2';
		foreach ($row as $field=>$value) {
			if (!is_numeric($row->$field)) $row->$field = $database->getEscaped($row->$field);
		}
		$sql = "INSERT INTO #__downloads_containers (parentid,name,published,description,filecount,icon,registered) VALUES (0,'$row->name',$row->published,'$row->description',$row->files,'$row->icon',$row->registered)";
		$database->setQuery($sql);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$newid = $database->insertid();
		$containermap['catid'][$row->id] = $newid;
		$container_reg[$newid] = $row->registered;
		$sql = "SELECT * FROM #__downloads_folders WHERE catid=$row->id";
		$database->setQuery($sql);
		$folders = $database->loadObjectList();
		if ($folders) {
			foreach ($folders as $folder) convertfolder ($folder, $newid, $containermap, $container_reg);
		}
	}
	$sql = "SELECT * FROM #__downloads";
	$database->setQuery($sql);
	$files = $database->loadObjectList();
	foreach ($files as $file) {
		$testurl = strtolower(trim($file->url));
		$findsite = strpos($testurl, strtolower(trim($mosConfig_live_site)));
		if ($findsite===false){
			$islocal = '0';
			$realname = '';
			$filedate = date('Y-m-d H:i:s');
			$url = $file->url;
			if (eregi(_REMOSITORY_REGEXP_URL,$url) OR eregi(_REMOSITORY_REGEXP_IP,$url)) $filefound = true;
			else $filefound = false;
		}
		else {
			$islocal = '1';
			$url_array=explode('/',$file->url);
			$url = '';
			$realname = $url_array[(count($url_array)-1)];
			$filepath = $repository->Down_Path.'/'.$realname;
			if (file_exists($filepath)) {
				$filefound = true;
				$filedate = date('Y-m-d H:i:s', filemtime($repository->Down_Path.'/'.$realname));
			}
			else $filefound = false;
		}
		$containerid = 0;
		if ($file->catid != 0) {
			if (isset($containermap['catid'][$file->catid])) $containerid = $containermap['catid'][$file->catid];
			else echo $file->id.'/'.$realname.'/'.$file->catid;
		}
		if ($file->folderid != 0) {

			if (isset($containermap['folderid'][$file->folderid])) $containerid = $containermap['folderid'][$file->folderid];
			else echo $file->id.'/'.$realname.'/'.$file->folderid;
		}
		if (isset($container_reg[$containerid])) $registered = $container_reg[$containerid];
		else $registered = 2;
		if ($filefound AND $containerid != 0) {
			foreach (get_class_vars('remositoryFile') as $field=>$value) {
				if (isset($file->$field) AND !is_numeric($file->$field)) $file->$field = $database->getEscaped($file->$field);
			}
			$sql="INSERT INTO #__downloads_files (realname,islocal,containerid,published,registered, url,description,smalldesc,autoshort,license,licenseagree,filetitle,filesize,filetype,downloads,icon,fileversion,fileauthor,filedate,filehomepage,screenurl,submittedby,submitdate) VALUES ('$realname',$islocal,$containerid,$file->published, '$registered', '$url','$file->description','$file->smalldesc',$file->autoshort,'$file->license',$file->licenseagree,'$file->filename','$file->filesize','$file->filetype','$file->downloads','$file->icon','$file->fileversion','$file->fileauthor','$filedate','$file->filehomepage','$file->screenurl', $file->submittedby,'$file->submitdate')";
			$database->setQuery($sql);
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
			$newid = $database->insertid();
			$sql = "SELECT * FROM #__downloads_comments WHERE id=$file->id";
			$database->setQuery($sql);
			$comments = $database->loadObjectList();
			if ($comments) {
				foreach ($comments as $comment) {
					$sql = "INSERT INTO #__downloads_reviews (component,itemid,userid,title,comment,date) VALUES ('com_remository',$newid,'$comment->userid','Review Title','$comment->comment','$comment->time')";
					$database->setQuery($sql);
					remositoryRepository::doSQL($sql);
				}
			}
		}
		else echo $file->url.'<br/>';
	}
	$repository->resetCounts(array());
	echo 'Database conversion completed.  Any files listed above were invalid. <br/>';
}

/* =================================================================================
   Support Functions
   ================================================================================= */

function check_selection ($cfid) {
	if (count( $cfid ) < 1) {
		echo "<script> alert('"._DOWN_SEL_FILE_DEL."'); window.history.go(-1);</script>\n";
		exit;
	}
}

function remove_magic_quotes ($array) {
	foreach ($array as $k => $v) {
		if (is_array($v)) $array[$k] = remove_magic_quotes($v);
		else $array[$k] = stripslashes($v);
	}
	return $array;
}

?>
