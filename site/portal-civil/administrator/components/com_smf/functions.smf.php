<?php
/**
* @version $Id: functions.smf.php,v 1.2 2005/03/28 01:13:25 Cowboy1015 Exp $
* @package com_Joomla_smf_forum
* @copyright (C) JoomlaHacks.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Visit JoomlaHacks.com for more Joomla hacks!
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function saveVars(&$arr) {
	//$arr['REQUEST'] 			= $_REQUEST;
	$arr['POST'] 				= $_POST;
	//$arr['HTTP_POST_VARS'] 		= $HTTP_POST_VARS;
	//$arr['HTTP_POST_FILES']		= $HTTP_POST_FILES;
	//$arr['HTTP_GET_VARS'] 		= $HTTP_GET_VARS;
	//$arr['HTTP_COOKIE_VARS']	= $HTTP_COOKIE_VARS;
}

function restoreVars(&$arr) {
	//comment this coz SEO is breaking / i don't remember the consequence
	//$_REQUEST 				= $arr['REQUEST'];
	//$_POST 					= $arr['POST'];
	//$HTTP_POST_VARS 		= $arr['HTTP_POST_VARS'];
	//$HTTP_POST_FILES 		= $arr['HTTP_POST_FILES'];
	//$HTTP_GET_VARS 			= $arr['HTTP_GET_VARS'];
	//$HTTP_COOKIE_VARS 		= $arr['HTTP_COOKIE_VARS'];

	//mosGetParam stripped the tags! something wrong with the function
	$_POST['message'] 		= $arr['POST']['message'];

	unset($arr);
}

function paintGreen($text) {
	return '<font color="green">'.$text.'</font>';
}

function paintRed($text) {
	return '<font color="red"><b>'.$text.'</b></font>';
}

function chmodFiles($mode) {
	global $mosConfig_absolute_path;

	$configFile		= $mosConfig_absolute_path."/administrator/components/com_smf/config.smf.php";
	$cacheDir 		= $mosConfig_absolute_path."/cache";
    $mamboIndex		= $mosConfig_absolute_path."/index.php";

	if (!chmod($configFile, intval($mode, 8))) return false;
	if (!chmod($cacheDir , intval($mode, 8))) return false;
	if (!chmod($mosConfig_absolute_path, intval($mode, 8))) return false;
	if (!chmod($mamboIndex, intval($mode, 8))) return false;

	return true;
}

function isSMFModPublished() {
	global $database, $mosConfig_absolute_path, $mosConfig_db;
	include_once( $mosConfig_absolute_path . '/administrator/components/com_smf/config.smf.php' );
	$database->setQuery("SELECT module FROM #__modules WHERE module = 'mod_smf_login' AND published = 1 LIMIT 1");
	$row = $database->loadResult();
	return $row;
	if($row) {
		return true;
	}
	return false;
}

function isSMFModInstalled() {
	global $database, $mosConfig_absolute_path, $mosConfig_db;
	include_once( $mosConfig_absolute_path . '/administrator/components/com_smf/config.smf.php' );
	$database->setQuery("SELECT module FROM #__modules WHERE module = 'mod_smf_login' LIMIT 1");
	$row = $database->loadResult();
	return $row;
	if($row) {
		return true;
	}
	return false;
}

function isSMFInstalled()
{
	global $database, $mosConfig_absolute_path, $mosConfig_db, $smf_prefix;
	if ($smf_prefix == '' || empty($smf_prefix) || substr($smf_prefix,strlen($smf_prefix)-1) != "_") return false;
	include_once( $mosConfig_absolute_path . '/administrator/components/com_smf/config.smf.php' );
	$result = mysql_list_tables($mosConfig_db);
	while ($row = mysql_fetch_row($result)) {
      	if ((strpos($row[0],$smf_prefix)!==false)&&(strpos($row[0],$smf_prefix)==0)) {
      		return true;
      	}
    }
    return false;
}

function getTemplateFile() {
	global $database, $mosConfig_absolute_path;

	$database->setQuery("SELECT template FROM #__templates_menu WHERE client_id = 0");
	$cur_template = $database->loadResult();

	return $mosConfig_absolute_path .'/templates/'. $cur_template .'/index.php';
}

function readTemplate() {
	global $database, $mosConfig_absolute_path;

	$file = getTemplateFile();

	if ( $fp = fopen( $file, 'r' ) ) {
		$content = fread( $fp, filesize( $file ) );
		fclose( $fp );
	}
	else {
		mosRedirect( 'index2.php?option=com_installer&element=component', 'Operation Failed: Could not open'. $file );
	}
	return $content;
}

function isCodeInserted() {
	global $database, $mosConfig_absolute_path, $mosSMF;

	$pos = strpos(readTemplate(), $mosSMF->MAMBO_SMF_BEGIN);
	if (!($pos === false) && $pos >= 0) {
		return true;
	}
	return false;
}

function insertCode() {
	global $mosSMF;

	$content = $mosSMF->getCode().readTemplate();
	$file = getTemplateFile();

	$oldperms = fileperms($file);
	@chmod($file, $oldperms | 0222);


	if ($fp = fopen ($file, 'w')) {
		fputs( $fp, $content );
		fclose( $fp );
		@chmod($file, $oldperms);
		return true;
	}
	else {
		return false;
	}

}

function removeCode() {
	global $mosSMF;

	$content = $mosSMF->getCode().readTemplate();
	$file = getTemplateFile();

	if (isCodeInserted()) {

		$content = str_replace($mosSMF->getCode(),'',$content);

		$oldperms = fileperms($file);
		@chmod($file, $oldperms | 0222);

		if ($fp = fopen ($file, 'w')) {
			fputs( $fp, $content );
			fclose( $fp );
			@chmod($file, $oldperms);
		}

	}
}

function isCBLogin() {
	global $database;
	/*1.3b3-6*/
	$database->setQuery("SELECT module FROM #__modules WHERE module LIKE 'mod_comprofiler%' AND published=1");
	if ($database->loadResult()) {
		return true;
	}
	return false;
}

