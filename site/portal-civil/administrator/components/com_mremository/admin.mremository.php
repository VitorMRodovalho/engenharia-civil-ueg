<?php 
/**
* FileName: admin.mremository.php
* Date: 10 June 2005
* License: GNU General Public License
* Script Version #: 1.00
* MOS Version #: 4.5+ (Tested on 4.5 (1.0.9 security fixed) and 4.5.2.1)
* Script TimeStamp: "10Jun2005 08:58PM"
* Developed by: Martin Brampton - remository@black-sheep-research.com (http://www.black-sheep-research.com)
**/
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

error_reporting(E_ALL);

// Is magic quotes on?
if (get_magic_quotes_gpc()) {
 // Yes? Strip the added slashes
 $_REQUEST = remove_magic_quotes($_REQUEST);
 $_GET = remove_magic_quotes($_GET);
 $_POST = remove_magic_quotes($_POST);
 $_FILES = remove_magic_quotes($_FILES);
}

require_once( $mosConfig_absolute_path.'/components/com_mremository/remository.class.php');
require_once( $mosConfig_absolute_path.'/components/com_mremository/com_remository_constants.php');

if (file_exists($mosConfig_absolute_path.'/components/com_remository/com_remository_settings.php'))
	include($mosConfig_absolute_path.'/components/com_remository/com_remository_settings.php');
	
