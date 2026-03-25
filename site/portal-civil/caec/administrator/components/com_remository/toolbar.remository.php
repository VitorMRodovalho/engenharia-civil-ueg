<?php

// Part of Remository, copyright (c) Martin Brampton 2005.
// For further information please refer to http://www.remository.com

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$act = trim(strtolower(mosGetParam($_REQUEST, 'act', '')));

require_once( $mainframe->getPath( 'toolbar_html' ) );

$opt='';
if (is_integer(strpos(strtolower($task),'edit'))){
	$opt='edit';
}
if (is_integer(strpos(strtolower($task),'add'))){
	$opt='new';
}
if (is_integer(strpos(strtolower($task),'editapprove'))){
	$opt='approve2';
}
if (($task=='approve') or ($act=='approve')){$opt='approve';}

if (($act=='orphans') or ($task=='orphans')) { $opt='orphans'; }

if (($task=='remosconfig') or ($act=='remosconfig')){$opt='remosconfig';}

if (($task=='stats') or ($act=='stats')){$opt='stats';}

if (($task=='about') or ($act=='about')){$opt='stats';}

$section = mosGetParam($_POST, 'section', '');
if ($section == ''){
	$section = mosGetParam($_REQUEST, 'section', '');
}
if ($section == 'groups'){
	$opt = 'groups_show';
	if ($task=='edit') $opt='groups_edit';
	if ($task=='new') $opt='groups_new';
	if ($task=='emailgroup') $opt='groups_email';
}

switch ( $opt ) {

	case "stats":
		menuremository::STATS_MENU();
		break;

	case "remosconfig":
		menuremository::CONFIG_MENU();
		break;

	case "orphans":
		menuremository::ORPHANS_MENU();
		break;

	case "approve":
		menuremository::APPROVE_MENU();
		break;

	case "approve2":
		menuremository::APPROVE_MENU2();
		break;

	case "edit":
		//$comcid = $cid[0];
		menuremository::EDIT_MENU( $task );
		break;

	case "new":
		menuremository::NEW_MENU( $task );
		break;

	case "groups_edit":
		TOOLBAR_mbt_group::EDIT_group_MENU();
		break;
	case "groups_new":
		TOOLBAR_mbt_group::EDIT_group_MENU();
		break;
	case "groups_show":
		TOOLBAR_mbt_group::group_MENU();
		break;
	case "groups_email":
		TOOLBAR_mbt_group::EMAIL_group_MENU();
		break;

	default:
		menuremository::DEFAULT_MENU( $act, $task );
		break;
}

?>