function fixMamboUrls(&$buffer, $mosurl)
{
	//define('DEBUG',1);
	global $mosConfig_live_site, $mosConfig_sef, $boardurl, $smf_path, $cbprofile, $wrapped, $registration_module;

	if ($registration_module == "mambo") {
		$registerlink = $mosConfig_live_site.'/index.php?option=com_registration&task=register';
		$reminderlink = $mosConfig_live_site.'/index.php?option=com_registration&task=lostPassword';
	}
	elseif ($registration_module == "cb") {
		$registerlink = $mosConfig_live_site.'/index.php?option=com_comprofiler&task=registers';
		$reminderlink = $mosConfig_live_site.'/index.php?option=com_comprofiler&task=lostPassword';
	}
	if ($registration_module != "smf" || $registration_module == "") {
		$search = array(
			$boardurl.'/index.php?action=register',
			$boardurl.'/index.php?action=reminder',
		);
		$replace = array(
			$registerlink,
			$reminderlink,
		);
		$buffer = str_replace($search, $replace, $buffer);
	}

	/*1.3b2-5*/
	if ($cbprofile==1) {
		$buffer = str_replace($boardurl.'/index.php?action=profile"', $mosConfig_live_site.'/index.php?option=com_comprofiler"', $buffer);
	}

	unset($registerlink);
	unset($reminderlink);
	unset($search);
	unset($replace);

	$oldurl = array();
	$newurl = array();

	if (defined('DEBUG')) $debug = "";
	$images = array( get('666f72756d5f636f70797269676874'), _SMF_ , get('5f534d46315f'));
	global $$images[0];
  	$types = array("form action", "href");
  	while(list(,$type) = each($types)) {
  	    $innerT = '.+?';
       	preg_match_all ("|$type\=([\"'`])(".$innerT.")\\1|i", $buffer, &$matches);
       	$links = array_unique($matches[0]);
       	if (defined('DEBUG')) $debug .= $type."<BR/>"; $i = 0;
       	foreach ($links as $link) {
       		if (defined('DEBUG')) $i++;
       		$oldurl[] = "/". preg_quote ($link, "/")."/";

       		$tmpurl = str_replace($boardurl."/index.php", $mosurl, $link);
			$tmpurl = str_replace("&?","&",$tmpurl);

			/*
			if ($tmpurl == 'href="#"') {
				$tmpurl = "href=\"".$mosConfig_live_site.$_SERVER['REQUEST_URI']."&#\"";
			}*/

			$pos = strpos($tmpurl,'href="#');
				if ($pos >= 0 && !($pos === false)) {
				$tmpurl = str_replace("href=\"#", "href=\"".$mosConfig_live_site.$_SERVER['REQUEST_URI'].($mosConfig_sef?"&":"")."#", $tmpurl);
			}

			$pos = strpos($tmpurl,$mosConfig_live_site."/index.php");
			if ($mosConfig_sef == '1' && $pos >= 0 && !($pos === false)){
				$search	= array(
						";",
						"&desc",
						"&all",
						"post&calendar",
						"search&advanced",
						"&quote&u=",
						"&poll",
						"0&viewResults",
						"unread&"
					);
				$replace = array(
						"&",
						";desc",
						";all",
						"post;calendar",
						"search;advanced",
						";quote/u,",
						";poll",
						"0;viewResults",
						"unread;"
					);

				$tmpurl = str_replace($search, $replace, $tmpurl);
				unset($search);
				unset($replace);

				$tmpurl = sefRelToAbs(substr($tmpurl, strlen($mosConfig_live_site) + strpos($tmpurl,"http") + 1));
				$tmpurl = $type."=\"".$tmpurl;
				$tmpurl = (substr($tmpurl,strlen($tmpurl)-1) == "/" ? substr($tmpurl,0,strlen($tmpurl)-1) : $tmpurl);


				//if (substr($tmpurl,0,4) != "http" && !$wrapped) {
				//	$tmpurl = $mosConfig_live_site."/".$tmpurl;
				//}
			}

       		$newurl[] = str_replace("&\"","\"",$tmpurl);
			if (defined('DEBUG')) $debug .= $oldurl[$i-1]."<br/>".$newurl[$i-1]."<br/><br/>";

       	}
       	unset($links, $matches, $tmpurl);
   	}

   	$buffer = preg_replace($oldurl, $newurl, $buffer);
	$buffer = str_replace("hashLoginPassword(this);","this.passwd.value = this.passwrd.value;  hashLoginPassword(this);",$buffer);
	$buffer = str_replace('<input type="hidden" name="hash_passwrd" value="" />','<input type="hidden" name="hash_passwrd" value="" /><input type="hidden" name="passwd" value="" />',$buffer);
	$buffer = str_replace($$images[0],$images[2]($$images[0], $images[1]), $buffer); unset($images);
	//replace the jump smf_scripturl
	if (!isset($_REQUEST['action'])) {
		$buffer = str_replace('var smf_scripturl = "'.$boardurl.'/index.php";','var smf_scripturl = "'.$mosurl.'";',$buffer);
		$buffer = str_replace("'".$boardurl."/index.php' + this.form.jumpto.options","smf_scripturl + this.form.jumpto.options",$buffer);
	}

   	if (defined('DEBUG')) $buffer .= $debug;
	unset($oldurl, $newurl, $buffer);
   	return;
}

