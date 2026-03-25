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

class HTML_MBTGroups 
{
    function showGroups($rows, $search, $pageNav)
    {
        ?>
        <form action="index2.php" method="post" name="adminForm">
			<table class="adminheading">
				<tr>
					<th><?php echo _MBT_GROUP_MANAGER; ?></th>
					<td nowrap="nowrap">
                    <?php echo _MBT_GROUP_FILTER; ?>
                    <input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
					</td>
				</tr>
            </table>
			<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
				<tr>
					<th width="2%" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows);?>);" /></th>
					<th class="title" width="30%"><div align="center"><?php echo _MBT_GROUP_GROUP; ?></div></th>
					<th class="title" width="55%"><div align="center"><?php echo _MBT_GROUP_DESCRIPTION; ?></div></th>
					<th class="title" width="15%"><div align="center"><?php echo _MBT_GROUP_EMAIL;?></div></th>
				</tr>
				<?php
        $k = 0;
        for ($i = 0, $n = count($rows);$i < $n;$i++) {
            $row = &$rows[$i];
            echo "<tr class='row $k'>";
            echo "<td width='20'>";

            ?>
				<input type="checkbox" id="cb<?php echo $i;?>" name="cfid[]" value="<?php echo $row->group_id;?>" onclick="isChecked(this.checked);" />
					</td>
					<td align="center">
						<a href="#edit" onclick="return listItemTask('cb<?php echo $i;?>','edit')">
					<?php echo $row->group_name;?>
						</a>
					</td>
					<td align="center"><?php echo $row->group_description;?></td>
					<td align="center"><img src="components/com_remository/images/email.png" border=0></td>
			  <?php
            echo "</tr>";
            $k = 1 - $k;
        } 

        ?>
		
		</table>
		<?php 
		echo $pageNav->getListFooter();
		basicHTML::cpanelLink();
		?>
	  <input type="hidden" name="option" value="com_remository" />
      <input type="hidden" name="act" value="groups" />
	  <input type="hidden" name="task" value="" />
	  <input type="hidden" name="boxchecked" value="0" />
	</form>
	
  <?php 
    } 

    function editGroup(&$row, $usersList, $toAddUsersList)
    {
		$interface =& remositoryInterface::getInstance();
        $tabs =& $interface->makeTabs();

        ?>
		<script>
			function submitbutton(pressbutton) {
		
			  var form = document.adminForm;
		
			  if (pressbutton == 'list') {
				submitform( pressbutton );
				return;
			  }
		
			  // do field validation
		
			  if (form.group_name.value == ""){
				alert( "<?php echo _MBT_GROUP_MISS_GROUP;?>" );
			  } else {
				allSelected(document.adminForm['users_selected[]']);
				submitform( pressbutton );
			  }
			}
		</script>
		
		<script>
			// moves elements from one select box to another one
			function moveOptions(from,to) {
			  // Move them over
			  for (var i=0; i<from.options.length; i++) {
				var o = from.options[i];
				if (o.selected) {
				  to.options[to.options.length] = new Option( o.text, o.value, false, false);
				}
			  }
			  // Delete them from original
			  for (var i=(from.options.length-1); i>=0; i--) {
				var o = from.options[i];
				if (o.selected) {
				  from.options[i] = null;
				}
			  }
			  from.selectedIndex = -1;
			  to.selectedIndex = -1;
			}
		
			function allSelected(element) {
		
			   for (var i=0; i<element.options.length; i++) {
					var o = element.options[i];
					o.selected = true;
		
				}
			 }
		</script>
	
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script language="Javascript" src="<?php echo $interface->getCfg('live_site');?>/includes/js/overlib_mini.js"></script>
		<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="<?php echo $interface->getCfg('live_site');?>/includes/js/tabs/tabpane.css" />
		<script type="text/javascript" src="<?php echo $interface->getCfg('live_site');?>/includes/js/tabs/tabpane.js"></script>
	
			   <table class="adminheading">
					<tr>
						<th><?php echo $row->group_id ? _MBT_GROUP_EDIT: _MBT_GROUP_ADD; ?>
						</th>
					</tr>
				</table>
		<?php
        $tabs->startPane("content-pane");
        $tabs->startTab(_MBT_GROUP_GROUP, "group-page");
        ?>
	
			<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
				<form action="index2.php" method="post" name="adminForm" id="adminForm">
					<tr>
						<td width="20%" align="right"><?php echo _MBT_GROUP_NAME;?>:</td>
						<td width="80%">
							<input class="inputbox" type="text" name="group_name" size="50" maxlength="100" value="<?php echo htmlspecialchars($row->group_name, ENT_QUOTES);?>" />
						</td>
					</tr>
					<tr>
						<td valign="top" align="right"><?php echo _MBT_GROUP_DESCRIPTION;?></td>
						<td valign="top">
							<textarea name="group_description" cols="80" rows="19"><?php echo htmlspecialchars($row->group_description, ENT_QUOTES);?></textarea>
						</td>
					</tr>
		   </table>
	
		<?php
        $tabs->endTab();
        $tabs->startTab(_MBT_GROUP_MEMBERS, "members-page");
        ?>
		   <table>
				<tr>
					<td width="200px"><?php echo _MBT_GROUP_AVAI_USER;?></td>
					<td width="40px">&nbsp;</td>
					<td width="200px"><?php echo _MBT_GROUP_SEL_USER;?></td>
				</tr>
				<tr>
					<td width="200px"><?php echo $toAddUsersList;?></td>
					<td width="40px">
						<input style="width: 50px" type="button" name="Button" value="&gt;" onClick="moveOptions(document.adminForm.users_not_selected, document.adminForm['users_selected[]'])" />
						<br /><br />
						<input style="width: 50px" type="button" name="Button" value="&lt;" onClick="moveOptions(document.adminForm['users_selected[]'],document.adminForm.users_not_selected)" />
						<br />
						<br />
					</td>
					<td width="200px"><?php echo $usersList;?></td>
				</tr>
				<tr>
					<td colspan=3>&nbsp;</td>
				</tr>
			</table>
			</div>
			<input type="hidden" name="group_id" value="<?php echo $row->group_id;?>" />
			<input type="hidden" name="option" value="com_remository" />
      		<input type="hidden" name="act" value="groups" />
			<input type="hidden" name="task" value="" />
		</form>
	
		<?php
        $tabs->endTab();
        $tabs->endPane();
    } 

    function messageForm($group, &$list)
    {

        ?>
		<script>
			function submitbutton(pressbutton) {
		
			  var form = document.adminForm;
		
			  if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			  }
		
			  // do field validation
		
			  if (form.mm_subject.value == ""){
				alert( "<?php echo _MBT_GROUP_MISS_SUB;?>" );
				form.mm_subject.focus();
			  } else if (form.mm_message.value == ""){
				alert( "<?php echo _MBT_GROUP_MISS_MSG;?>" );
				form.mm_message.focus();
			  }else
		      {
				submitform( pressbutton );
			  }
			}
		</script>
		<form action="index2.php" method="post" name="adminForm" id="adminForm">
        <table cellpadding="4" cellspacing="0" border="0" width="100%">
            <tr>
                <td width="100%"><span class="sectionname"><?php echo _MBT_GROUP_EMAIL.": ".$group->group_name;?></span></td>
            </tr>
        </table>
        <table cellpadding="5" cellspacing="1" border="0" width="100%" class="adminform">
            <tr>
                <td width="150"><?php echo _MBT_GROUP_GROUP;?>:</td>
                <td width="85%"><?php echo $group->group_name;?></td>
			</tr>
            <tr>
                <td width="150"><?php echo _MBT_GROUP_SUBJECT;?>:</td>
                <td width="85%"><input class="inputbox" type="text" name="mm_subject" value="" size="50"></td>
            </tr>
            <tr>
                <td width="150" valign="top"><?php echo _MBT_GROUP_MESSAGE;?>:</td>
                <td width="85%"><textarea cols="50" rows="5" name="mm_message" wrap="virtual" class="inputbox"></textarea></td>
            </tr>
        </table>
        <input type="button" name="send" value="<?php echo _MBT_GROUP_SEND;?>" onclick="submitbutton('sendemail')">
					
			<input type="hidden" name="gid" value="<?php echo $group->group_id; ?>" />
			<input type="hidden" name="option" value="com_remository" />
      		<input type="hidden" name="act" value="groups" />
			<input type="hidden" name="task" value="" />
        </form>
        <?php
    } 
} 

?>