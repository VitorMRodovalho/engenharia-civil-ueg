<?
/**
* AkoBook - A Mambo Guestbook Component
* @version 3.3
* @package AkoBook
* @copyright (C) 2003, 2004 by Arthur Konze
* @license Released under the terms of the GNU General Public License
**/

function com_install() {
  global $database, $mosConfig_absolute_path;

  # Show installation result to user
  ?>
  <center>
  <table width="100%" border="0">
    <tr>
      <td><img src="components/com_akobook/images/logo.png"></td>
      <td>
        <strong>AkoBook - A Mambo Guestbook Component</strong><br/>
        <font class="small">&copy; Copyright 2003, 2004 by Arthur Konze<br/>
        Released under the terms and conditions of the <a href="index2.php?option=com_admisc&task=license">GNU General Public License</a>.</font><br/>
      </td>
    </tr>
    <tr>
      <td background="E0E0E0" style="border:1px solid #999;" colspan="2">
        <code>Installation Process:<br />
        <?php
          # Set up new icons for admin menu
          $database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/edit.png' WHERE admin_menu_link='option=com_akobook&task=view'");
          $iconresult[0] = $database->query();
          $database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/config.png' WHERE admin_menu_link='option=com_akobook&task=config'");
          $iconresult[1] = $database->query();
          $database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/language.png' WHERE admin_menu_link='option=com_akobook&task=language'");
          $iconresult[2] = $database->query();
          $database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/credits.png' WHERE admin_menu_link='option=com_akobook&task=about'");
          $iconresult[3] = $database->query();
          foreach ($iconresult as $i=>$icresult) {
            if ($icresult) {
              echo "<font color='green'>FINISHED:</font> Image of menu entry $i has been corrected.<br />";
            } else {
              echo "<font color='red'>ERROR:</font> Image of menu entry $i could not be corrected.<br />";
            }
          }

        ?>
        <font color="green"><b>Installation finished.</b></font></code>
      </td>
    </tr>
  </table>
  </center>
  <?php
}
?>