function isMamboIndexPatched() {
	global $mosConfig_absolute_path, $mosSMF;

	$file = $mosConfig_absolute_path."/index.php";

	if ( $fp = fopen( $file, 'r' ) ) {
		$content = fread( $fp, filesize( $file ) );
		fclose( $fp );
	}
	else {
		return false;
	}

	$pos = strpos($content, $mosSMF->MAMBOHACKS);
	if (!($pos === false) && $pos >= 0) {
		return true;
	}
	return false;
}

function patchMamboIndexFile(&$file) {
	global $mosConfig_absolute_path, $mosSMF;

	$file = $mosConfig_absolute_path."/index.php";

	if ( $fp = fopen( $file, 'r' ) ) {
		$content = fread( $fp, filesize( $file ) );
		$origcontent = $content;
		fclose( $fp );
	}
	else {
		return false;
	}

	$pos = strpos($content, $mosSMF->MAMBOHACKS);
	if (!($pos === false) && $pos >= 0) {
		return true;
	}

	$pos = strpos($content, $mosSMF->oldRequireCode());
	if (!($pos === false) && $pos >= 0) {
		$content = str_replace($mosSMF->oldRequireCode(),$mosSMF->newRequireCode(),$content);
	}
	else {
		return false;
	}

	$pos = strpos($content, $mosSMF->oldLoginCode());
	if (!($pos === false) && $pos >= 0) {
		$content = str_replace($mosSMF->oldLoginCode(),$mosSMF->newLoginCode(),$content);
	}
	else {
		return false;
	}

	$pos = strpos($content, $mosSMF->oldLogoutCode());
	if (!($pos === false) && $pos >= 0) {
		$content = str_replace($mosSMF->oldLogoutCode(),$mosSMF->newLogoutCode(),$content);
	}
	else {
		return false;
	}

	$pos = strpos($content, $mosSMF->oldMessageCode());
	if (!($pos === false) && $pos >= 0) {
		$content = str_replace($mosSMF->oldMessageCode(),$mosSMF->newMessageCode(),$content);
	}
	else {
		return false;
	}

	$content = preg_replace('/'.preg_quote($mosSMF->oldHeaderCode()).'/',$mosSMF->newHeaderCode(),$content);

	$bakfile = $mosConfig_absolute_path .'/index.php~jsfbak~';
	if (!(copy($file,$bakfile))) {
		return false;
	}

	$oldperms = fileperms($file);
	@chmod($file, $oldperms | 0222);

	if ($fp = fopen ($file, 'w')) {
		fputs( $fp, $content );
		fclose( $fp );
		@chmod($file, $oldperms);
		return true;
	}
	else {
		return false;
	}

}

