<?php
/**
* AkoBook - A Mambo Guestbook Component
* @version 3.3
* @package AkoBook
* @copyright (C) 2003, 2004 by Arthur Konze
* @license Released under the terms of the GNU General Public License
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );
require_once( $mainframe->getPath( 'toolbar_default' ) );

switch ($task) {
  case "new":
    menuakobook::NEW_MENU();
    break;

  case "edit":
    menuakobook::EDIT_MENU();
    break;

  case "config":
    menuakobook::CONFIG_MENU();
    break;

  case "about":
    menuakobook::ABOUT_MENU();
    break;

  case "language";
    menuakobook::LANG_MENU();
    break;

  default:
    MENU_Default::MENU_Default();
    break;
}
?>
