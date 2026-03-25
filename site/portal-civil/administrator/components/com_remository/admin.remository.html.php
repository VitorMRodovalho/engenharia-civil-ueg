<?php

/**************************************************************
* This file is part of Remository
* Copyright (c) 2006 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
* Remository started life as the psx-dude script by psx-dude@psx-dude.net
* It was enhanced by Matt Smith up to version 2.10
* Since then development has been primarily by Martin Brampton,
* with contributions from other people gratefully accepted
*/

class basicHTML {
	var $pageNav = '';
	var $act = '';
	var $limit = 10;
	
	function basicHTML (&$controller, $limit) {
		$this->act = $_REQUEST['act'];
		$this->limit = $limit;
		$this->pageNav = $controller->pageNav;
	}
	
	function tickBox ($object, $property) {
		if (is_object($object) AND $object->$property) $checked = "checked='checked'";
		else $checked = '';
		echo "<td><input type='checkbox' name='$property' value='1' $checked /></td>";
	}

	function yesNoList ($object, $property) {
		$yesno[] = $this->repository->makeOption( 0, _NO );
		$yesno[] = $this->repository->makeOption( 1, _YES );
		if ($object) $default = $object->$property;
		else $default = 0;
		echo '<td valign="top">';
		echo $this->repository->selectList($yesno, $property, 'class="inputbox" size="1"', $default);;
		echo '</td></tr>';
	}

	function inputTop ($title, $redstar=false, $maxsize=0) {
		?>
		<tr>
		  	<td width="30%" valign="top" align="right">
				<b><?php if ($redstar) echo '<font color="red">*</font>'; echo $title; if ($maxsize) echo "</b>&nbsp;<br /><i>$maxsize</i>&nbsp;"; ?></b>&nbsp;
			</td>
		<?php
	}

	function blankRow () {
		?>
			<tr><td>&nbsp;</td></tr>
		<?php
	}

	function fileInputBox ($title, $name, $value, $width, $tooltip=null) {
		?>
		<tr>
		  	<td width="30%" valign="top" align="right">
		  	<b><?php echo $title; ?></b>
			</td>
			<td align="left" valign="top">
				<input class="inputbox" type="text" name="<?php echo $name; ?>" size="<?php echo $width; ?>" value="<?php echo $value; ?>" />
				<?php if ($tooltip) echo tooltip($tooltip); ?>
			</td>
		</tr>
		<?php
	}

	function fileUploadBox ($title, $width, $tooltip=null) {
		?>
		<tr>
		  	<td width="30%" valign="top" align="right">
		  	<b><?php echo $title; ?></b>
			</td>
			<td align="left" valign="top">
				<input class="inputbox" type="file" name="userfile" size="<?php echo $width ?>" />
				<?php if ($tooltip) echo tooltip($tooltip); ?>
			</td>
		</tr>
		<?php
	}

	function fileInputArea ($title, $maxsize, $name, $value, $rows, $cols, $editor=false, $tooltip=null) {
		?>
		<tr>
		  	<td width="30%" valign="top" align="right">
		  	<b><?php echo $title; echo "</b>&nbsp;<br /><i>$maxsize</i>&nbsp;";?></b>
			</td>
		<?php
		echo '<td valign="top">';
		if ($editor) editorArea( 'description', $value, $name, 500, 200, $rows, $cols );
		else echo "<textarea class='inputbox' name='$name' rows='$rows' cols='$cols'>$value</textarea>";
		if ($tooltip) echo tooltip($tooltip);
		echo '</td></tr>';
	}

	function tickBoxField ($object, $property, $title) {
		?>
		<tr>
			<td width="30%" valign="top" align="right">
				<b><?php echo $title; ?></b>&nbsp;
			</td>
		<?php
		if (is_object($object) AND $object->$property) $checked = "checked='checked'";
		else $checked = '';
		echo "<td><input type='checkbox' name='$property' value='1' $checked /></td>";
		echo '</tr>';
	}

	function simpleTickBox ($title, $name, $checked=false) {
		if ($checked) $check = 'checked="checked"';
		else $check = '';
		?>
		<tr>
			<td width="30%" valign="top" align="right">
				<b><?php echo $title; ?></b>&nbsp;
			</td>
			<td>
				<input type="checkbox" name="<?php echo $name; ?>" value="1" <?php echo $check; ?> />
			</td>
		</tr>
		<?php
	}
	function formStart ($title) {
		$interface =& remositoryInterface::getInstance();
		?>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
		<script language="Javascript" src="../includes/js/overlib_mini.js"></script>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
   		<tr>
			<td width="75%" colspan="3">
			<div class="title">
			<?php echo $this->repository->RemositoryImageURL('header.gif',64,64); ?>
			<span class="sectionname">&nbsp;ReMOSitory <?php echo $title; ?></span>
			</div>
			</td>
			<td width="25%">
			</td>
    	</tr>
		<?php
	}
	
