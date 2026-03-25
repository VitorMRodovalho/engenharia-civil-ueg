<?
/**
* @version $Id: install.smf.php,v 1.3 2005/03/28 01:13:25 Cowboy Exp $
* @package com_mambo_smf
* @copyright (C) JoomlaHacks.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Visit JoomlaHacks.com for more joomla hacks!
*/

function com_install() {
global $database, $mosConfig_absolute_path;

# Set up new icons for admin menu
$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/config.png' WHERE admin_menu_link='option=com_smf&task=config'");
$iconresult[1] = $database->query();

# Show installation result to user
?>
<center>
<table width="100%" border="0">
  <tr>
    <td>
      <strong>Joomla-SMF Forum</strong><br/>
      <br/>
      This component is released under the terms and conditions of the <a href="index2.php?option=com_admisc&task=license">GNU General Public License</a>.
      <br/>
      Please visit <a href="http://www.JoomlaHacks.com">JoomlaHacks.com</a> for updates.
    </td>
  </tr>
  <tr>
    <td>
      <code>Installation: <font color="green">successful</font></code>
    </td>
  </tr>
</table>
</center>

<?
}
?>
