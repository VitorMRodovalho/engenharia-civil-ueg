<?
/**
* @version $Id: uninstall.smf.php,v 1.2 2005/03/28 01:13:25 Cowboy1015 Exp $
* @package com_mambo_smf_integration
* @copyright (C) MamboHacks.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Visit MamboHacks.com for more Joomla hacks!
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function com_uninstall()
{

	global $mosConfig_absolute_path, $smf_path;

	include_once( $mosConfig_absolute_path . "/administrator/components/com_smf/config.smf.php" );
	$bakfile = $mosConfig_absolute_path .'/index.php~jsfbak~';
	$origfile = $mosConfig_absolute_path .'/index.php';

	if (file_exists($bakfile)) {
		if (!(copy($bakfile,$origfile))) {
			mosRedirect( 'index2.php?option=com_installer&element=component', 'Operation Failed: Could not rename backup file '.$bakfile );
		}
		unlink($bakfile);
	}

	$bakfile = $smf_path .'/index.php~jsfbak~';
	$origfile = $smf_path .'/index.php';

	if (file_exists($bakfile)) {
		if (!(copy($bakfile,$origfile))) {
			mosRedirect( 'index2.php?option=com_installer&element=component', 'Operation Failed: Could not rename backup file '.$bakfile );
		}
		unlink($bakfile);
	}
}

?>