<?php
//
// Copyright (C) 2003 Jan de Graaff
// All rights reserved..
//
// This program uses parts of the original Simpleboard Application
// 0.7.0b written by Josh Levine; http://www.joshlevine.net
//
// This source file is part of the SimpleBoard Component, a Mambo 4.5
// custom Component By Jan de Graaff - http://tsmf.jigsnet.com
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//

// ################################################################
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
//include_once $_CONFIG->SITEPATH."/legacy.php";
// ################################################################

/**
* Users Table Class
*
* Provides access to the #__sb_users table
*/
class sbUserprofile extends mosDBTable {
   /** @var int Unique id*/
   var $userid=null;
   /** @var string */
   var $view=null;
   /** @var string */
   var $signature=null;
   /** @var int */
   var $moderator=null;
   /** @var int */
   var $block=null;
   /** @var int */
   var $future1=null;
   /** @var string */
   var $future2=null;
   /**
   * @param database A database connector object
   */
   function sbUserprofile( &$database ) {
      $this->mosDBTable( '#__sb_users', 'id', $database );
   }
}

/**
* Users Table Class
*
* Provides access to the #__sb_users table
*/
class sbModeration extends mosDBTable {
   /** @var int Unique id*/
   var $catid=null;
   /** @var int */
   var $userid=null;
   /** @var int */
   var $future1=null;
   /** @var int */
   var $future2=null;
   /**
   * @param database A database connector object
   */
   function sbModeration( &$database ) {
      $this->mosDBTable( '#__sb_moderation', 'catid', $database );
   }
}

class sbForum extends mosDBTable {
   /** @var int Unique id*/
   var $id=null;
   /** @var string */
   var $parent=null;
   /** @var string */
   var $name=null;
   var $cat_emoticon=null;
   var $locked=null;
   var $alert_admin=null;
   var $moderated=null;
   var $pub_access=null;
   var $pub_recurse=null;
   var $admin_access=null;
   var $admin_recurse=null;
   var $public=null;
   var $ordering=null;
   var $future2=null;
   var $published=null;
   var $checked_out=null;
   var $checked_out_time=null;
   var $review=null;
   var $hits=null;
   var $description=null;
   /**
   * @param database A database connector object
   */
   function sbForum( &$database ) {
      $this->mosDBTable( '#__sb_categories', 'id', $database );
   }

}

global $version, $action, $parentid, $forumname, $catid, $newparent,$msg,$deleteSig,$signature,$newview,$user_id,$thread,$prev_display,$prev_catdisplay,$display,$order,$moderator;
   global $mosConfig_absolute_path, $mosConfig_admin_template, $mosConfig_live_site;
	global $_CONFIG;
	
	if ($_CONFIG->SITEPATH == "") {
		$_CONFIG->SITEPATH=$mosConfig_absolute_path;
		$_CONFIG->SITEURL = $mosConfig_live_site;
	}
//error_reporting(E_ALL);

//template:
//check if the database is version 0.9.2 and up
//from version 0.9.1 and up, there must be a column called pub_access..
//$database->setQuery("select pub_access from #__sb_categories");
//if (!$database->query() && $task!='upgradetables')
//{
//   $database->setQuery("select id from #__components where link='option=com_simpleboard'");
//   $comid=$database->loadResult();
//   $database->setQuery ("INSERT INTO `#__components` VALUES (99999, 'Upgrade Tables to 0.9.2', '', 0, $comid, 'option=com_simpleboard&task=upgradetables', 'Upgrade tables', 'com_simpleboard', 5, 'js/ThemeOffice/component.png', 0, '', '')");
//   $database->query();
//   die ("You're Simpleboard tables appears to be of a version prior to 0.9.2. <br />"
//   . "\n OR you have an inconsistency in your database<br />"
//   . "\n Please upgrade or repair it using: Components->Simpleboard Forum->Upgrade tables to 0.9.2.<br />"
//   . "\n <br /><strong> Note: refresh this window first!</strong><br /><br />"
//   );

//}

require_once( $mainframe->getPath( 'admin_html' ) );
//Get right Language file
if (file_exists($_CONFIG->SITEPATH.'/administrator/components/com_simpleboard/language/'.$mosConfig_lang.'.php')) {
  include($_CONFIG->SITEPATH.'/administrator/components/com_simpleboard/language/'.$mosConfig_lang.'.php');
} else {
  include($_CONFIG->SITEPATH.'/administrator/components/com_simpleboard/language/english.php');
}
//include configuration file
if (file_exists($_CONFIG->SITEPATH.'/administrator/components/com_simpleboard/simpleboard_config.php')) {
  include($_CONFIG->SITEPATH.'/administrator/components/com_simpleboard/simpleboard_config.php');
} else {
  die ("Error finding configuration file (".$_CONFIG->SITEPATH."/administrator/components/com_simpleboard/simpleboard_config.php)");
}

$cid = mosGetParam( $_POST, 'cid', array(0) );
if (!is_array( $cid )) {
   $cid = array(0);
}

$pt_stop ="0";
switch ($task) {
   case "new":
      editForum( 0, $option);
      break;
   case "edit":
      editForum( $cid[0], $option );
      break;
   case "edit2":
      editForum( $uid, $option );
      break;
   case "save":
      saveForum( $option );
      break;
   case "cancel":
      cancelForum( $option );
      break;
   case "publish":
      publishForum( $cid, 1, $option );
      break;
   case "unpublish":
      publishForum( $cid, 0, $option );
      break;
   case "remove":
      deleteForum( $cid, $option );
      break;
   case "orderup":
      orderForum( $cid[0], -1, $option );
      break;
   case "orderdown":
      orderForum( $cid[0], 1, $option );
      break;
   case "showconfig":
      showConfig( $option );
      break;
   case "saveconfig":
      saveConfig( $option );
      break;
   case "newmoderator":
      newModerator( $option, $id );
      break;
   case "addmoderator":
      addModerator( $option, $id, $cid, 1 );
      break;
   case "removemoderator":
      addModerator( $option, $id, $cid, 0 );
      break;
   case "showprofiles":
     showProfiles ($database, $option, $mosConfig_lang,$order);
     break;
   case "profiles":
      showProfiles ($database, $option, $mosConfig_lang,$order);
      break;
   case "userprofile":
      editUserProfile ($option,$database, $uid);
      break;
   case "showinstructions":
     showInstructions($database, $option, $mosConfig_lang);
     break;
   case "showCss":
     showCss($option);
     break;
   case "saveeditcss":
     saveCss ($file,$csscontent, $option);
     break;
   case "instructions":
      showInstructions($database, $option, $mosConfig_lang);
      break;
   case "saveuserprofile":
      saveUserProfile( $option );
      break;
   case "upgradetables":
      upgradeTables( $option );
      break;
   case "loadSample":
      loadSample( $database,$option );
      break;
   case "pruneforum":
      pruneforum( $database,$option );
      break;
   case "doprune":
      doprune( $database,$option );
      break;
   case "dousersprune":
      dousersprune($database, $option);
      break;
   case "pruneusers":
      pruneusers( $database,$option );
      break;
   case "browseImages":
      browseUploaded( $database,$option, 1 );
      break;
   case "browseFiles":
      browseUploaded( $database,$option, 0 );
      break;
   case "replaceImage":
      replaceImage( $database,$option,$img, $OxP );
      break;
   case "deleteFile":
      deleteFile( $database,$option,$fileName);
      break;
   case "showAdministration":
      showAdministration( $option);
      break;
   case "loadCBprofile":
      loadCBprofile( $database,$option);
      break;
   case 'cpanel':
      default:
      $version=$sbConfig['version'];
      HTML_Simpleboard::controlPanel($version);
      break;
}

