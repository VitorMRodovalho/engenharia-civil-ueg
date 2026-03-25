<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
class menusygmund {
  function NEW_MENU() {
    mosMenuBar::startTable();
    mosMenuBar::save();
    mosMenuBar::cancel();
    mosMenuBar::spacer();
    mosMenuBar::endTable();
  }
  function EDIT_MENU() {
    mosMenuBar::startTable();
    mosMenuBar::save();
    mosMenuBar::cancel();
    mosMenuBar::spacer();
    mosMenuBar::endTable();
  }
  function CONFIG_MENU() {
    mosMenuBar::startTable();
    mosMenuBar::save( 'savesettings', 'Save Settings' );
    mosMenuBar::back();
    mosMenuBar::spacer();
    mosMenuBar::endTable();
  }
}
?>