<?php

// Part of Remository, copyright (c) Martin Brampton 2005.
// For further information please refer to http://www.remository.com

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class menuremository {
	/**
	* Draws the menu for a New Contact
	*/
	function NEW_MENU( $task ) {
		
		mosMenuBar::startTable();
		
		if ($task=='addfolder'){
			mosMenuBar::save( 'savecontainer', 'Save Folder' );
			mosMenuBar::custom( 'cancel|showfolders', 'cancel.png', 'cancel_f2.png', 'Cancel', false );	
		} elseif ($task=='addfile'  || $task=='addorphan') {
			mosMenuBar::save( 'savefile', 'Save File' );
			mosMenuBar::custom( 'cancel|showfiles', 'cancel.png', 'cancel_f2.png', 'Cancel', false );	
		} else {
			mosMenuBar::save( 'savecontainer', 'Save Category' );
			mosMenuBar::custom( 'cancel|showcats', 'cancel.png', 'cancel_f2.png', 'Cancel', false );	
		} 
		
		mosMenuBar::spacer();
		
		mosMenuBar::endTable();
	}
	
	function EDIT_MENU( $task ) {
		
		mosMenuBar::startTable();
		
		if ($task=='editfolder') {
			mosMenuBar::save( 'savecontainer', 'Save Folder' );
			mosMenuBar::custom( 'cancel|showfolders', 'cancel.png', 'cancel_f2.png', 'Cancel', false );	
		} elseif ($task=='editfile') {
			mosMenuBar::save( 'savefile', 'Save File' );
			mosMenuBar::custom( 'cancel|showfiles', 'cancel.png', 'cancel_f2.png', 'Cancel', false );	
		} else {
			mosMenuBar::save( 'savecontainer', 'Save Category' );
			mosMenuBar::custom( 'cancel|showcats', 'cancel.png', 'cancel_f2.png', 'Cancel', false );	
		}
		
		mosMenuBar::spacer();
		
		mosMenuBar::endTable();
	}

	function DEFAULT_MENU( $act='', $task='' ) {

		mosMenuBar::startTable();
		
		if (($act=='showfolders') or ($task=='showfolders')){
			mosMenuBar::publishList( 'publishfolder', 'Publish Folder' );
			mosMenuBar::unpublishList( 'unpublishfolder', 'UnPublish Folder' );
			mosMenuBar::addNew( 'addfolder', 'Add Folder' );
			mosMenuBar::editList( 'editfolder', 'Edit Folder' );
			mosMenuBar::deleteList( '', 'delfolder', 'Delete Folder' );
		} elseif (($act=='showfiles') or ($task=='showfiles')) {
			mosMenuBar::publishList( 'publishfile', 'Publish file' );
			mosMenuBar::unpublishList( 'unpublishfile', 'UnPublish file' );
			mosMenuBar::addNew( 'addfile', 'Add file' );
			mosMenuBar::editList( 'editfile', 'Edit file' );
			mosMenuBar::deleteList( '', 'delfile', 'Delete file' );
		} else {
			mosMenuBar::publishList( 'publishcat', 'Publish cat' );
			mosMenuBar::unpublishList( 'unpublishcat', 'UnPublish cat' );
			mosMenuBar::addNew( 'addcat', 'Add cat' );
			mosMenuBar::editList( 'editcat', 'Edit cat' );
			mosMenuBar::deleteList( '', 'delcat', 'Delete cat' );
		}

		mosMenuBar::spacer();

		mosMenuBar::endTable();
	}
	
	function APPROVE_MENU() {
		
		mosMenuBar::startTable();
		
		mosMenuBar::custom( 'bulkapprove', 'approve.png', 'approve_f2.png', 'Approve', false );	
		
		mosMenuBar::editList( 'editapprove', 'Edit Approval' );

		mosMenuBar::deleteList( '', 'delsubmit', 'Delete Submision' );
	
		mosMenuBar::spacer();

		mosMenuBar::endTable();
		
	}

	function APPROVE_MENU2() {
		
		mosMenuBar::startTable();

		mosMenuBar::custom( 'approve2', 'approve.png', 'approve_f2.png', 'Approve', false );		
	
		mosMenuBar::custom( 'cancel|approve', 'cancel.png', 'cancel_f2.png', 'Cancel', false );	
		
		mosMenuBar::spacer();
		
		mosMenuBar::endTable();
		
	}

	function ORPHANS_MENU() {
		
		mosMenuBar::startTable();

		mosMenuBar::deleteList( '', 'delorphans', 'Delete Orphans' );
		
		mosMenuBar::custom( 'cancel|orphans', 'cancel.png', 'cancel_f2.png', 'Cancel', false );	
		
		mosMenuBar::spacer();
		
		mosMenuBar::endTable();
		
	}

	function CONFIG_MENU() {
		
		mosMenuBar::startTable();

		mosMenuBar::save( 'saveconfig', 'Save Config' );
		
		mosMenuBar::custom( 'cancel|showcats', 'cancel.png', 'cancel_f2.png', 'Cancel', false );	
		
		mosMenuBar::spacer();
		
		mosMenuBar::endTable();
		
	}

	function STATS_MENU() {
		
		mosMenuBar::startTable();
		
		mosMenuBar::spacer();
		
		mosMenuBar::endTable();
		
	}
	
}

class TOOLBAR_mbt_group {
    function EDIT_group_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::save('saveg', 'Save');
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function group_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::addNew('new', 'Add');
        mosMenuBar::editList();
        mosMenuBar::deleteList();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function EMAIL_group_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }
}

?>