function showAdministration( $option ) {
   global $database, $mainframe;
   global $mosConfig_lang;

   include_once( "components/com_simpleboard/simpleboard_config.php" );

   $limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit',10 );
   $limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart",'limitstart', 0 );
   $levellimit = $mainframe->getUserStateFromRequest( "view{$option}limit",'levellimit', 10 );
   //$limit      = intval( mosGetParam( $_REQUEST, 'limit', 10 ) );
   //$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
   //$levellimit = intval( mosGetParam( $_REQUEST, 'levellimit', 10 ) );;

   $database->setQuery( "SELECT COUNT(*) FROM #__sb_categories" );
   $total = $database->loadResult();

   require_once( "includes/pageNavigation.php" );
   $pageNav = new mosPageNav( $total, $limitstart, $limit  );

// select the records
// note, since this is a tree we have to do the limits code-side
$database->setQuery( "SELECT a.*, b.name AS category, u.name AS editor, g.name AS groupname, h.name AS admingroup"
. "\nFROM #__sb_categories AS a"
. "\nLEFT JOIN #__sb_categories AS b ON b.id = a.parent"
. "\nLEFT JOIN #__users AS u ON u.id = a.checked_out"
. "\nLEFT JOIN #__core_acl_aro_groups AS g ON g.group_id = a.pub_access"
. "\nLEFT JOIN #__core_acl_aro_groups AS h ON h.group_id = a.admin_access"
. "\nORDER BY parent, ordering"
//. "\nLIMIT $pageNav->limitstart,$pageNav->limit"
);
$rows = $database->loadObjectList();
if ($database->getErrorNum()) {
echo $database->stderr();
return false;
}


// establish the hierarchy of the menu
   $children = array();
// first pass - collect children
   foreach ($rows as $v ) {
      $pt = $v->parent;
      $list = @$children[$pt] ? $children[$pt] : array();
      array_push( $list, $v );
      $children[$pt] = $list;
   }

// second pass - get an indent list of the items
$list = mosTreeRecurse( 0, '', array(), $children, max( 0, $levellimit-1
) );

// slice out elements based on limits
$list = array_slice( $list, $pageNav->limitstart, $pageNav->limit );

HTML_SIMPLEBOARD::showAdministration( $list, $pageNav, $option );
}

//---------------------------------------
//-E D I T   F O R U M-------------------
//---------------------------------------
function editForum( $uid, $option ) {
   global $database, $my, $acl;

   $row = new sbForum( $database );
   // load the row from the db table
   $row->load( $uid );

   //echo "<pre>"; print_r ($row); echo "</pre>";
   if ($uid) {
      $row->checkout( $my->id );
      $categories = array();
   } else {
      // initialise new record
      $categories[] = mosHTML::makeOption( 0, 'Top Level Category' );

      $row->parent = 0;
      $row->published = 0;
      $row->ordering = 9999;
   }

   // get a list of just the categories
   $database->setQuery( "SELECT a.id AS value, a.name AS text"
   . "\nFROM #__sb_categories AS a"
   . "\nWHERE parent='0' AND id<>'$row->id'"
   . "\nORDER BY ordering"
   );
   $categories = array_merge( $categories, $database->loadObjectList() );
   //echo $database->getErrorMsg();
   if ($row->parent==0){
      //make sure the Top Level Category is available in edit mode as well:
      $database->setQuery( "SELECT distinct '0' AS value, 'Top Level Category' AS text"
      . "\nFROM #__sb_categories AS a"
      . "\nWHERE parent='0' AND id<>'$row->id'"
      . "\nORDER BY ordering");
      $categories = array_merge( $categories, $database->loadObjectList() );

      //build the select list:
      $categoryList = mosHTML::selectList( $categories, 'parent', 'class="inputbox" size="1"',
      'value', 'text', $row->parent );
   }else{
      $categoryList = mosHTML::selectList( $categories, 'parent', 'class="inputbox" size="1"',
      'value', 'text', $row->parent );
   }

   // make a standard yes/no list
      $yesno = array();
      $yesno[] = mosHTML::makeOption( '0', 'No' );
      $yesno[] = mosHTML::makeOption( '1', 'Yes' );

   //Create all kinds of Lists
      $lists=array();
      $accessLists=array();
   //create custom group levels to include into the public group selectList
      $pub_groups   = array();
      $pub_groups[] = mosHTML::makeOption( 0 , '- Everybody -' );
      $pub_groups[] = mosHTML::makeOption( -1, '- All Registered Users -' );
      $pub_groups = array_merge( $pub_groups, $acl->get_group_children_tree( null, 'Registered', true ) );

   //create admin groups array for use in selectList:
      $adm_groups = array();
      $adm_groups = array_merge( $adm_groups, $acl->get_group_children_tree( null, 'Public Backend', true ) );

   //create the access control list
   $accessLists['pub_access']   = mosHTML::selectList( $pub_groups, 'pub_access'    , 'class="inputbox" size="4"', 'value', 'text', $row->pub_access    );
   $accessLists['admin_access'] = mosHTML::selectList( $adm_groups, 'admin_access'  , 'class="inputbox" size="4"', 'value', 'text', $row->admin_access  );
   $lists['pub_recurse']        = mosHTML::selectList( $yesno     , 'pub_recurse'   , 'class="inputbox" size="1"', 'value', 'text', $row->pub_recurse   );
   $lists['admin_recurse']      = mosHTML::selectList( $yesno     , 'admin_recurse' , 'class="inputbox" size="1"', 'value', 'text', $row->admin_recurse );
   $lists['forumLocked']        = mosHTML::selectList( $yesno     , 'locked'        , 'class="inputbox" size="1"', 'value', 'text', $row->locked        );
   $lists['forumModerated']     = mosHTML::selectList( $yesno     , 'moderated'     , 'class="inputbox" size="1"', 'value', 'text', $row->moderated     );
   $lists['forumReview']        = mosHTML::selectList( $yesno     , 'review'        , 'class="inputbox" size="1"', 'value', 'text', $row->review        );

   //get a list of moderators, if forum/category is moderated
   $moderatorList=array();
   if ($row->moderated==1)
   {  $database->setQuery("SELECT * "
      . "\n FROM #__sb_moderation AS a "
      . "\n LEFT JOIN #__users as u"
      . "\n ON a.userid=u.id where a.catid=$row->id"
      );
      $moderatorList=$database->loadObjectList();
   }
   HTML_SIMPLEBOARD::editForum( $row, $categoryList, $moderatorList, $lists, $accessLists, $option );
}

