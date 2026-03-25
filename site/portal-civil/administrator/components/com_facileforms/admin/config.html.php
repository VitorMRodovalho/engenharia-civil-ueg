<?php
/**
* FacileForms - A Joomla Forms Application
* @version 1.4.5
* @package FacileForms
* @copyright (C) 2004-2006 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

class HTML_facileFormsConf
{
	function edit($option, $caller, $pkg, $downloadfile='')
	{
		global $ff_mossite, $ff_config, $ff_admsite, $ff_admicon, $ff_version;
?>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script type="text/javascript" src="<?php echo $ff_mossite; ?>/includes/js/overlib_mini.js"></script>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="1" border="0" class="adminform" style="width:300px;">
			<tr><th colspan="6" class="title" >FacileForms <?php echo $ff_version.' - '._FACILEFORMS_CONFIG; ?></th></tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_USELIVESITE; ?></td>
				<td nowrap colspan="3">
<?php
					echo mosHTML::yesnoRadioList("livesite", "", $ff_config->livesite);
					echo mosToolTip(_FACILEFORMS_CONFIG_TIPLIVESITE);
?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_PREVIEWFRAME; ?></td>
				<td nowrap colspan="3">
<?php
					echo mosHTML::yesnoRadioList("stylesheet", "", $ff_config->stylesheet);
					echo mosToolTip(_FACILEFORMS_CONFIG_TIPPREVIEW);
?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_GRIDSIZE; ?></td>
				<td nowrap colspan="3">
					<input size="4" maxlength="4" name="gridsize" value="<?php echo $ff_config->gridsize; ?>"/>
					&nbsp;&nbsp;<?php echo _FACILEFORMS_CONFIG_COLOR; ?> 1 <input size="7" maxlength="20" name="gridcolor1" value="<?php echo $ff_config->gridcolor1; ?>"/>
					&nbsp;&nbsp;<?php echo _FACILEFORMS_CONFIG_COLOR; ?> 2 <input size="7" maxlength="20" name="gridcolor2" value="<?php echo $ff_config->gridcolor2; ?>"/>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_USEWYSIWYG; ?></td>
				<td nowrap colspan="3">
<?php
					echo mosHTML::yesnoRadioList("wysiwyg", "", $ff_config->wysiwyg);
					echo mosToolTip(_FACILEFORMS_CONFIG_TIPWYSIWYG);
?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_COMPRESS; ?></td>
				<td nowrap colspan="3">
<?php
					echo mosHTML::yesnoRadioList("compress", "", $ff_config->compress);
					echo mosToolTip(_FACILEFORMS_CONFIG_TIPCOMPRESS);
?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_GETPROVIDER; ?></td>
				<td nowrap colspan="3">
<?php
					echo mosHTML::yesnoRadioList("getprovider", "", $ff_config->getprovider);
					echo mosToolTip(_FACILEFORMS_CONFIG_TIPPROVIDER);
?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_SMALL; ?></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_MEDIUM; ?></td>
				<td nowrap width="100%"><?php echo _FACILEFORMS_CONFIG_LARGE; ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_TEXTAREA; ?></td>
				<td nowrap><input size="4" maxlength="4" name="areasmall" value="<?php echo $ff_config->areasmall; ?>"/></td>
				<td nowrap><input size="4" maxlength="4" name="areamedium" value="<?php echo $ff_config->areamedium; ?>"/></td>
				<td nowrap><input size="4" maxlength="4" name="arealarge" value="<?php echo $ff_config->arealarge; ?>"/></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_LIMITDESC; ?></td>
				<td nowrap colspan="3"><input size="6" maxlength="6" name="limitdesc" value="<?php echo $ff_config->limitdesc; ?>"/> <?php echo _FACILEFORMS_CONFIG_CHARS; ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_DEFAULTEMAIL; ?></td>
				<td nowrap colspan="3"><input size="50" maxlength="50" name="emailadr" value="<?php echo $ff_config->emailadr; ?>"/></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_FFIMAGES; ?></td>
				<td nowrap colspan="3"><input size="70" maxlength="255" name="images" value="<?php echo $ff_config->images; ?>"/></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_CONFIG_FFUPLOADS; ?></td>
				<td nowrap colspan="3"><input size="70" maxlength="255" name="uploads" value="<?php echo $ff_config->uploads; ?>"/></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap colspan="4" style="text-align:right"><br/>
					<a class="toolbar" href="javascript:submitbutton('save');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('save','','images/save_f2.png',1);">
						<img src="images/save.png" alt="" name="save" border="0" align="middle" />&nbsp;<?php echo _FACILEFORMS_TOOLBAR_SAVE; ?>
					</a>&nbsp;&nbsp;
					<a class="toolbar" href="javascript:submitbutton('instpackage');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('instpackage','','images/upload_f2.png',1);">
						<img src="images/upload.png" alt="" name="instpackage" border="0" align="middle" />&nbsp;<?php echo _FACILEFORMS_TOOLBAR_PKGINSTLR; ?>
					</a>&nbsp;&nbsp;
					<a class="toolbar" href="javascript:submitbutton('makepackage');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('makepackage','','<?php echo $ff_admicon; ?>/download_f2.png',1);">
						<img src="<?php echo $ff_admicon; ?>/download.png"  alt="" name="makepackage" border="0" align="middle" />&nbsp;<?php echo _FACILEFORMS_TOOLBAR_CREAPKG; ?>
					</a>&nbsp;&nbsp;
					<a class="toolbar" href="javascript:submitbutton('cancel');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('cancel','','images/cancel_f2.png',1);">
						<img src="images/cancel.png"  alt="" name="cancel" border="0" align="middle" />&nbsp;<?php echo _FACILEFORMS_TOOLBAR_CANCEL; ?>
					</a>
				</td>
				<td></td>
			</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="configuration" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="caller_url" value="<?php echo htmlspecialchars($caller, ENT_QUOTES); ?>" />
		<input type="hidden" name="pkg" value="<?php echo $pkg; ?>" />
		</form>
<?php
		if ($downloadfile != '') {
?>
		<script type="text/javascript">onload=function(){document.dldform.submit();}</script>
		<form action="<?php echo $ff_admsite; ?>/admin/download.php" method="post" name="dldform">
		<input type="hidden" name="filename" value="<?php echo $downloadfile; ?>" />
		</form>
<?php
		} // if
	} // edit

	function instPackage($option, $caller, $pkg, &$rows)
	{
		global $ff_mossite, $mosConfig_lang, $ff_config, $ff_admpath;
		if (file_exists($ff_admpath.'/packages/samples.'.$mosConfig_lang.'.xml'))
			$startdir = '{mospath}/administrator/components/com_facileforms/packages/samples.'.$mosConfig_lang.'.xml';
		else
			$startdir = '{mospath}/administrator/components/com_facileforms/packages/samples.english.xml';
?>
		<script type="text/javascript">
		<!--
		function submitbutton(pressbutton)
		{
			var form = document.adminForm;
			switch (pressbutton) {
				case 'delpkgs':
					if (form.boxchecked.value==0) {
						alert("<?php echo _FACILEFORMS_INSTALLER_SELPKGSFIRST; ?>");
						return;
					} // if
					break;
				default:
					break;
			} // switch
			switch (pressbutton) {
				case 'start':
					if (form.uploadpackage.checked) {
						if (form.uploadfile.value == '') {
							alert("<?php echo _FACILEFORMS_INSTALLER_NOUPLOADFILE; ?>");
							form.uploadfile.focus();
							return;
						} // if
						pressbutton = 'uploadpackage';
					} else
						if (form.localpackage.checked) {
							if (form.installfile.value == '') {
								alert("<?php echo _FACILEFORMS_INSTALLER_NOFILENAME; ?>");
								form.installfile.focus();
								return;
							} // if
							pressbutton = 'localpackage';
						} else {
							alert("<?php echo _FACILEFORMS_INSTALLER_SELECTMODE; ?>");
							return;
						} // if
					break;
				case 'delpkgs':
					if (!confirm("<?php echo _FACILEFORMS_INSTALLER_ASKUNINST; ?>")) return;
					break;
				default:
					break;
			} // switch
			submitform(pressbutton);
		} // submitbutton

		function clickInstMode(value)
		{
			var form = document.adminForm;
			switch (parseInt(value)) {
				case 0:
					document.getElementById('div_uploadpackage').style.display='';
					document.getElementById('div_localpackage').style.display='none';
					break;
				default:
					document.getElementById('div_uploadpackage').style.display='none';
					document.getElementById('div_localpackage').style.display='';
					break;
			} // switch
		} // clickInstMode
		//-->
		</script>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script type="text/javascript" src="<?php echo $ff_mossite; ?>/includes/js/overlib_mini.js"></script>

		<form action="index2.php" method="post" name="adminForm" enctype="multipart/form-data" >
		<table cellpadding="4" cellspacing="1" border="0">
			<tr>
				<td nowrap>
					<table class="adminheading">
						<tr><th class="sections">FacileForms : <span class="componentheading"><?php echo _FACILEFORMS_INSTALLER_INSTALLPKG; ?></span></th></tr>
					</table>
				</td>
				<td nowrap width="50">
				</td>
				<td nowrap width="100%">
<?php
			if ((bool)ini_get('file_uploads')) {
				$upldattr = ' checked="checked"';
				$uplddisp = '';
				$loclattr = '';
				$locldisp = ' style="display:none;"';
			} else {
				$upldattr = ' disabled="disabled"';
				$uplddisp = ' style="display:none;"';
				$loclattr = ' checked="checked"';
				$locldisp = '';
			} // if
?>
					<input type="radio" id="uploadpackage" name="tasksel" value="0" onclick="clickInstMode(this.value)"<?php echo $upldattr; ?>/>
					<label for="uploadpackage"> <?php echo _FACILEFORMS_INSTALLER_CLIENTUPLOAD; ?></label>
					<br/>
					<input type="radio" id="localpackage" name="tasksel" value="1" onclick="clickInstMode(this.value)"<?php echo $loclattr; ?>/>
					<label for="localpackage"> <?php echo _FACILEFORMS_INSTALLER_SERVERINSTALL; ?></label>
					<div id="div_uploadpackage"<?php echo $uplddisp; ?>>
						<br/>
						<input type="file" id="uploadfile" name="uploadfile" size="70" />
					</div>
					<div id="div_localpackage"<?php echo $locldisp; ?>>
						<br/>
						<input type="text" id="installfile" name="installfile" size="80" value="<?php echo $startdir; ?>"/>
					</div>
				</td>
			</tr>
		</table>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
				<th nowrap align="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
				<th nowrap colspan="4" align="left"><?php echo _FACILEFORMS_INSTALLER_PACKAGE; ?></th>
				<th nowrap colspan="2" align="left"><?php echo _FACILEFORMS_INSTALLER_AUTHOR; ?></th>
			</tr>
<?php
			$k = 0;
			for($i=0; $i < count($rows); $i++) {
				$row = $rows[$i];
				$url = htmlspecialchars($row->url);
				if (substr($url,0,7)=='http://') $url = substr($url,7);
				$email = htmlspecialchars($row->email);
				if (substr($email,0,7)=='mailto:') $email = substr($email,7);
?>
				<tr class="row<?php echo $k; ?>">
					<td nowrap valign="top" align="center"><input type="checkbox" id="cb<?php echo $i; ?>" name="ids[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
					<td nowrap valign="top" align="left">
						<strong><?php echo _FACILEFORMS_INSTALLER_ID; ?></strong><br/>
						<strong><?php echo _FACILEFORMS_INSTALLER_NAME; ?></strong><br/>
						<strong><?php echo _FACILEFORMS_INSTALLER_VERS; ?></strong><br/>
						<strong><?php echo _FACILEFORMS_INSTALLER_CREATEDATE; ?></strong>
					</td>
					<td nowrap valign="top" align="left">
						<?php echo htmlspecialchars($row->id); ?><br/>
						<?php echo htmlspecialchars($row->name); ?><br/>
						<?php echo htmlspecialchars($row->version); ?><br/>
						<?php echo htmlspecialchars($row->created); ?>
					</td>
					<td nowrap valign="top" align="left">
						<br/>
						<strong><?php echo _FACILEFORMS_INSTALLER_TITLE; ?></strong><br/>
						<strong><?php echo _FACILEFORMS_INSTALLER_DESC; ?></strong><br/>
						<strong><?php echo _FACILEFORMS_INSTALLER_CPYRT; ?></strong>
					</td>
					<td nowrap valign="top" align="left">
						<br/>
						<?php echo htmlspecialchars($row->title); ?><br/>
						<?php echo htmlspecialchars($row->description); ?><br/>
						<?php echo htmlspecialchars($row->copyright); ?>
					</td>
					<td nowrap valign="top" align="left">
						<br/>
						<strong><?php echo _FACILEFORMS_INSTALLER_NAME; ?></strong><br/>
						<strong><?php echo _FACILEFORMS_INSTALLER_EMAIL; ?></strong><br/>
						<strong><?php echo _FACILEFORMS_INSTALLER_URL; ?></strong>
					</td>
					<td nowrap valign="top" align="left" width="100%">
						<br/>
						<?php echo htmlspecialchars($row->author); ?><br/>
						<?php if ($email!='') echo '<a href="mailto:'.$email.'">'.$email.'</a>'; else echo '&nbsp;' ;?><br/>
						<?php if ($url!='') echo '<a href="http://'.$url.'" target="_blank">http://'.$url.'</a>'; else echo '&nbsp;' ;?>
					</td>
				</tr>
<?php
				$k = 1 - $k;
			} // for
?>
		</table>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="configuration" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="caller_url" value="<?php echo htmlspecialchars($caller, ENT_QUOTES); ?>" />
		<input type="hidden" name="pkg" value="<?php echo $pkg; ?>" />
		</form>
<?php
	} // instPackage

	function makePackage($option, $caller, $pkg, $lists)
	{
		global $ff_mossite, $ff_config;
		$startdir = '{mossite}/administrator/components/com_facileforms/packages';
		$rows = $lists['pkgnames'];
		$empty = false; $selpkg = '';
		if (count($rows)) foreach ($rows as $row) {
			if ($row->package=='') $empty = true;
			if ($row->package==$pkg) $selpkg = $pkg;
		} // foreach
?>
		<script type="text/javascript">
			<!--
			onload = function()
			{
				var form = document.adminForm;
<?php
				echo "\t\t\t\tpkgChange('$selpkg');\n"
?>
				form.pkg.focus();
			} // onload

			function setSelection(topic, selected)
			{
				var form = document.adminForm;
				var opts;
				switch (topic) {
					case  2: opts = form.id_menusel.options; break;
					case  3: opts = form.id_scriptsel.options; break;
					case  4: opts = form.id_piecesel.options; break;
					default: opts = form.id_formsel.options;
				} // switch
				for (var i = 0; i < opts.length; i++) opts[i].selected = selected;
			} // setSelection

			var pkgs = [
<?php
			$first = true;
			$pkgs = $lists['packages'];
			if (count($pkgs)) foreach ($pkgs as $pkg1) {
				if (!$first) echo ",\n";
				echo "\t\t\t\t[".
					"'".htmlentities($pkg1->id)."',".
					"'".htmlentities($pkg1->name)."',".
					"'".htmlentities($pkg1->version)."',".
					"'".htmlentities($pkg1->title)."',".
					"'".htmlentities($pkg1->author)."',".
					"'".htmlentities($pkg1->email)."',".
					"'".htmlentities($pkg1->url)."',".
					"'".htmlentities($pkg1->description)."',".
					"'".htmlentities($pkg1->copyright)."']";
				$first = false;
			} // foreach
			echo "\n";
?>
			];

			function pkgChange(pkg)
			{
				if (pkg == '') {
					document.getElementById('menusel').style.display = '';
					document.getElementById('scriptsel').style.display = '';
				} else {
					document.getElementById('menusel').style.display = 'none';
					document.getElementById('scriptsel').style.display = 'none';
				} // if
				var p;
				var form = document.adminForm;
				for (p = 0; p < pkgs.length; p++) {
					if (pkgs[p][0]==pkg) {
						form.pkg_name.value         = pkgs[p][1];
						form.pkg_version.value      = pkgs[p][2];
						form.pkg_title.value        = pkgs[p][3];
						form.pkg_author.value       = pkgs[p][4];
						form.pkg_email.value        = pkgs[p][5];
						form.pkg_url.value          = pkgs[p][6];
						form.pkg_description.value  = pkgs[p][7];
						form.pkg_copyright.value    = pkgs[p][8];
						break;
					} // if
				} // for
			} // pkgChange

			function submitbutton( pressbutton )
			{
				if (pressbutton == 'mkpackage') {
					var invalidChars = /\W\./;
					var form = document.adminForm;
					error = '';
					foc = '';
					if (form.pkg_name.value=='') {
						error += "<?php echo _FACILEFORMS_INSTALLER_ENTPACKAGENAME; ?>\n";
						if (foc=='') foc = form.pkg_name;
					} else
						if (invalidChars.test(form.pkg_name.value)) {
							error += "<?php echo _FACILEFORMS_INSTALLER_ENTIDENTIFIER; ?>\n";
							if (foc=='') foc = form.pkg_name;
						} // if
					if (form.pkg_version.value=='') {
						error += "<?php echo _FACILEFORMS_INSTALLER_ENTVERSION; ?>\n";
						if (foc=='') foc = form.pkg_version;
					} // if
					if (form.pkg_title.value=='') {
						error += "<?php echo _FACILEFORMS_INSTALLER_ENTTITLE; ?>\n";
						if (foc=='') foc = form.pkg_title;
					} // if
					if (form.pkg_author.value=='') {
						error += "<?php echo _FACILEFORMS_INSTALLER_ENTAUTHORNM; ?>\n";
						if (foc=='') foc = form.pkg_author;
					} // if
					if (form.pkg_email.value=='') {
						error += "<?php echo _FACILEFORMS_INSTALLER_ENTAUTHORMAIL; ?>\n";
						if (foc=='') foc = form.pkg_email;
					} // if
					if (form.pkg_url.value=='') {
						error += "<?php echo _FACILEFORMS_INSTALLER_ENTAUTHORURL; ?>\n";
						if (foc=='') foc = form.pkg_url;
					} // if
					if (form.pkg_description.value=='') {
						error += "<?php echo _FACILEFORMS_INSTALLER_ENTDESCR; ?>\n";
						if (foc=='') foc = form.pkg_description;
					} // if
					if (form.pkg_copyright.value=='') {
						error += "<?php echo _FACILEFORMS_INSTALLER_ENTCOPYRT; ?>\n";
						if (foc=='') foc = form.pkg_copyright;
					} // if
					if (form.pkg.value=='') {
						var cnt = 0;
						var i;
						opts = form.id_scriptsel.options;
						for (i = 0; i < opts.length; i++) if (opts[i].selected) cnt++;
						opts = form.id_piecesel.options;
						for (i = 0; i < opts.length; i++) if (opts[i].selected) cnt++;
						opts = form.id_formsel.options;
						for (i = 0; i < opts.length; i++) if (opts[i].selected) cnt++;
						opts = form.id_menusel.options;
						for (i = 0; i < opts.length; i++) if (opts[i].selected) cnt++;
						if (cnt == 0)
							error += "<?php echo _FACILEFORMS_INSTALLER_SELECTAPKG; ?>\n";
					} // if
					if (error) {
						alert(error);
						if (foc != '') foc.focus();
						return;
					} // if
				} // if
				submitform( pressbutton );
			} // submitbutton
			//-->
		</script>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script type="text/javascript" src="<?php echo $ff_mossite; ?>/includes/js/overlib_mini.js"></script>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="1" border="0" class="adminform" style="width:300px;">
			<tr><th colspan="4" class="title" >FacileForms - <?php echo _FACILEFORMS_INSTALLER_CREATEPKG; ?></th></tr>
			<tr>
				<td></td>
				<td colspan="2">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td nowrap><?php echo _FACILEFORMS_INSTALLER_ID; ?>:</td>
							<td nowrap width="100%">
								<select id="id_pkg" name="pkg" class="inputbox" onchange="pkgChange(this.value);" size="1">
<?php
									$rows = $lists['pkgnames'];
									if (!$empty) {
										if ($pkg == '') $sel = ' selected="selected"'; else $sel = '';
										echo '<option value=""'.$sel.'></option>';
									} // if
									if (count($rows)) foreach ($rows as $row) {
										if ($pkg == $row->package) $sel = ' selected="selected"'; else $sel = '';
										echo '<option value="'.$row->package.'"'.$sel.'>'.$row->package.'</option>';
									} // foreach
?>
								</select>
							</td>
						<tr>
							<td nowrap><?php echo _FACILEFORMS_INSTALLER_PACKAGE.' '._FACILEFORMS_INSTALLER_NAME; ?>:</td>
							<td nowrap width="100%"><input type="text" name="pkg_name" size="30" maxlength="30"/></td>
						</tr>
						<tr>
							<td nowrap><?php echo _FACILEFORMS_INSTALLER_PACKAGE.' '._FACILEFORMS_INSTALLER_VERS; ?>:</td>
							<td nowrap><input type="text" name="pkg_version" size="30" maxlength="30"/></td>
						</tr>
						<tr>
							<td nowrap><?php echo _FACILEFORMS_INSTALLER_PACKAGE.' '._FACILEFORMS_INSTALLER_TITLE; ?>:</td>
							<td nowrap width="100%"><input type="text" name="pkg_title" size="50" maxlength="50"/></td>
						</tr>
						<tr>
							<td nowrap><?php echo _FACILEFORMS_INSTALLER_AUTHOR.' '._FACILEFORMS_INSTALLER_NAME; ?>:</td>
							<td nowrap><input type="text" name="pkg_author" size="50" maxlength="50"/></td>
						</tr>
						<tr>
							<td nowrap><?php echo _FACILEFORMS_INSTALLER_AUTHOR.' '._FACILEFORMS_INSTALLER_EMAIL; ?>:</td>
							<td nowrap><input type="text" name="pkg_email" size="50" maxlength="50"/></td>
						</tr>
						<tr>
							<td nowrap><?php echo _FACILEFORMS_INSTALLER_AUTHOR.' '._FACILEFORMS_INSTALLER_URL; ?>:</td>
							<td nowrap><input type="text" name="pkg_url" size="50" maxlength="50"/></td>
						</tr>
						<tr>
							<td nowrap><?php echo _FACILEFORMS_INSTALLER_DESC; ?>:</td>
							<td nowrap><input type="text" name="pkg_description" size="100" maxlength="100"/></td>
						</tr>
						<tr>
							<td nowrap><?php echo _FACILEFORMS_INSTALLER_CPYRT; ?>:</td>
							<td nowrap><input type="text" name="pkg_copyright" size="100" maxlength="100"/></td>
						</tr>
					</table>
				</td>
				<td></td>
			</tr>
			<tr id="menusel" style="display:none;">
				<td></td>
				<td>
					<fieldset><legend><?php echo _FACILEFORMS_INSTALLER_FORMSEL; ?></legend>
					<table align="center" cellpadding="4" cellspacing="1" border="0">
						<tr>
							<td nowrap style="text-align:center">
								<select id="id_formsel" name="formsel[]" class="inputbox" size="15" multiple="multiple">
<?php
									$rows = $lists['forms'];
									for ($i = 0; $i < count($rows); $i++) {
										$row = $rows[$i];
										echo '<option value="'.$row->id.'">'.$row->title.'</option>';
									} // for
?>
								</select><br/><br/>
								<input class="button" type="button" value="<?php echo _FACILEFORMS_INSTALLER_SELECTALL; ?>" onclick="setSelection(1,true)" />&nbsp;
								<input class="button" type="button" value="<?php echo _FACILEFORMS_INSTALLER_CLRSELECTION; ?>" onclick="setSelection(1,false)" />
							</td>
						</tr>
					</table>
					</fieldset>
				</td>
				<td>
					<fieldset><legend><?php echo _FACILEFORMS_INSTALLER_MENUSEL; ?></legend>
					<table align="center" cellpadding="4" cellspacing="1" border="0">
						<tr>
							<td nowrap style="text-align:center">
								<select id="id_menusel" name="menusel[]" class="inputbox" size="15" multiple="multiple">
<?php
									$rows = $lists['compmenus'];
									for ($i = 0; $i < count($rows); $i++) {
										$row = $rows[$i];
										echo '<option value="'.$row->id.'">'.$row->title.'</option>';
									} // for
?>
								</select><br/><br/>
								<input class="button" type="button" value="<?php echo _FACILEFORMS_INSTALLER_SELECTALL; ?>" onclick="setSelection(2,true)" />&nbsp;
								<input class="button" type="button" value="<?php echo _FACILEFORMS_INSTALLER_CLRSELECTION; ?>" onclick="setSelection(2,false)" />
							</td>
						</tr>
					</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			<tr id="scriptsel" style="display:none;">
				<td></td>
				<td>
					<fieldset><legend><?php echo _FACILEFORMS_INSTALLER_SCRIPTSEL; ?></legend>
					<table align="center" cellpadding="4" cellspacing="1" border="0">
						<tr>
							<td nowrap style="text-align:center">
								<select id="id_scriptsel" name="scriptsel[]" class="inputbox" size="15" multiple="multiple">
<?php
									$rows = $lists['scripts'];
									for ($i = 0; $i < count($rows); $i++) {
										$row = $rows[$i];
										echo '<option value="'.$row->id.'">'.$row->title.'</option>';
									} // for
?>
								</select><br/><br/>
								<input class="button" type="button" value="<?php echo _FACILEFORMS_INSTALLER_SELECTALL; ?>" onclick="setSelection(3,true)" />&nbsp;
								<input class="button" type="button" value="<?php echo _FACILEFORMS_INSTALLER_CLRSELECTION; ?>" onclick="setSelection(3,false)" />
							</td>
						</tr>
					</table>
					</fieldset>
				</td>
				<td>
					<fieldset><legend><?php echo _FACILEFORMS_INSTALLER_PIECESEL; ?></legend>
					<table align="center" cellpadding="4" cellspacing="1" border="0">
						<tr>
							<td nowrap style="text-align:center">
								<select id="id_piecesel" name="piecesel[]" class="inputbox" size="15" multiple="multiple">
<?php
									$rows = $lists['pieces'];
									for ($i = 0; $i < count($rows); $i++) {
										$row = $rows[$i];
										echo '<option value="'.$row->id.'">'.$row->title.'</option>';
									} // for
?>
								</select><br/><br/>
								<input class="button" type="button" value="<?php echo _FACILEFORMS_INSTALLER_SELECTALL; ?>" onclick="setSelection(4,true)" />&nbsp;
								<input class="button" type="button" value="<?php echo _FACILEFORMS_INSTALLER_CLRSELECTION; ?>" onclick="setSelection(4,false)" />
							</td>
						</tr>
					</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap colspan="2" style="text-align:right">
					<a class="toolbar" href="javascript:submitbutton('mkpackage');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('next','','images/next_f2.png',1);">
						<img src="images/next.png"  alt="" name="next" border="0" align="middle" />&nbsp;<?php echo _FACILEFORMS_TOOLBAR_CONTINUE; ?>
					</a>&nbsp;&nbsp;
					<a class="toolbar" href="javascript:submitbutton('edit');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('cancel','','images/cancel_f2.png',1);">
						<img src="images/cancel.png"  alt="" name="cancel" border="0" align="middle" />&nbsp;<?php echo _FACILEFORMS_TOOLBAR_CANCEL; ?>
					</a>
				</td>
				<td></td>
			</tr>

		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="configuration" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="caller_url" value="<?php echo htmlspecialchars($caller, ENT_QUOTES); ?>" />
		</form>
<?php
	} // makePackage

	function message($option, $caller, $pkg, $message, $task='edit')
	{
?>
		<script type="text/javascript">onload=function(){submitform('<?php echo $task; ?>');}</script>
		<form action="index2.php" method="post" name="adminForm" >
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="configuration" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="caller_url" value="<?php echo htmlspecialchars($caller, ENT_QUOTES); ?>" />
		<input type="hidden" name="pkg" value="<?php echo $pkg; ?>" />
		<input type="hidden" name="mosmsg" value="<?php echo $message; ?>" />
		</form>
<?php
	} // message

	function showParam(&$inst, $tag)
	{
		if (array_key_exists($tag, $inst->params[0])) echo $inst->params[0][$tag];
	} // showParam

	function showPackage($option, $caller, $pkgid, &$inst)
	{
?>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="0" cellspacing="0" border="0" class="adminform" style="width:600px;">
			<tr><th colspan="4" class="title" >FacileForms - <?php echo _FACILEFORMS_INSTALLER_PKGREPORT; ?></th></tr>
<?php
		if (array_key_exists('pkgid', $inst->params[0])) {
			$pkgid = $inst->params[0]['pkgid'];
?>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_ID; ?>:</td>
				<td nowrap><?php echo $pkgid; ?></td>
				<td></td>
			</tr>
<?php
		} else
			$pkgid = '';
?>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_INSTTYPE; ?>:</td>
				<td nowrap width="100%"><?php HTML_facileFormsConf::showParam($inst, 'pkgtype'); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_FFVERSION; ?>:</td>
				<td nowrap><?php HTML_facileFormsConf::showParam($inst, 'pkgversion'); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_PACKAGE.' '._FACILEFORMS_INSTALLER_NAME; ?>:</td>
				<td nowrap><?php HTML_facileFormsConf::showParam($inst, 'name'); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_PACKAGE.' '._FACILEFORMS_INSTALLER_TITLE; ?>:</td>
				<td nowrap><?php HTML_facileFormsConf::showParam($inst, 'title'); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_PACKAGE.' '._FACILEFORMS_INSTALLER_VERS; ?>:</td>
				<td nowrap><?php HTML_facileFormsConf::showParam($inst, 'version'); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap valign="top"><?php echo _FACILEFORMS_INSTALLER_DESC; ?>:</td>
				<td nowrap><?php HTML_facileFormsConf::showParam($inst, 'description'); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_CPYRT; ?>:</td>
				<td nowrap><?php HTML_facileFormsConf::showParam($inst, 'copyright'); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_CREATEDATE; ?>:</td>
				<td nowrap><?php HTML_facileFormsConf::showParam($inst, 'creationDate'); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_AUTHOR.' '._FACILEFORMS_INSTALLER_NAME; ?>:</td>
				<td nowrap><?php HTML_facileFormsConf::showParam($inst, 'author'); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_AUTHOR.' '._FACILEFORMS_INSTALLER_EMAIL; ?>:</td>
				<td nowrap><?php HTML_facileFormsConf::showParam($inst, 'authorEmail'); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_AUTHOR.' '._FACILEFORMS_INSTALLER_URL; ?>:</td>
				<td nowrap><?php HTML_facileFormsConf::showParam($inst, 'authorUrl'); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_SCRIPTSIMP; ?>:</td>
				<td nowrap><?php echo count($inst->scripts); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_PIECESIMP; ?>:</td>
				<td nowrap><?php echo count($inst->pieces); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_FORMSIMP; ?>:</td>
				<td nowrap><?php echo count($inst->forms); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_ELEMSIMP; ?>:</td>
				<td nowrap><?php echo count($inst->elements); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_INSTALLER_MENUSIMP; ?>:</td>
				<td nowrap><?php echo count($inst->menus); ?></td>
				<td></td>
			</tr>
<?php
			if (count($inst->warnings)) {
?>
			<tr>
				<td></td>
				<td nowrap colspan="2">
					<hr/><br/>
					<?php echo _FACILEFORMS_INSTALLER_WARNINGS; ?>:<br/><br/>
<?php
					foreach ($inst->warnings as $warn) echo $warn.'<br/>';
?>
				</td>
				<td></td>
			</tr>
<?php
			} // if
?>
			<tr>
				<td></td>
				<td nowrap colspan="2" style="text-align:right">
					<a class="toolbar" href="javascript:submitbutton('instpackage');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('next','','images/next_f2.png',1);">
						<img src="images/next.png"  alt="" name="next" border="0" align="middle" />&nbsp;<?php echo _FACILEFORMS_TOOLBAR_CONTINUE; ?>
					</a>
				</td>
				<td></td>
			</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="configuration" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="caller_url" value="<?php echo htmlspecialchars($caller, ENT_QUOTES); ?>" />
		<input type="hidden" name="pkg" value="<?php echo $pkgid; ?>" />
		</form>
<?php
	} // showPackage
} // class HTML_facileFormsConf
?>