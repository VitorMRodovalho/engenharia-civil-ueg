<?php /* $Id $ */

 /**
 * SMF Menubar Handler
 * @package JOOMLA-SMF bridge by JoomlaHacks.com
 * @license http://www.gnu.org/copyleft/gpl.html. GNU Public License
 * @version 1.0
 */

// Ensure this file is being included by a parent file.
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

require_once($mainframe->getPath('toolbar_html'));

switch ($task)
{
	case 'new':
	case 'edit':
		TOOLBAR_smf::_EDIT();
		break;
	default:
		TOOLBAR_smf::_DEFAULT();
	break;
}

?>