<?php
/**
* @version $Id: admin.cursos.php,v 1.13 2004/09/19 14:08:52 stingrey Exp $
* @package Mambo_4.5.1
* @copyright (C) 2000 - 2004 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// ensure user has access to this function
if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
		| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_cursos' ))) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );

$cid = mosGetParam( $_POST, 'cid', array(0) );

switch ($task) {
	case 'new':
		editCursos( $option, 0 );
		break;

	case 'edit':
		editCursos( $option, $cid[0] );
		break;

	case 'save':
		saveCursos( $option );
		break;

	case 'remove':
		removeCursoss( $cid, $option );
		break;

	case 'publish':
		publishCursoss( $cid, 1, $option );
		break;

	case 'unpublish':
		publishCursoss( $cid, 0, $option );
		break;

	case 'approve':
		break;

	case 'cancel':
		cancelCursos( $option );
		break;
		
	case 'archive':
		changeCursos( $cid, -1, $sectionid, $option );
		break;
		
	case 'unarchive':
		changeContent( $cid, 0, $sectionid, $option );
		break;
		
	case 'showarchive':
		viewArchive( $sectionid, $option );
		break;
		
	case 'orderup':
		orderCursoss( $cid[0], -1, $option );
		break;

	case 'orderdown':
		orderCursoss( $cid[0], 1, $option );
		break;

	default:
		showCursoss( $option );
		break;
}

/**
* Compiles a list of records
* @param database A database connector object
*/
function showCursoss( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;

	$catid = $mainframe->getUserStateFromRequest( "catid{$option}", 'catid', 0 );
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );
	$search = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search = $database->getEscaped( trim( strtolower( $search ) ) );

	$where = array();
	
	$where[] = "a.state >=0 ";

	if ($catid > 0) {
		$where[] = "a.catid='$catid'";
	}
	
	/*************
	/* 1 of 2 Change or add to the where clause
	/*************  */
	
	if ($search) {
		$where[] = "LOWER(a.Curso) LIKE '%$search%'";
	}

	// get the total number of records
	$database->setQuery( "SELECT count(*) FROM #__cursos AS a"
		. (count( $where ) ? "
WHERE " . implode( ' AND ', $where ) : "")
	);
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	$query = "SELECT a.*, cc.name AS category, u.name AS editor"
	. "
 FROM #__cursos AS a "
	. "
 LEFT JOIN #__categories AS cc ON cc.id = a.catid "
	. "
 LEFT JOIN #__users AS u ON u.id = a.checked_out"
	. ( count( $where ) ? "
 WHERE " . implode( ' AND ', $where ) : "")
	. "
 ORDER BY a.catid, a.ordering"
	. "
 LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );

	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	// build list of categories
	$javascript = 'onchange="document.adminForm.submit();"';
	$lists['catid'] 			= mosAdminMenus::ComponentCategory( 'catid', $option, intval( $catid ), $javascript ); 

	HTML_cursos::showCursoss( $option, $rows, $lists, $search, $pageNav );
}

/**
* Compiles information to add or edit
* @param integer The unique id of the record to edit (0 if new)
*/
function editCursos( $option, $id ) {
	global $database, $my, $mosConfig_absolute_path, $mosConfig_live_site;

	$lists = array();

	$row = new Cursos( $database );
	// load the row from the db table
	$row->load( $id );

	// fail if checked out not by 'me'
	if ($row->checked_out && $row->checked_out <> $my->id) {
		mosRedirect( 'index2.php?option='. $option, 'The module $row->Curso is currently being edited by another administrator.' );
	}

	if ($id) {
		$row->checkout( $my->id );
	} else {
		// initialise new record
		$row->published 		= 1;
		$row->order 			= 0;
	}

	// build the html select list for ordering
	$query = "SELECT ordering AS value, Curso AS text"
	. "
 FROM #__cursos"
	. "
 WHERE catid='$row->catid'"
	. "
 ORDER BY ordering"
	;
	$lists['ordering'] 			= mosAdminMenus::SpecificOrdering( $row, $id, $query, 1 ); 

	// build list of categories
	$lists['catid'] 			= mosAdminMenus::ComponentCategory( 'catid', $option, intval( $row->catid ) ); 
	// build the html select list
	$lists['published'] 		= mosHTML::yesnoRadioList( 'published', 'class="inputbox"', $row->published );

	HTML_cursos::editCursos( $row, $lists, $option );
}

/**
* Saves the record on an edit form submit
* @param database A database connector object
*/
function saveCursos( $option ) {
	global $database, $my;

	$row = new Cursos( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>";
		exit();
	}

	$row->date = date( "Y-m-d H:i:s" );
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>";
		exit();
	}
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>";
		exit();
	}
	$row->checkin();
	$row->updateOrder( "catid='$row->catid'" );

	mosRedirect( "index2.php?option=$option" );
}

