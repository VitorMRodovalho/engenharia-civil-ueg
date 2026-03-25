<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );
require_once( $mainframe->getPath( 'toolbar_default' ) );

if ($act) $task = $act;

switch ($task) {
  case "new":
    menusygmund::NEW_MENU();
    break;

  case "edit":
    menusygmund::EDIT_MENU();
    break;

  case "settings":
    menusygmund::CONFIG_MENU();
    break;

  default:
    MENU_Default::MENU_Default();
    break;
}
?>