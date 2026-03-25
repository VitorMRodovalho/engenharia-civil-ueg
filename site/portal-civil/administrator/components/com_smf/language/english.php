<?php
/**
* @version $Id: english.php,v 1.2 2005/03/28 01:13:25 Cowboy Exp $
* @package com_mambo_smf_forum
* @copyright (C) JoomlaHacks.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Visit JoomlaHacks.com for more mambo hacks!
*/

class MamboSMFLanguage {

## MAIN COMPONENT ADMIN PAGE GEADER
##
var $HEADER_MAIN = 'Joomla-SMF Forum Configuration';

## TABS
##
var $TAB_CONFIGURATION_TITLE 		= "Configuration";
var $TAB_HELP 						= "Help";

## CONFIGURATION TAB CONSTANTS
##
var $FIELD_SMF_PATH_ABSOLUT			= "SMF Absolute Path:";
var $FIELD_SMF_DB_PREFIX			= "SMF Database Prefix:";
var $FIELD_MOS_DB_PREFIX			= "Joomla Database Prefix:";
var $FIELD_INTEGRATION_OPTION 		= "Integration Mode:";
var $FIELD_HIDE_EMAIL				= "Hide emails in SMF profile on sync:";
var $FIELD_CB_PROFILE				= "Redirect SMF profile link to CB:";
var $FIELD_VERSION_INFO				= "Version Info:";
var $FIELD_SMF_REDIRECT				= "Always redirect to forum on login:";

var $FIELD_VALUE_WRAPPED 			= "Wrapped";
var $FIELD_VALUE_UNWRAPPED 			= "Unwrapped";

var $HEADER_PERMISSION_SETTINGS		= "PERMISSION SETTINGS:";
var $HEADER_INSTALLATION_CHECKLIST	= "INSTALLATION CHECKLIST:";

var $MSG_MAKE_WRITEABLE				= "make all writeable";
var $MSG_MAKE_NOT_WRITEABLE			= "make all not writeable";
var $MSG_INSERTED					= "inserted";
var $MSG_INSTALLED					= "installed";
var $MSG_VERIFY						= "verify";
var $MSG_NOT_INSTALLED				= "not installed";
var $MSG_NOT_PUBLISHED				= "not published";
var $MSG_SYNC						= "click to sync";
var $MSG_PATCH						= "click to patch";
var $MSG_UNPATCH					= "un-patch";
var $MSG_APPLY						= "apply";
var $MSG_DONE						= "done";
var $MSG_INCORRECT_PREFIX			= "(incorrect prefix)";
var $MSG_INCORRECT_PATH				= "(incorrect path)";
var $MSG_SMF_MOD_NOT_INSTALLED		= "SMF index.php is not yet patched!";
var $MSG_SMF_VERSION_IS				= "SMF version is";
var $MSG_MAMBO_SMF_VERSION_IS		= "Your Joomla-SMF Integration version is";
var $MSG_LATEST_MAMBO_SMF_VERSION_IS= "Latest Joomla-SMF Forum Version is";
var $MSG_SMF_MAMBO_IN_SYNC			= "SMF and Joomla users in sync.";
var $MSG_SMF_MAMBO_NOT_IN_SYNC		= "SMF and Joomla users not in sync.";

var $MSG_CONFIGURATION_FILE_IS 		= "Configuration file";
var $MSG_MAMBO_CACHE_DIR_IS			= "Joomla cache directory";
var $MSG_MAMBO_INDEX_FILE_IS		= "Joomla index file";
var $MSG_MAMBO_PATH_IS 				= "Joomla main path";

var $MSG_ERR_CANNOT_REACH_SITE 			= 'Error: Cannot reach <a href="http://www.joomlahacks.com" target="_blank">JoomlaHacks.com</a> feed!';
var $MSG_ERR_MAMBO_INDEX_NOT_WRITEABLE 	= "Joomla main path or index.php is not writeable!";
var $MSG_ERR_SMF_INDEX_NOT_WRITEABLE 	= "SMF main path or index.php is not writeable!";
var $MSG_ERR_CONF_NOT_WRITEABLE 		= "Configuration file is not writeable!";
var $MSG_SETTINGS_SAVED 				= "Settings saved";
var $MSG_WRITABLE 						= 'is writeable';
var $MSG_NOT_WRITABLE 					= 'file or the parent path is not writeable!';
var $MSG_SUCCESS						= 'Success';
var $MSG_FAILED							= 'Failed';
var $MSG_CANNOT_COPY_USER_TO_MAMBO		= "Warning: You are logged to SMF but not in Joomla. Cannot load user to Joomla.";
var $MSG_CANNOT_COPY_USER_TO_SMF		= "Warning: You are logged to Joomla but not in SMF. Cannot load user to SMF.";
var $MSG_PLEASE_SET_CHMOD_MANUALLY		= "Please set the permissions manually.";

var $TEXT_SET_PERMISSION			= "Please set the all the permission settings first to writeable.";
var $TEXT_INSTALL_SMF				= "Install SMF Forum on the same database as Joomla.";
var $TEXT_INSTALL_SMF_MOD			= "Patch SMF index.php and Sources/Subs-Post.php file. (<b>Note:</b> Unpatch before upgrading SMF or installing mod.)";
var $TEXT_PATCH_MAMBO_INDEX_FILE	= "Patch mambo index.php file.";
var $TEXT_SYNC_MAMBO_SMF			= "Synchronize Joomla and SMF users (optional).";
var $TEXT_IMPORTANT					= "IMPORTANT:";
var $TEXT_IMPORTANT_MSG				= "Uncheck only if you are using this for a fresh SMF install!";
var $TEXT_JOOMLA_SMF_SAME_DB		= "<b>Note:</b> Joomla (Mambo) and SMF must be on the same db.";

## COMMON FOR ALL LANGUAGES
##
var $SMFB_ISO 								= 'iso-8859-1';
var $SMFB_DATE_FORMAT_LC 					= '%A, %d. %B %Y'; //Verwendet das PHP strftime Format
var $SMFB_DATE_FOMAT_SHORT 					= ' %d.%m.%Y'; // short date
var $SMFB_DATE_FORMAT_LONG 					= '%d.%m.%Y %H:%M'; // use PHP strftime Format, more info at http://php.net
}
?>
