<?php
/**
* AkoBook - A Mambo Guestbook Component
* @version 3.3
* @package AkoBook
* @copyright (C) 2003, 2004 by Arthur Konze
* @license Released under the terms of the GNU General Public License
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class menuakobook {

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

  function ABOUT_MENU() {
    mosMenuBar::startTable();
    mosMenuBar::back();
    mosMenuBar::spacer();
    mosMenuBar::endTable();
  }

   function LANG_MENU() {
    mosMenuBar::startTable();
    mosMenuBar::save( 'savelanguage', 'Save File' );
    mosMenuBar::cancel();
    mosMenuBar::spacer();
    mosMenuBar::endTable();
  }

  function DEFAULT_MENU() {
    mosMenuBar::startTable();
    mosMenuBar::addNew();
    mosMenuBar::editList();
    mosMenuBar::deleteList();
    mosMenuBar::spacer();
    mosMenuBar::endTable();
  }

}
?>
