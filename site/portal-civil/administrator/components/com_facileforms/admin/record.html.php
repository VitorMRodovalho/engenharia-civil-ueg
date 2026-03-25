<?php
/**
* FacileForms - A Joomla Forms Application
* @version 1.4.5
* @package FacileForms
* @copyright (C) 2004-2006 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

require_once($ff_admpath.'/admin/element.html.php');

class HTML_facileFormsRecord
{
	function edit(&$recparams, &$rec, &$subs)
	{
		global $ff_mossite;
?>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script type="text/javascript" src="<?php echo $ff_mossite; ?>/includes/js/overlib_mini.js"></script>
		<form action="index2.php" method="post" name="adminForm" id="adminForm" class="adminForm">
		<table cellpadding="0" cellspacing="0" border="0" class="adminform" style="width:300px;">
			<tr><th colspan="4" class="title">FacileForms - <?php echo _FACILEFORMS_RECORDS_VIEWRECORD; ?></th></tr>
			<tr>
				<td></td>
				<td colspan="2">
					<fieldset><legend><?php echo _FACILEFORMS_RECORDS_SUBMINFO; ?></legend>
						<table cellpadding="0" cellspacing="0" border="1">
							<tr>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_SUBMITTED; ?></strong></td>
								<td nowrap><strong>IP</strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_PROVIDER; ?></strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_OPSYS; ?></strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_BROWSER; ?></strong></td>
							</tr>
							<tr>
								<td nowrap valign="top"><?php echo $rec->submitted; ?></td>
								<td nowrap valign="top"><?php echo $rec->ip; ?></td>
								<td nowrap valign="top"><?php echo htmlspecialchars($rec->provider, ENT_QUOTES); ?></td>
								<td nowrap valign="top"><?php echo htmlspecialchars($rec->opsys, ENT_QUOTES); ?></td>
								<td valign="top"><?php echo htmlspecialchars($rec->browser, ENT_QUOTES); ?></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<fieldset><legend><?php echo _FACILEFORMS_RECORDS_RECORDINFO; ?></legend>
						<table cellpadding="0" cellspacing="0" border="1">
							<tr>
								<td nowrap><strong>ID</strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_VIEWED; ?></strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_EXPORTED; ?></strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_ARCHIVED; ?></strong></td>
							</tr>
							<tr>
								<td nowrap><?php echo $rec->id; ?></td>
								<td nowrap><?php if ($rec->viewed) echo _FACILEFORMS_RECORDS_YES; else echo _FACILEFORMS_RECORDS_NO; ?></td>
								<td nowrap><?php if ($rec->exported) echo _FACILEFORMS_RECORDS_YES; else echo _FACILEFORMS_RECORDS_NO; ?></td>
								<td nowrap><?php if ($rec->archived) echo _FACILEFORMS_RECORDS_YES; else echo _FACILEFORMS_RECORDS_NO; ?></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td>
					<fieldset><legend><?php echo _FACILEFORMS_RECORDS_FORMINFO; ?></legend>
						<table cellpadding="0" cellspacing="0" border="1">
							<tr>
								<td nowrap><strong>ID</strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_TITLE; ?></strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_NAME; ?></strong></td>
							</tr>
							<tr>
								<td nowrap><?php echo $rec->form; ?></td>
								<td nowrap><?php echo $rec->title; ?></td>
								<td nowrap><?php echo $rec->name; ?></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="2">
					<fieldset><legend><?php echo _FACILEFORMS_RECORDS_SUBMVALUES; ?></legend>
						<table cellpadding="0" cellspacing="0" border="1">
							<tr>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_RECORDID; ?></strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_ELEMENTID; ?></strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_TITLE; ?></strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_NAME; ?></strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_TYPE; ?></strong></td>
								<td nowrap><strong><?php echo _FACILEFORMS_RECORDS_VALUE; ?></strong></td>
							</tr>
<?php
			for($i=0; $i < count( $subs ); $i++) {
				$sub = $subs[$i];
?>
							<tr>
								<td nowrap valign="top"><?php echo $sub->id; ?></td>
								<td nowrap valign="top"><?php echo $sub->element; ?></td>
								<td nowrap valign="top"><?php echo $sub->title; ?></td>
								<td nowrap valign="top"><?php echo $sub->name; ?></td>
								<td nowrap valign="top"><?php echo HTML_facileFormsElement::displayType($sub->type); ?></td>
								<td width="100%" valign="top"><?php echo $sub->value; ?></td>
							</tr>
<?php
			} // for
?>
						</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="2" style="text-align:right">
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
		<input type="hidden" name="option" value="<?php echo $recparams['option']; ?>" />
		<input type="hidden" name="viewed" value="<?php echo $recparams['viewed']; ?>" />
		<input type="hidden" name="exported" value="<?php echo $recparams['exported']; ?>" />
		<input type="hidden" name="archived" value="<?php echo $recparams['archived']; ?>" />
		<input type="hidden" name="formname" value="<?php echo $recparams['formname']; ?>" />
		<input type="hidden" name="act" value="mngrecs" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="record_id" value="<?php echo $rec->id; ?>" />
		</form>
<?php
	} // edit

	function listitems( &$recparams, &$rows, $downloadfile='')
	{
		global $ff_admsite, $ff_version;
?>
		<script type="text/javascript">
			<!--
			function submitbutton(pressbutton)
			{
				var form = document.adminForm;
				switch (pressbutton) {
					case 'expxml':
					case 'viewed':
					case 'exported':
					case 'archived':
					case 'remove':
						if (form.boxchecked.value==0) {
							alert("<?php echo _FACILEFORMS_RECORDS_PLSSELECTRECS; ?>");
							return;
						} // if
						break;
					default:
						break;
				} // switch
				switch (pressbutton) {
					case 'remove':
						if (!confirm("<?php echo _FACILEFORMS_RECORDS_ASKDELETE; ?>")) return;
						break;
					default:
						break;
				} // switch
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
		<form action="index2.php" method="post" name="adminForm" id="adminForm" class="adminForm">
		<table cellpadding="4" cellspacing="1" border="0">
			<tr>
				<td width="50%" nowrap>
					<table class="adminheading">
						<tr><th class="impressions">FacileForms <?php echo $ff_version; ?><br/><span class="componentheading"><?php echo _FACILEFORMS_RECORDS_MANAGERECS; ?></span></th></tr>
					</table>
				</td>
				<td nowrap valign="top">
					<fieldset><legend><?php echo _FACILEFORMS_RECORDS_VIEWSTATUS; ?></legend>
						<input type="radio" id="viewed1" name="viewed" value="0"<?php if ($recparams['viewed']=='0') echo ' checked'; ?> /><label for="viewed1"> <?php echo _FACILEFORMS_RECORDS_UNVIEWEDONLY; ?></label><br/>
						<input type="radio" id="viewed2" name="viewed" value="1"<?php if ($recparams['viewed']=='1') echo ' checked'; ?> /><label for="viewed2"> <?php echo _FACILEFORMS_RECORDS_VIEWEDONLY; ?></label><br/>
						<input type="radio" id="viewed3" name="viewed" value="2"<?php if ($recparams['viewed']=='2') echo ' checked'; ?> /><label for="viewed3"> <?php echo _FACILEFORMS_RECORDS_BOTH; ?></label>
					</fieldset>
				</td>
				<td nowrap valign="top">
					<fieldset><legend><?php echo _FACILEFORMS_RECORDS_EXPORTSTATUS; ?></legend>
						<input type="radio" id="exported1" name="exported" value="0"<?php if ($recparams['exported']=='0') echo ' checked'; ?> /><label for="exported1"> <?php echo _FACILEFORMS_RECORDS_UNEXPORTEDONLY; ?></label><br/>
						<input type="radio" id="exported2" name="exported" value="1"<?php if ($recparams['exported']=='1') echo ' checked'; ?> /><label for="exported2"> <?php echo _FACILEFORMS_RECORDS_EXPORTEDONLY; ?></label><br/>
						<input type="radio" id="exported3" name="exported" value="2"<?php if ($recparams['exported']=='2') echo ' checked'; ?> /><label for="exported3"> <?php echo _FACILEFORMS_RECORDS_BOTH; ?></label>
					</fieldset>
				</td>
				<td nowrap valign="top">
					<fieldset><legend><?php echo _FACILEFORMS_RECORDS_ARCHSTATUS; ?></legend>
						<input type="radio" id="archived1" name="archived" value="0"<?php if ($recparams['archived']=='0') echo ' checked'; ?> /><label for="archived1"> <?php echo _FACILEFORMS_RECORDS_UNARCHIVEDONLY; ?></label><br/>
						<input type="radio" id="archived2" name="archived" value="1"<?php if ($recparams['archived']=='1') echo ' checked'; ?> /><label for="archived2"> <?php echo _FACILEFORMS_RECORDS_ARCHIVEDONLY; ?></label><br/>
						<input type="radio" id="archived3" name="archived" value="2"<?php if ($recparams['archived']=='2') echo ' checked'; ?> /><label for="archived3"> <?php echo _FACILEFORMS_RECORDS_BOTH; ?></label>
					</fieldset>
				</td>
				<td nowrap align="center" valign="top">
					<fieldset><legend><?php echo _FACILEFORMS_RECORDS_FORMNAME; ?></legend>
						<input type="text" size="30" maxlength="30" name="formname" value="<?php echo $recparams['formname']; ?>" />
					</fieldset><br/>
					<input type="button" name="savebutton" value="<?php echo _FACILEFORMS_RECORDS_SAVERELOAD; ?>" onclick="submitbutton('savesettings')"/>&nbsp;
					<input type="button" name="reloadbutton" value="<?php echo _FACILEFORMS_RECORDS_RELOAD; ?>" onclick="submitbutton('listrecs')"/>
				</td>
				<td width="50%" align="right" nowrap>
<?php
		$icon1 = '../components/com_facileforms/images/icons/switch.png';
		$icon2 = '../components/com_facileforms/images/icons/switch_f2.png';
		$icon3 = '../components/com_facileforms/images/icons/download.png';
		$icon4 = '../components/com_facileforms/images/icons/download_f2.png';
		mosMenuBar::startTable();
		mosMenuBar::custom('expxml',    $icon3,             $icon4,             _FACILEFORMS_TOOLBAR_EXPXML,    false);
		mosMenuBar::custom('remove',    'delete.png',       'delete_f2.png',    _FACILEFORMS_TOOLBAR_DELETE,    false);
		mosMenuBar::endTable();
		echo '<br/>';
		mosMenuBar::startTable();
		mosMenuBar::custom('viewed',    $icon1,             $icon2,             _FACILEFORMS_TOOLBAR_VIEWED,    false);
		mosMenuBar::custom('exported',  $icon1,             $icon2,             _FACILEFORMS_TOOLBAR_EXPORTED,  false);
		mosMenuBar::custom('archived',  $icon1,             $icon2,             _FACILEFORMS_TOOLBAR_ARCHIVED,  false);
		mosMenuBar::endTable();
?>
				</td>
			</tr>
		</table>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
				<th nowrap align="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
				<th nowrap align="left">ID</th>
				<th nowrap align="left"><?php echo _FACILEFORMS_RECORDS_SUBMITTED; ?></th>
				<th nowrap align="left">IP</th>
				<th nowrap align="left"><?php echo _FACILEFORMS_RECORDS_PROVIDER; ?></th>
				<th nowrap align="left"><?php echo _FACILEFORMS_RECORDS_TITLE; ?></th>
				<th nowrap align="left"><?php echo _FACILEFORMS_RECORDS_NAME; ?></th>
				<th nowrap align="center"><?php echo _FACILEFORMS_RECORDS_VIEWED; ?></th>
				<th nowrap align="center"><?php echo _FACILEFORMS_RECORDS_EXPORTED; ?></th>
				<th nowrap align="center"><?php echo _FACILEFORMS_RECORDS_ARCHIVED; ?></th>
				<th width="100%"></th>
			</tr>
<?php
			$k = 0;
			for($i=0; $i < count( $rows ); $i++) {
				$row = $rows[$i];
				if ($row->viewed) $view_src = "images/tick.png"; else $view_src = "images/publish_x.png";
				if ($row->exported) $exp_src = "images/tick.png"; else $exp_src = "images/publish_x.png";
				if ($row->archived) $arch_src = "images/tick.png"; else $arch_src = "images/publish_x.png";
?>
				<tr class="row<?php echo $k; ?>">
					<td nowrap align="center"><input type="checkbox" id="cb<?php echo $i; ?>" name="ids[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
					<td nowrap align="left"><a href="#" onclick="return listItemTask('cb<?php echo $i; ?>','edit')"><?php echo $row->id; ?></a></td>
					<td nowrap align="left"><a href="#" onclick="return listItemTask('cb<?php echo $i; ?>','edit')"><?php echo $row->submitted; ?></a></td>
					<td nowrap align="left"><?php echo $row->ip; ?></td>
					<td nowrap align="left"><?php echo $row->provider; ?></td>
					<td nowrap align="left"><?php echo $row->title; ?></td>
					<td nowrap align="left"><?php echo $row->name; ?></td>
					<td nowrap align="center"><a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','viewed')"><img src="<?php echo $view_src; ?>" alt="+" border="0" /></a></td>
					<td nowrap align="center"><a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','exported')"><img src="<?php echo $exp_src; ?>" alt="+" border="0" /></a></td>
					<td nowrap align="center"><a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','archived')"><img src="<?php echo $arch_src; ?>" alt="+" border="0" /></a></td>
					<td></td>
				</tr>
<?php
				$k = 1 - $k;
			} // for
?>
		</table>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="<?php echo $recparams['option']; ?>" />
		<input type="hidden" name="act" value="mngrecs" />
		<input type="hidden" name="task" value="" />
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
	} // listitems

} // class HTML_facileFormsRecord

?>