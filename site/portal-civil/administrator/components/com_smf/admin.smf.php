<?php
/**
* @version $Id: admin.smf.php,v 1.3 2005/03/28 01:13:25 Cowboy1015 Exp $
* @package com_joomla_smf_forum
* @copyright (C) JoomlaHacks.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Visit JoomlaHacks.com for more joomla hacks!
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

if (!defined("_MOS_ALLOWHTML")) {
	define( "_MOS_ALLOWHTML", 0x0002 );
}

// get language file
if( file_exists( $mosConfig_absolute_path . '/administrator/components/com_smf/language/' . $mosConfig_lang . '.php' ) ) {
	include( $mosConfig_absolute_path . '/administrator/components/com_smf/language/' . $mosConfig_lang . '.php' );
}else{
	include( $mosConfig_absolute_path. '/administrator/components/com_smf/language/english.php' );
}

$mosLang =& new MamboSMFLanguage();
// end language

include_once( $mosConfig_absolute_path . '/administrator/components/com_smf/functions.smf.php' );
include_once( $mosConfig_absolute_path. '/administrator/components/com_smf/smf.class.php' );
$mosSMF =& new mosSMF();

if (empty($_SESSION['sync_done'])) $_SESSION['sync_done'] = true;

switch ($task) {
	case "config":
		showConfig($option);
		break;
	case "cancel":
		showConfig($option);
		break;
	case "":
		showConfig($option);
		break;
	case "save":
    	saveConfig ($option,$smf_path,$smf_redirect,$smf_prefix,$wrapped,$hideemail,$cbprofile,$registration_module);
    	break;
    case "apply";
		if (!insertCode()) {
			mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_FAILED.": ".$mosLang->MSG_ERR_TEMPLATE_NOT_WRITEABLE);
		}
		mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_SUCCESS);
		break;
	case "chmodwrite";
		if (chmodFiles('0777')) {
			$msg=$mosLang->MSG_SUCCESS;
		} else {
			$msg=$mosLang->MSG_FAILED." : ".$mosLang->MSG_PLEASE_SET_CHMOD_MANUALLY;
		}
		mosRedirect( "index2.php?option=$option&task=config", $msg);
		break;
	case "chmodsecure";
		if (chmodFiles('0644')) {
			$msg=$mosLang->MSG_SUCCESS;
		} else {
			$msg=$mosLang->MSG_FAILED." : ".$mosLang->MSG_PLEASE_SET_CHMOD_MANUALLY;
		}
		mosRedirect( "index2.php?option=$option&task=config", $msg);
		break;
	case "checksync";
		showConfig($option);
		break;
    case "sync";
    	include_once( $mosConfig_absolute_path . "/administrator/components/com_smf/config.smf.php" );
    	$count1 = syncSMFtoMambo();
       	$count2 = syncMamboToSMF();
       	$_SESSION['sync_done'] = true;
		mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_SUCCESS." : Mambo[".$count1."] / SMF[".$count2."] ". $mosLang->MSG_INSERTED);
		break;
    case "patch";
    	include_once( $mosConfig_absolute_path . "/administrator/components/com_smf/config.smf.php" );
    	if (!patchMamboIndexFile($file)) {
			mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_FAILED.": ".$file." ".$mosLang->MSG_NOT_WRITABLE);
		}
		mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_SUCCESS);
		break;
    case "patchsmf";
    	include_once( $mosConfig_absolute_path . "/administrator/components/com_smf/config.smf.php" );
    	if (!patchFile($smf_path."/index.php",$mosSMF->oldSMFCodeLine(),$mosSMF->newSMFCodeLine())) {
			mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_FAILED.": ".$smf_path."/index.php"." ".$mosLang->MSG_NOT_WRITABLE);
		}
		if (!patchFile($smf_path."/Sources/Subs-Post.php",$mosSMF->oldSubsPostCodeLine(),$mosSMF->newSubsPostCodeLine())) {
			mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_FAILED.": ".$smf_path."/Sources/Subs-Post.php"." ".$mosLang->MSG_NOT_WRITABLE);
		}
		mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_SUCCESS);
		break;
    case "unpatchsmf";
    	include_once( $mosConfig_absolute_path . "/administrator/components/com_smf/config.smf.php" );
    	if (!unpatchFile($smf_path."/index.php")) {
			mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_FAILED.": ".$smf_path."/index.php"." ".$mosLang->MSG_NOT_WRITABLE);
		}
    	if (!unpatchFile($smf_path."/Sources/Subs-Post.php")) {
			mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_FAILED.": ".$smf_path."/Sources/Subs-Post.php"." ".$mosLang->MSG_NOT_WRITABLE);
		}
		mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_SUCCESS);
		break;
    case "unpatch";
    	include_once( $mosConfig_absolute_path . "/administrator/components/com_smf/config.smf.php" );
    	if (!unpatchMamboIndexFile($file)) {
			mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_FAILED.": ".$file." ".$mosLang->MSG_NOT_WRITABLE);
		}
		mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_SUCCESS);
		break;
}

function showConfig($option) {
	global $mosConfig_absolute_path, $database, $mosConfig_live_site, $mosLang, $mosConfig_dbprefix, $mosSMF;
	$tabs = new mosTabs(0);

	include_once( $mosConfig_absolute_path . "/administrator/components/com_smf/config.smf.php" );
	include_once( $mosConfig_absolute_path . "/administrator/components/com_smf/functions.smf.php" );

?>

	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
	<script language="JavaScript" src="<?php echo $mosConfig_live_site; ?>/includes/js/overlib_mini.js" type="text/javascript"></script>

	<table class="adminheading">
	<tr>
		<th class="config"><?php echo $mosLang->HEADER_MAIN; ?></span>
		</span></th>
	</tr>
	</table>

    <script language="javascript" type="text/javascript">
    	<!--
    	function submitbutton(pressbutton) {
    		var form = document.adminForm;
    		submitform( pressbutton );
    		return;
    	}
    	//-->
    </script>

    <form action="index2.php" method="POST" name="adminForm">

    	<?php /* this is outdated since mambo 4.5.1 !! <script language="javascript" src="js/dhtml.js"></script> */ ?>
    	<!--<script language="javascript" type="text/javascript" src="../includes/js/mambojavascript.js"></script>-->

    	<?php $tabs->startPane( 'configPane' ); ?>


		<?php $tabs->startTab( $mosLang->TAB_CONFIGURATION_TITLE, 'general-page' ); ?>

		<table width="100%" border="0" cellpadding="2" cellspacing="2">
		<tr>
		<td valign="top">

    	<table width="100%" border="0" cellpadding="2" cellspacing="2" class="adminForm">
    	<tr>
    		<td colspan="2"><b><?php echo $mosLang->HEADER_PERMISSION_SETTINGS; ?></b></td>
    	</tr>
    	<tr>
    		<td align="left" colspan="2">
    		<?php
    			$configFile		= $mosConfig_absolute_path . '/administrator/components/com_smf/config.smf.php';
    			$cacheDir 		= $mosConfig_absolute_path."/cache";
    			$mamboIndex		= $mosConfig_absolute_path."/index.php";
    		?>
    		<?php echo $mosLang->MSG_CONFIGURATION_FILE_IS ." (". $configFile .") "; ?>
			<?php echo is_writable( $configFile	 ) ? paintGreen($mosLang->MSG_WRITABLE) : paintRed($mosLang->MSG_NOT_WRITABLE); ?><br/>
			<?php echo $mosLang->MSG_MAMBO_CACHE_DIR_IS ." (". $cacheDir .") "; ?>
			<?php echo is_writable( $cacheDir ) ? paintGreen($mosLang->MSG_WRITABLE) : paintRed($mosLang->MSG_NOT_WRITABLE); ?><br/>
			<?php echo $mosLang->MSG_MAMBO_INDEX_FILE_IS ." (". $mamboIndex .") "; ?>
			<?php echo is_writable( $mamboIndex ) ? paintGreen($mosLang->MSG_WRITABLE) : paintRed($mosLang->MSG_NOT_WRITABLE); ?><br/>
			<?php echo $mosLang->MSG_MAMBO_PATH_IS ." (". $mosConfig_absolute_path .") "; ?>
			<?php echo is_writable( $mosConfig_absolute_path ) ? paintGreen($mosLang->MSG_WRITABLE) : paintRed($mosLang->MSG_NOT_WRITABLE); ?><br/>
			</td>
    	</tr>

    	<?php
    	if (is_writable( $configFile ) && is_writable( $cacheDir ) ) {

    	?>

    	<tr>
    	<td colspan="2">
    	<b><?php echo"<a href=\"".$mosConfig_live_site."/administrator/index2.php?option=com_smf&task=chmodwrite\">".$mosLang->MSG_MAKE_WRITEABLE."</a>"; ?></b> |
    	<b><?php echo"<a href=\"".$mosConfig_live_site."/administrator/index2.php?option=com_smf&task=chmodsecure\">".$mosLang->MSG_MAKE_NOT_WRITEABLE."</a>"; ?></b></td>
		</tr>

    	<tr>
    	<td colspan="2">&nbsp;</td>
    	</tr>
		<tr>
			<td align="left" valign="top" width="170"><?php echo $mosLang->FIELD_SMF_PATH_ABSOLUT; ?></td>
			<td align="left" valign="top">
				<?php $smfpath = trim($mosConfig_absolute_path) .'/forum'; ?>
				<input type="text" name="smf_path" value="<?php if( $smf_path != '' ) echo $smf_path; else echo $mosConfig_absolute_path."/<SMF path here>"; ?>" size="60" /><?php echo file_exists($smf_path."/Settings.php") ? "" : $mosLang->TEXT_JOOMLA_SMF_SAME_DB . " ". paintRed($mosLang->MSG_INCORRECT_PATH);?>
			</td>
		</tr>

		<tr>
			<td align="left" valign="top"><?php echo $mosLang->FIELD_SMF_DB_PREFIX; ?></td>
			<td align="left" valign="top">
				<input type="text" name="smf_prefix" value="<?php echo "$smf_prefix"; ?>" /><?php echo isSMFInstalled() ? "" : " e.g.: \"smf_\" ".paintRed($mosLang->MSG_INCORRECT_PREFIX) ;?>
			</td>
		</tr>

		<tr>
			<td align="left" valign="top"><?php echo $mosLang->FIELD_INTEGRATION_OPTION; ?></td>
			<td align="left" valign="top">
				<input type="radio" name="wrapped" value="true" <?php echo $wrapped == true ? 'checked="checked"' : '' ; ?>><?php echo $mosLang->FIELD_VALUE_WRAPPED; ?>
				<input type="radio" name="wrapped" value="false" <?php echo $wrapped == false ? 'checked="checked"' : '' ; ?>><?php echo $mosLang->FIELD_VALUE_UNWRAPPED; ?>
				&nbsp;&nbsp;
			</td>
		</tr>

		<tr>
			<td align="left" valign="top"><?php echo $mosLang->FIELD_HIDE_EMAIL; ?></td>
			<td align="left" valign="top">
				<input type="radio" name="hideemail" value="0" <?php echo $hideemail == 0 ? 'checked="checked"' : '' ; ?>>No
				<input type="radio" name="hideemail" value="1" <?php echo $hideemail == 1 ? 'checked="checked"' : '' ; ?>>Yes
				&nbsp;&nbsp;
			</td>
		</tr>

		<?php
		if (isCBLogin()) { ?>
		<tr>
			<td align="left" valign="top"><?php echo $mosLang->FIELD_CB_PROFILE; ?></td>
			<td align="left" valign="top">
				<input type="radio" name="cbprofile" value="0" <?php echo $cbprofile == 0 ? 'checked="checked"' : '' ; ?>>No
				<input type="radio" name="cbprofile" value="1" <?php echo $cbprofile == 1 ? 'checked="checked"' : '' ; ?>>Yes
				&nbsp;&nbsp;
			</td>
		</tr>
		<?php } ?>

		<tr>
			<td align="left"><?php echo $mosLang->FIELD_SMF_REDIRECT; ?></td>
			<td align="left">
				<input type="radio" name="smf_redirect" value="0" <?php echo $smf_redirect == 0 ? 'checked="checked"' : '' ; ?>>No
				<input type="radio" name="smf_redirect" value="1" <?php echo $smf_redirect == 1 ? 'checked="checked"' : '' ; ?>>Yes
				&nbsp;&nbsp;
			</td>
		</tr>

		<tr>
			<td align="left">Registration module to use:</td>
			<td align="left">
			    <input type="radio" name="registration_module" value="smf" <?php echo $registration_module == smf ? 'checked="checked"' : '' ; ?>>SMF
				<input type="radio" name="registration_module" value="mambo" <?php echo $registration_module == mambo ? 'checked="checked"' : '' ; ?>>Mambo
				<input type="radio" name="registration_module" value="cb" <?php echo $registration_module == cb ? 'checked="checked"' : '' ; ?>>CB
				&nbsp;&nbsp;
			</td>
		</tr>

    	<tr><td colspan="2">&nbsp;</td></td></tr>

		<tr>
			<td colspan=2><b><?php echo $mosLang->HEADER_INSTALLATION_CHECKLIST; ?></b></td>
		</tr>
		<tr>
			<td align="right"><?php echo isSMFInstalled() ? paintGreen($mosLang->MSG_INSTALLED) : paintRed($mosLang->MSG_NOT_INSTALLED) ;?></td>
			<td><?php echo $mosLang->TEXT_INSTALL_SMF; ?></td>
		</tr>
		<tr>
			<td align="right"><?php echo isMamboIndexPatched() ? paintGreen($mosLang->MSG_DONE) . " [<b><a href=\"".$mosConfig_live_site."/administrator/index2.php?option=com_smf&task=unpatch\">".$mosLang->MSG_UNPATCH."</a></b>]" : "<b><a href=\"".$mosConfig_live_site."/administrator/index2.php?option=com_smf&task=patch\">".$mosLang->MSG_PATCH."</a></b>" ;?></td>
			<td><?php echo $mosLang->TEXT_PATCH_MAMBO_INDEX_FILE; ?></td>
		</tr>
		<tr>
			<td align="right"><?php echo isPatched($smf_path.'/index.php', $mosSMF->MAMBOHACKS) && isPatched($smf_path.'/Sources/Subs-Post.php', $mosSMF->MAMBOHACKS) ? paintGreen($mosLang->MSG_DONE) . " [<b><a href=\"".$mosConfig_live_site."/administrator/index2.php?option=com_smf&task=unpatchsmf\">".$mosLang->MSG_UNPATCH."</a></b>]" : "<b><a href=\"".$mosConfig_live_site."/administrator/index2.php?option=com_smf&task=patchsmf\">".$mosLang->MSG_PATCH."</a></b>" ;?></td>
			<td><?php echo $mosLang->TEXT_INSTALL_SMF_MOD; ?></td>
		</tr>

		<?php if ($_REQUEST['task'] == 'checksync') {
			$_SESSION['sync_done'] = isMamboSMFUserSync();
			if ($_SESSION['sync_done']) mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_SMF_MAMBO_IN_SYNC);
		 } ?>

		<tr>
			<td align="right"><?php echo $_SESSION['sync_done']  ? paintGreen($mosLang->MSG_DONE) . " [<b><a href=\"".$mosConfig_live_site."/administrator/index2.php?option=com_smf&task=checksync\">".$mosLang->MSG_VERIFY."</a></b>] " : "<b><a href=\"".$mosConfig_live_site."/administrator/index2.php?option=com_smf&task=sync\">".$mosLang->MSG_SYNC."</a></b>" ;?></td>
			<td><?php echo $mosLang->TEXT_SYNC_MAMBO_SMF; ?></td>
		</tr>

		<tr><td colspan="2">&nbsp;</td></td></tr>

		<tr>
			<td colspan=2>
			<b>Update Feeds From JoomlaHacks.com:</b><p>
			<?php
				include_once($mosConfig_absolute_path . "/administrator/components/com_smf/rdf.php");
				$rdf = new rdf();
				$url = "http://www.joomlahacks.com/news/mh_announcement.rdf" ;
				$base = str_replace("?","&",basename($url));
				if ($rdf->getRDF($url, 30)) {
					$rdf->displayRDF($base, False, 1, "");
				}
			?>
			<p>
			<?php
				$rdf = new rdf();
				$url = "http://www.joomlahacks.com/index2.php?option=com_rss&feed=RSS1.0&no_html=ss1" ;
				$base = str_replace("?","&",basename($url));
				if ($rdf->getRDF($url, 30)) {
					$rdf->displayRDF($base, False, 1, "");
				}
			?>
			<p>
			<?php echo $mosLang->MSG_MAMBO_SMF_VERSION_IS." ".readComVersion(); ?><br/>
			<?php
				$rdf = new rdf();
				$url = "http://www.joomlahacks.com/news/mh_mambo_smf_update.rdf" ;
				$base = str_replace("?","&",basename($url));
				if ($rdf->getRDF($url, 30)) {
					if (!$rdf->displayRDF($base, False, 1, $mosLang->MSG_LATEST_MAMBO_SMF_VERSION_IS." "))
						echo $mosLang->MSG_ERR_CANNOT_REACH_SITE;
				} else {
					echo $mosLang->MSG_ERR_CANNOT_REACH_SITE;
				}
			?>
	  		</td>
		</tr>

		</table>

		</td>
		</tr>

		<?php
		} else {
		?>
			<tr>
				<td colspan="2"><?php echo paintRed($mosLang->TEXT_SET_PERMISSION); ?>
				<br/><b><?php echo"<a href=\"".$mosConfig_live_site."/administrator/index2.php?option=com_smf&task=chmodwrite\">".$mosLang->MSG_MAKE_WRITEABLE."</a>"; ?></b></td>
			</tr>
		<?php
		}
		?>

		</table>

		<?php $tabs->endTab(); ?>


		<?php $tabs->startTab( $mosLang->TAB_HELP, 'general-page' ); ?>
		<div align="left">
		<a href="http://www.MamboServe.com" target="_blank">MamboServe.com</a>, JoomlaHacks.com's preferred hosting! Just $5.95 for 2500mb/40gb!<br/>
		Visit <a href="http://www.JoomlaHacks.com" target="_blank">JoomlaHacks.com</a> for now.<br/><br/>
			<?php
				$rdf = new rdf();
				$url = "http://www.joomlahacks.com/news/mh_mambo_smf_help.rdf" ;
				$base = str_replace("?","&",basename($url));
				if ($rdf->getRDF($url, 30)) {
					$rdf->displayRDF($base, False, 1, "");
				}
				else {
					echo '<br/><br/><br/><br/><br/><br/>';
				}
			?>
		</div>
		<?php $tabs->endTab();?>

    	<?php $tabs->endPane();?>

    	<input type="hidden" name="option" value="<?php echo $option; ?>" />
    	<input type="hidden" name="act" value="<?php echo $act; ?>" />
    	<input type="hidden" name="task" value="" />
    	<input type="hidden" name="boxchecked" value="0" />
    </form>