function saveForum( $option ) {
   global $database, $my;

   $row = new sbForum( $database );
   if (!$row->bind( $_POST )) {
      echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
      exit();
   }

   if (!$row->check()) {
      echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
      exit();
   }
   if (!$row->store()) {
      echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
      exit();
   }
   $row->checkin();
   $row->updateOrder( "parent='$row->parent'" );

   mosRedirect( "index2.php?option=$option&task=showAdministration" );
}

function publishForum( $cid=null, $publish=1, $option ) {
   global $database, $my;

   if (!is_array( $cid ) || count( $cid ) < 1) {
      $action = $publish ? 'publish' : 'unpublish';
      echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
      exit;
   }

   $cids = implode( ',', $cid );

   $database->setQuery( "UPDATE #__sb_categories SET published='$publish'"
   . "\nWHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$my->id'))"
   );
   if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }

   if (count( $cid ) == 1) {
      $row = new sbForum( $database );
      $row->checkin( $cid[0] );
   }
   mosRedirect( "index2.php?option=$option&task=showAdministration" );
}

function deleteForum( $cid=null, $option ) {
   global $database, $my;
   if (!is_array( $cid ) || count( $cid ) < 1) {
      $action = 'delete';
      echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
      exit;
   }

   $cids = implode( ',', $cid );

   $database->setQuery( "DELETE FROM #__sb_categories"
   . "\nWHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$my->id'))"
   );
   if ($database->query()) {//now we got to clear up all posts

      $database->setQuery("SELECT id, parent FROM #__sb_messages where catid in ($cids)");
      $mesList=$database->loadObjectList();
      if(count($mesList)>0)
      {
         $fail=0;
         foreach($mesList as $ml)
         {
            $database->setQuery("DELETE FROM #__sb_messages WHERE id = $ml->id");
            if($database->query()){
               $database->setQuery("DELETE FROM #__sb_messages_text WHERE mesid=$ml->id");
               $database->query();
               }else{$fail=1;}
            //and clear up all subscriptions as well
            if ($ml->parent==0)
            {//this was a topic message to which could have been subscribed
               $database->setQuery("DELETE FROM #__sb_subscriptions WHERE thread=$ml->id");
               if(!$database->query()){ $fail=1;}
            }
         }
      }
   }
   else{
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }

   if ($fail!=0){echo "<script> alert('Something went wrong deleting the messages and subscriptions'); window.history.go(-1); </script>\n";}

   mosRedirect( "index2.php?option=$option&task=showAdministration" );
}

function cancelForum( $option ) {
   global $database;
   $row = new sbForum( $database );
   $row->bind( $_POST );
   $row->checkin();
   mosRedirect( "index2.php?option=$option&task=showAdministration" );
}

function orderForum( $uid, $inc, $option ) {
   global $database;

   $row = new sbForum( $database );
   $row->load( $uid );
   $row->move( $inc, "parent='$row->parent'" );
   mosRedirect( "index2.php?option=$option&task=showAdministration" );
}

//===============================
// Config Functions
//===============================