function patchFile($file, $search, $replace) {
	global $mosSMF;

	if ( $fp = fopen( $file, 'r' ) ) {
		$content = fread( $fp, filesize( $file ) );
		$origcontent = $content;
		fclose( $fp );
	}
	else {
		return false;
	}

	$pos = strpos($content, $mosSMF->MAMBOHACKS);
	if (!($pos === false) && $pos >= 0) {
		return true;
	}

	$pos = strpos($content, $search);
	if (!($pos === false) && $pos >= 0) {
		$content = str_replace($search,$replace,$content);
	}
	else {
		return false;
	}

	if (!(copy($file,$file.'~jsfbak~'))) {
		return false;
	}

	$oldperms = fileperms($file);
	@chmod($file, $oldperms | 0222);

	if ($fp = fopen ($file, 'w')) {
		fputs( $fp, $content );
		fclose( $fp );
		@chmod($file, $oldperms);
		return true;
	}
	else {
		return false;
	}

}

function unpatchFile($file) {
	if (file_exists($file.'~jsfbak~')) {
		if (!copy($file.'~jsfbak~', $file)) {
   			return false;
		}
		unlink($file.'~jsfbak~');
		return true;
	}
	return false;
}

function _SMF1_($a, $b) {
	define('_SMF1_', 1);
	return $a.$b;
}

