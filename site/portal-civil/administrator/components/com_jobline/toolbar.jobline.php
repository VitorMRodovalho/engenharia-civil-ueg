<?php
//Jobline Admin//
	/**
	 *	Jobline Component for Joomla 1.5
 	 *
	 *	Copyright (C) 2006 Olle Johansson
	 *	Distributed under the terms of the GNU General Public License
	 *	This software may be used without warrany provided and
	 *  copyright statements are left intact.
	 *
	 *	Package Name: Jobline
	 *	File Name: toolbar.jobline.php
	 *	Developer: Olle Johansson - Olle@Johansson.com
	 *	Date: 21 Oct 2006
	 * 	Version #: 1.0
	 *	Comments:
	**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ( $task ) {
	case "conf":
		TOOLBAR_jobline::_EDIT_CONFIG();
		break;
	case "list":
		TOOLBAR_jobline::LIST_MENU();
		break;
	case "queue":
		TOOLBAR_jobline::QUEUE_MENU();
		break;
	case "new":
	case "edit":
	case "editqueue":
		TOOLBAR_jobline::EDIT_MENU();
		break;
	case "listtemplates":
		TOOLBAR_jobline::LISTTMPL_MENU();
		break;
	case "edittemplate":
		TOOLBAR_jobline::EDITTMPL_MENU();
		break;
	case "info":
	default:
		TOOLBAR_jobline::BACKONLY_MENU();
		break;
}
?>