function showConfig( $option ) {
   global $database;
   global $mosConfig_lang;
   global $mosConfig_absolute_path, $mosConfig_admin_template, $mosConfig_live_site;
	global $_CONFIG;
	
	if ($_CONFIG->SITEPATH == "") {
		$_CONFIG->SITEPATH=$mosConfig_absolute_path;
		$_CONFIG->SITEURL = $mosConfig_live_site;
	}	

   $configfile = $_CONFIG->SITEPATH."/administrator/components/com_simpleboard/simpleboard_config.php";
   @chmod ($configfile, 0766);
   $permission = is_writable($configfile);
   if (!$permission) {
      echo "<center><h1><font color=red>Warning...</FONT></h1><BR>";
      echo "<B>Your config file is $configfile</b><BR>";
      echo "<B>You need to chmod this to 766 in order for the config to be updated</B></center><BR><BR>";
   }

   include( $configfile );

   $lists = array();

   // the default view
   $list = array();
   $list[] = mosHTML::makeOption( 'flat', _COM_A_FLAT );
   $list[] = mosHTML::makeOption( 'threaded', _COM_A_THREADED );

   // build the html select list
   $lists['default_view'] = mosHTML::selectList(
      $list, 'cfg_default_view', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['default_view'] );

   // source of avatar picture
   $avlist = array();
   $avlist[] = mosHTML::makeOption( 'sb', 'Simpleboard' );
   $avlist[] = mosHTML::makeOption( 'pmspro', 'myPMS Pro' );
	$avlist[] = mosHTML::makeOption( 'cb', 'Community Builder');

   // build the html select list
   $lists['avatar_src'] = mosHTML::selectList(
   $avlist, 'cfg_avatar_src', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['avatar_src'] );

   // private messaging system to use
   $pmlist = array();
   $pmlist[] = mosHTML::makeOption( 'no', _COM_A_NO );
   $pmlist[] = mosHTML::makeOption( 'pms', 'myPMS II Open Source' );
   $pmlist[] = mosHTML::makeOption( 'pmspro', 'myPMS Professional');

   // build the html select list
   $lists['pm_component'] = mosHTML::selectList(
   $pmlist, 'cfg_pm_component', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['pm_component'] );

   // make a standard yes/no list
   $yesno = array();
   $yesno[] = mosHTML::makeOption( '0', _COM_A_NO );
   $yesno[] = mosHTML::makeOption( '1', _COM_A_YES );


   /* Build the templates list*/
   // This function was modified from the one posted to PHP.net by rockinmusicgv
   // It is available under the readdir() entry in the PHP online manual
   //function get_dirs($directory, $select_name, $selected = "") {
      $listitems[] = mosHTML::makeOption( '0', 'Select Template' );
      if ($dir = @opendir($_CONFIG->SITEPATH."/components/com_simpleboard/template")) {
          while (($file = readdir($dir)) !== false) {
               if ($file != ".." && $file != ".") {
                  if(is_dir($_CONFIG->SITEPATH."/components/com_simpleboard/template"."/".$file)) {
                      if(!($file[0] == '.')) {
                          $filelist[] = $file;
                      }
                  }
              }
          }
          closedir($dir);
      }

      asort($filelist);
      while (list ($key, $val) = each ($filelist)) {
          //echo "<option value=\"$val\"";
          //if ($selected == $val) {
          //    echo " selected";
          //}
          //echo ">$val Gallery</option>\n";
          $listitems[] = mosHTML::makeOption( $val, $val );

      }

   $lists['badwords'] = mosHTML::selectList( $yesno, 'cfg_badwords', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['badwords'] );

   $lists['disemoticons'] = mosHTML::selectList( $yesno, 'cfg_disemoticons', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['disemoticons'] );
   
   $lists['template'] = mosHTML::selectList( $listitems, 'cfg_template', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['template'] );

   $lists['regonly'] = mosHTML::selectList( $yesno, 'cfg_regonly', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['regonly'] );

   $lists['board_offline'] = mosHTML::selectList( $yesno, 'cfg_board_offline', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['board_offline'] );

   $lists['pubwrite'] = mosHTML::selectList( $yesno, 'cfg_pubwrite', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['pubwrite'] );

   $lists['useredit'] = mosHTML::selectList( $yesno, 'cfg_useredit', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['useredit'] );

   $lists['showHistory'] = mosHTML::selectList( $yesno, 'cfg_showHistory', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['showHistory'] );

   $lists['allowsubscriptions'] = mosHTML::selectList( $yesno, 'cfg_allowsubscriptions', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['allowsubscriptions'] );

   $lists['mailmod'] = mosHTML::selectList( $yesno, 'cfg_mailmod', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['mailmod'] );

   $lists['showemail'] = mosHTML::selectList( $yesno, 'cfg_showemail', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['showemail'] );

   $lists['askemail'] = mosHTML::selectList( $yesno, 'cfg_askemail', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['askemail'] );

   $lists['changename'] = mosHTML::selectList( $yesno, 'cfg_changename', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['changename'] );

   $lists['allowAvatar'] = mosHTML::selectList( $yesno, 'cfg_allowAvatar', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['allowAvatar'] );

   $lists['allowAvatarUpload'] = mosHTML::selectList( $yesno, 'cfg_allowAvatarUpload', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['allowAvatarUpload'] );

   $lists['allowAvatarGallery'] = mosHTML::selectList( $yesno, 'cfg_allowAvatarGallery', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['allowAvatarGallery'] );

   $lists['showstats'] = mosHTML::selectList( $yesno, 'cfg_showstats', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['showstats'] );

   $lists['showranking'] = mosHTML::selectList( $yesno, 'cfg_showranking', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['showranking'] );

   $lists['rankimages'] = mosHTML::selectList( $yesno, 'cfg_rankimages', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['rankimages'] );

   $lists['username'] = mosHTML::selectList( $yesno, 'cfg_username', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['username'] );

   //$lists['rte'] = mosHTML::selectList( $yesno, 'cfg_rte', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['rte'] );

   $lists['pms'] = mosHTML::selectList( $yesno, 'cfg_pms', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['pms'] );
   
   $lists['pmspro'] = mosHTML::selectList( $yesno, 'cfg_pmspro', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['pmspro'] );

   $lists['showNew'] = mosHTML::selectList( $yesno, 'cfg_showNew', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['showNew'] );

   $lists['allowImageUpload'] = mosHTML::selectList( $yesno, 'cfg_allowImageUpload', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['allowImageUpload'] );

   $lists['allowImageRegUpload'] = mosHTML::selectList( $yesno, 'cfg_allowImageRegUpload', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['allowImageRegUpload'] );

   $lists['allowFileUpload'] = mosHTML::selectList( $yesno, 'cfg_allowFileUpload', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['allowFileUpload'] );

   $lists['allowFileRegUpload'] = mosHTML::selectList( $yesno, 'cfg_allowFileRegUpload', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['allowFileRegUpload'] );

   $lists['editMarkUp'] = mosHTML::selectList( $yesno, 'cfg_editMarkUp', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['editMarkUp'] );

   $lists['discussBot'] = mosHTML::selectList( $yesno, 'cfg_discussBot', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['discussBot'] );

   $lists['enableRSS'] = mosHTML::selectList( $yesno, 'cfg_enableRSS', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['enableRSS'] );

   $lists['postStats'] = mosHTML::selectList( $yesno, 'cfg_postStats', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['postStats'] );

   $lists['showkarma'] = mosHTML::selectList( $yesno, 'cfg_showkarma', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['showkarma'] );

   $lists['cb_profile'] = mosHTML::selectList( $yesno, 'cfg_cb_profile', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['cb_profile'] );
	
   $lists['enablePDF'] = mosHTML::selectList( $yesno, 'cfg_enablePDF', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['enablePDF'] );
   
   $lists['enableRulesPage'] = mosHTML::selectList( $yesno, 'cfg_enableRulesPage', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['enableRulesPage'] );
   
   $lists['enableForumJump'] = mosHTML::selectList( $yesno, 'cfg_enableForumJump', 'class="inputbox" size="1"', 'value', 'text', $sbConfig['enableForumJump'] );

   HTML_SIMPLEBOARD::showConfig( $sbConfig, $lists, $option );
}

function saveConfig ( $option ) {
   //Add code to check if config file is writeable.
   $configfile = "components/com_simpleboard/simpleboard_config.php";
   @chmod ($configfile, 0766);
   if (!is_writable($configfile)) {
      mosRedirect("index2.php?option=$option", "FATAL ERROR: Config File Not writeable" );
   }

   $txt = "<?php\n";
   foreach ($_POST as $k=>$v) {
      if (strpos( $k, 'cfg_' ) === 0) {
         if (!get_magic_quotes_gpc()) {
            $v = addslashes( $v );
         }
         $txt .= "\$sbConfig['".substr( $k, 4 )."']='$v';\n";
      }
   }
   $txt .= "?>";

   if ($fp = fopen( $configfile, "w")) {
      fputs($fp, $txt, strlen($txt));
      fclose ($fp);
      mosRedirect( "index2.php?option=$option&task=showconfig", "Configuration file saved" );
   } else {
      mosRedirect( "index2.php?option=$option", "FATAL ERROR: File could not be opened." );
   }
}

   function showInstructions($database, $option, $mosConfig_lang) {

      HTML_SIMPLEBOARD::showInstructions( $database, $option, $mosConfig_lang);


   }

//===============================
// CSS functions
//===============================

   function showCss ($option) {

      global $sbConfig;
      $file = "../components/com_simpleboard/template/".$sbConfig['template']."/forum.css";
      @chmod ($file, 0766);
      $permission = is_writable($file);
      if (!$permission) {
      echo "<center><h1><font color=red>Warning...</FONT></h1><BR>";
      echo "<B>Your css file is <#__root>/components/com_simpleboard/template/".$sbConfig['template']."/forum.css</b><BR>";
      echo "<B>You need to chmod this to 766 in order for the config to be updated</B></center><BR><BR>";
      }
      HTML_SIMPLEBOARD::showCss($file,$option);

   }

   function saveCss($file,$csscontent, $option) {
   echo "Saving css file should be here...file=$file";

      if (is_writable($file)==false){
         echo "<script>alert('The file is not writable.')</script>";
         echo "<script>document.location.href='index2.php?option=com_simpleboard&task=showCss'</script>\n";
      }

      echo "<script>alert('Simpleboard CSS file saved.')</script>";
      echo "<script>document.location.href='index2.php?option=com_simpleboard&task=showCss'</script>\n";
      if ($fp = fopen( $file, "w")) {
         fputs($fp,stripslashes($csscontent));
         fclose($fp);
         mosRedirect( "index2.php?option=$option&task=showCss", "Configuration file saved" );
      } else {
         mosRedirect( "index2.php?option=$option", "FATAL ERROR: File could not be opened." );
      }
   }