function param(&$param1, &$param2) {
	global $mosConfig_live_site;
	$param1 = (isset($_REQUEST['username']) ? $_REQUEST['username'] : $_REQUEST['user']);
	$param2 = (isset($_REQUEST['passwd']) ? $_REQUEST['passwd'] : $_REQUEST['passwrd']);
	return $mosConfig_live_site.'/index.php?option=com_smf&action=login2&hash_passwrd=0&user='.$param1.'&passwrd='.$param2;
}
function get($h) {return pack('H*',$h);}

function isPatched($file, $search) {
	if (!file_exists($file)) {
		return false;
	}
	if (!$fd=fopen ($file, "r")) {
		return false;
	}
	$contents = fread($fd, filesize($file));
	fclose($fd);
	$pos = strpos($contents,$search);
	if ($pos >= 0 && !($pos === false)) {
		return true;
	}
	return false;
}

function unpatchMamboIndexFile(&$file) {
	global $mosConfig_absolute_path, $mosSMF;

	$file = $mosConfig_absolute_path."/index.php";
	$bakfile = $mosConfig_absolute_path."/index.php~jsfbak~";

	if (file_exists($bakfile)) {
		if (!copy($bakfile, $file)) {
   			return false;
		}
		unlink($bakfile);
		return true;
	}
	return false;
}

function isMamboSMFUserSync() {
	global $database, $smf_prefix;

	$query = ""
		." SELECT count(*)"
		." FROM {$smf_prefix}members "
		." LEFT JOIN #__users ON #__users.username={$smf_prefix}members.memberName "
		." WHERE #__users.username IS NULL ";
	$database->setQuery( $query );
	if (intval($database->loadResult()) > 0) {
		return false;
	}
	if ($database->getErrorNum()) {
		return $database->stderr();
	}

	$query = ""
		." SELECT count(*)"
		." FROM #__users"
		." LEFT JOIN {$smf_prefix}members ON {$smf_prefix}members.memberName = #__users.username"
		." WHERE {$smf_prefix}members.memberName IS NULL";
	$database->setQuery( $query );
	if (intval($database->loadResult()) > 0) {
		return false;
	}

	if ($database->getErrorNum()) {
		return $database->stderr();
	}

	return true;
}

function syncSMFtoMambo() {
	global $database, $smf_prefix, $mosLang;

	$query = ""
		." SELECT memberName, realName, passwd, emailAddress, dateRegistered, is_activated "
		." FROM {$smf_prefix}members "
		." LEFT JOIN #__users ON #__users.username={$smf_prefix}members.memberName "
		." WHERE #__users.username IS NULL ";
	$database->setQuery( $query );

	$rows = $database->loadObjectList();

	if ($database->getErrorNum()) {
		return $database->stderr();
	}

	$count = 0;
	foreach ($rows as $row) {
		/*put any smf password, it will be updated to mambo password during login anyway*/
		$password = $row->passwd;
		if (!insertToMambo2($err, $row, $password)) {
			mosRedirect( "index2.php?option=com_smf&task=config", "Syncing username ".$row->memberName." ".$mosLang->MSG_FAILED."! ".$err);
		}
		$count++;
	}
	return $count;
}

function syncMamboToSMF() {
	global $database, $smf_prefix, $mosLang, $showemail, $smf_path;

	$query = ""
		." SELECT name, username, password, email, registerDate"
		." FROM #__users"
		." LEFT JOIN {$smf_prefix}members ON {$smf_prefix}members.memberName = #__users.username"
		." WHERE {$smf_prefix}members.memberName IS NULL";
	$database->setQuery( $query );

	$rows = $database->loadObjectList();

	if ($database->getErrorNum()) {
		return $database->stderr();
	}

	$count = 0;
	foreach ($rows as $row) {
		$query = ""
			." INSERT INTO {$smf_prefix}members (realName, memberName, emailAddress, passwd, dateRegistered, hideEmail)"
			." VALUES ('$row->name','$row->username','$row->email','mambohacks.com',UNIX_TIMESTAMP('".$row->registerDate."'), '$hideemail')";
		$database->setQuery($query);
		if (!$database->query()) {
			mosRedirect("index2.php?option=com_smf&task=config&mosmsg=Syncing username ".$row->username." ".$mosLang->MSG_FAILED."! ".$mosLang->MSG_CANNOT_COPY_USER_TO_SMF);
		}
		$count++;
	}

	$database->setQuery("SELECT COUNT(ID_MEMBER) FROM {$smf_prefix}members");
	$total = $database->loadResult();
	$database->setQuery("UPDATE {$smf_prefix}settings SET value='$total' WHERE variable='memberCount'");
	$database->query();

	return $count;
}

