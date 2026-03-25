<?php
/**
* Facile Forms - A Joomla Forms Application
* @version 1.4.4
* @package FacileForms
* @copyright (C) 2004 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

class HTML_facileFormsInstaller
{
	function step2($option, $release)
	{
?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton)
		{
			var form = document.adminForm;
			var error = '';
			var checked = false;
			for (var i = 0; true; i++) {
				opt = eval('form.opt'+i);
				if (!opt) break;
				if (opt.checked) {
					checked = true;
					break;
				} // if
			} // for
			if (!checked)
				alert("<?php echo _FACILEFORMS_INSTALL_SELECTMODE; ?>");
			else
				submitform(pressbutton);
		} // submitbutton

		</script>
		<table cellpadding="4" cellspacing="1" border="0" class="adminform" style="width:450px;">
		<form action="index2.php" method="post" name="adminForm" id="adminForm" class="adminForm">
			<tr><th colspan="3" class="title"><?php echo _FACILEFORMS_INSTALL_STEP2; ?></th></tr>
			<tr>
				<td></td>
				<td><?php echo _FACILEFORMS_INSTALL_STEP2MSG; ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap>
					<fieldset><legend><?php echo _FACILEFORMS_INSTALL_SELECTDBMODE; ?></legend>
					<table cellpadding="4" cellspacing="1" border="0">
						<tr>
							<td nowrap>
								<input type="radio" id="opt0" name="ff_installmode" value="0"<?php if ($release=='') echo ' checked="checked"'; ?>/>
								<label for="opt0"> <?php echo _FACILEFORMS_INSTALL_NEWINSTALL; ?></label>
							</td>
						</tr>
						<tr>
							<td nowrap>
								<input type="radio" id="opt1" name="ff_installmode" value="1"<?php if ($release=='1.4') echo ' checked="checked"'; ?>/>
								<label for="opt1"> <?php echo _FACILEFORMS_INSTALL_REINSTALL.' 1.4.* ('._FACILEFORMS_INSTALL_UPTODATE.')'; ?></label>
							</td>
						</tr>
						<tr>
							<td nowrap>
								<input type="radio" id="opt5" name="ff_installmode" value="5"<?php if ($release=='1.3') echo ' checked="checked"'; ?>/>
								<label for="opt5"> <?php echo _FACILEFORMS_INSTALL_UPGRADE.' 1.3.*'; ?></label>
							</td>
						</tr>
						<tr>
							<td nowrap>
								<input type="radio" id="opt4" name="ff_installmode" value="4"<?php if ($release=='1.2') echo ' checked="checked"'; ?>/>
								<label for="opt4"> <?php echo _FACILEFORMS_INSTALL_UPGRADE.' 1.2.*'; ?></label>
							</td>
						</tr>
						<tr>
							<td nowrap>
								<input type="radio" id="opt3" name="ff_installmode" value="3"<?php if ($release=='1.1') echo ' checked="checked"'; ?>/>
								<label for="opt3"> <?php echo _FACILEFORMS_INSTALL_UPGRADE.' 1.1.*'; ?></label>
							</td>
						</tr>
						<tr>
							<td nowrap>
								<input type="radio" id="opt2" name="ff_installmode" value="2"<?php if ($release=='1.0') echo ' checked="checked"'; ?>/>
								<label for="opt2"> <?php echo _FACILEFORMS_INSTALL_UPGRADE.' 1.0.*'; ?></label>
							</td>
						</tr>
					</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap>
					<fieldset><legend><?php echo _FACILEFORMS_INSTALL_SELECTOPTIONS; ?></legend>
					<table cellpadding="4" cellspacing="1" border="0">
						<tr>
							<td nowrap>
								<input id="smp2" type="checkbox" name="ff_instsamples" value="1" checked="checked" />
								<label for="smp2"> <?php echo _FACILEFORMS_INSTALL_INSTSAMPLES; ?></label>
							</td>
						</tr>
						<tr>
							<td nowrap>
								<input id="old3" type="checkbox" name="ff_instoldlib" value="1" />
								<label for="old3"> <?php echo _FACILEFORMS_INSTALL_INSTOLDLIB; ?></label>
							</td>
						</tr>
					</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap style="text-align:right">
					<a class="toolbar" href="javascript:submitbutton('step3');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('next','','images/next_f2.png',1);">
						<img src="images/next.png"  alt="" name="next" border="0" align="middle" value="next" />&nbsp;<?php echo _FACILEFORMS_TOOLBAR_CONTINUE; ?>
					</a>
				</td>
				<td></td>
			</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="installation" />
		<input type="hidden" name="task" value="" />
		</form>
<?php
	} // step2


	function step3($option, &$errors)
	{
?>
		<table cellpadding="4" cellspacing="1" border="0" class="adminform" style="width:450px;">
		<form action="index2.php" method="post" name="adminForm" id="adminForm" class="adminForm">
			<tr><th colspan="3" class="title"><?php echo _FACILEFORMS_INSTALL_COMPLETE; ?></th></tr>
			<tr>
				<td></td>
				<td><?php echo _FACILEFORMS_INSTALL_COMPLETEMSG; ?>
					<hr/><br/>
<?php
					if (count($errors)==0)
						echo _FACILEFORMS_INSTALL_NOMESSAGES;
					else
						for ($i = 0; $i < count($errors); $i++) echo $errors[$i]."<br/>";
?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap style="text-align:right">
					<a class="toolbar" href="javascript:submitbutton('step4');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('next','','images/next_f2.png',1);">
						<img src="images/next.png"  alt="" name="next" border="0" align="middle" value="next" />&nbsp;<?php echo _FACILEFORMS_TOOLBAR_CONTINUE; ?>
					</a>
				</td>
				<td></td>
			</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="installation" />
		<input type="hidden" name="task" value="" />
		</form>
<?php
	} // step3

} // class HTML_facileFormsInstaller
?>