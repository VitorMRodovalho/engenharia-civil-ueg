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
	 *	File Name: toolbar.jobline.html.php
	 *	Developer: Olle Johansson - Olle@Johansson.com
	 *	Date: 21 Oct 2006
	 * 	Version #: 1.0
	 *	Comments:
	**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class TOOLBAR_jobline {

	function _EDIT_CONFIG() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::save( 'saveconf' );
		mosMenuBar::back();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function BACKONLY_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::back();
		mosMenuBar::endTable();
	}

	function QUEUE_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::editList();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	
	function LIST_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::publish();
		mosMenuBar::unpublish();
		mosMenuBar::divider();
		mosMenuBar::addNew();
		mosMenuBar::editList();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	
	function EDIT_MENU() {
		mosMenuBar::startTable();
		if ( defined( '_JEXEC' ) ) {
			mosMenuBar::preview( 'index.php?option=com_jobline&tmpl=component' );
		} else {
			mosMenuBar::preview( '../components/com_jobline/preview' );
		}
		mosMenuBar::divider();
		mosMenuBar::save();
		mosMenuBar::divider();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function LISTTMPL_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::editList( 'edittemplate' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function EDITTMPL_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'savetemplate' );
		mosMenuBar::divider();
		mosMenuBar::cancel( 'canceltemplate' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

}
?>