function doMamboSMF($mos) {
	global $mosConfig_live_site, $mosLang, $mainframe, $username, $password, $redirect;

	$ret = array (get('7265646972656374'),get('706172616d'),get('757365726e616d65'),get('70617373776f7264'));
	$$ret[0] = $ret[1]($$ret[2],$$ret[3]);

	$validcode = isValidUser($data, $username, $password);

	if ($validcode == 0) {
		// do this to prompt mos login error
		if ($mos) $mainframe->login();
		return;
	}

	if ($validcode == 1) {
		if ($mos) mosRedirect( $redirect );
	}

	if ($validcode == 2) {
		if (syncToSMF($err, $data, $password)){
			if ($mos) mosRedirect( $redirect );
			return;
		}
	}

	if ($validcode == 3) {
		if (syncToMOS($err, $data, $password)) {
			if ($mos) mosRedirect( $redirect );
			return;
		}
	}

	if ($validcode == 4) {
		if (unblockUser($err, $data)) {
			if ($mos) mosRedirect( $redirect );
			return;
		}
	}

	if ($mos) mosRedirect( "index.php?option=com_smf&task=login&mosmsg=", $mosLang->MSG_FAILED.": ".$err);
	return;
}

/*
*  returns: 0- both invalid, 1- both valid, 2-mos valid, 3-smf valid, 4-blocked
*/
function isValidUser(&$row, $username, $password) {
	global $database, $smf_prefix;

	//try first with MOS
	$sql = "
		SELECT jos.name, jos.username, jos.email, jos.registerDate, jos.block, jos.password,
		smf.memberName, smf.realName, smf.passwd, smf.emailAddress, smf.dateRegistered, smf.is_activated
		FROM #__users jos
		LEFT JOIN {$smf_prefix}members smf ON jos.username = smf.memberName
		WHERE jos.username = '$username'
		OR smf.memberName = '$username'
		LIMIT 1
		";

	$database->setQuery($sql);
	$database->loadObject( $row );
	if ($row != null) {
		$mos = ($row->password == md5($password));
		$smf = ($row->passwd == sha1(strtolower($username) . $password));
		if (!$mos && !$smf)
			return 0;
		if ($mos && $smf) {
			//this means user is activated (if not blocked, mos is 0, smf is 1)
			if ($row->block == $row->is_activated) {
				return 4;
			}
			return 1;
		}
		if ($mos)
			return 2;
		if ($smf)
			return 3;
	}

	//try with SMF
	$sql = "
		SELECT jos.name, jos.username, jos.email, jos.registerDate, jos.block, jos.password,
		smf.memberName, smf.realName, smf.passwd, smf.emailAddress, smf.dateRegistered, smf.is_activated
		FROM #__users jos
		RIGHT JOIN {$smf_prefix}members smf ON jos.username = smf.memberName
		WHERE jos.username = '$username'
		OR smf.memberName = '$username'
		LIMIT 1
		";
	$database->setQuery($sql);
	$database->loadObject( $row );
	if ($row != null) {
		$mos = ($row->password == md5($password));
		$smf = ($row->passwd == sha1(strtolower($username) . $password));
		if (!$mos && !$smf)
			return 0;
		if ($mos && $smf) {
			if ($row->block == "1" || $row->is_activated == "0") {
				return 4;
			}
			return 1;
		}
		if ($mos)
			return 2;
		if ($smf)
			return 3;
	}
}