/**
* Deletes one or more records
* @param array An array of unique category id numbers
* @param string The current url option
*/
function removeCursoss( $cid, $option ) {
	global $database;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>";
		exit;
	}
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$database->setQuery( "DELETE FROM #__cursos WHERE id IN ($cids)" );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>";
		}
	}

	mosRedirect( "index2.php?option=$option" );
}

/**
* Publishes or Unpublishes one or more records
* @param array An array of unique category id numbers
* @param integer 0 if unpublishing, 1 if publishing
* @param string The current url option
*/
function publishCursoss( $cid=null, $publish=1,  $option ) {
	global $database, $my;

	$catid = mosGetParam( $_POST, 'catid', array(0) );

	if (!is_array( $cid ) || count( $cid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>
";
		exit;
	}

	$cids = implode( ',', $cid );

	$database->setQuery( "UPDATE #__cursos SET published='$publish'"
		. "
WHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$my->id'))"
	);
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>
";
		exit();
	}

	if (count( $cid ) == 1) {
		$row = new Cursos( $database );
		$row->checkin( $cid[0] );
	}
	mosRedirect( "index2.php?option=$option" );
}

/**
* Changes the state of one or more cursos items
* @param string The name of the category section
* @param integer A unique category id (passed from an edit form)
* @param array An array of unique category id numbers
* @param integer 0 if unpublishing, 1 if publishing
* @param string The name of the current user
*/
function changeCursos( $cid=null, $state=0, $section, $option ) {
	global $database, $my;

	if (count( $cid ) < 1) {
		$action = $publish == 1 ? 'publish' : ($publish == -1 ? 'archive' : 'unpublish');
		echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>
";
		exit;
	}

	$total = count ( $cid );
	$cids = implode( ',', $cid );

	$database->setQuery( "UPDATE #__cursos SET state='$state'"
	. "
WHERE id IN ($cids) AND (checked_out=0 OR (checked_out='".$my->id."'))"
	);
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>
";
		exit();
	}

	if (count( $cid ) == 1) {
		$row = new Cursos( $database );
		$row->checkin( $cid[0] );
	}

	if ( $state == "-1" ) {
		$msg = $total ." Item(s) successfully Archived";
	} else if ( $state == "1" ) {
		$msg = $total ." Item(s) successfully Published";
	} else if ( $state == "0" ) {
		$msg = $total ." Item(s) successfully Unpublished";
	}

	$redirect = mosGetParam( $_POST, 'redirect', $row->sectionid );
	$task = mosGetParam( $_POST, 'returntask', '' );
	if ( $task ) {
		$task = '&task='. $task;
	} else {
		$task = '';
	}

	mosRedirect( 'index2.php?option='. $option . $task .'&sectionid='. $redirect .'&mosmsg='. $msg );
}

/**
* Moves the order of a record
* @param integer The increment to reorder by
*/
function orderCursoss( $uid, $inc, $option ) {
	global $database;
	$row = new Cursos( $database );
	$row->load( $uid );
	$row->move( $inc, "published >= 0" );

	mosRedirect( "index2.php?option=$option" );
}

/**
* Cancels an edit operation
* @param string The current url option
*/
function cancelCursos( $option ) {
	global $database;
	$row = new Cursos( $database );
	$row->bind( $_POST );
	$row->checkin();
	mosRedirect( "index2.php?option=$option" );
}

/**
* Shows a list of archived content items
* @param int The section id
*/
function viewArchive( $sectionid, $option ) {
	global $database, $mainframe, $mosConfig_list_limit;

	$catid = $mainframe->getUserStateFromRequest( "catid{$option}", 'catid', 0 );
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );
	$search = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search = $database->getEscaped( trim( strtolower( $search ) ) );

	$where = array();

	if ($catid > 0) {
		$where[] = "a.catid='$catid'";
	}
	
	/*************
	/*  2 of 2 Change or add to the where clause
	/************* */
	
	if ($search) {
		$where[] = "LOWER(a.Curso) LIKE '%$search%'";
	}

	// get the total number of records
	$database->setQuery( "SELECT count(*) FROM #__cursos AS a"
		. (count( $where ) ? "
WHERE " . implode( ' AND ', $where ). " AND state=-1 " : " WHERE state=-1")
	);
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	$query = "SELECT a.*, cc.name AS category, u.name AS editor"
	. "
 FROM #__cursos AS a"
	. "
 LEFT JOIN #__categories AS cc ON cc.id = a.catid"
	. "
 LEFT JOIN #__users AS u ON u.id = a.checked_out"
	. (count( $where ) ? "
WHERE " . implode( ' AND ', $where ). " AND state=-1 " : " WHERE state=-1")
	. "
 ORDER BY a.catid, a.ordering"
	. "
 LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );

	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	// build list of categories
	$javascript = 'onchange="document.adminForm.submit();"';
	$lists['catid'] 			= mosAdminMenus::ComponentCategory( 'catid', $option, intval( $catid ), $javascript ); 

	HTML_cursos::showCursoss( $option, $rows, $lists, $search, $pageNav );
}

?>
