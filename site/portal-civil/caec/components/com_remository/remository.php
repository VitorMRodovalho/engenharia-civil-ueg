<?php
/**
* FileName: remository.php
* Date: 05/03/2005
* License: GNU General Public License
* Script Version #: 3.24
* MOS Version #: 4.5 and 4.5.1+
* Script TimeStamp: "05/03/2005 07:07AM"
* Original Script: psx-dude - psx-dude@psx-dude.net
* Enhancements & Integration: Matt Smith, Martin Brampton - martin@remository.com (http://www.remository.com)
**/
// Part of Remository, copyright (c) Martin Brampton 2005.
// For further information please refer to http://www.remository.com

// Don't allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once ($mosConfig_absolute_path.'/components/com_remository/com_remository_constants.php');

//error_reporting(E_ALL);

// Is magic quotes on?
if (get_magic_quotes_gpc()) {
 // Yes? Strip the added slashes
 $_REQUEST = remove_magic_quotes($_REQUEST);
 $_GET = remove_magic_quotes($_GET);
 $_POST = remove_magic_quotes($_POST);
}
set_magic_quotes_runtime(0);

$canSetTitle = array($mainframe, 'SetPageTitle');
if (is_callable($canSetTitle)) $mainframe->SetPageTitle("ReMOSitory");

require_once( $mainframe->getPath( 'class' ) );
require_once( $mainframe->getPath( 'front_html' ) );

//include('configuration.php');
require_once($mosConfig_absolute_path.'/components/com_remository/com_remository_settings.php');
if(file_exists($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php')) require_once($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php');
else require_once($mosConfig_absolute_path.'/components/com_remository/language/english.php');

$remUser = new remositoryUser ($my->id, $my);
$repository = new remositoryRepository('GLOBAL');

$idparm = remositoryRepository::getParam($_REQUEST, 'id', 0);
$Itemid = remositoryRepository::getParam($_REQUEST, 'Itemid', 0);
$orderby = remositoryRepository::getParam($_REQUEST, 'orderby', 2);

switch (remositoryRepository::getParam ($_REQUEST, 'func', '')) {
	case 'download':
		if (!isset($_GET['chk']) OR $repository->wrongCheck($_GET['chk'],$idparm,'download')){
			echo _DOWN_LEECH_WARN;
			?>
			<br>&nbsp;<br><a href="../../index.php?option=com_remository&Itemid=<?php echo $Itemid;?>"><img src="<?php echo $mosConfig_live_site;?>/components/com_remository/images/gohome.png" width="16" height="16" border="0" align="absmiddle"> <?php echo _MAIN_DOWNLOADS; ?></a>
			<?php
			exit;
		}
	    $file = createFile ($idparm, $remUser);
		if (!$file->downloadForbidden($remUser)) HTML_downloads::downloadHTML ($file, $repository, $remUser->id);
	  	break;
	case 'downloadagree':
		downloadagree ( $idparm, $repository, $Itemid );
	  	break;
	case 'select':
	    $container = createContainer ($idparm);
		selectcontainer ( $container, $orderby, $remUser, $repository );
	  	break;
	case 'fileinfo':
	    $file = createFile ($idparm, $remUser);
		fileinfo ( $file, $remUser );
	  	break;
	case 'search':
		search ( $remUser, $repository );
	  break;
	case 'addfile':
	    $file = new remositoryFile();
		HTML_downloads::addfileHTML ( $remUser, $repository, $file );
	  	break;
	case 'savefile':
		savefile ( $remUser, $repository );
		break;		
	case 'userupdate':
		$file = createFile ($idparm, $remUser);
		updatefile ( $file, $remUser, $repository );
	  	break;
	default:
		front($remUser, $repository);
	  break;		
}


function front (&$remUser, &$repository)
{
	function dirsize($iniDir) {
   		$totalsize = 0;
	    $file = "";
		$dirsleft = array();
		array_push($dirsleft, $iniDir);
		while (count($dirsleft)>0) {
			$curdir = array_pop($dirsleft);
			if ($handle = opendir($curdir)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						if (is_dir($curdir."/".$file)) array_push($dirsleft, $curdir."/".$file);
						else $totalsize += filesize($curdir."/".$file);
					}
				}
				closedir($handle);
			}
		}
		return $totalsize;
	}

	$zerocontainer = new remositoryContainer;
	$categories = $zerocontainer->getVisibleChildren($remUser,$repository);
	$submitok = true;
	$submit_text = _SUBMIT_FILE_BUTTON;
	if ($submitok AND !$repository->Allow_User_Sub AND !$remUser->isAdmin()){
		$submitok = false;
		$submit_text = _SUBMIT_FILE_NOUSER;
	}
	clearstatcache();
	if ($remUser->isAdmin()) {
    	if (!file_exists($repository->Down_Path.'/')) {
      		$submitok = false;
			$submit_text = _SUBMIT_NO_DDIR;
    	}
  	} elseif ($remUser->isUser()) {
    	if (!file_exists($repository->Up_Path.'/')) {
			$submitok = false;
			$submit_text = _SUBMIT_NO_UDDIR;
    	}
   	}
	if ($submitok) {
		if ($repository->Max_Up_Per_Day > 0 AND $remUser->uploadsToday() >= $repository->Max_Up_Per_Day) {
		    $submitok = false;
		    $submit_text = _SUBMIT_FILE_NOLIMIT;
        }
	}
    if ($submitok) {
    	 $Curr_Up_Dir_Space=(dirsize($repository->Up_Path))/1024;
         $up_dir_space_avail=$repository->Max_Up_Dir_Space-$Curr_Up_Dir_Space-$repository->MaxSize;
         if ($up_dir_space_avail<0) {
         	$submitok = false;
            $submit_text = _SUBMIT_FILE_NOSPACE;
		}
	}
	HTML_downloads::frontHTML( $categories, $submitok, $submit_text, $repository, $remUser );
}