if(file_exists($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php'))
	include_once($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php');
elseif (file_exists($mosConfig_absolute_path.'/components/com_remository/language/english.php'))
	include_once($mosConfig_absolute_path.'/components/com_remository/language/english.php');

$act = remositoryRepository::getParam($_REQUEST, 'act', '');
$kill = remositoryRepository::getParam($_REQUEST, 'kill', '');

?>
<h2>CAUTION!</h2>
<p>
This program is capable of deleting database tables and files.  Once deleted, there is no way
to get them back again unless you have backups that you can restore.
It can delete whole directories (including contained directories and
all the files in them).  It is your responsibility to make sure you have backed up anything that
is important to you and to ensure that you use the program correctly.  While the program has been
tested, the author gives no warranty that it will not erroneously delete the wrong things, and
use of the program is strictly conditional upon the author having no liability for any such
problems.
</p>
<p>
It is essential to follow the instructions for use very carefully - please read the full explanation
for any operation you think of activating.  Please refer to the "Instructions for use" option from
the drop down menu.
</p>
<p>
The deletion of files and directories may fail.  The program will give you an indication of what
has happened.  The reason for this is that file permissions and file ownership will sometimes make
it impossible for deletion to be done by a component.  In that case, you will have to resort to
other techniques, calling on the assistance of your hosting provider if necessary.
</p>
<?php

switch ($act) {
	case 'about':
	    ?>
	    <p>
	    The Remository Management component is provided to deal with a number of issues and problems
	    that can arise in the running of the Remository file repository.  PLEASE NOTE that the option
		to delete the download/upload directories can ONLY be used if the Remository programs are still
		in place, because it depends on the Remository settings, which are with the program files.
		Typical uses are:
	    </p>
	    <ul>
	    <li>The option "Remove Remository ready for upgrade" carries out both of the options "Remove
	    Remository Mambo database entries" and "Remove Remository program files".  See below for why
		this is a useful thing to do.</li>
	    <li>All versions of Remository from 3.0 onwards do not remove the Remository database tables
	    when the component is uninstalled.  This is deliberate, because one reason for uninstalling is
	    to be able to do a fresh installation.  However, if you want to completely remove Remository
	    and tidy up the database, you can use the "Remove all Remository database tables" function to do this.
		You may also want to use the "Remove Remository from Mambo menus" option and the
		"Remove Remository download/upload files" option.</li>
	    <li>Versions of Remository prior to 3.0 will completely delete the Remository database tables
	    if they are uninstalled.  That makes it difficult to achieve a clean upgrade because some
	    operations are best handled through the full installation process.  You can achieve the
	    equivalent of an uninstall WITHOUT losing the database tables by carrying out two operations.
	    They are "Remove Remository Mambo database entries" and "Remove Remository program files".
	    The first will delete all references to Remository from the Mambo components table; the second
	    removes the program code and its containing directories, which will allow Remository to be
	    installed again.  You MUST use version 3.0 or later, otherwise the new installation will delete
	    the old database tables.</li>
	    <li>Although it should not happen, it is possible that an attempt to install Remository will fail.
	    In this situation, the installer often does not tidy up, and leaves the program files in place.
	    It is then impossibe to either install or uninstall Remository.  The "Remove Remository program files"
	    can be used to try to rectify this situation.</li>
	    <li>When you convert the database to version 3.20, the conversion process can be repeated
	    as many times as required.  That is because the new database tables are all distinct from
	    the old database tables, and the old database tables are left in place.  Naturally, any
	    changes made to the new database tables are lost if the conversion is done again.  But leaving
	    the old tables in place makes the upgrade safer, as it can be rolled back if necessary.  Once
	    you are completely happy with version 3.20 or later, the old tables can be deleted by using
	    the "Remove pre 3.20 Remository Database tables" option.</li>
	    </ul>
	    <?php
	    break;
	case 'mode':
	    $modepath1 = $mosConfig_absolute_path.'/components/com_remository/';
	    echo $modepath1.'<br/>';
	    $modepath2 = $mosConfig_absolute_path.'/administrator/components/com_remository/';
	    echo $modepath2.'<br/>';
	    echo 'The above directories and all contained files and directories will have their permissions set to 0644/0755.<br/>';
   		if ($kill == 'yes') {
		    permission_all_from_dir($modepath1);
		    permission_all_from_dir($modepath2);
		    @chmod($mosConfig_absolute_path.'/components/com_remository/com_remository_settings.php',0707);
		    if (isset($Down_Path)) @chmod($Down_Path,0707);
		    if (isset($Up_Path)) @chmod($Up_Path,0707);
		}
   		else {
   			?>
   			Do you want this operation to be carried out?<br/>
   			If you are sure you want to set file permissions in the directories listed above, then
   			<a href="<?php echo $mosConfig_live_site; ?>/administrator/index2.php?option=com_mremository&amp;act=mode&amp;kill=yes">
   			click on this link</a>
   			<?php
	   	}
	    break;
	case 'killdb2':
	    $tablenames = array('#__downloads','#__downloads_category','#__downloads_comments','#__downloads_uploads','#__downloads_folders','#__downloads_votes');
	    foreach ($tablenames as $table) {
    		$sql = "DROP TABLE $table";
    		echo $sql.'<br/>';
    	}
    	echo '#_ will be replaced by your database prefix <br/>';
   		if ($kill == 'yes') {
   			foreach ($tablenames as $table) {
    			$sql = "DROP TABLE $table";
				$database->setQuery($sql);
				if (!$database->query()) {
					echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				}
			}
			echo '<br/>Database tables removed, subject to any error message reported above';
		}
   		else {
   			?>
   			Do you want the above operations to be carried out?<br/>
   			If you are sure you want to drop tables from the database as listed above, then
   			<a href="<?php echo $mosConfig_live_site; ?>/administrator/index2.php?option=com_mremository&amp;act=killdb2&amp;kill=yes">
   			click on this link</a>
   			<?php
   		}
	    break;
	case 'killdb3':
	    $tablenames = array('#__downloads_files','#__downloads_containers','#__downloads_reviews','#__downloads_structure','#__downloads_log','#__downloads_temp');
	    foreach ($tablenames as $table) {
    		$sql = "DROP TABLE $table";
    		echo $sql.'<br/>';
    	}
    	echo '#_ will be replaced by your database prefix <br/>';
   		if ($kill == 'yes') {
   			foreach ($tablenames as $table) {
    			$sql = "DROP TABLE $table";
				$database->setQuery($sql);
				if (!$database->query()) {
					echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				}
			}
			echo '<br/>Database tables removed, subject to any error message reported above';
		}
   		else {
   			?>
   			Do you want the above operations to be carried out?<br/>
   			If you are sure you want to drop tables from the database as listed above, then
   			<a href="<?php echo $mosConfig_live_site; ?>/administrator/index2.php?option=com_mremository&amp;act=killdb3&amp;kill=yes">
   			click on this link</a>
   			<?php
   		}
	    break;
	case 'killdb':
	    $sql = 'SHOW TABLES';
	    remositoryRepository::doSQL($sql);
	    $tablenames = $database->loadResultArray();
	    foreach ($tablenames as $table) {
	    	$splitname = explode('_',$table);
	    	if (isset($splitname[1]) AND $splitname[1] == 'downloads') {
	    		$sql = "DROP TABLE $table";
	    		echo $sql.'<br/>';
	    	}
	    }
   		if ($kill == 'yes') {
		    foreach ($tablenames as $table) {
		    	$splitname = explode('_',$table);
		    	if (isset($splitname[1]) AND $splitname[1] == 'downloads') {
		    		$sql = "DROP TABLE $table";
					$database->setQuery($sql);
					if (!$database->query()) {
						echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
					}
				}
			}
			echo '<br/>Database tables removed, subject to any error message reported above';
		}
   		else {
   			?>
   			Do you want the above operations to be carried out?<br/>
   			If you are sure you want to drop tables from the database as listed above, then
   			<a href="<?php echo $mosConfig_live_site; ?>/administrator/index2.php?option=com_mremository&amp;act=killdb&amp;kill=yes">
   			click on this link</a>
   			<?php
    	}
	    break;
	case 'killcomp':
	case 'upgrade':
	    $sql = 'DELETE FROM `#__components` WHERE `option`="com_remository"';
	    echo $sql.'<br/>';
	    echo 'The above command will remove Remository from the Mambo components table.</br>';
   		if ($kill == 'yes') {
			remositoryRepository::doSQL($sql);
			echo '<br/>Database tables entries removed, subject to any error message reported above';
		}
   		else {
   			?>
   			Do you want the above operation to be carried out?<br/>
   			If you are sure you want to drop table entries from the database as listed above, then
   			<a href="<?php echo $mosConfig_live_site; ?>/administrator/index2.php?option=com_mremository&amp;act=killcomp&amp;kill=yes">
   			click on this link</a>
   			<?php
	   	}
		if ($act != 'upgrade') break;
	case 'killprogram':
	    $delpath1 = $mosConfig_absolute_path.'/components/com_remository/';
	    echo $delpath1.'<br/>';
	    $delpath2 = $mosConfig_absolute_path.'/administrator/components/com_remository/';
	    echo $delpath2.'<br/>';
	    echo 'The above directories and all contained files and directories will be deleted.<br/>';
   		if ($kill == 'yes') {
		    delete_all_from_dir($delpath1);
		    if (file_exists($delpath1)) {
				echo 'Sorry, the deletion has not completed successfully<br/>';
				echo 'There is still a directory at '.$delpath1;
			}
		    else echo $delpath1.' was successfully removed<br/>';
		    delete_all_from_dir($delpath2);
		    if (file_exists($delpath2)) {
				echo 'Sorry, the deletion has not completed successfully<br/>';
				echo 'There is still a directory at '.$delpath2.'<br/>';
			}
		    else echo $delpath2.' was successfully removed<br/>';
		}
   		else {
   			?>
   			Do you want this operation to be carried out?<br/>
   			If you are sure you want to delete directories and files as listed above, then
   			<a href="<?php echo $mosConfig_live_site; ?>/administrator/index2.php?option=com_mremository&amp;act=killprogram&amp;kill=yes">
   			click on this link</a>
   			<?php
	   	}
	    break;
	case 'killmenu':
	    $sql = 'DELETE FROM `#__menu` WHERE `link`="index.php?option=com_remository"';
	    echo $sql.'<br/>';
	    echo 'The above command will remove Remository from the Mambo menu items table.</br>';
   		if ($kill == 'yes') {
			remositoryRepository::doSQL($sql);
			echo '<br/>Database tables entries removed, subject to any error message reported above';
		}
   		else {
   			?>
   			Do you want the above operation to be carried out?<br/>
   			If you are sure you want to drop table entries from the database as listed above, then
   			<a href="<?php echo $mosConfig_live_site; ?>/administrator/index2.php?option=com_mremository&amp;act=killmenu&amp;kill=yes">
   			click on this link</a>
   			<?php
	   	}
	    break;
	case 'killdown':
	    if (isset($Down_Path)) {
	    	echo $Down_Path.'<br/>';
		    echo 'The above directory and all contained files and directories will be deleted.<br/>';
	   		if ($kill == 'yes') {
			    delete_all_from_dir($Down_Path.'/');
			    if (file_exists($Down_Path)) {
					echo 'Sorry, the deletion has not completed successfully<br/>';
					echo 'There is still a directory at '.$Down_Path;
				}
			    else echo $Down_Path.' was successfully removed<br/>';
			}
	   		else {
	   			?>
	   			Do you want this operation to be carried out?<br/>
	   			If you are sure you want to delete directories and files as listed above, then
	   			<a href="<?php echo $mosConfig_live_site; ?>/administrator/index2.php?option=com_mremository&amp;act=killdown&amp;kill=yes">
	   			click on this link</a>
	   			<?php
		   	}
	    }
	    else echo 'There is no path set for downloads.  Perhaps the Remository settings have been deleted?';
	    break;
	default:
	    die('Unexpected action - internal error');
}

function delete_all_from_dir($Dir){
	// delete everything in the directory
	if ($handle = @opendir($Dir)) {
		while (($file = readdir($handle)) !== false) {
			if ($file == '.' || $file == '..') continue;
			$newpath = $Dir.$file;
			if (is_dir($newpath)) {
				// call self for this directory
				delete_all_from_dir($newpath.'/');
			}
			else {
				chmod($newpath,0700);
				unlink($newpath); // remove this file
			}
		}
	}
	@closedir($handle);
	chmod($Dir,0700);
	rmdir($Dir);
}

function permission_all_from_dir($Dir){
	// delete everything in the directory
	if ($handle = @opendir($Dir)) {
		while (($file = readdir($handle)) !== false) {
			if ($file == '.' || $file == '..') continue;
			$newpath = $Dir.$file;
			if (is_dir($newpath)) permission_all_from_dir($newpath.'/');
			else {
				chmod($newpath,0644);
				echo "chmod $newpath 0644 <br/>";
			}
		}
	}
	@closedir($handle);
	chmod($Dir,0755);
	echo "chmod $Dir 0755 <br/>";
}

function remove_magic_quotes ($array) {
	foreach ($array as $k => $v) {
		if (is_array($v)) $array[$k] = remove_magic_quotes($v);
		else $array[$k] = stripslashes($v);
	}
	return $array;
}

function sayUserName ($filepath) {
	$userid = fileowner($filepath);
	$userinfo = posix_getpwuid ( $userid );
	echo 'Path='.$filepath.' UserName='.$userinfo['name'].'<br/>';
}


?>