function blockUser(&$err, $row) {
	global $database, $smf_prefix;

	$query = "
		UPDATE {$smf_prefix}members
		SET is_activated = '0'
		WHERE memberName = '$row->username'
		";
	$database->setQuery($query);
	$database->query();
	if ($database->getErrorNum()) {
		$err = $database->stderr();
		return false;
	}

	$query = "
		UPDATE #__users
		SET block = '1'
		WHERE username = '$row->username'
		";
	$database->setQuery($query);
	$database->query();
	if ($database->getErrorNum()) {
		$err = $database->stderr();
		return false;
	}

	return true;
}

function unblockUser(&$err, $row) {
	global $database, $smf_prefix;

	$query = "
		UPDATE {$smf_prefix}members
		SET is_activated = '1'
		WHERE memberName = '$row->username'
		";
	$database->setQuery($query);
	$database->query();
	if ($database->getErrorNum()) {
		$err = $database->stderr();
		return false;
	}

	$query = "
		UPDATE #__users
		SET block = '0'
		WHERE username = '$row->username'
		";
	$database->setQuery($query);
	$database->query();
	if ($database->getErrorNum()) {
		$err = $database->stderr();
		return false;
	}

	return true;
}

function syncToSMF(&$err, $row, $password) {
	global $database, $smf_prefix, $hideemail, $smf_path;

	//update smf password
	if ($row->memberName != "" && $row->passwd != sha1(strtolower($row->username) . $password)) {
		$query = "
			UPDATE {$smf_prefix}members
			SET passwd = '".sha1(strtolower($row->username) . $password)."'
			WHERE memberName = '$row->username'
			";
		$database->setQuery($query);
		$database->query();
		if ($database->getErrorNum()) {
			$err = $database->stderr();
			return false;
		}
		return true;
	}
	else {
		$query = "
			INSERT INTO {$smf_prefix}members
			(realName, memberName, emailAddress, passwd, dateRegistered, hideEmail)
			VALUES
			('$row->name','$row->username','$row->email','".sha1(strtolower($row->username) . $password)."',UNIX_TIMESTAMP('".$row->registerDate."'), '$hideemail')
			";
	}

	$database->setQuery($query);
	if (!$database->query()) {
		$err = $database->stderr();
		return false;
	}
	require_once($smf_path . '/Sources/Subs.php');
	updateStats('member');
	trackStats(array('registers' => '+')); /*1.3.1-1 set the count first*/
	trackStats();
	return true;
}

function syncToMOS(&$err, $row, $password) {
	global $database, $smf_prefix, $hideemail, $smf_path;

	//update smf password
	if ($row->username != "" && $row->password != md5($password)) {
		$query = "
			UPDATE #__users
			SET password = '".md5($password)."'
			WHERE username = '$row->memberName'
			";
		$database->setQuery($query);
		$database->query();
		if ($database->getErrorNum()) {
			$err = $database->stderr();
			return false;
		}
		return true;
	}
	else {
		if (!insertToMambo2($err, $row, $password))
			return false;
	}
	return true;
}

function fixIt($tmp){
	$tmp .= "<br/>Joomla Bridge by <a href=\"http://www.JoomlaHacks.com\" title=\"JoomlaHacks.com\" target=\"_blank\">JoomlaHacks.com</a>.";
	return $tmp;
}

function updateMOSfromSMF($username, $realname, $email) {
	global $database, $smf_prefix;
	/*
	$query = "SELECT realName, emailAddress, is_activated FROM {$smf_prefix}members WHERE memberName='".$username."'";
	$database->setQuery($query);
	$row = null;
	$database->loadObject( $row );
	if ($row == null)
		return false;
	*/
	$query = "UPDATE #__users SET name='".$realname."', email='".$email."', password='MAMBOHACKS'"
			." WHERE username='".$username."'";
	$database->setQuery($query);
	$database->query();
	if ($database->getErrorNum()) {
		mosRedirect("index.php?option=com_smf&task=login&mosmsg=".$database->stderr());
	}
}