function downloadagree ( $idparm, &$repository, $Itemid )
{
	global $mosConfig_live_site;
	if (!isset($_POST['da']) OR $repository->wrongCheck($_POST['da'],$idparm,'downloadagree')) {
		echo _DOWN_LICENSE_WARN;
		?>
		<br/>&nbsp;<br/><a href="index.php?option=com_remository&Itemid=<?php echo $Itemid;?>"><img src="<?php echo $mosConfig_live_site;?>/components/com_remository/images/gohome.png" width="16" height="16" border="0" align="absmiddle"> <?php echo _MAIN_DOWNLOADS; ?></a>
		<?php
		exit;
	}
	else {
		$chk=$repository->makeCheck($idparm,'startdown');
		header("Location: $mosConfig_live_site/components/com_remository/com_remository_startdown.php?id=$idparm&chk=$chk");
	}
}


function selectcontainer( &$container, $orderby, &$remUser, &$repository ) {
	
	$parent = $container->getParent();
	HTML_downloads::filesHeaderHTML( $parent );
	
//	if ($container->downloadForbidden($remUser)) return;
	$subfolders = $container->getVisibleChildren($remUser, $repository);
	$page = remositoryRepository::getParam($_REQUEST, 'page', 1);
	$pagecontrol = new remositoryPage ( $container, $remUser, _ITEMS_PER_PAGE, $page, $orderby );
	if ($container->areFilesVisible($remUser, $repository))	$files = $container->getFiles(!$remUser->isAdmin(), $orderby, null, $pagecontrol->startItem(), $pagecontrol->itemsperpage);
	else $files = array();
	HTML_downloads::selectContainerHTML( $container, $subfolders, $files, $orderby, $remUser, $pagecontrol );
}