<?php
}

function saveConfig ($option,$smf_path,$smf_redirect,$smf_prefix,$wrapped,$hideemail,$cbprofile,$registration_module) {
	global $mosLang, $mosConfig_dbprefix;

	$configfile = "components/com_smf/config.smf.php";
	@chmod ($configfile, 0777);
	$permission = @is_writable($configfile);
	if (!$permission) {
		mosRedirect( "index2.php?option=$option&task=config", $mosLang->MSG_ERR_CONF_NOT_WRITEABLE );
	break;
	}

	if ($cbprofile == '') $cbprofile = 0;

	$config  = "<?php\n";
	$config  .= "global \$smf_path, \$smf_redirect, \$smf_prefix, \$mos_prefix, \$wrapped, \$hideemail, \$cbprofile, \$registration_module;\n";
	$config  .= "\$smf_path = \"$smf_path\";\n";
	$config  .= "\$smf_redirect = $smf_redirect;\n";
	$config  .= "\$mos_prefix = \"".trim($mosConfig_dbprefix)."\";\n";
	$config  .= "\$smf_prefix = \"$smf_prefix\";\n";
	$config  .= "\$wrapped = $wrapped;\n";
	$config  .= "\$hideemail = $hideemail;\n";
	$config  .= "\$cbprofile = $cbprofile;\n";
	$config  .= "\$registration_module = \"$registration_module\";\n";
	$config  .= "?>";

	if ($fp = @fopen("$configfile", "w")) {
		@fputs($fp, $config, strlen($config));
		@fclose ($fp);
	}

	mosRedirect( "index2.php?option=com_smf&task=config", $mosLang->MSG_SETTINGS_SAVED );
}

function readComVersion() {
	global $mosConfig_absolute_path;
	require_once( $mosConfig_absolute_path . '/includes/domit/xml_domit_lite_include.php' );

	$thefile = $mosConfig_absolute_path . "/administrator/components/com_smf/smf.xml";

	$xmlDoc =& new DOMIT_Lite_Document();
	$xmlDoc->resolveErrors( true );

	if (!$xmlDoc->loadXML( $thefile, false, true )) {
		continue;
	}

	$element = &$xmlDoc->documentElement;

	if ($element->getTagName() != 'mosinstall') {
		continue;
	}

	$element = &$xmlDoc->getElementsByPath('version', 1);
	return $element ? $element->getText() : '';
}

?>
