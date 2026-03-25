<?php
/**
* @version $Id: admin.jce.php,v 1.0 2006/02/12 12:27:00 Ryan Demmer$
* @package JCE Admin
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// ensure user has access to this function
if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
                | $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_jce' ))) {
        mosRedirect( 'index2.php', _NOT_AUTH );
}

$comp_path = $mosConfig_absolute_path.'/administrator/components/com_jce';
require_once( $mainframe->getPath( 'admin_html' ) );

require_once( $comp_path.'/layout.php' );
require_once( $comp_path.'/layout.html.php' );

require_once( $comp_path.'/plugins/plugins.php' );
require_once( $comp_path.'/languages/languages.php' );
require_once( $comp_path.'/installer/installer.php' );

switch ($task) {
  		case 'plugin':
	        $plugin = mosGetParam( $_GET, 'plugin' );
	        $file = mosGetParam( $_GET, 'file' );
	        $path = 'mambots/editors/jce/jscripts/tiny_mce/plugins/'.$plugin.'/'.$file;
	        include_once $mosConfig_absolute_path . DIRECTORY_SEPARATOR . $path;
   				break;
        case 'main':
                HTML_JCEAdmin::showAdmin();
                break;
        case 'save':
                saveconfig();
                break;
        case 'config':
                global $client;
                $query = "SELECT id"
				. "\n FROM #__mambots"
				. "\n WHERE element = 'jce' AND folder = 'editors'"
				;
				$database->setQuery( $query );
				$eid = $database->loadResult();
                mosRedirect( 'index2.php?option=com_mambots&client=' . $client . '&task=editA&hidemainmenu=1&id=' . $eid. '' );
                break;
        case 'editlayout':
                editLayout( $option, $client );
                break;
        case 'savelayout':
                saveLayout( $option, $client );
                break;
        case 'spell':
                showspell( $database, $option );
                break;
        case 'applyaccess':
                applyAccess( $cid, $option, $client );
                break;
        case 'showplugins':
        case 'plugins':
                viewPlugins( $option, $client );
                break;
        case 'publish':
	    case 'unpublish':
        		publishPlugins( $cid, ($task == 'publish'), $option, $client );
        		break;
        case 'newplugin':
        case 'editplugin':
		        editPlugins( $option, $id, $client );
		        break;
        case 'saveplugin':
    	case 'applyplugin':
        		savePlugins( $option, $client, $task );
        		break;
        case 'canceledit':
                cancelEdit( $option, $client );
                break;
         case 'removeplugin':
                removePlugin( $cid[0], $option, $client );
                break;
         case 'installplugin':
                mosRedirect( 'index2.php?option=com_jce&client=' . $client . '&task=install&element=plugins' );
                break;
        case 'install':
        		jceInstaller( $option, $client, 'show' );
                break;
        case 'uploadfile':
                jceInstaller( $option, $client, 'uploadfile' );
                break;
		case 'installfromdir':
                jceInstaller( $option, $client, 'installfromdir' );
			    break;
        case 'remove':
                jceInstaller( $option, $client, 'remove' );
                break;
        case 'lang':
                viewLanguages( $option );
                break;
        case 'newlang':
                mosRedirect( 'index2.php?option=com_jce&client=' . $client . '&task=install&element=language' );
                break;
        case 'removelang':
                removeLanguage( $cid[0], $option, $client );
                break;
        case 'publishlang':
                publishLanguage( $cid[0], $option, $client );
                break;
        default:
                HTML_JCEAdmin::showAdmin();
                break;
}
?>