function fileinfo ( &$file, &$remUser )
{
	if (remositoryRepository::getParam($_POST,'submit_vote','')) {
		$user_rating = remositoryRepository::getParam($_POST,'user_rating',0);
		if (!is_numeric($user_rating)) $user_rating = 0;
		if (($user_rating>=1) AND ($user_rating<=5)) {
			if (!$file->userVoted($remUser)) $file->addVote($remUser, $user_rating);
		}
	}
	if (remositoryRepository::getParam($_POST,'submit_comm','')) {
		if (!$file->userCommented($remUser)) {
			$thecomment = new remositoryComment($remUser->id,$remUser->fullname,$remUser->name,'Review Title',remositoryRepository::getParam($_POST,'comment',''));
			$thecomment->saveComment($file);
		}
	}
	$parent = $file->getContainer();
	HTML_downloads::filesHeaderHTML( $parent );
	if (($file->downloadForbidden($remUser))) return;
	HTML_downloads::fileinfoHTML( $file, $remUser);
}


function search ( &$remUser, &$repository )
{
	HTML_downloads::filesHeaderHTML( null );
	if (remositoryRepository::getParam($_REQUEST,'submit',0)) {
		$search_text = remositoryRepository::getParam($_REQUEST,'search_text','');
		$seek_title = remositoryRepository::getParam($_REQUEST,'search_filetitle',0);
		$seek_desc = remositoryRepository::getParam($_REQUEST,'search_filedesc',0);
		$seek_author = remositoryRepository::getParam($_REQUEST,'search_author',0);
		$file_array = $repository->searchRepository($search_text, $seek_title, $seek_desc, $seek_author, $remUser, $repository);
		HTML_downloads::searchResultsHTML( $file_array, $repository );
  	}
	else HTML_downloads::searchBoxHTML($repository);
}


function updatefile ( &$file, &$remUser, &$repository )
{
	if ($remUser->isAdmin() OR $repository->Allow_User_Edit) ;
	else error_popup (_DOWN_NOT_AUTH);

	HTML_downloads::addfileHTML ( $remUser, $repository, $file );
}


function gotuploadfile (&$remUser, &$repository, &$file) {
	global $mosConfig_absolute_path;

	if (!isset($_FILES['userfile'])){
		error_popup(_ERR1);
		exit;
	}
	
	$uperr = $_FILES['userfile']['error'];
	$FileName = $_FILES['userfile']['name'];
	$FileSize = ($_FILES['userfile']['size']);
	$TempFile = $_FILES['userfile']['tmp_name'];
	$oldid = $_POST['oldid'];
	if ($TempFile == 'none' || $TempFile == ''){
		error_popup(_ERR1);
		exit;
	}
	if (!is_uploaded_file($TempFile)) {
	    error_popup (_ERR2);
	    exit;
    }
	if ($FileSize == 0) {
	    error_popup (_ERR3);
	    exit;
	}
	if ($uperr) {
	    error_popup (_ERR11);
		exit;
	}
	$FileSize = $FileSize/1024;
	if($FileSize > $repository->MaxSize) {
    	error_popup (_ERR5.$MaxSize.'Kb');
    	exit;
    }
    if ($repository->Anti_Leach){
    	$leach_code = substr(md5(date('r')),0,8);
    } else {
    	$leach_code = '';
    }
   	$file_dest = $leach_code.$FileName;
    if ($remUser->isAdmin() OR $repository->Enable_User_Autoapp) {
    	if(file_exists($repository->Down_Path.'/'.$FileName) & !$repository->Allow_Up_Overwrite) {
            error_popup (_ERR6);
            exit;
        } 
		$container = new remositoryContainer($file->containerid);
		if ($container->filepath) $file_path = $container->filepath.'/'.$file_dest;
   		else $file_path = $repository->Down_Path.'/'.$file_dest;
   		$filetemp = '';
   		$filetemphash = '';
    }
	else {
		$filetemp = time().','.$file_dest;
		$file_path = $repository->Up_Path.'/'.time().$file_dest;
    	//Generate security hash to pass filetempname
		//$filetemp = serialize ($filetemp);
		$filetemphash = md5 (serialize($filetemp));
	}
    $file->url = '';
    $file->realname = $file_dest;
    $file->islocal = '1';
	if ($file->filetitle == '') $file->filetitle = $FileName;
    $file->filesize = number_format($FileSize,2).'Kb';
	if (strtolower(get_class($file)) == 'remositorytempfile') {
		$file->filetempname = $filetemp;
		$file->filetemphash = $filetemphash;
	}
    move_uploaded_file ($TempFile, $file_path);
	$file->filedate = date('Y-m-d H:i:s');
    chmod($file_path, 0644);

}


