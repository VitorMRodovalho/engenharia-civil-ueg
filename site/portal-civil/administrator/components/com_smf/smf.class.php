<?php
/**
* @version $Id: smf.class.php,v 1.3 2005/03/28 01:13:25 Cowboy1015 Exp $
* @package com_Joomla_smf_forum
* @copyright (C) JoomlaHacks.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Visit JoomlaHacks.com for more Joomla hacks!
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* @package Mambo_4.5.1
*/
class mosSMF {

	var $MAMBO_SMF_BEGIN 	= "<!-- Begin Joomla-SMF -->";
	var $MAMBO_SMF_END 		= "<!-- End Joomla-SMF -->";
	var $MAMBOHACKS			= "//JOOMLAHACKS.COM";
	function getCode() {

		return ""
		. $this->MAMBO_SMF_BEGIN."\n"
		. "<?php\n"
		. "define('_MAMBO_SMF', 1);\n"
		. "global \$sc, \$context;\n"
		. "if (!defined('SMF')) {\n"
    	. "    if (file_exists(\$mosConfig_absolute_path.\"/administrator/components/com_smf/config.smf.php\"))\n"
        . "        require_once (\"administrator/components/com_smf/config.smf.php\");\n"
    	. "    if (file_exists(\$smf_path.\"/SSI.php\"))\n"
    	. "        require_once (\$smf_path.\"/SSI.php\");\n"
		. "}\n"
		. "\$sc = &\$context['session_id'];\n"
		. "\$_SESSION['USER_AGENT'] = \$_SERVER['HTTP_USER_AGENT'];\n"
		. "?>\n"
		. $this->MAMBO_SMF_END."\n\n";
	}


	/*
	* put the require code in mambo
	*/
	function oldRequireCode() {
		return "require_once( 'configuration.php' );";
	}
	function newRequireCode() {
		return '
'.$this->oldRequireCode(). '
'.$this->MAMBOHACKS. ' : require SSI is causing havoc to Joomla, so move it elsewhere
if (file_exists($mosConfig_absolute_path."/administrator/components/com_smf/config.smf.php")) {
	global $context;
	require_once ($mosConfig_absolute_path."/administrator/components/com_smf/config.smf.php");
	require_once ($mosConfig_absolute_path."/administrator/components/com_smf/functions.smf.php");
	saveVars($savedVars);
}
		';

	}

	/*
	* patch the login part of mambo code
	*/
	function oldLoginCode() {
		return '$mainframe->login();';
	}
	function newLoginCode() {
		return ""
		. "\n\t".$this->MAMBOHACKS
		. "\n\t//".$this->oldLoginCode()
		. "\n\t"."require_once (\$smf_path.'/SSI.php');"
		. "\n\t"."\$_SESSION['USER_AGENT'] = \$_SERVER['HTTP_USER_AGENT'];"
		. "\n\t"."\$_SESSION['_FROM_MOS'] = true;"
		. "\n\t"."doMamboSMF(true);"
		. "\n\n\t";
	}

	/* do not logout from mambo coz mos kills the session w/c invalidates smf logout
	*/
	function oldLogoutCode() {
		return "\$mainframe->logout();";
	}
	function newLogoutCode() {
		return ""
		. "\n\n\t".$this->MAMBOHACKS
		. "\n\t"."//".$this->oldLogoutCode()
		. "\n\t"."require_once (\$smf_path.'/SSI.php');"
		. "\n\t"."mosRedirect( 'index.php?option=com_smf&action=logout;sesc='.\$context['session_id'] );"
		. "\n";
	}

	/* restores saved $_POST['message'] coz mos strips tags
	*/
	function oldMessageCode() {
		return "\$message = mosGetParam( \$_POST, 'message', 0 );";

	}
	function newMessageCode() {
		return ""
		. "\n\n".$this->MAMBOHACKS
		. "\n".$this->oldMessageCode()
		. "\n"."restoreVars(\$savedVars);"
		. "\n";
	}

	/*
	* get the code and patch it with condition to fix SMF error warning in DB
	*/
	function oldHeaderCode() {

		return "
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
";

	}
	function newHeaderCode() {
		return "
".$this->MAMBOHACKS."
if (!headers_sent()) {
".$this->oldHeaderCode()."
}
		";
	}

	/*
	*  patch smf index
	*/
	function oldSMFCodeLine() {
		return "define('WIRELESS', isset(\$_REQUEST['wap']) || isset(\$_REQUEST['wap2']) || isset(\$_REQUEST['imode']));";
	}
	function newSMFCodeLine() {
		return "
".$this->MAMBOHACKS."
if (defined('_VALID_MOS')) {
	\$modSettings['integrate_login'] 			= 'integrateLogin';
	\$modSettings['integrate_redirect'] 		= 'integrateRedirect';
	\$modSettings['integrate_exit'] 			= 'integrateExit';
	\$modSettings['enableCompressedOutput'] 	= '0';
	error_reporting(E_ALL ^ E_NOTICE);
}
".$this->oldSMFCodeLine()."
		";
	}

	/*
	*  patch smf index
	*/
	function oldSubsPostCodeLine() {
		return '$mail_result = true;';
	}

	function newSubsPostCodeLine() {
		return '
	'.$this->oldSubsPostCodeLine(). '
	'.$this->MAMBOHACKS. ' : fix links
	global $mosurl;
	$message = str_replace($scripturl."?", $mosurl, $message);';
	}

}

?>