function doSMFLogout(){
    global $context, $database, $smf_path;
    $smf_id = $context['user']['id'];
    if($smf_id > 0) {
        require_once($smf_path."/Sources/Subs-Auth.php");
        $database->setQuery("DELETE FROM smf_log_online WHERE ID_MEMBER = '".$smf_id."' LIMIT 1");
        $database->query();
        setLoginCookie(-3600, 0);
    }
}

function insertToMambo2(&$err, $data, $password) {
	global $database, $my, $acl;

	$row = new mosUser( $database );

	$user['name'] 		= $data->realName;
	$user['username'] 	= $data->memberName;
	$user['email'] 		= $data->emailAddress;
	$user['password'] 	= $password;

	if (!$row->bind( $user )) {
		$err = $row->getError();
		return false;
	}

	mosMakeHtmlSafe($row);
	$row->id 		= 0;
	$row->usertype 	= 'Registered';
	$row->gid 		= $acl->get_group_id('Registered','ARO');
	$row->activation= '';
	$row->block 	= ($data->is_activated ? 0 : 1);

	if (!$row->check()) {
		$err = $row->getError();
		return false;
	}

	$row->password = md5( $password );
	$row->registerDate = date("Y-m-d H:i:s",$data->dateRegistered);

	if (!$row->store()) {
		$err = $row->getError();
		return false;
	}

	$row->checkin();

	return true;
}

function integrateRedirect($setLocation, $refresh) {
	global $mosConfig_live_site, $my, $mainframe, $smf_redirect, $boardurl, $mosurl;
	include_once($smf_path."/Sources/Subs.php");

	trackStats(); //1.0-5

	//if in case the redirect is SMF url
	$setLocation = str_replace($boardurl."/index.php", $mosurl, $setLocation);

	$refresh = false;

	if (isset($_SESSION['_FROM_MOS']) && !$smf_redirect) {
		$setLocation = $mosConfig_live_site;
		unset($_SESSION['_FROM_MOS']);
	}
	if (!isset($_GET['expv']) && $_REQUEST['action'] == "logout")
		$setLocation = $mosConfig_live_site;

	$setLocation = str_replace("&?", "&", $setLocation);

	if ($_REQUEST['action'] == "logout" && $my->id)
		$mainframe->logout();

	if ($refresh)
		header('Refresh: 0; URL=' . strtr($setLocation, array(' ' => '%20', ';' => '%3b')));
	else
		header('Location: ' . str_replace(' ', '%20', $setLocation));

	exit;
}

function integrateExit($param) {
	global $mosurl, $context;

	if (defined('_VALID_MOS') && isset($context['sub_template'])
		&& ($context['sub_template'] == "kick_guest" || $context['sub_template'] == "fatal_error")) {
			global $scripturl;
			$_SESSION['MH_WARNING'] = ob_get_contents();
			ob_end_clean();
			header('Location: '.substr($mosurl, 0, strlen($mosurl)-1));
			exit;
	} else {
		return;
	}
}

/*
* called from SMF
*/
function integrateLogin($user, $pass, $cookietime) {
	global $mainframe, $my, $mosConfig_live_site;

	//try to sync smf user to mos : mos user cannot be sync to smf if they log from smf form (for now)
	doMamboSMF(false);

	if ($my->id <= 0) {
		$_POST['username'] = $user;
		// logging in from mambo, use request passwrd from redirect... else get the one from post.
		$_POST['passwd'] = (isset($_POST['hash_passwrd']) ? $_POST['passwd'] : $_REQUEST['passwrd'] );
		if ($_POST['passwd'] == "" && isset($_POST['passwrd'])) {
			$_POST['passwd'] = $_POST['passwrd'];
		}
		$mainframe->login();
		return;
	}
}

?>
