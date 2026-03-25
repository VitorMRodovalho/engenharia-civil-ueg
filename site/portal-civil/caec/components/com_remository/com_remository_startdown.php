<?php

// Part of Remository, copyright (c) Martin Brampton 2005.
// For further information please refer to http://www.remository.com

define( "_VALID_MOS", 1 );
define("_LOG_DOWNLOAD", 1);

// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: David Irvine <dave@codexweb.co.za>                          |
// |          Aidan Lister <aidan@php.net>                                |
// +----------------------------------------------------------------------+
//
// $Id: html_entity_decode.php,v 1.6 2004/11/14 16:10:50 aidan Exp $


if (!defined('ENT_NOQUOTES')) {
    define('ENT_NOQUOTES', 0);
}

if (!defined('ENT_COMPAT')) {
    define('ENT_COMPAT', 2);
}

if (!defined('ENT_QUOTES')) {
    define('ENT_QUOTES', 3);
}


/**
 * Replace html_entity_decode()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @link        http://php.net/function.html_entity_decode
 * @author      David Irvine <dave@codexweb.co.za>
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.6 $
 * @since       PHP 4.3.0
 * @internal    Setting the charset will not do anything
 * @require     PHP 4.0.1 (trigger_error)
 */
if (!function_exists('html_entity_decode')) {
    function html_entity_decode($string, $quote_style = ENT_COMPAT, $charset = null)
    {
        if (!is_int($quote_style)) {
            trigger_error('html_entity_decode() expects parameter 2 to be long, ' . gettype($quote_style) . ' given', E_USER_WARNING);
            return;
        }

        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);

        // Add single quote to translation table;
        $trans_tbl['&#039;'] = '\'';

        // Not translating double quotes
        if ($quote_style & ENT_NOQUOTES) {
            // Remove double quote from translation table
            unset($trans_tbl['&quot;']);
        }

        return strtr($string, $trans_tbl);
    }
}

// End of PHP group code for html_entity_decode