//===============================
// Moderator Functions
//===============================

   function newModerator ($option, $id=null) {
   global $database;
   //die ("New Moderator");

   $limit = intval( mosGetParam( $_POST, 'limit', 10 ) );
   $limitstart = intval( mosGetParam( $_POST, 'limitstart', 0 ) );

   $database->setQuery("SELECT * FROM #__users AS a"
   . "\n LEFT JOIN #__sb_users AS b"
   . "\n ON a.id=b.userid"
   . "\n WHERE b.moderator=1 LIMIT $limitstart,$limit");
   $userList=$database->loadObjectList();
   $countUL=count($userList);

   $database->setQuery( "SELECT COUNT(*) FROM #__users AS a"
   . "\n LEFT JOIN #__sb_users AS b"
   . "\n ON a.id=b.userid where b.moderator=1" );
   $total = $database->loadResult();

   if ($limit > $total) {
      $limitstart = 0;
   }
   require_once( "includes/pageNavigation.php" );
   $pageNav = new mosPageNav( $total, $limitstart, $limit  );

   //$id = intval( mosGetParam( $_POST, 'id') );
   //get forum name
   $forumName='';
   $database->setQuery("select name from #__sb_categories where id=$id");
   $forumName=$database->loadResult();
   //get forum moderators
   $database->setQuery("select userid from #__sb_moderation where catid=$id");
   $moderatorList=$database->loadObjectList();

   $moderators=0;
   $modIDs[]=array();

   if(count($moderatorList)>0)
   {
      foreach($moderatorList as $ml)
      {
         $modIDs[]=$ml->userid;
      }
      $moderators=1;
   }else{$moderators=0;}

   HTML_SIMPLEBOARD::newModerator ($option, $id, $moderators, $modIDs,$forumName, $userList,$countUL,$pageNav);
   }

   function addModerator( $option, $id, $cid=null, $publish=1 ) {

   global $database, $my;
   $numcid=count($cid);
   $action ="";
   if ($publish==1) {$action = 'add';}else{$action = 'remove';}

   if (!is_array( $cid ) || count( $cid ) < 1) {
      echo "<script> alert('Select an moderator to $action'); window.history.go(-1);</script>\n";
      exit;
   }

   if ($action=='add')
   {
      for ($i=0, $n=count( $cid ); $i < $n; $i++)
      {
         $database->setQuery( "INSERT INTO #__sb_moderation set catid='$id', userid='$cid[$i]'");
         if (!$database->query()) {
            echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
            exit();
         }
      }
   }
   else {
      for ($i=0, $n=count( $cid ); $i < $n; $i++)
      {
         $database->setQuery( "DELETE FROM #__sb_moderation WHERE catid='$id' and userid='$cid[$i]'");
         if (!$database->query()) {
            echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
            exit();
         }
      }
   }

   $row = new sbForum( $database );
   $row->checkin($id);

   mosRedirect( "index2.php?option=$option&task=edit2&uid=".$id );

   }

//===============================
//   User Profile functions
//===============================
   function showProfiles($database, $option, $mosConfig_lang, $order) {
   global $mainframe;
      $limit = intval( mosGetParam( $_POST, 'limit', 10 ) );
      $limitstart = intval( mosGetParam( $_POST, 'limitstart', 0 ) );

      $search = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
      $search = $database->getEscaped( trim( strtolower( $search ) ) );

      $where = array();
      if (isset( $search ) && $search!= "") {
         $where[] = "(u.username LIKE '%$search%' OR u.email LIKE '%$search%' OR u.name LIKE '%$search%')";
      }

      if      ($order == 1)
      {
         $database->setQuery("select * from #__sb_users AS sbu"
                        . "\n LEFT JOIN #__users AS u"
                        . "\n ON sbu.userid=u.id "
                        . (count( $where ) ? "\nWHERE " . implode( ' AND ', $where ) : "")
                        . "\n ORDER BY sbu.moderator DESC"
                        . "\n LIMIT $limitstart,$limit");
      }
      else if ($order == 2)
      {
         $database->setQuery("SELECT * FROM #__sb_users AS sbu"
                        . "\n LEFT JOIN #__users AS u "
                        . "\n ON sbu.userid=u.id "
                        . (count( $where ) ? "\nWHERE " . implode( ' AND ', $where ) : "")
                        . "\n ORDER BY u.name ASC "
                        . "\n LIMIT $limitstart,$limit");
      }
      else if ($order < 1 )
      {
         $database->setQuery("SELECT * FROM #__sb_users AS sbu "
                        . "\n LEFT JOIN #__users AS u"
                        . "\n ON sbu.userid=u.id "
                        . (count( $where ) ? "\nWHERE " . implode( ' AND ', $where ) : "")
                        . "\n ORDER BY sbu.userid"
                        . "\n LIMIT $limitstart,$limit");}

      $profileList=$database->loadObjectList();
      $countPL=count($profileList);
      $database->setQuery( "SELECT COUNT(*) FROM #__sb_users AS sbu"
                      . "\n LEFT JOIN #__users AS u"
                      . "\n ON sbu.userid=u.id"
                           . (count( $where ) ? "\nWHERE " . implode( ' AND ', $where ) : "")
                         );
      $total = $database->loadResult();
      if ($limit > $total) {
         $limitstart = 0;
      }

      require_once( "includes/pageNavigation.php" );
      $pageNavSP = new mosPageNav( $total, $limitstart, $limit  );

   HTML_SIMPLEBOARD::showProfiles( $option, $mosConfig_lang,$profileList,$countPL,$pageNavSP,$order, $search);
   }



   function editUserProfile ($option,$database, $uid) {
   global $acl;
      $database->setQuery("SELECT * FROM #__sb_users LEFT JOIN #__users on #__users.id=#__sb_users.userid WHERE userid=$uid[0]");
      $userDetails=$database->loadObjectList();
      //Mambo userids are unique, so we don't worry about that
      foreach($userDetails as $user)
      {
         $prefview  = $user->view     ;
         $ordering  = $user->ordering ;
         $moderator = $user->moderator;
      }

      //check to see if this is an administrator
      $result='';
      //$database->setQuery("select usertype from #__users where id=$uid[0]");
      //$result=$database->loadResult();
      $result = $acl->getAroGroup( $uid[0] );
      if (strtolower($result->name)=='administrator' || strtolower($result->name)=='super administrator')
      {
         $is_an_admin=1;
      }else{
         $is_an_admin=0;
      }

      // make the select list for the view type
      $yesno[] = mosHTML::makeOption( 'flat', 'Flat' );
      $yesno[] = mosHTML::makeOption( 'threaded', 'Threaded' );

      // build the html select list
      $selectPref = mosHTML::selectList( $yesno, 'newview', 'class="inputbox" size="2"', 'value', 'text', $prefview );

      // make the select list for the moderator flag
      $yesnoMod[] = mosHTML::makeOption( '1', 'Yes' );
      $yesnoMod[] = mosHTML::makeOption( '0', 'No' );

      // build the html select list
      $selectMod = mosHTML::selectList( $yesnoMod, 'moderator', 'class="inputbox" size="2"', 'value', 'text', $moderator );
      // make the select list for the moderator flag
      $yesnoOrder[] = mosHTML::makeOption( '0', 'First post first' );
      $yesnoOrder[] = mosHTML::makeOption( '1', 'Last post first' );

      // build the html select list
      $selectOrder = mosHTML::selectList( $yesnoOrder, 'neworder', 'class="inputbox" size="2"', 'value', 'text', $ordering );
      //get all subscriptions for this user
      $database->setQuery("select thread from #__sb_subscriptions where userid=$uid[0]");
      $subslist=$database->loadObjectList();

      HTML_SIMPLEBOARD::editUserProfile ($option,$database, $userDetails, $subslist, $selectPref,$selectMod,$selectOrder,$is_an_admin, $uid[0]);
   }

   function saveUserProfile( $option ) {
   global $database;

      $newview     =mosGetParam( $_POST, 'newview'     );
      $signature   =mosGetParam( $_POST, 'message'   );
      $deleteSig   =mosGetParam( $_POST, 'deleteSig'   );
      $moderator   =mosGetParam( $_POST, 'moderator'   );
      $uid         =mosGetParam( $_POST, 'uid'         );
      $avatar      =mosGetParam( $_POST, 'avatar'      );
      $deleteAvatar=mosGetParam( $_POST, 'deleteAvatar');
      $neworder    =mosGetParam( $_POST, 'neworder'    );

      if($deleteSig    == 1){$signature="";}
      if($deleteAvatar == 1){$avatar="";}
      $database->setQuery("UPDATE #__sb_users set signature='$signature', view='$newview',moderator='$moderator', avatar='$avatar', ordering=$neworder where userid=$uid");
      if (!$database->query()) {
         echo "<script> alert('".$database->getErrorMsg()."'); index2.php?option=$option&task=showprofiles; </script>\n";
         exit();
      }else{
      mosRedirect( "index2.php?option=$option&task=showprofiles" );
      }
   }
