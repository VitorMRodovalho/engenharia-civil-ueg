<?php
/**
* Letterman Newsletter Component
* 
* @package Mambo_4.5.1
* @subpackage Letterman
Authors:
* @copyright Soeren Eberhardt <soeren@mambo-phpshop.net>
    (who just needed an easy and *working* Newsletter component for Mambo 4.5.1 and mixed up Newsletter and YaNC)
* @copyright Mark Lindeman <mark@pictura-dp.nl> 
    (parts of the Newsletter component by Mark Lindeman; Pictura Database Publishing bv, Heiloo the Netherland)
* @copyright Adam van Dongen <adam@tim-online.nl>
    (parts of the YaNC component by Adam van Dongen, www.tim-online.nl)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class MENU_letterman {
	/**
	* Draws the menu for a New Contact
	*/
	function EDIT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function SEND_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::publish( "sendMail" );
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function DEFAULT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::publish();
		mosMenuBar::unpublish();
		mosMenuBar::divider();
		mosMenuBar::addNew();
		mosMenuBar::editList();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	function SUBSCRIBE_MENU() {
	  global $mosConfig_live_site;
		mosMenuBar::startTable();
		mosMenuBar::addNew( "editSubscriber", LM_NEW_SUBSCRIBER );
		mosMenuBar::editList( "editSubscriber", LM_EDIT_SUBSCRIBER );
		mosMenuBar::deleteList( "", "deleteSubscriber" );
		mosMenuBar::divider();
		$href = "javascript:submitbutton('assignUsers')";
		?>
		<td>
		<a class="toolbar" href="<?php echo $href;?>" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('users','','<?php echo $mosConfig_live_site."/components/com_letterman/images/user_f2.png" ?>',1);">
		<img name="users" src="<?php echo $mosConfig_live_site."/components/com_letterman/images/user.png" ?>" alt="assignUsers" border="0" align="middle" />
		&nbsp;
		<?php echo LM_ASSIGN_USERS; ?>
		</a>
		</td>
  <?php
		mosMenuBar::divider();
		mosMenuBar::custom( 'importSubscribers', 'upload.png', 'upload_f2.png', LM_IMPORT_USERS, false );
		mosMenuBar::custom( 'exportSubscribers', 'archive.png', 'archive_f2.png', LM_EXPORT_USERS, false );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	function SUBSCRIBER_EDIT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::save( "saveSubscriber" );
		mosMenuBar::cancel( "subscribers" );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	function SUBSCRIBER_IMPORT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::save( "importSubscribers" );
		mosMenuBar::cancel( "subscribers" );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
}?>