require('../../configuration.php');
require('com_remository_settings.php');
if(file_exists($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php')) require($mosConfig_absolute_path.'/components/com_remository/language/'.$mosConfig_lang.'.php');
else require($mosConfig_absolute_path.'/components/com_remository/language/english.php');
if(file_exists($mosConfig_absolute_path . "/includes/database.php")){
  	require_once($mosConfig_absolute_path . "/includes/database.php");
//    require($mosConfig_absolute_path . "/includes/mambo.php");
}
else {
	if(file_exists($mosConfig_absolute_path . "/classes/database.php")){
	  require_once($mosConfig_absolute_path . "/classes/database.php");
//	  require($mosConfig_absolute_path . "/classes/mambo.php");
	}
}

if ($mosConfig_offline == 1){
	include( '../../offline.php' );
	exit();
}

$database = new database( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );

if (isset($_REQUEST['id'])) $id=$_REQUEST['id'];
else $id = 0;
if (isset($_REQUEST['chk'])) $chk=$_REQUEST['chk'];
else $chk = '';
if (isset($_REQUEST['userid'])) $userid=$_REQUEST['userid'];
else $userid = 0;

$chk0 = md5($Time_Stamp.$mosConfig_absolute_path.date('md').$id.'startdown');
if ($id == 0 OR $chk<>$chk0){
	echo _DOWN_LEECH_WARN;
	?>
	<br>&nbsp;<br><a href="../../index.php?option=com_remository&Itemid=<?php echo $Itemid;?>"><img src="<?php echo $mosConfig_live_site;?>/components/com_remository/images/gohome.png" width="16" height="16" border="0" align="absmiddle"> <?php echo _MAIN_DOWNLOADS; ?></a>
	<?php
	exit;
}

$sql = "SELECT realname, islocal, url, filepath FROM #__downloads_files WHERE id = $id";
$database->setQuery( $sql );
$fileinfo=null;
$database->loadObject( $fileinfo );

$nofile = false;
clearstatcache();
if ($fileinfo->islocal) {
	if ($fileinfo->filepath) $downpath = $fileinfo->filepath.$fileinfo->realname;
	else $downpath = $Down_Path.'/'.$fileinfo->realname;
	if(!file_exists($downpath))$nofile = true;
	else {
		if ($Anti_Leach) $displayname = substr($fileinfo->realname, 8);
		else $displayname = $fileinfo->realname;
		//this fixes the single quotes (apostrophes)
		$displayname = html_entity_decode($displayname,ENT_QUOTES);
		$downpath = html_entity_decode($downpath,ENT_QUOTES);
		$file_extension = GetExt ($displayname);
		$len = filesize($downpath);
	}
}
else $len = 0;

if ($nofile) {
	echo _DOWN_FILE_NOTFOUND;
	$database->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_remository'");
	$Itemid = $database->loadResult();
	?>
	<br>&nbsp;<br><a href=../../index.php?option=com_remository&Itemid=<?php echo $Itemid;?>><img src="<?php echo $mosConfig_live_site;?>/components/com_remository/images/gohome.png" width="16" height="16" border="0" align="absmiddle"> <?php echo _MAIN_DOWNLOADS; ?></a>
	<?php
	exit;
}

//Update download count
$sql = "UPDATE #__downloads_files SET downloads=downloads+1 WHERE id = $id";
$database->setQuery( $sql );
$database->query();
$type = _LOG_DOWNLOAD;
$ip = $_SERVER['REMOTE_ADDR'];
$timestamp = date('Y-m-d H:i:s');
$sizekb = intval($len/1024);
$sql = "INSERT INTO #__downloads_log (type, date, userid, fileid, value, ipaddress) VALUES ($type, '$timestamp', '$userid', $id, '$sizekb', '$ip')";
$database->setQuery($sql);
$database->query();

if (!$fileinfo->islocal) {
	$slimurl = trim($fileinfo->url);
	header("Location:$slimurl");
	exit;
}

//This will set the Content-Type to the appropriate setting for the file
switch( $file_extension ) {
	 case "pdf": $ctype="application/pdf"; break;
     case "exe": $ctype="application/octet-stream"; break;
     case "zip": $ctype="application/zip"; break;
     case "doc": $ctype="application/msword"; break;
     case "xls": $ctype="application/vnd.ms-excel"; break;
     case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
     case "gif": $ctype="image/gif"; break;
     case "png": $ctype="image/png"; break;
     case "jpeg":
     case "jpg": $ctype="image/jpg"; break;
     case "mp3": $ctype="audio/mpeg"; break;
     case "wav": $ctype="audio/x-wav"; break;
     case "mpeg":
     case "mpg":
     case "mpe": $ctype="video/mpeg"; break;
     case "mov": $ctype="video/quicktime"; break;
     case "avi": $ctype="video/x-msvideo"; break;

     //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
     case "php":
     case "htm":
     case "html": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;

     default: $ctype="application/force-download";
}

//Begin writing headers
header("Cache-Control: max-age=60");
header("Cache-Control: private");
header("Content-Description: File Transfer");

//Use the switch-generated Content-Type
header("Content-Type: $ctype");

//Force the download
header("Content-Disposition: attachment; filename=\"$displayname\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$len);

@set_time_limit(0);
$fp = @fopen($downpath, "rb");
set_magic_quotes_runtime(0);
$chunksize = 1*(512*1024); // how many bytes per chunk
while($fp && !feof($fp)) {
	$buffer = fread($fp, $chunksize);
	print $buffer;
	flush();
	sleep(1);
}
set_magic_quotes_runtime(get_magic_quotes_gpc());
fclose($fp);

exit;


function GetExt($Filename) {
  $RetVal = explode ( '.', $Filename);
  return strtolower($RetVal[count($RetVal)-1]);
}


?>
