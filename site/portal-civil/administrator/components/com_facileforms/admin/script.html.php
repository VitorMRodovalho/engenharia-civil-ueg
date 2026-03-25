<?php
/**
* FacileForms - A Joomla Forms Application
* @version 1.4.5
* @package FacileForms
* @copyright (C) 2004-2006 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

class HTML_facileFormsScript
{
	function edit($option, $pkg, &$row, &$typelist)
	{
		global $ff_mossite, $ff_admsite, $ff_config;
		$action = $row->id ? _FACILEFORMS_SCRIPTS_EDITSCRIPT : _FACILEFORMS_SCRIPTS_ADDSCRIPT;
?>
		<script type="text/javascript" src="<?php echo $ff_admsite; ?>/admin/areautils.js"></script>
		<script type="text/javascript">
		<!--
		function checkIdentifier(value, name)
		{
			var invalidChars = /\W/;
			var error = '';
			if (value == '')
				error += "<?php echo _FACILEFORMS_SCRIPTS_ENTERNAME; ?>\n";
			else
				if (invalidChars.test(value))
					error += "<?php echo _FACILEFORMS_SCRIPTS_ENTERIDENT; ?>\n";
			return error;
		} // checkIdentifier

		function submitbutton(pressbutton)
		{
			var form = document.adminForm;
			var error = '';
			if (pressbutton != 'cancel') {
				error += checkIdentifier(form.name.value, 'name');
				if (form.title.value == '') error += "<?php echo _FACILEFORMS_SCRIPTS_ENTTITLE; ?>\n";
			} // if
			if (error != '')
				alert(error);
			else
				submitform(pressbutton);
		} // submitbutton

		function createCode()
		{
			form = document.adminForm;
			name = form.name.value;
			if (name=='') {
				alert("<?php echo _FACILEFORMS_SCRIPTS_ENTNAMEFIRST; ?>");
				return;
			} // if
			stype = form.type.value;
			code = '';
			switch (stype) {

				case 'Element Action':
					if (!confirm("<?php echo _FACILEFORMS_SCRIPTS_CREATEACTCODE; ?>\n<?php echo _FACILEFORMS_SCRIPTS_EXISTAPP; ?>")) return;
					code =
						"function "+name+"(element, action)\n"+
						"{\n"+
						"    switch (action) {\n"+
						"        case 'click':\n"+
						"            break;\n"+
						"        case 'blur':\n"+
						"            break;\n"+
						"        case 'change':\n"+
						"            break;\n"+
						"        case 'focus':\n"+
						"            break;\n"+
						"        case 'select':\n"+
						"            break;\n"+
						"        default:;\n"+
						"    } // switch\n"+
						"} // "+name+"\n";
					break;

				case 'Element Init':
					if (!confirm("<?php echo _FACILEFORMS_SCRIPTS_CREATEINICODE; ?>\n<?php echo _FACILEFORMS_SCRIPTS_EXISTAPP; ?>")) return;
					code =
						"function "+name+"(element, condition)\n"+
						"{\n"+
						"} // "+name+"\n";
					break;

				case 'Element Validation':
					if (!confirm("<?php echo _FACILEFORMS_SCRIPTS_CREATEVALCODE; ?>\n<?php echo _FACILEFORMS_SCRIPTS_EXISTAPP; ?>")) return;
					code =
						"function "+name+"(element, message)\n"+
						"{\n"+
						"    if (element_fails_my_test) {\n"+
						"        if (message=='') message = element.name+\" faild in my test.\\n\"\n"+
						"        ff_validationFocus(element.name);\n"+
						"        return message;\n"+
						"    } // if\n"+
						"    return '';\n"+
						"} // "+name+"\n";
					break;

				case 'Form Init':
					if (!confirm("<?php echo _FACILEFORMS_SCRIPTS_CREATEFINICODE; ?>\n<?php echo _FACILEFORMS_SCRIPTS_EXISTAPP; ?>")) return;
					code =
						"function "+name+"()\n"+
						"{\n"+
						"} // "+name+"\n";
					break;

				case 'Form Submitted':
					if (!confirm("<?php echo _FACILEFORMS_SCRIPTS_CREATESUBCODE; ?>\n<?php echo _FACILEFORMS_SCRIPTS_EXISTAPP; ?>")) return;
					code =
						"function "+name+"(status, message)\n"+
						"{\n"+
						"    switch (status) {\n"+
						"        case FF_STATUS_OK:\n"+
						"           // do whatever desired on success\n"+
						"           break;\n"+
						"        case FF_STATUS_UNPUBLISHED:\n"+
						"        case FF_STATUS_SAVERECORD_FAILED:\n"+
						"        case FF_STATUS_SAVESUBRECORD_FAILED:\n"+
						"        case FF_STATUS_UPLOAD_FAILED:\n"+
						"        case FF_STATUS_ATTACHMENT_FAILED:\n"+
						"        case FF_STATUS_SENDMAIL_FAILED:\n"+
						"        default:\n"+
						"           alert(message);\n"+
						"    } // switch\n"+
						"} // "+name+"\n";
					break;

				case 'Untyped':
					if (!confirm("<?php echo _FACILEFORMS_SCRIPTS_CREATEUNTCODE; ?>\n<?php echo _FACILEFORMS_SCRIPTS_EXISTAPP; ?>")) return;
					code =
						"function "+name+"()\n"+
						"{\n"+
						"} // "+name+"\n";
					break;

				default:
					alert("<?php echo _FACILEFORMS_SCRIPTS_UNKNOWNTYPE; ?> "+stype);

			} // switch
			oldcode = form.code.value;
			if (oldcode != '')
				form.code.value =
					code+
					"\n// -------------- <?php echo _FACILEFORMS_SCRIPTS_OLDBELOW; ?> --------------\n\n"+
					oldcode;
			else
				form.code.value = code;
		} // createCode

		onload = function()
		{
			document.adminForm.title.focus();
			codeAreaAdd('code', 'code_lines');
		} // onload
		//-->
		</script>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script type="text/javascript" src="<?php echo $ff_mossite; ?>/includes/js/overlib_mini.js"></script>
		<form action="index2.php" method="post" name="adminForm" id="adminForm" class="adminForm">
		<table cellpadding="4" cellspacing="1" border="0" class="adminform" style="width:100px;">
			<tr><th colspan="4" class="title">FacileForms - <?php echo $action; ?></th></tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_SCRIPTS_TITLE; ?>:</td>
				<td nowrap>
					<input type="text" size="50" maxlength="50" name="title" value="<?php echo $row->title; ?>" class="inputbox"/>
<?php
					echo mosToolTip(_FACILEFORMS_SCRIPTS_TIPTITLE);
?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_SCRIPTS_PUBLISHED; ?>:</td>
				<td nowrap><?php echo mosHTML::yesnoRadioList("published", "", $row->published); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_SCRIPTS_PACKAGE; ?>:</td>
				<td nowrap>
					<input type="text" size="30" maxlength="30" id="package" name="package" value="<?php echo $row->package; ?>" class="inputbox"/>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_SCRIPTS_NAME; ?>:</td>
				<td nowrap>
					<input type="text" size="30" maxlength="30" id="name" name="name" value="<?php echo $row->name; ?>" class="inputbox"/>
<?php
					echo mosToolTip(_FACILEFORMS_SCRIPTS_TIPNAME);
?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo _FACILEFORMS_SCRIPTS_TYPE; ?>:</td>
				<td nowrap>
					<select id="type" name="type" class="inputbox" size="1">
<?php
					for ($t = 0; $t < count($typelist); $t++) {
						$tl = $typelist[$t];
						$selected = '';
						if ($tl[0] == $row->type) $selected = ' selected';
						echo '<option value="'.$tl[0].'"'.$selected.'>'.$tl[1].'</option>';
					} // for
?>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap colspan="2">
					<?php echo _FACILEFORMS_SCRIPTS_DESCRIPTION; ?>:
					<a href="#" onClick="textAreaResize('description',<?php echo $ff_config->areasmall; ?>);">[<?php echo $ff_config->areasmall; ?>]</a>
					<a href="#" onClick="textAreaResize('description',<?php echo $ff_config->areamedium; ?>);">[<?php echo $ff_config->areamedium; ?>]</a>
					<a href="#" onClick="textAreaResize('description',<?php echo $ff_config->arealarge; ?>);">[<?php echo $ff_config->arealarge; ?>]</a>
					<br/>
					<textarea wrap="off" name="description" style="width:750px;" rows="<?php echo $ff_config->areasmall; ?>" class="inputbox"><?php echo $row->description; ?></textarea>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap colspan="2">
					<?php echo _FACILEFORMS_SCRIPTS_CODE; ?>:
					<a href="#" onClick="codeAreaResize('code',<?php echo $ff_config->areasmall; ?>);">[<?php echo $ff_config->areasmall; ?>]</a>
					<a href="#" onClick="codeAreaResize('code',<?php echo $ff_config->areamedium; ?>);">[<?php echo $ff_config->areamedium; ?>]</a>
					<a href="#" onClick="codeAreaResize('code',<?php echo $ff_config->arealarge; ?>);">[<?php echo $ff_config->arealarge; ?>]</a>
					<a href="#" onClick="createCode();"><?php echo _FACILEFORMS_SCRIPTS_CREATECODE; ?></a>
					<br/>
					<textarea wrap="off" onFocus="codeAreaFocus(this);" readonly="readonly" name="code_lines" style="width:60px;" rows="<?php echo $ff_config->areamedium; ?>" class="inputbox"></textarea>
					<textarea wrap="off" onFocus="codeAreaFocus(this);" onKeyUp="codeAreaChange(this,event);" name="code" style="width:690px;" rows="<?php echo $ff_config->areamedium; ?>" class="inputbox"><?php echo htmlspecialchars($row->code, ENT_QUOTES); ?></textarea>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap colspan="2" style="text-align:right">
					<a class="toolbar" href="javascript:submitbutton('save');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('save','','images/save_f2.png',1);">
						<img src="images/save.png"  alt="" name="save" border="0" align="middle" />&nbsp;<?php echo _FACILEFORMS_TOOLBAR_SAVE; ?>
					</a>&nbsp;&nbsp;
					<a class="toolbar" href="javascript:submitbutton('cancel');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('cancel','','images/cancel_f2.png',1);">
						<img src="images/cancel.png"  alt="" name="cancel" border="0" align="middle" />&nbsp;<?php echo _FACILEFORMS_TOOLBAR_CANCEL; ?>
					</a>
				</td>
				<td></td>
			</tr>
		</table>
		<input type="hidden" name="pkg" value="<?php echo $pkg; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="act" value="managescripts" />
		</form>
<?php
	} // edit

	function typeName($type)
	{
		switch ($type) {
			case 'Untyped': return _FACILEFORMS_SCRIPTS_UNTYPED;
			case 'Element Init': return _FACILEFORMS_SCRIPTS_ELEMENTINIT;
			case 'Element Action': return _FACILEFORMS_SCRIPTS_ELEMENTACTION;
			case 'Element Validation': return _FACILEFORMS_SCRIPTS_ELEMENTVALID;
			case 'Form Init': return _FACILEFORMS_SCRIPTS_FORMINIT;
			case 'Form Submitted': return _FACILEFORMS_SCRIPTS_FORMSUBMIT;
			default:;
		} // switch
		return '???';
	} // typeName

	function listitems( $option, &$rows, &$pkglist )
	{
		global $ff_config, $ff_version;
?>
		<script type="text/javascript">
			<!--
			function submitbutton(pressbutton)
			{
				var form = document.adminForm;
				switch (pressbutton) {
					case 'copy':
					case 'publish':
					case 'unpublish':
					case 'remove':
						if (form.boxchecked.value==0) {
							alert("<?php echo _FACILEFORMS_SCRIPTS_SELSCRIPTSFIRST; ?>");
							return;
						} // if
						break;
					default:
						break;
				} // switch
				if (pressbutton == 'remove')
					if (!confirm("<?php echo _FACILEFORMS_SCRIPTS_ASKDELETE; ?>")) return;
				if (pressbutton == '' && form.pkgsel.value == '')
					form.pkg.value = '- blank -';
				else
					form.pkg.value = form.pkgsel.value;
				submitform(pressbutton);
			} // submitbutton

			function listItemTask( id, task )
			{
				var f = document.adminForm;
				cb = eval( 'f.' + id );
				if (cb) {
					for (i = 0; true; i++) {
						cbx = eval('f.cb'+i);
						if (!cbx) break;
						cbx.checked = false;
					} // for
					cb.checked = true;
					f.boxchecked.value = 1;
					submitbutton(task);
				}
				return false;
			} // listItemTask
			//-->
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="1" border="0">
			<tr>
				<td width="50%" nowrap>
					<table class="adminheading">
						<tr><th nowrap class="sections">FacileForms <?php echo $ff_version; ?><br/><span class="componentheading"><?php echo _FACILEFORMS_SCRIPTS_MANAGESCRIPTS; ?></span></th></tr>
					</table>
				</td>
				<td nowrap>
					<?php echo _FACILEFORMS_SCRIPTS_PACKAGE; ?>:
					<select id="pkgsel" name="pkgsel" class="inputbox" size="1" onchange="submitbutton('');">
<?php
					if (count($pkglist)) foreach ($pkglist as $pkg) {
						$selected = '';
						if ($pkg[0]) $selected = ' selected';
						echo '<option value="'.$pkg[1].'"'.$selected.'>'.$pkg[1].'&nbsp;</option>';
					} // foreach
?>
					</select>
				</td>
				<td align="right" width="50%" nowrap>
<?php
		mosMenuBar::startTable();
		mosMenuBar::custom('new',       'new.png',       'new_f2.png',       _FACILEFORMS_TOOLBAR_NEW,       false);
		mosMenuBar::custom('copy',      'copy.png',      'copy_f2.png',      _FACILEFORMS_TOOLBAR_COPY,      false);
		mosMenuBar::custom('publish',   'publish.png',   'publish_f2.png',   _FACILEFORMS_TOOLBAR_PUBLISH,   false);
		mosMenuBar::custom('unpublish', 'unpublish.png', 'unpublish_f2.png', _FACILEFORMS_TOOLBAR_UNPUBLISH, false);
		mosMenuBar::custom('remove',    'delete.png',    'delete_f2.png',    _FACILEFORMS_TOOLBAR_DELETE,    false);
		mosMenuBar::endTable();
?>
				</td>
			</tr>
		</table>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
				<th nowrap align="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
				<th nowrap align="left"><?php echo _FACILEFORMS_SCRIPTS_TITLE; ?></th>
				<th nowrap align="left"><?php echo _FACILEFORMS_SCRIPTS_NAME; ?></th>
				<th nowrap align="left"><?php echo _FACILEFORMS_SCRIPTS_TYPE; ?></th>
				<th nowrap align="right">ID</th>
				<th nowrap align="center"><?php echo _FACILEFORMS_SCRIPTS_PUBLISHED; ?></th>
				<th align="left" width="100%"><?php echo _FACILEFORMS_SCRIPTS_DESCRIPTION; ?></th>
			</tr>
<?php
			$k = 0;
			for($i=0; $i < count($rows); $i++) {
				$row = $rows[$i];
				$desc = $row->description;
				if (strlen($desc) > $ff_config->limitdesc) $desc = substr($desc,0,$ff_config->limitdesc).'...';
?>
				<tr class="row<?php echo $k; ?>">
					<td nowrap valign="top" align="center"><input type="checkbox" id="cb<?php echo $i; ?>" name="ids[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
					<td nowrap valign="top" align="left"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','edit')"><?php echo $row->title; ?></a></td>
					<td nowrap valign="top" align="left"><?php echo $row->name; ?></td>
					<td nowrap valign="top" align="left"><?php echo HTML_facileFormsScript::typeName($row->type); ?></td>
					<td nowrap valign="top" align="right"><?php echo $row->id; ?></td>
					<td nowrap valign="top" align="center"><?php
					if ($row->published == "1") {
						?><a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','unpublish')"><img src="images/publish_g.png" alt="+" border="0" /></a><?php
					} else {
						?><a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','publish')"><img src="images/publish_x.png" alt="-" border="0" /></a><?php
					} // if
					?></td>
					<td valign="top" align="left"><?php echo htmlspecialchars($desc, ENT_QUOTES); ?></td>
				</tr>
<?php
				$k = 1 - $k;
			} // for
?>
		</table>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="managescripts" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="pkg" value="" />
		</form>
<?php
	} // listitems

} // class HTML_facileFormsScript
?>