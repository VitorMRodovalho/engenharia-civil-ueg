<?php
	/**
	 *	Jobline Component for Joomla 1.5
 	 *
	 *	Copyright (C) 2006 Olle Johansson
	 *	Distributed under the terms of the GNU General Public License
	 *	This software may be used without warrany provided and
	 *  copyright statements are left intact.
     */
function com_install() {
	global $database, $mosConfig_absolute_path;

	$errmsg = "";

	// Change the image for the Job List Administration submenu item in the admin section.
	$sql = "UPDATE #__components SET admin_menu_img='js/ThemeOffice/content.png' WHERE admin_menu_link='option=com_jobline&task=list'";
	$database->setQuery($sql);
	if ( !$database->query() ) {
		$errmsg .= "Couldn't update image for Job Postings submenu item.<br />\n";
	}

	// Change the image for the Posting Approval Queue Administration submenu item in the admin section.
	$sql = "UPDATE #__components SET admin_menu_img='js/ThemeOffice/checkin.png' WHERE admin_menu_link='option=com_jobline&task=queue'";
	$database->setQuery($sql);
	if ( !$database->query() ) {
		$errmsg .= "Couldn't update image for Posting Approval Queue submenu item.<br />\n";
	}

	// Change the image for the Configuration submenu item in the admin section.
	$sql = "UPDATE #__components SET admin_menu_img='js/ThemeOffice/config.png' WHERE admin_menu_link='option=com_jobline&task=conf'";
	$database->setQuery($sql);
	if ( !$database->query() ) {
		$errmsg .= "Couldn't update image for Configuration submenu item.<br />\n";
	}

	// Change the image for the Information submenu item in the admin section.
	$sql = "UPDATE #__components SET admin_menu_img='js/ThemeOffice/help.png' WHERE admin_menu_link='option=com_jobline&task=info'";
	$database->setQuery($sql);
	if ( !$database->query() ) {
		$errmsg .= "Couldn't update image for Information submenu item.<br />\n";
	}

	// Change the image for the List Templates submenu item in the admin section.
	$sql = "UPDATE #__components SET admin_menu_img='js/ThemeOffice/template.png' WHERE admin_menu_link='option=com_jobline&task=listtemplates'";
	$database->setQuery($sql);
	if ( !$database->query() ) {
		$errmsg .= "Couldn't update image for List Templates submenu item.<br />\n";
	}

/*
	// Install modules
	$modules = array( "mod_gtlinks_menu", "mod_gtlinks_categories" );
	foreach ( $modules as $mod ) {
		$modfile = "$mosConfig_absolute_path/components/com_gtlinks/$mod.zip";
		$base_Dir = "$mosConfig_absolute_path/uploadfiles/";
		if ( is_writable( $base_Dir ) ) {
			if ( !( @rename( $modfile, "$base_Dir/$mod.zip" ) && chmod( "$base_Dir/$mod.zip", 0777 ) ) ) {
				$errmsg .= "Couldn't copy module file $mod.zip to installation directory.<br />\n";
			} else {
				$modinstaller = new mosInstallerModule( "$mod.zip" );
				if( !$modinstaller->install() )
				{
					$errmsg .= "Error installing module $mod. " . $modinstaller->getError() . "<br />\n";
				}
				cleanupInstall( "$mod.zip", $modinstaller->unpackDir() );
			}
		} else {
			$errmsg .= "Directory uploadfiles is not writeable!<br />\n";
		}
	}
*/

	// Show installation error messages
	if ( $errmsg ) {
		HTML_installer::showInstallMessage( $errmsg, "Installation error messages", $option );
	}

	return "Jobline Successfully Installed. Visit <a href=\"http://joomla.theyard.org\" target=\"_blank\">Joomla at the Yard</a> for more exciting Joomla extensions.";
}
?>