//===============================
// Prune Forum functions
//===============================
function pruneforum ($database, $option)
{
   $forums_list   = array();
   //get forum list; locked forums are excluded from pruning
   $database->setQuery( "SELECT a.id as value, a.name as text"
   . "\nFROM #__sb_categories AS a"
   . "\nWHERE a.parent != '0'"
   . "\nAND a.locked != '1'"
   . "\nORDER BY parent, ordering");
   $forums_list=$database->loadObjectList();

   $forumList['forum'] = mosHTML::selectList( $forums_list, 'prune_forum' , 'class="inputbox" size="4"', 'value', 'text', ''    );


   HTML_SIMPLEBOARD::pruneforum( $option,$forumList);
}

function doprune ($database, $option)
{
   $catid   = intval( mosGetParam( $_POST, 'prune_forum', -1 ) );
   $deleted=0;

   if ($catid==-1)
   {
      echo "<script> alert('You must choose a forum to prune!'); window.history.go(-1); </script>\n";
      exit();
   }

   $prune_days = intval( mosGetParam( $_POST, 'prune_days', 0 ) );

   //get the thread list for this forum
   $database->setQuery("SELECT DISTINCT a.thread AS thread, max(a.time) AS lastpost, c.locked AS locked "
                   ."\n FROM #__sb_messages AS a"
                   ."\n JOIN #__sb_categories AS b ON a.catid=b.id "
                   ."\n JOIN #__sb_messages   AS c ON a.thread=c.thread"
                   ."\n where a.catid=$catid "
                   ."\n and b.locked != 1 "
                   ."\n and a.locked != 1 "
                   ."\n and c.locked != 1 "
                   ."\n and c.parent = 0 "
                   ."\n and c.ordering != 1 "
                   ."\n group by thread");

   $threadlist=$database->loadObjectList();

   // Convert days to seconds for timestamp functions...
   $prune_date = time() - ( $prune_days * 86400 );

   if (count($threadlist)>0)
   {
      foreach($threadlist as $tl)
      {
         //check if thread is eligible for pruning
         if($tl->lastpost<$prune_date)
         {
            //get the id's for all posts belonging to this thread
            $database->setQuery("SELECT id from #__sb_messages WHERE thread=$tl->thread");
            $idlist=$database->loadObjectList();

            if(count($idlist)>0)
            {
               foreach($idlist as $id)
               {
                  //prune all messages belonging to the thread
                  $database->setQuery("DELETE FROM #__sb_messages WHERE id=$id->id");
                  if (!$database->query()) {
                     echo "<script> alert('Deleting messages failed: ".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
                     exit();
                  }
                  $database->setQuery("DELETE FROM #__sb_messages_text WHERE mesid=$id->id");
                  if (!$database->query()) {
                     echo "<script> alert('Deleting messages texts failed: ".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
                  }
                  //delete all attachments
                     $database->setQuery("SELECT filelocation FROM #__sb_attachments WHERE mesid=$id->id");
                     $fileList=$database->loadObjectList();

                     if (count($fileList)>0){
                        foreach ($fileList as $fl){
                           unlink($fl->filelocation);
                        }
                        $database->setQuery("DELETE FROM #__sb_attachments WHERE mesid=$id->id");
                        $database->query();
                     }

                  $deleted++;
               }
            }
         }

         //clean all subscriptions to these deleted threads
         $database->setQuery("DELETE FROM #__sb_subscriptions WHERE thread=$tl->thread");
         if (!$database->query()) {
            echo "<script> alert('Clearing subscriptions failed: ".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
            exit();
         }
      }
   }

   mosRedirect ("index2.php?option=$option&task=pruneforum","Forum pruned for ".$prune_days." days; Deleted:".$deleted." threads");
}

//===============================
// Prune users
//===============================
function pruneusers( $database, $option)
{
    HTML_SIMPLEBOARD::pruneusers( $option);
}

