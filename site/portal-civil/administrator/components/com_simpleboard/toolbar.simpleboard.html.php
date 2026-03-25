<?php
//
// Copyright (C) 2003 Jan de Graaff
// All rights reserved.
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
// ################################################################

class TOOLBAR_simpleBoard {

   function _ADMIN() {
      mosMenuBar::startTable();
      mosMenuBar::spacer();
      mosMenuBar::publish();
      mosMenuBar::unpublish();
      //mosMenuBar::divider();
      mosMenuBar::addNew();
      mosMenuBar::editList();
      mosMenuBar::deleteList();
      //mosMenuBar::custom( 'move', 'move.png', 'move_f2.png', 'Move' );
      mosMenuBar::spacer();
      mosMenuBar::endTable();
   }

   function _EDIT() {
      mosMenuBar::startTable();
      mosMenuBar::spacer();
      mosMenuBar::save();
      mosMenuBar::cancel();
      //mosMenuBar::divider();
      //mosMenuBar::custom('addmoderator','publish.png','publish_f2.png','New Moderator');
      mosMenuBar::addNew('newmoderator', 'New Moderator');
      mosMenuBar::unpublish('removemoderator');
      mosMenuBar::spacer();
      mosMenuBar::endTable();
   }

   function _NEWMOD_MENU() {
      mosMenuBar::startTable();
      mosMenuBar::spacer();
      mosMenuBar::publish('addmoderator');
      mosMenuBar::unpublish('removemoderator');
     // mosMenuBar::divider();
      mosMenuBar::cancel();
      mosMenuBar::spacer();
      mosMenuBar::endTable();
   }
   function _EDIT_CONFIG() {

      mosMenuBar::startTable();
      mosMenuBar::spacer();
      mosMenuBar::save( 'saveconfig' );
      mosMenuBar::back();
      mosMenuBar::spacer();
      mosMenuBar::endTable();


   }
   function _EDITUSER_MENU() {

      mosMenuBar::startTable();
      mosMenuBar::spacer();
      mosMenuBar::save( 'saveuserprofile' );
      mosMenuBar::cancel('showprofiles','Back to the User Profiles');
      mosMenuBar::spacer();
      mosMenuBar::endTable();


   }
   function _PROFILE_MENU() {

      mosMenuBar::startTable();
      mosMenuBar::spacer();
      //mosMenuBar::editList( 'userprofile' );
      mosMenuBar::custom( 'userprofile','edit.png','edit_f2.png','Edit User Profile' );
      mosMenuBar::cancel();
      //mosMenuBar::back();
      mosMenuBar::spacer();
      mosMenuBar::endTable();


   }

   function CSS_MENU() {

      mosMenuBar::startTable();
      mosMenuBar::spacer();
      mosMenuBar::save('saveeditcss');
      mosMenuBar::cancel();
      mosMenuBar::spacer();
      mosMenuBar::endTable();


   }
   function _PRUNEFORUM_MENU() {
      mosMenuBar::startTable();
      mosMenuBar::spacer();
      mosMenuBar::custom( 'doprune','delete.png','delete_f2.png','Start Pruning',false );
      //mosMenuBar::divider();
      mosMenuBar::cancel();
      mosMenuBar::spacer();
      mosMenuBar::endTable();
   }
   function _PRUNEUSERS_MENU() {
      mosMenuBar::startTable();
      mosMenuBar::spacer();
      mosMenuBar::custom( 'dousersprune','delete.png','delete_f2.png','Start Pruning',false );
      //mosMenuBar::divider();
      mosMenuBar::cancel();
      mosMenuBar::spacer();
      mosMenuBar::endTable();
   }

   function BACKONLY_MENU() {
   mosMenuBar::startTable();
   mosMenuBar::back();
   mosMenuBar::endTable();
   }


      function DEFAULT_MENU() {

      mosMenuBar::startTable();

      //mosMenuBar::publish();

      //mosMenuBar::unpublish();

      mosMenuBar::deleteList();

      mosMenuBar::spacer();

      mosMenuBar::endTable();
   }
}?>