	function listHeadingStart ($count) {
		?>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
				<th width="5" align="left">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $count; ?>);" />
				</th>
		<?php
	}

	function headingItem ($width, $title) {
		echo "<th width=\"$width\" align=\"left\">$title</th>";
	}

	function commonScripts ($edit_fields) {
		?>
		<script type="text/javascript">
        function submitbutton(pressbutton) {
                <?php
				if (is_array($edit_fields)) foreach ($edit_fields as $field) getEditorContents( $field, $field );
				else getEditorContents ($edit_fields, $edit_fields);
				?>
                submitform( pressbutton );
        }
        </script>
        <?php
	}

	function listFormEnd ($pagecontrol=true) {
		if ($pagecontrol) {
			?>
			<tr>
	    		<th align="center" colspan="13"> <?php echo $this->pageNav->writePagesLinks(); ?></th>
			</tr>
			<tr>
				<td align="center" colspan="13"> <?php echo $this->pageNav->writePagesCounter(); ?></td>
			</tr>
			<?php
		}
		?>
		<input type="hidden" name="option" value="com_remository" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="act" value="<?php echo $_REQUEST['act']; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		</table>
		</form>
		<?php
	}

	function editFormEnd ($id, $oldpath) {
		?>
		<input type="hidden" name="cfid" value="<?php echo $id; ?>" />
		<input type="hidden" name="limit" value="<?php echo $this->limit; ?>" />
		<input type="hidden" name="oldpath" value="<?php echo $oldpath; ?>" />
		<input type="hidden" name="option" value="com_remository" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="act" value="<?php echo $_REQUEST['act']; ?>" />
		</table>
		</form>
		<?php
	}

	function multiOptionList ($name, $title, $options, $current, $tooltip=null) {
		$alternatives = explode(',',$options);
		$already = explode(',', $current);
		?>
		<tr>
	    <td width="30%" valign="top" align="right">
	  	<b><?php echo $title; ?></b>&nbsp;
	    </td>
	    <td valign="top">
		<?php
		foreach ($alternatives as $one) {
			if (in_array($one,$already)) $mark = 'checked="checked"';
			else $mark = '';
			$value = $name.'_'.$one;
			echo "<input type=\"checkbox\" name=\"$value\" $mark />$one";
		}
		if ($tooltip) echo '&nbsp;'.tooltip($tooltip);
		echo '</td></tr>';
	}

	function tooltip ($text) {
		return '<a href="javascript:void(0)"  onmouseover="return escape('."'".$text."'".')">'.RemositoryRepository::RemositoryImageURL('tooltip.png').'</a>';
	}

}

class remositoryAdminHTML extends basicHTML {
	var $repository = '';
	var $pageNav = '';
	var $clist = '';
	var $act = '';
	
	function remositoryAdminHTML (&$controller, $limit, $clist) {
	    basicHTML::basicHTML($controller, $limit);
		$this->repository =& $controller->repository;
		$this->clist = $clist;
	}

	function displayIcons ($object, $iconList) {
		if (is_object($object)) $icon = $object->icon;
		else $icon = '';
		?>
		<script type="text/javascript">
		function paste_strinL(strinL){
			var input=document.forms["adminForm"].elements["icon"];
			input.value='';
			input.value=strinL;
		}
		</script>
		<tr>
			<td width="30%" valign="top" align="right">
				<b><?php echo _DOWN_ICON; ?></b>&nbsp;
			</td>
			<td valign="top">
				<input class="inputbox" type="text" name="icon" size="25" value="<?php echo $icon; ?>" />
				<table>
					<tr>
						<td>
							<?php echo $iconList; ?>
						</td>
					</tr>
				</table>
			</td>
  		</tr>
  		<?php
	}
	
	function listHeader ($descendants, $search) {
		?>
		<tr>
    		<td align="left"><?php echo _DOWN_DISPLAY_NUMBER.$this->pageNav->writeLimitBox(); ?>
			</td>
			<td align="left"><?php echo _DOWN_SEARCH_COLON; ?><input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
    		</td>
			<td align="left"><?php echo _DOWN_SHOW_DESCENDANTS; ?><input type="checkbox" name="descendants" value="1" <?php if ($descendants) echo 'checked="checked"'; ?> onChange="document.adminForm.submit();" />
			</td>
		</tr>
		<tr>
		<?php
		if ($this->clist<>'') {
			echo '<td align="left" colspan=3>'.$this->clist.'</td>';
		} 
		echo '</tr>';
	}
	
	function containerSelectBox () {
		?>
		<tr>
			<td width="30%" valign="top" align="right">
				<b><?php echo _DOWN_SUGGEST_LOC; ?></b>&nbsp;
			</td>
			<td valign="top">
				<?php echo $this->clist; ?>
			</td>
		</tr>
		<?php
	}
	
	function startEditHeader () {
		$tabclass_arr = $this->repository->getTableClasses();
		?>
		<form method="post" name="adminForm" action="index2.php" enctype="multipart/form-data">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="<?php echo $tabclass_arr[0]; ?>">
		<?php
		$this->containerSelectBox();
	}
	
	function publishedBox (&$object) {
		?>
				<tr>
					<td width="30%" align="right">
				  	<b><?php echo _DOWN_PUB; ?></b>&nbsp;
				  </td>
				  	<?php 
					if (is_object($object) AND @$object->published) $checked = "checked='checked'";
					else $checked = '';
					echo "<td><input type='checkbox' name='published' value='1' $checked /></td>";
					?>
				</tr>
		<?php
	}
	
	function editLink ($id, $containerid=0) {
		$url = "index2.php?option=com_remository&amp;act=$this->act&amp;task=edit&amp;cfid=$id";
		if ($containerid) $url .= "&amp;containerid=$containerid";
		return $url;
	}
	
	function visitLink ($id) {
	    $url = "index2.php?option=com_remository&amp;act=$this->act&amp;parentid=$id";
	    return $url;
	}

	function legalTypeList ($current) {
		$alternatives = explode(',',_REMOS_LEGAL_TYPES);
		foreach ($alternatives as $one) {
			if ($one == $current) $mark = 'selected=\'selected\'';
			else $mark = '';
			echo "<option $mark value='$one'>$one</option>";
		}
	}

}


?>