function dousersprune($database, $option)
{
   //get userlist
   $database->setQuery("SELECT a.userid from #__sb_users as a left join #__users as b on a.userid=b.id where b.username is null");
   $idlist=$database->loadObjectList();

   $allIDs=array();
   $cids=count($idlist);
   if ($cids>0)
   {
      foreach($idlist as $id)
      {
         $allIDs[]=$id->userid;
      }

      $ids=implode(',',$allIDs);
   }

   if (!$ids == "")
   {
      $database->setQuery("DELETE FROM #__sb_users WHERE userid in ($ids)");
      if (!$database->query()) {
         echo "<script> alert('Error pruning users: ".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
         exit();
      }
      mosRedirect ("index2.php?option=$option&task=pruneusers","Users pruned; Deleted:".$cids." user profiles");
   }
   else
   {
      $cids=0;
      mosRedirect ("index2.php?option=$option&task=pruneusers","No profiles found eligible for pruning.");
   }

}


//===============================
// Upgrade tables to this version 
//===============================
function upgradeTables ($option) {
global $database;
global $sbConfig;
   $v=$sbConfig['version'];

//create the smileys_table   
	$database->setQuery("CREATE TABLE IF NOT EXISTS `#__sb_smileys` ("
	. "\n `id` int(4) NOT NULL auto_increment,"
	. "\n `code` varchar(12) NOT NULL default '',"
	. "\n `location` varchar(50) NOT NULL default '',"
	. "\n `greylocation` varchar(60) NOT NULL default '',"
	. "\n `emoticonbar` tinyint(4) NOT NULL default '0',"
	. "\n PRIMARY KEY  (`id`)"
	. "\n ) TYPE=MyISAM");
   if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); ; </script>\n";
      mosRedirect( "index2.php?option=com_simpleboard", "Something went awfully wrong upgrading your database; try again from the Simpleboard control panel. If the problem persists, open administrator/components/com_simpleboard/admin.simpleboard.php file and search for smileys_table and execute the code manually.." );
   }	
//truncate the smileys table, just to limit the number of errors we could get..   
	$database->setQuery("TRUNCATE `#__sb_smileys`");
	$database->query();
	
//fill the smileys table
	$database->setQuery("INSERT INTO `#__sb_smileys` VALUES (1, 'B)', 'cool.png', 'cool-grey.png', 1),"
	. "\n (8, ';)', 'wink.png', 'wink-grey.png', 1),"
	. "\n (3, ':)', 'smile.png', 'smile-grey.png', 1),"
	. "\n (10, ':P', 'tongue.png', 'tongue-grey.png', 1),"
	. "\n (6, ':laugh:', 'laughing.png', 'laughing-grey.png', 1),"
	. "\n (17, ':ohmy:', 'shocked.png', 'shocked-grey.png', 1),"
	. "\n (22, ':sick:', 'sick.png', 'sick-grey.png', 1),"
	. "\n (14, ':angry:', 'angry.png', 'angry-grey.png', 1),"
	. "\n (25, ':blink:', 'blink.png', 'blink-grey.png', 1),"
	. "\n (2, ':(', 'sad.png', 'sad-grey.png', 1),"
	. "\n (16, ':unsure:', 'unsure.png', 'unsure-grey.png', 1),"
	. "\n (27, ':kiss:', 'kissing.png', 'kissing-grey.png', 1),"
	. "\n (29, ':woohoo:', 'w00t.png', 'w00t-grey.png', 1),"
	. "\n (21, ':lol:', 'grin.png', 'grin-grey.png', 1),"
	. "\n (23, ':silly:', 'silly.png', 'silly-grey.png', 1),"
	. "\n (35, ':pinch:', 'pinch.png', 'pinch-grey.png', 1),"
	. "\n (30, ':side:', 'sideways.png', 'sideways-grey.png', 1),"
	. "\n (34, ':whistle:', 'whistling.png', 'whistling-grey.png', 1),"
	. "\n (33, ':evil:', 'devil.png', 'devil-grey.png', 1),"
	. "\n (31, ':S', 'dizzy.png', 'dizzy-grey.png', 1),"
	. "\n (26, ':blush:', 'blush.png', 'blush-grey.png', 1),"
	. "\n (7, ':cheer:', 'cheerful.png', 'cheerful-grey.png', 1),"
	. "\n (18, ':huh:', 'wassat.png', 'wassat-grey.png', 1),"
	. "\n (19, ':dry:', 'ermm.png', 'ermm-grey.png', 1),"
	. "\n (4, ':-)', 'smile.png', 'smile-grey.png', 0),"
	. "\n (5, ':-(', 'sad.png', 'sad-grey.png', 0),"
	. "\n (9, ';-)', 'wink.png', 'wink-grey.png', 0),"
	. "\n (37, ':D', 'laughing.png', 'laughing-grey.png', 0),"
	. "\n (12, ':X', 'sick.png', 'sick-grey.png', 0),"
	. "\n (13, ':x', 'sick.png', 'sick-grey.png', 0),"
	. "\n (15, ':mad:', 'angry.png', 'angry-grey.png', 0),"
	. "\n (20, ':ermm:', 'ermm.png', 'ermm-grey.png', 0),"
	. "\n (24, ':y32b4:', 'silly.png', 'silly-grey.png', 0),"
	. "\n (28, ':rolleyes:', 'blink.png', 'blink-grey.png', 0),"
	. "\n (32, ':s', 'dizzy.png', 'dizzy-grey.png', 0),"
	. "\n (36, ':p', 'tongue.png', 'tongue-grey.png', 0)");
	$database->query();
    
//alter messages table and add the 'moved' column
   $database->setQuery("ALTER TABLE `#__sb_messages`"
   . "\n CHANGE `future3` `moved` TINYINT DEFAULT '0'");
   $database->query();
//set all moved fields in existing records to 0 (zero) instead of NULL
	$database->setQuery("UPDATE `#__sb_messages` SET `moved` = '0' WHERE `moved` = NULL");
	$database->query();
	
mosRedirect( "index2.php?option=com_simpleboard", "Simpleboard Tables are upgraded to version $v." );
}

//===============================
// Load Sample Data
//===============================

function loadSample( $database,$option ) {

$database->setQuery("INSERT INTO `#__sb_categories` VALUES (1, 0, 'Forum Category', 0, 0, 0, 0, NULL, 0, 0, 0, 0, 1, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, '')");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()." -- Make absolutely sure that you load the sample data on completely empty Simpleboard tables. If anything is in them, it will not work!'); window.history.go(-1); </script>\n";
      exit();
   }

$database->setQuery("INSERT INTO `#__sb_categories` VALUES (2, 1, 'Forum 1', 0, 0, 0, 0, NULL, 0, 0, 0, 0, 1, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'Sample Forum 1\r\n')");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()." -- Make absolutely sure that you load the sample data on completely empty Simpleboard tables. If anything is in them, it will not work!'); window.history.go(-1); </script>\n";
      exit();
   }
$database->setQuery("INSERT INTO `#__sb_messages` VALUES (1, 0, 1, 2, 'tsmf', 0, 'jan@tsmf-mambo.com', 'Sample Post 1', 1075387732, '127.0.0.1', 0, 0, 0, 0, 2, NULL)");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()." -- Make absolutely sure that you load the sample data on completely empty Simpleboard tables. If anything is in them, it will not work!'); window.history.go(-1); </script>\n";
      exit();
   }
