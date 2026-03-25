<?php /* $Id $ */

/**
* @version $Id: smf.class.php,v 1.3 2005/03/28 01:13:25 Cowboy1015 Exp $
* @package com_Joomla_smf_forum
* @copyright (C) JoomlaHacks.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Visit JoomlaHacks.com for more Joomla hacks!
*/

// Ensure this file is being included by a parent file.
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

class TOOLBAR_smf
{
	/**
	 * Draws the menu to add or edit an item
	 */
	function _EDIT()
	{
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function _DEFAULT()
	{
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
}

?>