function savefile ( &$remUser, &$repository )
{
	function sendAdminMail($user_full, &$repository, &$file){

		global $mosConfig_sitename, $mosConfig_live_site;
		
		$superadmin = remositoryUser::superAdminMail();
		if ($repository->Sub_Mail_Alt_Addr=='') $recipient = $superadmin;
	    else $recipient = "$repository->Sub_Mail_Alt_Name <$repository->Sub_Mail_Alt_Addr>";
		$subject = $mosConfig_sitename.':'._DOWN_MAIL_SUB;
		$message = $file->filetitle.' : ';
		if ($file->published) $message .= _DOWN_MAIL_MSG_APP;
		else $message = _DOWN_MAIL_MSG;

		eval ("\$message = \"$message\";");
		$headers = "From: $superadmin\n";
		$headers .= "X-Sender: <$mosConfig_live_site> \n";
		$headers .= "X-Mailer: PHP\n"; // mailer
		$headers .= "Return-Path: $superadmin\n";  // Return path for errors
		mail($recipient, $subject, $message, $headers);
	}
	
	$remUser->allowUploadCheck($repository);
	//Process the variables
	if (($remUser->isAdmin() AND $repository->Enable_Admin_Autoapp) OR ($remUser->isUser() AND $repository->Enable_User_Autoapp)) {
	    $newfile = new remositoryFile();
	    $newfile->published = '1';
	}
	else $newfile = new remositoryTempFile();
	$newfile->addPostData();
	$newfile->smalldesc = strip_tags($newfile->smalldesc);
	$newfile->makeAutoshort($repository);
	$newfile->checkLicenseagree();
	$container = new remositoryContainer($newfile->containerid);
	$newfile->memoContainer($container);
	$newfile->submittedby = $remUser->id;
	if (eregi(_REMOSITORY_REGEXP_URL,$newfile->url) OR eregi(_REMOSITORY_REGEXP_IP,$newfile->url)) {
		if ($newfile->filetitle == '') $newfile->filetitle = $newfile->lastPart($newfile->url,'/');
	}
	else {
		gotuploadfile($remUser, $repository, $newfile);
		$newfile->checkExtensionOK($repository);
	}
	$newfile->saveFile();
	if ($newfile->published) {
		if ($newfile->islocal) $uploadsize = $newfile->filesize;
		else $uploadsize = 0;
		$logentry = new remositoryLogEntry(_LOG_UPLOAD, $remUser->id, $newfile->id, $uploadsize);
		$logentry->insertEntry();
	}

	//Send Admin notice
	if ($repository->Send_Sub_Mail) sendAdminMail($remUser->fullname.' ('.$remUser->name.')', $repository, $newfile);

	HTML_downloads::filesHeaderHTML( null );

	HTML_downloads::addFileDoneHTML ($newfile, $repository);

}

/* =================================================================================
   Support Functions
   ================================================================================= */

function error_popup ($message) {
	
	echo "<script> alert('".$message."'); window.history.go(-1); </script>\n";
	
}

function createFile ($id, $remUser) {
	
	if (is_numeric($id) AND ($id != 0)) {
		$file = new remositoryFile ($id);
		$file->getValues($remUser);
		return $file;
	}
	die ('We should have had a valid file ID');
}

function createContainer ($id) {
	
	if (is_numeric($id) AND ($id != 0)) {
		$container = new remositoryContainer ($id);
		return $container;
	}
	die ('We should have had a valid container ID='.$id);
}

function remove_magic_quotes ($array) {
	foreach ($array as $k => $v) {
		if (is_array($v)) $array[$k] = remove_magic_quotes($v);
		else $array[$k] = stripslashes($v);
	}
	return $array;
}

?>