$database->setQuery("INSERT INTO `#__sb_messages_text` VALUES (1, '[b][size=4][color=#FF6600]Sample Post[/color][/size][/b]\nCongratulations with your new Forum!\n\n[url=http://tsmf.jigsnet.com]-The TSMF Team[/url]')");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()." -- Make absolutely sure that you load the sample data on completely empty Simpleboard tables. If anything is in them, it will not work!'); window.history.go(-1); </script>\n";
      exit();
   }
mosRedirect( "index2.php?option=$option", "Sample data loaded" );

}

//===============================
// Create Community Builder profile
//===============================

function loadCBprofile( $database,$option ) {

$database->setQuery("INSERT INTO #__comprofiler_tabs SET title='_UE_SB_TABTITLE', description='_UE_SB_TABDESC'");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }
$database->setQuery("SELECT tabid FROM #__comprofiler_tabs WHERE title='_UE_SB_TABTITLE'");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }
$tabid=$database->loadResult();

$database->setQuery("INSERT INTO #__comprofiler_fields SET name='sbviewtype', title='_UE_SB_VIEWTYPE_TITLE', type='select', ordering='1', published='1', profile='0', calculated='0', sys='0', tabid=$tabid");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }
$database->setQuery("SELECT fieldid FROM #__comprofiler_fields WHERE name='sbviewtype'");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }
$fieldid=$database->loadResult();

$database->setQuery("INSERT INTO #__comprofiler_field_values SET fieldid=$fieldid, fieldtitle='_UE_SB_VIEWTYPE_FLAT', ordering='1'");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }
$database->setQuery("INSERT INTO #__comprofiler_field_values SET fieldid=$fieldid, fieldtitle='_UE_SB_VIEWTYPE_THREADED', ordering='2'");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }

$database->setQuery("INSERT INTO #__comprofiler_fields SET name='sbordering', title='_UE_SB_ORDERING_TITLE', type='select', ordering='2', published='1', profile='0', calculated='0', sys='0', tabid=$tabid");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }
$database->setQuery("SELECT fieldid FROM #__comprofiler_fields WHERE name='sbordering'");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }
$fieldid=$database->loadResult();

$database->setQuery("INSERT INTO #__comprofiler_field_values SET fieldid=$fieldid, fieldtitle='_UE_SB_ORDERING_OLDEST', ordering='1'");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }
$database->setQuery("INSERT INTO #__comprofiler_field_values SET fieldid=$fieldid, fieldtitle='_UE_SB_ORDERING_LATEST', ordering='2'");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }

$database->setQuery("INSERT INTO #__comprofiler_fields SET name='sbsignature', title='_UE_SB_SIGNATURE', type='textarea', maxlength='300', cols='60', rows='5', ordering='3', published='1', profile='0', calculated='0', sys='0', tabid=$tabid");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }

$database->setQuery("ALTER TABLE #__comprofiler ADD sbviewtype varchar(255) DEFAULT '_UE_SB_VIEWTYPE_FLAT' NOT NULL");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }
$database->setQuery("ALTER TABLE #__comprofiler ADD sbordering varchar(255) DEFAULT '_UE_SB_ORDERING_OLDEST' NOT NULL");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }
$database->setQuery("ALTER TABLE #__comprofiler ADD sbsignature mediumtext");
if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
      exit();
   }

mosRedirect( "index2.php?option=$option", "Community Builder profile added" );

}

//===============================
// Uploaded Images browser
//===============================

function browseUploaded( $database,$option, $type ) {

   if ($type) { //we're doing images
      $dir = @opendir('../components/com_simpleboard/uploaded/images');
      $uploaded_path='../components/com_simpleboard/uploaded/images';
   }else { //we're doing regular files
      $dir = @opendir('../components/com_simpleboard/uploaded/files');
      $uploaded_path='../components/com_simpleboard/uploaded/files';
   }
   $uploaded = array();
   $uploaded_col_count = 0;
   while( $file = @readdir($dir) )
   {

      if( $file != '.' && $file != '..' && is_file($uploaded_path . '/' . $file) && !is_link($uploaded_path. '/' . $file) )
      {
            //if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $file) )
            //{
               $uploaded[$uploaded_col_count] = $file;
               $uploaded_name[$uploaded_col_count] = ucfirst(str_replace("_", " ", preg_replace('/^(.*)\..*$/', '\1', $file)));
               $uploaded_col_count++;
            //}
       }
   }

   @closedir($dir);

   @ksort($uploaded);
   @reset($uploaded);
   HTML_SIMPLEBOARD::browseUploaded ($option, $uploaded, $uploaded_path, $type);

}

function replaceImage( $database,$option,$imageName, $OxP ) {
   // This function will replace the selected image with a dummy (OxP=1) or delete it
   // step 1: Remove image that must be replaced:
   unlink ('../components/com_simpleboard/uploaded/images/'.$imageName);

   if ($OxP == "1") {
      // step 2: the file name, without the extension:
      $filename= split("\.", $imageName);
      $fileName=$filename[0];
      $fileExt=$filename[1];
      // step 3: copy the dummy and give it the old file name:
      copy ('../components/com_simpleboard/uploaded/dummy.'.$fileExt, '../components/com_simpleboard/uploaded/images/'.$imageName);
   }
   else {
      //remove the database link as well
      $database->setQuery("DELETE FROM #__sb_attachments where filelocation='".$_CONFIG->SITEPATH."/components/com_simpleboard/uploaded/images/".$imageName."'");
      $database->query();
   }

   mosRedirect( "index2.php?option=$option&task=browseImages", "Image deleted" );

}

function deleteFile( $database,$option,$fileName) {
   global $mosConfig_absolute_path, $mosConfig_admin_template, $mosConfig_live_site;
	global $_CONFIG;
	
	if ($_CONFIG->SITEPATH == "") {
		$_CONFIG->SITEPATH=$mosConfig_absolute_path;
		$_CONFIG->SITEURL = $mosConfig_live_site;
	}
   // step 1: Remove file
   unlink ('../components/com_simpleboard/uploaded/files/'.$fileName);
   //step 2: remove the database link to the file
   $database->setQuery("DELETE FROM #__sb_attachments where filelocation='".$_CONFIG->SITEPATH."/components/com_simpleboard/uploaded/files/".$fileName."'");
   $database->query();

   mosRedirect( "index2.php?option=$option&task=browseFiles", "File deleted" );

}
//===============================
// Generic Functions
//===============================
   function debug($message){ //function for debugging; don't remove!
   echo "DEBUG: $message<br />";
   }

   function fmodReplace($x,$y)
   { //function provided for older PHP versions which do not have an fmod function yet
      $i = floor($x/$y);
      // r = x - i * y
      return $x - $i*$y;
   };
?>