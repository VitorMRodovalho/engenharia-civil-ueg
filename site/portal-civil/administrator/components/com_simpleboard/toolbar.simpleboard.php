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



require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ( $task ) {
   case "new":
   case "edit":
   case "edit2":
      TOOLBAR_simpleBoard::_EDIT();
      break;

   case "cancel":
      TOOLBAR_simpleBoard::DEFAULT_MENU();
      break;

   case "showconfig":
      TOOLBAR_simpleBoard::_EDIT_CONFIG();
      break;

   case "showCss":
      TOOLBAR_simpleBoard::CSS_MENU();
      break;

   case "profiles":
      TOOLBAR_simpleBoard::_PROFILE_MENU();
      break;

   case "instructions":
      break;

   case "newmoderator":
      TOOLBAR_simpleBoard::_NEWMOD_MENU();
      break;

   case "userprofile":
      TOOLBAR_simpleBoard::_EDITUSER_MENU();
      break;

   case "pruneforum":
      TOOLBAR_simpleBoard::_PRUNEFORUM_MENU();
      break;

   case "pruneusers":
      TOOLBAR_simpleBoard::_PRUNEUSERS_MENU();
      break;

   case "showAdministration":
      TOOLBAR_simpleBoard::_ADMIN();
      break;

   case "showprofiles":
      TOOLBAR_simpleBoard::_PROFILE_MENU();
      break;

   default:
      TOOLBAR_simpleBoard::BACKONLY_MENU();
      break;
}
?>
