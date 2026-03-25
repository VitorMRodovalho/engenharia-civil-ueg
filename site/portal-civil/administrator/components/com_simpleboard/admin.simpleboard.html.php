<?php
//
// Copyright (C) 2003 Two Shoes Module Factory
// All rights reserved.
//
// This program uses parts of the original Simpleboard Application
// 0.7.0b written by Josh Levine; http://www.joshlevine.net
//
// This source file is part of the SimpleBoard Component, a Mambo 4.5
// custom Component By Two Shoes Module Factory - http://tsmf.jigsnet.com
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//


// ################################################################
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
// ################################################################

class HTML_SIMPLEBOARD {
   
   function controlPanel($version) {
   global $mosConfig_absolute_path, $mosConfig_admin_template, $mosConfig_live_site;
	global $_CONFIG;
	
	if ($_CONFIG->SITEPATH == "") {
		$_CONFIG->SITEPATH=$mosConfig_absolute_path;
		$_CONFIG->SITEURL = $mosConfig_live_site;
	}
   ?>
   <table class="adminheading" border="0">
   <tr>
      <th class="cpanel">Simpleboard Control Panel</th>
   </tr>
   </table>
<?php
   $path = $_CONFIG->SITEPATH."/administrator/components/com_simpleboard/sb_cpanel.php";
   if (file_exists( $path )) {
          require $path;
      } else {
          echo '<br />mcap==: '.$_CONFIG->SITEPATH.' .... help!!';
         mosLoadAdminModules( 'cpanel', 1 );
      }
   }  

   function showAdministration( $rows, $pageNav, $option ) {
?>
   <form action="index2.php" method="post" name="adminForm">
   <table cellpadding="4" cellspacing="0" border="0" width="100%">
   <tr>
      <td width="100%" class="sectionname">SimpleBoard Administration</td>
      <td nowrap>Display #</td>
      <td>
         <?php echo $pageNav->writeLimitBox(); ?>
      </td>
   </tr>
   <tr>
      <td colspan="3"><img src="images/cpanel.png" width="16px" height="16px" align="middle" border="0"/>  <a href="index2.php?option=com_simpleboard"><?php echo _COM_C_BACK;?></a></td>
   </tr>
   </table>

   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
         <th width="20">#</th>
         <th width="20">
            <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
         </th>
         <th class="title">Category / Forum</td>
         <th><small>Locked</small></td>
         <th><small>Moderated</small></td>
         <th><small>Review</small></td>
         <th><small>Published</small></td>
         <th><small>Public Access</small></td>
         <th><small>Admin Access</small></td>
         <th><small>Checked Out</small></td>
         <th colspan="2"><small>Reorder</small></th>
      </tr>
      <?php
      $k = 0;
      $i = 0;

      for ($i=0, $n=count( $rows ); $i < $n; $i++) {
         $row = $rows[$i];

         if($row->parent==0){?>
            <tr bgcolor="#D4D4D4"><?php
         }else{?>
            <tr class="row<?php echo $k; ?>">
       <?php }?>
            <td width="20" align="right"><?php echo $i+$pageNav->limitstart+1;?></td>
            <td width="20"><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onClick="isChecked(this.checked);"></td>
            <td width="70%"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','edit')"><?php echo ($row->category ? "$row->category/$row->name" : "$row->name"); ?></a></td>
            <td align="center"><?php echo (!$row->category ? "&nbsp;" : ($row->locked==1    ? "<img src=\"images/tick.png\">" : "<img src=\"images/publish_x.png\">")); ?></td>
            <td align="center"><?php echo ($row->moderated==1 ? "<img src=\"images/tick.png\">" : "<img src=\"images/publish_x.png\">"); ?></td>
            <td align="center"><?php echo (!$row->category ? "&nbsp;" : ($row->review==1    ? "<img src=\"images/tick.png\">" : "<img src=\"images/publish_x.png\">")); ?></td>
<?php
         $task = $row->published ? 'unpublish' : 'publish';
         $img = $row->published ? 'publish_g.png' : 'publish_x.png';
         if ($row->pub_access==0)
         {
            $groupname='Everybody';
         }
         else if ($row->pub_access==-1)
         {
            $groupname='All Registered';
         }
         else
         {
            $groupname=$row->groupname=="" ? "&nbsp;" : $row->groupname;
         }

         $adm_groupname = $row->admingroup=="" ? "&nbsp;" :$row->admingroup;

?>
            <td width="10%" align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a></td>
            <td width="" align="center"><?php echo $groupname;?></td>
            <td width="" align="center"><?php echo $adm_groupname ;?></td>
            <td width="15%" align="center"><?php echo $row->editor;?>&nbsp;</td>
      <td>
<?php    if ($i > 0 || ($i+$pageNav->limitstart > 0)) { ?>
         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderup')">
            <img src="images/uparrow.png" width="12" height="12" border="0" alt="Move Up">
         </a>
<?php    } ?>
      </td>
      <td>
<?php    if ($i < $n-1 || $i+$pageNav->limitstart < $pageNav->total-1) { ?>
         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderdown')">
            <img src="images/downarrow.png" width="12" height="12" border="0" alt="Move Down">
         </a>
<?php    } ?>
      </td>
            <?php
               $k = 1 - $k;
         }?>
      </tr>
      <tr>
         <th align="center" colspan="12">
            <?php echo $pageNav->writePagesLinks(); ?></th>
      </tr>
      <tr>
         <td align="center" colspan="12">
            <?php echo $pageNav->writePagesCounter(); ?></td>
      </tr>
      </table>
         <input type="hidden" name="option" value="<?php echo $option; ?>">
         <input type="hidden" name="task" value="showAdministration">
         <input type="hidden" name="boxchecked" value="0">
   </form>
<?php
   }

   function editForum( &$row, $categoryList, $moderatorList, $lists, $accessLists, $option ) {
?>
      <style>
      .hideable {position: relative; visibility: hidden; }
      </style>
      <script language="javascript" type="text/javascript">

      function submitbutton(pressbutton) {
         var form = document.adminForm;
         if (pressbutton == 'cancel') {
            submitform( pressbutton );
            return;
         }

         // do field validation
         try {
         document.adminForm.onsubmit();
         }
         catch(e){}
         if (form.name.value == ""){
            alert( "Category / Forum must have a name" );
         } else {
            submitform( pressbutton );
         }
      }
      </script>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%"><span class="sectionname"><?php echo $row->id ? 'Edit' : 'Add';?> Category/Forum</span></td>
    </tr>
  </table>
      <form action="index2.php" method="POST" name="adminForm">
<script language="javascript" src="js/dhtml.js"></script>
<table cellpadding="3" cellspacing="0" border="0" width="100%">
  <tr>
    <td width="" class="tabpadding">&nbsp;</td>
    <td id="tab1" class="offtab" onclick="dhtml.cycleTab(this.id)">Basics</td>
    <td id="tab2" class="offtab" onclick="dhtml.cycleTab(this.id)">Advanced</td>
    <td id="tab3" class="offtab" onclick="dhtml.cycleTab(this.id)">Moderation</td>
    <td width="90%" class="tabpadding">&nbsp;</td>
  </tr>
</table>
   <div id="page1" class="pagetext">
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
         <tr>
            <td width="200">Parent:</td>
            <td><?php echo $categoryList; ?></td>
            <td>
               Please note: To create a Category, choose 'Top Level Category' as a Parent.
               A Category serves as a container for Forums.<br />
               A Forum can <strong>only</strong> be created within a Category by selecting a previously created
               Category as the Parent for the Forum.<br />
               Messages can <strong>NOT</strong> be posted to a Category; only to Forums
            </td>
         </tr>

         <tr>
            <td width="200">Name:</td>
            <td colspan="2"><input class="inputbox" type="text" name="name" size="25" maxlength="100" value="<?php echo $row->name; ?>"></td>
         </tr>

         <tr>
            <td valign="top">Description:</td>
            <td colspan="2"><textarea class="inputbox" cols="50" rows="3" name="description" id="description" style="width:500px" width="500"><?php echo $row->description; ?></textarea></td>

         </tr>
      </table>
   </div>
   <div id="page2" class="pagetext">
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
         <tr>
            <td> Locked:</td>
            <td> <?php echo $lists['forumLocked']; ?></td>
            <td>
               Set to &quot;Yes&quot; if you want to lock this forum.
               Nobody, except Moderators and Admins can create new topics or replies in a locked forum
               (or move posts to it).
            </td>
         </tr>
         <tr height="5"><td>&nbsp;</td></tr>
         <tr>
            <td> Public Access Level:</td>
            <td> <?php echo $accessLists['pub_access']; ?></td>
            <td>
               To create a Non-Public Forum you can specify the minimum userlevel that can see/enter the forum here.
               By default the minumum userlevel is set to &quot;Everybody&quot;.<br />
               <b>Please note</b>: if you restrict access on a whole Category to one or more certain groups, it will
               hide all Forums it contains to anybody not having proper privileges on the Category <b>even</b> if one
               or more of these Forums have a lower access level set! This holds for Moderators too; you will have to
               add a Moderator to the moderator list of the Category if (s)he does not have the proper group level to
               see the Category.<br />
               This is irrespective of the fact that Categories can not be Moderated; Moderators can still be added to
               the moderator list.
            </td>
         </tr>
         <tr>
            <td> Include Child Groups:</td>
            <td> <?php echo $lists['pub_recurse']; ?></td>
            <td>
               Should child groups be allowed access as well? If set to &quot;No&quot; access to this forum is
               restricted to the selected group <b>only</b>
            </td>
         </tr>
         <tr height="5"><td>&nbsp;</td></tr>
         <tr>
            <td> Admin Access Level:</td>
            <td> <?php echo $accessLists['admin_access']; ?></td>
            <td>
               If you create a forum with Public Access restrictions, you can specify here an additional Administration Access Level.<br />
               If you restrict the access to the forum to a special Public Frontend user group and don't specify a Public Backend Group here,
               administrators will not be able to enter/view the Forum.
            </td>
         </tr>
         <tr>
            <td> Include Child Groups:</td>
            <td> <?php echo $lists['admin_recurse']; ?></td>
            <td>
               Should child groups be allowed access as well? If set to &quot;No&quot; access to this forum is
               restricted to the selected group <b>only</b>
            </td>
         </tr>
         <tr height="5"><td>&nbsp;</td></tr>
         <tr>
            <td> Review Posts:</td>
            <td> <?php echo $lists['forumReview']; ?></td>
            <td>
               Set to &quot;Yes&quot; if you want posts to be reviewed by Moderators
               prior to publishing them in this forum. This is useful in a Moderated forum only!<br />
               If you set this without any Moderators specified, the Site Admin is solely responsible
               for approving/deleting submitted posts as these will be kept 'on hold'!
            </td>
         </tr>
      </table>
   </div>
   <div id="page3" class="pagetext">
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
         <tr>
            <td> Moderated:</td>
            <td> <?php echo $lists['forumModerated']; ?></td>
            <td>
               Set to &quot;Yes&quot; if you want to be able to assign Moderators
               to this forum.<br />
               <strong>Note:</strong> This doesn't mean that new post must be reviewed
               prior to publishing them to the forum!<br />
               You will need to set the &quot;Review&quot; option for that on the
               advanced tab.<br /><br />
               <strong>Please do note:</strong> After setting Moderation to &quot;Yes&quot; you must save the forum
               configuration first before you will be able to add Moderators using the new button.
            </td>
         </tr>
      </table><br />
      <?php
      if ($row->moderated) {?>
      <table cellpadding="4" cellspacing="0" border="0" width="100%">
          <tr>
            <td width="100%"><span class="sectionname">Moderators assigned to this forum:</span></td>
          </tr>
      </table>
      <table class="adminlist" border=0 cellspacing=0 cellpadding=3 width="100%" >
         <tr>
            <th width="20">#</th>
            <th width="20">
               <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $moderatorList ); ?>);" />
            </th>
            <th align="left">Name</th>
            <th align="left">Username</th>
            <th align="left">Email</th>
            <th align="centercase "edit":">Published</th>

         </tr>
         <?php
         if (count($moderatorList)== 0)
         {
            echo "<tr><td colspan=\"5\">There are no Moderators assigned to this forum</td></tr>";
         }else
         {
            $k=1;
            $i=0;
            foreach($moderatorList as $ml)
            {
               $k=1-$k;
               ?>
               <tr class="row<?php echo $k;?>">
                  <td width="20"><?php echo $i+1;?></td>
                  <td width="20"><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $ml->id; ?>" onClick="isChecked(this.checked);"></td>
                  <td><?php echo $ml->name;?></td>
                  <td><?php echo $ml->username;?></td>
                  <td><?php echo $ml->email;?></td>
                  <td align="center"><img src="images/tick.png"></td>
               </tr>
               <?php
               $i++;
            }
         }
         ?>
      </table>
   <?php
   }?>
   </div>

   <input type="hidden" name="id" value="<?php echo $row->id; ?>">
   <input type="hidden" name="option" value="<?php echo $option; ?>">
   <input type="hidden" name="task" value="showAdministration">
   <?if ($row->ordering!=0){echo '<input type="hidden" name="ordering" value="'.$row->ordering.'">';}?>
   <script language="javascript" type="text/javascript">dhtml.cycleTab('tab1');</script>

   </form>
   <?php
   }

   function showConfig( &$sbConfig, &$lists, $option ) {
   global $mosConfig_live_site;
?>
   <script language="javascript" src="js/dhtml.js"></script>

   <form action="index2.php" method="post" name="adminForm">
   <table cellpadding="4" cellspacing="0" border="0" width="100%">
      <tr>
         <td width="100%" class="sectionname">Simpleboard <?php echo _COM_A_CONFIG ?></td>
      </tr>
      <tr>
         <td colspan="2"><img src="images/cpanel.png" width="16px" height="16px" align="middle" border="0"/>  <a href="index2.php?option=com_simpleboard"><?php echo _COM_C_BACK;?></a></td>
      </tr>
   </table>

<table cellpadding="3" cellspacing="0" border="0" width="100%">
  <tr>
    <td width="" class="tabpadding">&nbsp;</td>
    <td id="tab1" class="offtab" onclick="dhtml.cycleTab(this.id)"><?php echo _COM_A_BASICS ;?></td>
    <td id="tab2" class="offtab" onclick="dhtml.cycleTab(this.id)"><?php echo _COM_A_FRONTEND ;?></td>
    <td id="tab3" class="offtab" onclick="dhtml.cycleTab(this.id)"><?php echo _COM_A_SECURITY ;?></td>
    <td id="tab4" class="offtab" onclick="dhtml.cycleTab(this.id)"><?php echo _COM_A_AVATARS ;?></td>
    <td id="tab5" class="offtab" onclick="dhtml.cycleTab(this.id)"><?php echo _COM_A_UPLOADS ;?></td>
    <td id="tab6" class="offtab" onclick="dhtml.cycleTab(this.id)"><?php echo _COM_A_RANKING ;?></td>
    <td id="tab7" class="offtab" onclick="dhtml.cycleTab(this.id)"><?php echo _COM_A_INTEGRATION ;?></td>
    <td width="90%" class="tabpadding">&nbsp;</td>
  </tr>
</table>
<div id="page1" class="pagetext">
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _COM_A_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _COM_A_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_BOARD_TITLE ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_board_title" value="<?php echo $sbConfig['board_title']; ?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_BOARD_TITLE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_EMAIL ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_email" value="<?php echo $sbConfig['email']; ?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_EMAIL_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_BOARD_OFFLINE ?></td>
         <td align="left" valign="top"><?php echo $lists['board_offline']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_BOARD_OFFLINE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_BOARD_OFSET ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_board_ofset" value="<?php echo $sbConfig['board_ofset']; ?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_BOARD_OFSET_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_BOARD_OFFLINE_MES ?></td>
         <td align="left" valign="top" colspan="2"><textarea name="cfg_offline_message" rows="3" cols="50" ><?php echo $sbConfig['offline_message']; ?></textarea></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_VIEW_TYPE ?></td>
         <td align="left" valign="top"><?php echo $lists['default_view']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_VIEW_TYPE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_RSS ?></td>
         <td align="left" valign="top"><?php echo $lists['enableRSS']; ?></td>
         <td align="left" valign="top"><img src="<?php echo $mosConfig_live_site;?>/images/M_images/rss.png" /> <?php echo _COM_A_RSS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_PDF ?></td>
         <td align="left" valign="top"><?php echo $lists['enablePDF']; ?></td>
         <td align="left" valign="top"><img src="<?php echo $mosConfig_live_site;?>/images/M_images/pdf_button.png" /> <?php echo _COM_A_PDF_DESC ?></td>
      </tr>		
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_HEADER ?></td>
         <td align="left" valign="top" colspan="2"><textarea name="cfg_header" rows="3" cols="50"><?php echo stripslashes($sbConfig['header']); ?></textarea></td>
      </tr>
      <tr align="center" valign="middle">
         <th colspan="3">&nbsp;</th>
      </tr>
   </table>
   </div>
<div id="page2" class="pagetext">
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _COM_A_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _COM_A_EXPLANATION ?></th>
      </tr>
      <tr><td colspan="3" class="sectionname" style="border-bottom: 1px dashed #CCCCCC;"><?php echo _COM_A_LOOKS ?></td></tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_THREADS ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_threads_per_page" value="<?php echo $sbConfig['threads_per_page']; ?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_THREADS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_MESSAGES ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_messages_per_page" value="<?php echo $sbConfig['messages_per_page']; ?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_MESSAGES_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_HISTORY ?></td>
         <td align="left" valign="top"><?php echo $lists['showHistory']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_HISTORY_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_HISTLIM ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_historyLimit" value="<?php echo $sbConfig['historyLimit'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_HISTLIM_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_SHOWNEW ?></td>
         <td align="left" valign="top"><?php echo $lists['showNew']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_SHOWNEW_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_NEWCHAR ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_newChar" value="<?php echo $sbConfig['newChar'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_NEWCHAR_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_DISEMOTICONS ?></td>
         <td align="left" valign="top"><?php echo $lists['disemoticons']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_DISEMOTICONS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_TEMPLATE ?></td>
         <td align="left" valign="top"><?php echo $lists['template']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_TEMPLATE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_TAWIDTH ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rtewidth" value="<?php echo $sbConfig['rtewidth'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_TAWIDTH_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_TAHEIGHT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rteheight" value="<?php echo $sbConfig['rteheight'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_TAHEIGHT_DESC ?></td>
      </tr>      
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_RULESPAGE ?></td>
         <td align="left" valign="top"><?php echo $lists['enableRulesPage']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_RULESPAGE_DESC ?></td>
      </tr>	  	
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_FORUM_JUMP ?></td>
         <td align="left" valign="top"><?php echo $lists['enableForumJump']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_FORUM_JUMP_DESC ?></td>
      </tr>            
      <tr><td colspan="3" class="sectionname" style="border-bottom: 1px dashed #CCCCCC;"><?php echo _COM_A_USERS ?></td></tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_USERNAME ?></td>
         <td align="left" valign="top"><?php echo $lists['username']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_USERNAME_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_ASK_EMAIL ?></td>
         <td align="left" valign="top"><?php echo $lists['askemail']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_ASK_EMAIL_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_SHOWMAIL ?></td>
         <td align="left" valign="top"><?php echo $lists['showemail']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_SHOWMAIL_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_USERSTATS ?></td>
         <td align="left" valign="top"><?php echo $lists['showstats']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_USERSTATS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_POSTSTATSBAR ?></td>
         <td align="left" valign="top"><?php echo $lists['postStats']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_POSTSTATSBAR_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_POSTSTATSCOLOR ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_statsColor" value="<?php echo $sbConfig['statsColor'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_POSTSTATSCOLOR_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td colspan=2>&nbsp;</td>
         <td align="left" valign="top">
            <table size=100%>
               <tr>
                  <td> 1: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col1m.png" width="15" height="4"><td>
                  <td> 2: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col2m.png" width="15" height="4"><td>
                  <td> 3: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col3m.png" width="15" height="4"><td>
                  <td> 4: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col4m.png" width="15" height="4"><td>
                  <td> 5: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col5m.png" width="15" height="4"><td>
                  <td> 6: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col6m.png" width="15" height="4"><td>
                </tr><tr>
                  <td> 7: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col7m.png" width="15" height="4"><td>
                  <td> 8: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col8m.png" width="15" height="4"><td>
                  <td> 9: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col9m.png" width="15" height="4"><td>
                  <td> 10: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col10m.png" width="15" height="4"><td>
                  <td> 11: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col11m.png" width="15" height="4"><td>
                  <td> 12: <img src="<?php echo $mosConfig_live_site;?>/components/com_simpleboard/graph/col12m.png" width="15" height="4"><td>
                </tr>
              </table>
         </td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_KARMA ?></td>
         <td align="left" valign="top"><?php echo $lists['showkarma']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_KARMA_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_USER_EDIT ?></td>
         <td align="left" valign="top"><?php echo $lists['useredit']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_USER_EDIT_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_USER_MARKUP ?></td>
         <td align="left" valign="top"><?php echo $lists['editMarkUp']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_USER_MARKUP_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_SUBSCRIPTIONS ?></td>
         <td align="left" valign="top"><?php echo $lists['allowsubscriptions']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_SUBSCRIPTIONS_DESC ?></td>
      </tr>
      <tr><td colspan="3" class="sectionname" style="border-bottom: 1px dashed #CCCCCC;"><?php echo _COM_A_LENGTHS ?></td></tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_WRAP ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_wrap" value="<?php echo $sbConfig['wrap'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_WRAP_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_SUBJECTLENGTH ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_maxSubject" value="<?php echo $sbConfig['maxSubject'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_SUBJECTLENGTH_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_SIGNATURE ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_maxSig" value="<?php echo $sbConfig['maxSig'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_SIGNATURE_DESC ?></td>
      </tr>
   </table>
</div>
<div id="page3" class="pagetext">
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _COM_A_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _COM_A_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_REGISTERED_ONLY ?></td>
         <td align="left" valign="top"><?php echo $lists['regonly']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_REG_ONLY_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_CHANGENAME ?></td>
         <td align="left" valign="top"><?php echo $lists['changename']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_CHANGENAME_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_PUBWRITE ?></td>
         <td align="left" valign="top"><?php echo $lists['pubwrite']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_PUBWRITE_DESC ?></td>
      </tr>
       <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_FLOOD ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_floodprotection" value="<?php echo $sbConfig['floodprotection'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_FLOOD_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_MODERATION ?></td>
         <td align="left" valign="top"><?php echo $lists['mailmod']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_MODERATION_DESC ?></td>
      </tr>
   </table>
</div>
<div id="page4" class="pagetext">
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _COM_A_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _COM_A_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_AVATAR ?></td>
         <td align="left" valign="top"><?php echo $lists['allowAvatar']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_AVATAR_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_AVATARUPLOAD ?></td>
         <td align="left" valign="top"><?php echo $lists['allowAvatarUpload']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_AVATARUPLOAD_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_AVATARGALLERY ?></td>
         <td align="left" valign="top"><?php echo $lists['allowAvatarGallery']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_AVATARGALLERY_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_AVHEIGHT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_avatarHeight" value="<?php echo $sbConfig['avatarHeight'];?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_AVWIDTH ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_avatarWidth" value="<?php echo $sbConfig['avatarWidth'];?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_AVSIZE ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_avatarSize" value="<?php echo $sbConfig['avatarSize'];?>" /></td>
      </tr>
   </table>
</div>
<div id="page5" class="pagetext">
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _COM_A_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _COM_A_EXPLANATION ?></th>
      </tr>
      <tr><td colspan="3" class="sectionname" style="border-bottom: 1px dashed #CCCCCC;"><?php echo _COM_A_IMAGE ?></td></tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_IMAGEUPLOAD ?></td>
         <td align="left" valign="top"><?php echo $lists['allowImageUpload']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_IMAGEUPLOAD_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_IMAGEREGUPLOAD ?></td>
         <td align="left" valign="top"><?php echo $lists['allowImageRegUpload']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_IMAGEREGUPLOAD_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_IMGHEIGHT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_imageHeight" value="<?php echo $sbConfig['imageHeight'];?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_IMGWIDTH ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_imageWidth" value="<?php echo $sbConfig['imageWidth'];?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_IMGSIZE ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_imageSize" value="<?php echo $sbConfig['imageSize'];?>" /></td>
      </tr>
      <tr><td colspan="3" class="sectionname" style="border-bottom: 1px dashed #CCCCCC;"><?php echo _COM_A_FILE ?></td></tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_FILEUPLOAD ?></td>
         <td align="left" valign="top"><?php echo $lists['allowFileUpload']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_FILEUPLOAD_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_FILEREGUPLOAD ?></td>
         <td align="left" valign="top"><?php echo $lists['allowFileRegUpload']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_FILEREGUPLOAD_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_FILEALLOWEDTYPES ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_fileTypes" value="<?php echo $sbConfig['fileTypes'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_FILEALLOWEDTYPES_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_IMGSIZE ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_fileSize" value="<?php echo $sbConfig['fileSize'];?>" /></td>
      </tr>
   </table>
</div>
<div id="page6" class="pagetext">
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _COM_A_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _COM_A_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_RANKING ?></td>
         <td align="left" valign="top"><?php echo $lists['showranking']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_RANKING_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_RANKINGIMAGES ?></td>
         <td align="left" valign="top"><?php echo $lists['rankimages']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_RANKINGIMAGES_DESC ?></td>
      </tr>
   </table>
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
      <tr align="center" valign="middle">
         <th align="left" valign="top"><?php echo _COM_A_RANK ?></td>
         <th align="left" valign="top"><?php echo _COM_A_RANK_LIMIT ?></td>
         <th align="left" valign="top">&nbsp;</td>
         <th align="left" valign="top"><?php echo _COM_A_RANK_NAME ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top">0 - <?php echo _COM_A_RANK1 ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rank1" value="<?php echo $sbConfig['rank1'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_RANK1TXT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rank1txt" value="<?php echo $sbConfig['rank1txt'];?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_RANK2 ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rank2" value="<?php echo $sbConfig['rank2'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_RANK2TXT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rank2txt" value="<?php echo $sbConfig['rank2txt'];?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_RANK3 ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rank3" value="<?php echo $sbConfig['rank3'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_RANK3TXT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rank3txt" value="<?php echo $sbConfig['rank3txt'];?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_RANK4 ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rank4" value="<?php echo $sbConfig['rank4'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_RANK4TXT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rank4txt" value="<?php echo $sbConfig['rank4txt'];?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_RANK5 ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rank5" value="<?php echo $sbConfig['rank5'];?>" /></td>
         <td align="left" valign="top"><?php echo _COM_A_RANK5TXT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rank5txt" value="<?php echo $sbConfig['rank5txt'];?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_RANK6 ?></td>
         <td align="left" valign="top">- 9999999</td>
         <td align="left" valign="top"><?php echo _COM_A_RANK6TXT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_rank6txt" value="<?php echo $sbConfig['rank6txt'];?>" /></td>
      </tr>
   </table>
</div>
<div id="page7" class="pagetext">
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _COM_A_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _COM_A_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_AVATAR_SRC ?></td>
         <td align="left" valign="top"><?php echo $lists['avatar_src']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_AVATAR_SRC_DESC ?></td>
      </tr>
      <tr><td colspan="3" class="sectionname" style="border-bottom: 1px dashed #CCCCCC;"><?php echo _COM_A_PMS_TITLE ?></td></tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_PMS ?></td>
         <td align="left" valign="top"><?php echo $lists['pm_component']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_PMS_DESC ?></td>
      </tr>
      <tr><td colspan="3" class="sectionname" style="border-bottom: 1px dashed #CCCCCC;"><?php echo _COM_A_COMBUILDER_TITLE ?></td></tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_COMBUILDER ?></td>
         <td align="left" valign="top"><?php echo $lists['cb_profile']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_COMBUILDER_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_COMBUILDER_PROFILE ?></td>
         <td align="left" valign="top"><a href="index2.php?option=com_simpleboard&amp;task=loadCBprofile" style="text-decoration:none;" title="<?php echo _COM_A_COMBUILDER_PROFILE_DESC;?>"><?php echo _COM_A_COMBUILDER_PROFILE_CLICK ?></a></td>
         <td align="left" valign="top"><?php echo _COM_A_COMBUILDER_PROFILE_DESC ?></td>
      </tr>
      <tr><td colspan="3" class="sectionname" style="border-bottom: 1px dashed #CCCCCC;"><?php echo _COM_A_BADWORDS_TITLE ?></td></tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_BADWORDS ?></td>
         <td align="left" valign="top"><?php echo $lists['badwords']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_BADWORDS_DESC ?></td>
      </tr>
      <tr><td colspan="3" class="sectionname" style="border-bottom: 1px dashed #CCCCCC;"><?php echo _COM_A_MOSBOT_TITLE ?></td></tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _COM_A_MOSBOT ?></td>
         <td align="left" valign="top"><?php echo $lists['discussBot']; ?></td>
         <td align="left" valign="top"><?php echo _COM_A_MOSBOT_DESC ?><br /><br />
            <!-- Start Arno code test -->
            <script language="javascript">
            <!--
            function openWin(url)
            {
               var newWin= window.open(url,"popup","toolbar=no,scrollbars=yes,width=500,height=250,left=0,top=0");

              newWin.focus();
            }
            //-->
            </script>
            <input type="button" value="<?php echo _COM_A_BOT_REFERENCE;?>" onClick="openWin('components/com_simpleboard/simpleboard_mosbot_help.php')">
            <!-- End Arno code test -->
         </td>
      </tr>
   </table>
</div>
   <input type="hidden" name="task" value="showConfig" />
   <input type="hidden" name="option" value="<?php echo $option; ?>" />
   <input type="hidden" name="cfg_version" value="<?php echo $sbConfig['version']; ?>" />
   <script language="javascript" type="text/javascript">dhtml.cycleTab('tab1');</script>
   </form>


   <p><?php echo _COM_A_SB_BY ?>
      <font class="small"><a href="http://tsmf.jigsnet.com" target="_blank">Two Shoes Module Factory</a></font>
      <br />
      <font class="small"><?php echo _COM_A_VERSION ?>: <?php echo $sbConfig['version']; ?></font>
   </p>
<?php
   }

function showInstructions($database, $option, $mosConfig_lang)
   {
      include("components/com_simpleboard/simpleboard_config.php");
   ?>


   <table width="90%" border="0" cellpadding="2" cellspacing="2" class="adminForm">
      <TR>
        <td width="100%" >
        <table width="60%"><tr>
        <td><img src="components/com_simpleboard/images/logo.png"></td><td><div align="center" class="sectionname">Instructions</div></td>
        </tr></table>
        <p>
        Thank you for using the Simpleboard Forum Component..<br /><br />
        Extensive documentation on Simpleboard, how to configure it and how to use it can be found on the
        Two Shoes Module Factory website. Check the main menu for 'Documentation' and select the 'Simpleboard 0.7
        and up' section for all documents. If all goes well, the documents can be downloaded in PDF format as
        well.

        <br /><br />
        There you will find all you need to know about Forum Administration (almost). If you got any questions, remarks, wishes or suggestions: feel
        free to contact me on the <a href="http://tsmf.jigsnet.com" target="_blank">Two Shoes Module Factory</a> website. There you can use the
        Forum (of course) or the well known 'Contact Us' link.
        <br /><br />
        I hope you have fun with it!<br />
        Two Shoes Module Factory.<br /><br />
        PS. I would also like to hear from you if you like this Forum Component ;-)
      </p>
      </td></tr></table>
      <?php global $version; ?>
      <P><?php echo _COM_A_SB_BY ?> <font class="small"><a href="http://tsmf.jigsnet.com" target="_blank">Two Shoes Module Factory</a></font><BR><font class="small"><?php echo _COM_A_VERSION ?>: <?php echo $version; ?></font>
      </p>
 <?php
   } //end function showInstructions

function showCss($file, $option)
{

   $file = stripslashes($file);
   $f=fopen($file,"r");
   $content = fread($f, filesize($file));
   $content = htmlspecialchars($content);
   ?>
   <form action="index2.php?" method="post" name="adminForm" class="adminForm" id="adminForm">
   <table cellpadding="4" cellspacing="0" border="0" width="100%">
      <tr>
         <td class="sectionname" width="100%">Simpleboard Template CSS Editor</td>
      </tr>
      <tr>
         <td><img src="images/cpanel.png" width="16px" height="16px" align="middle" border="0"/>  <a href="index2.php?option=com_simpleboard"><?php echo _COM_C_BACK;?></a></td>
      </tr>
   </table>
   <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
       <tr>
         <th colspan="4">Path: <?php echo $file; ?></td> </tr>
       <tr>
         <td> <textarea cols="100" rows="20" name="csscontent"><?php echo $content; ?></textarea>
         </td>
       </tr>
       <tr>
         <td class="error">Please Note:CSS Template file must be Writable to Save Changes.</td>
       </tr>
   </table>
   <input type="hidden" name="file" value="<?php echo $file; ?>" />
   <input type="hidden" name="option" value="<?php echo $option; ?>">
   <input type="hidden" name="task" value="">
   <input type="hidden" name="boxchecked" value="0">
   </form>
   <?php

}//end function showCss

   function showProfiles( $option, $mosConfig_lang,&$profileList,$countPL,$pageNavSP,$order, $search)
   {


      ?>
      <form action="index2.php" method="POST" name="adminForm">
         <table cellpadding="4" cellspacing="0" border="0" width="100%">
         <tr>
            <td width="100%" class="sectionname">Simpleboard User Profile Manager <a href="index2.php?option=com_simpleboard&task=profiles&order=0" class="small"> :: sort by userid</a><a href="index2.php?option=com_simpleboard&task=profiles&order=1" class="small"> :: sort by moderators</a><a href="index2.php?option=com_simpleboard&task=profiles&order=2" class="small"> :: sort by name</a></td>
            <td nowrap>Display #</td>
            <td>
               <?php echo $pageNavSP->writeLimitBox(); ?>
            </td>
            <td>Search:</td>
            <td> <input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
            </td>
         </tr>
         <tr>
            <td colspan="5"><img src="images/cpanel.png" width="16px" height="16px" align="middle" border="0"/>  <a href="index2.php?option=com_simpleboard"><?php echo _COM_C_BACK;?></a></td>
         </tr>
   </table>
      <table class="adminlist" border=0 cellspacing=0 cellpadding=3 width="100%" >
      <tr>
         <th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $profileList ); ?>);" /></th>
         <th width="10">id</th>
         <th width="100">name</th>
         <th width="100">email</th>
         <th width="15">Moderator</th>
         <th width="10">view</th>
         <th width="*" >signature</th>
      </tr>
      <?php
      if ($countPL>0)
      {
         $k=0;
         //foreach ($profileList as $pl)
         $i = 0;
         for ($i=0, $n=count( $profileList ); $i < $n; $i++)
         {
            $pl = &$profileList[$i];

            $k = 1-$k;
            ?>
            <tr class="row<?php echo $k;?>">
               <td width="20"><input type="checkbox" id="cb<?php echo $i;?>" name="uid[]" value="<?php echo $pl->id; ?>" onClick="isChecked(this.checked);"></td>
               <td width="10"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','userprofile')"><?php echo $pl->userid;?></a></td>
               <td width="100"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','userprofile')"><?php echo $pl->name;?></a></td>
               <td width="100"><?php echo $pl->email;?>&nbsp;</td>
               <td align="center" width="15"><?php if($pl->moderator){echo "Yes";}else{echo "No";} ;?>&nbsp;</td>
               <td align="center" width="10"><?php echo $pl->view;?>&nbsp;</td>
               <td width="*" ><?php echo $pl->signature;?>&nbsp;</td>
            </tr>
            <?php
         }
      }
      else
      {
         echo "<tr><td colspan=\"6\">No userprofiles found.</td></tr>";
      }?>
   <input type="hidden" name="order" value="<?php echo "$order";?>">
   <input type="hidden" name="option" value="<?php echo $option; ?>">
   <input type="hidden" name="task" value="showprofiles">
   <input type="hidden" name="boxchecked" value="0">
      <tr>
         <th align="center" colspan="7">
            <?php echo $pageNavSP->writePagesLinks(); ?></th>
      </tr>
      <tr>
         <td align="center" colspan="7">
            <?php echo $pageNavSP->writePagesCounter(); ?></td>
      </tr>
   </table>


   </form>
   <?php
   }//end function showProfiles


   function newModerator ($option, $id, $moderators, &$modIDs,$forumName, &$userList,$countUL,$pageNav) {

   ?>
   <form action="index2.php" method="post" name="adminForm">
         <table cellpadding="4" cellspacing="0" border="0" width="100%">
         <tr>
            <td width="100%" class="sectionname">Add Moderator to Forum "<?php echo $forumName;?>" </td>
            <td nowrap>Display #</td>
            <td>
               <?php echo $pageNav->writeLimitBox(); ?>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>
   </table>
      <table class="adminlist" border=0 cellspacing=0 cellpadding=3 width="100%" >
      <tr>
         <th width="20">#</th>
         <th width="20">
            <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $userList ); ?>);" />
         </th>
         <th>id</th>
         <th align="left">name</th>
         <th align="left">email</th>
         <th>Published</th>
         <th>&nbsp;</th>
      </tr>
      <?php
      if ($countUL>0)
      {
         $k=0;
         $i = 0;
         for ($i=0, $n=count( $userList ); $i < $n; $i++)
         {
            $pl = &$userList[$i];

            $k = 1-$k;
            ?>
            <tr class="row<?php echo $k;?>">
               <td width="20" align="right"><?php echo $i+$pageNav->limitstart+1;?></td>
               <td width="20"><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $pl->id; ?>" onClick="isChecked(this.checked);"></td>
               <td width="20"><a href="index2.php?option=com_simpleboard&task=userprofile&do=show&user_id=<?php echo $pl->id;?>"><?php echo $pl->id;?>&nbsp;</td>
               <td ><?php echo $pl->name;?>&nbsp;</td>
               <td ><?php echo $pl->email;?>&nbsp;</td>
               <td align="center">
               <?php
               if($moderators)
               {
                  if (in_array($pl->id, $modIDs))
                  { echo "<img src=\"images/tick.png\">";}
                  else{
                     echo "<img src=\"images/publish_x.png\">";
                  }
               }else {
                  echo "<img src=\"images/publish_x.png\">";
               }?>
               </td>
               <td >&nbsp;</td>
            </tr>
            <?php
         }
      }
      else
      {
         echo "There are no possible moderators found. Read the 'note' below.";
      }?>
   <input type="hidden" name="option" value="<?php echo $option; ?>">
   <input type="hidden" name="id" value="<?php echo $id; ?>">
   <input type="hidden" name="boxchecked" value="0">
   <input type="hidden" name="task" value="newmoderator">
      <tr>
         <th align="center" colspan="7">
            <?php echo $pageNav->writePagesLinks(); ?></th>
      </tr>
      <tr>
         <td align="center" colspan="7">
            <?php echo $pageNav->writePagesCounter(); ?></td>
      </tr>
   </table>
   <p align="center">NOTE: Only users which have the moderator flag set in their Simpleboard Profile are shown here. In<br />
   order to be able to add a moderator give a user a moderator flag, go to <a href="index2.php?option=com_simpleboard&task=profiles">User Administration</a><br />
   and search for the user you want to make a moderator. Then select his or her profile and update it. The moderator flag<br />
   can only be set by an site administrator. </p>

   </form>
   <?php
   }

//   function showUserProfile ($database,$mosLang,$user_id,$do,$deleteSig,$signature,$newview,$user_id,$thread,$moderator)
//   {
//
//      include ('components/com_simpleboard/moderate_user.php');
//   }

   function editUserProfile ($option, $database,$userDetails, $subslist, $selectPref,$selectMod,$selectOrder,$is_an_admin, $uid) {

   //fill the variables needed later
   foreach($userDetails as $user)
   {
      $signature = $user->signature;
      $username  = $user->name     ;
      $avatar    = $user->avatar   ;
      $ordering  = $user->ordering ;
      //that's what we got now; later the 'future_use' columns can be used..
   }
   $csubslist=count($subslist);

   include_once('components/com_simpleboard/bb_adm.js');
   include_once('components/com_simpleboard/simpleboard_config.php');
   ?>
      <form action="index2.php?option=<?php echo $option;?>" method="POST" name="adminForm">
      <p class="sectionname">Profile for <?php echo $username;?></p>

      <table border=0 cellspacing=0 width="100%" align="center" class="adminlist">
         <tr>
            <th colspan="3" class="title">General Profile Options</td>
         </tr>
         <tr>
            <td width="150" class="contentpane"><strong>Prefered Viewtype</strong>:</td>
            <td align="left" valign="top" class="contentpane">
               <?php
               echo $selectPref;
               ?>
            </td>
            <td>&nbsp;</td>
         </tr>
         <tr>
            <td width="150" class="contentpane"><strong>Prefered Message Ordering</strong>:</td>
            <td align="left" valign="top" class="contentpane">
               <?php
               echo $selectOrder;
               ?>
            </td>
            <td>&nbsp;</td>
         </tr>
         <tr>
             <td width="150" class="contentpane"><strong>Is Moderator</strong>:</td>
             <?php
             //admins are always moderators
             if ($is_an_admin)
             {?>
               <td align="left" valign="top" class="contentpane">
               <strong>Yes</strong> (not changeable, this user is an site (super)administrator)
               <input type="hidden" name="moderator" value="1">
               </td>
               <td>&nbsp;</td>
               <?php
             }
             else
             {?>
             <td align="left" valign="top" class="contentpane">
                <?php
                echo $selectMod;
             }   ?>
            </td>
         <tr>
            <td width="150" class="contentpane">
               <strong>Signature</strong>:<br />
               <i>max <?php echo $sbConfig['maxSig'];?> chars</i><br />
               <input readonly type=text name=rem size=3 maxlength=3 value="" class="inputbox"><br />
               HTML is on.
            </td>
            <td align="left" valign="top" class="contentpane">

            <textarea rows="6" class="inputbox" onMouseOver="textCounter(this.form.message,this.form.rem,<?php echo $sbConfig['maxSig'];?>);" onClick="textCounter(this.form.message,this.form.rem,<?php echo $sbConfig['maxSig'];?>);" onKeyDown="textCounter(this.form.message,this.form.rem,<?php echo $sbConfig['maxSig'];?>);" onKeyUp="textCounter(this.form.message,this.form.rem,<?php echo $sbConfig['maxSig'];?>);"  cols="50" type="text" name="message"><?php echo $signature;?></textarea><br />
               <input type="button" class="button" accesskey="b" name="addbbcode0" value=" B " style="font-weight:bold; width: 30px" onClick="bbstyle(0)" onMouseOver="helpline('b')" />
               <input type="button" class="button" accesskey="i" name="addbbcode2" value=" i " style="font-style:italic; width: 30px" onClick="bbstyle(2)" onMouseOver="helpline('i')" />
               <input type="button" class="button" accesskey="u" name="addbbcode4" value=" u " style="text-decoration: underline; width: 30px" onClick="bbstyle(4)" onMouseOver="helpline('u')" />
               <input type="button" class="button" accesskey="p" name="addbbcode14" value="Img" style="width: 40px"  onClick="bbstyle(14)" onMouseOver="helpline('p')" />
               <input type="button" class="button" accesskey="w" name="addbbcode16" value="URL" style="text-decoration: underline; width: 40px" onClick="bbstyle(16)" onMouseOver="helpline('w')" />
               <br />Colour:
               <select name="addbbcode20" onChange="bbfontstyle('[color=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;" onMouseOver="helpline('s')">
                    <option style="color:black;  background-color: #FAFAFA" value="">Standard</option>
                    <option style="color:red;    background-color: #FAFAFA" value="#FF0000">Red</option>
                    <option style="color:blue;   background-color: #FAFAFA" value="#0000FF">Blue</option>
                    <option style="color:green;  background-color: #FAFAFA" value="#008000">Green</option>
                    <option style="color:yellow; background-color: #FAFAFA" value="#FFFF00">Yellow</option>
                    <option style="color:orange; background-color: #FAFAFA" value="#FF6600">Orange</option>
                    </select>
               Size: <select name="addbbcode22" onChange="bbfontstyle('[size=' + this.form.addbbcode22.options[this.form.addbbcode22.selectedIndex].value + ']', '[/size]')" onMouseOver="helpline('f')">
                    <option value="1">Very Small</option>
                    <option value="2">Small</option>
                    <option value="3" selected>Normal</option>
                    <option value="4">Big</option>
                    <option value="5">Very Big</option>
                    </select>
               <a href="javascript:bbstyle(-1)" onMouseOver="helpline('a')"><small><?php echo _BBCODE_CLOSA;?></small></a></span><br />
               <input type="text" name="helpbox" size="45" maxlength="100" style="width:400px; font-size:8px" class="options" value="<?php echo _BBCODE_HINT;?>" />
            </td>
            </td>
            <?php if ($sbConfig['allowAvatar']){?>
            <td class="contentpane" align="center">
               User avatar:<br />
               <?php
               if ($avatar!=''){
                  echo '<img src="../components/com_simpleboard/avatars/'.$avatar.'" ><br />';
                  echo '<input type="hidden" value="'.$avatar.'" name="avatar">';
               }else{
                  echo "<em>None selected</em><br />";
                  echo '<input type="hidden" value="$avatar" name="avatar">';
               }?>
            </td>
      <?php } else { echo "<td>&nbsp;</td>"; echo '<input type="hidden" value="" name="avatar">'; }?>
         </tr>
         <tr>
            <td colspan="2" class="contentpane"><input type="checkbox" value="1" name="deleteSig"><i> check this box to delete this signature</i></td>
            <?php if ($sbConfig['allowAvatar']){?>
               <td  class="contentpane"><input type="checkbox" value="1" name="deleteAvatar"><i> check this box to delete this avatar</i></td>
            <?php } else { echo "<td>&nbsp;</td>"; }?>
         </tr>
         <tr cellspacing="3" colspan="2">&nbsp;</td>
         </tr>
      </table>
      <input type="hidden" name="uid" value="<?php echo $uid;?>">
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="option" value="<?php echo $option; ?>" />
      </form>
      <table border=0 cellspacing=0 width="100%" align="center" class="adminlist">
         <tr>
            <th colspan="2" class="title"><?php echo $username;?>'s Subscriptions</td>
         </tr>
         <?php
         $enum=1;//reset value
         $k=0; //value for alternating rows
         if($csubslist >0){
            foreach($subslist as $subs){//get all message details for each subscription
               $database->setQuery("select * from #__sb_messages where id=$subs->thread");
               $subdet=$database->loadObjectList();
               foreach($subdet as $sub){
                  $k=1-$k;
                  echo "<tr class=\"row$k\">";
                  echo "  <td>$enum: $sub->subject by $sub->name";
                  echo "  <td>&nbsp;</td>";
                  echo "</tr>";
                  $enum++;
               }
            }
         }
         else{
            echo "<tr><td class=\"message\">No subscriptions found for this user</td></tr>";
         }
      echo "</table>";
   }
   //**************************
   // Prune Forum
   //**************************
   function pruneforum($option, $forumList)
   {
      include('components/com_simpleboard/simpleboard_config.php');
      ?>
      <form action="index2.php" method="post" name="adminForm">
      <table cellpadding="4" cellspacing="0" border="0" width="100%">
         <tr>
            <td width="100%" class="sectionname" colspan="2"><?php echo _COM_A_PRUNE ?></td>
         </tr>
         <tr>
            <td colspan="2"><img src="images/cpanel.png" width="16px" height="16px" align="middle" border="0"/>  <a href="index2.php?option=com_simpleboard"><?php echo _COM_C_BACK;?></a></td>
         </tr>
         <tr>
            <td heigth="10px" colspan="2">&nbsp;</td>
         </tr>
         <tr>
            <td colspan="2"><?php echo _COM_A_PRUNE_DESC ?></td>
         </tr>
         <tr>
            <td width="40"><?php echo _COM_A_PRUNE_NAME ?></td>
            <td><?php echo $forumList['forum'] ?></td>
         </tr>
         <tr>
            <td width="40"><?php echo _COM_A_PRUNE_NOPOSTS ?></td>
            <td><input type="text" name="prune_days" value="30"> <?php echo _COM_A_PRUNE_DAYS ?></td>
         </tr>
      </table>
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="option" value="<?php echo $option; ?>" />

      </form>


      <p><?php echo _COM_A_SB_BY ?>
         <font class="small"><a href="http://tsmf.jigsnet.com" target="_blank">Two Shoes Module Factory</a></font>
         <br />
         <font class="small"><?php echo _COM_A_VERSION ?>: <?php echo $sbConfig['version']; ?></font>
      </p>
   <?php
   }

   //**************************
   // Prune Users
   //**************************
   function pruneusers($option)
   {
      include('components/com_simpleboard/simpleboard_config.php');
      ?>
      <form action="index2.php" method="post" name="adminForm">
      <table cellpadding="4" cellspacing="0" border="0" width="100%">
         <tr>
            <td>&nbsp;</td>
            <td width="100%" class="sectionname"><?php echo _COM_A_PRUNE_USERS ?></td>
         </tr>
         <tr>
            <td colspan="2">
               <img src="images/cpanel.png" width="16px" height="16px" align="middle" border="0"/>  <a href="index2.php?option=com_simpleboard"><?php echo _COM_C_BACK;?></a>
            </td>
         </tr>
         <tr>
            <td heigth="10px" colspan="2">&nbsp;</td>
         </tr>
         <tr>
            <td colspan="2"><?php echo _COM_A_PRUNE_USERS_DESC ?></td>
         </tr>
      </table>
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="option" value="<?php echo $option; ?>" />

      </form>


      <p><?php echo _COM_A_SB_BY ?>
         <font class="small"><a href="http://tsmf.jigsnet.com" target="_blank">Two Shoes Module Factory</a></font>
         <br />
         <font class="small"><?php echo _COM_A_VERSION ?>: <?php echo $sbConfig['version']; ?></font>
      </p>
   <?php
   }

   //***************************************
   // uploaded Image browser
   //***************************************
   function browseUploaded ($option, $uploaded,$uploaded_path, $type)
   {
      global $database, $mainframe;
      $map=$mainframe->getCfg( 'absolute_path' );
      include('components/com_simpleboard/simpleboard_config.php');
      ?>
      <SCRIPT LANGUAGE="Javascript">
      <!---
      function decision(message, url){
      if(confirm(message)) location.href = url;
      }
      // --->
      </SCRIPT>
      <?php
      echo '<table cellpadding="4" cellspacing="0" border="0" width="100%">';
      echo '    <tr>';
      echo'      <td width="100%"><span class="sectionname">';
      echo $type ? _COM_A_IMGB_IMG_BROWSE : _COM_A_IMGB_FILE_BROWSE;
      echo '</span></td>';
      echo'    </tr>';
      echo '   <tr>';
      echo '      <td>';
      echo '         <img src="images/cpanel.png" width="16px" height="16px" align="middle" border="0"/>  <a href="index2.php?option=com_simpleboard">'._COM_C_BACK.'</a>';
      echo '      </td>';
      echo '   </tr>';
      echo'</table>';

      echo '<div align="left"><font size=2>';
      echo $type ? _COM_A_IMGB_TOTAL_IMG : _COM_A_IMGB_TOTAL_FILES;
      echo ': '.count($uploaded).'</font>';
      echo '<br /><font size=1>';
      echo $type ? _COM_A_IMGB_ENLARGE : _COM_A_IMGB_DOWNLOAD;
      echo '</font><br /><br />';
      echo $type ? _COM_A_IMGB_DUMMY_DESC.'<br />'._COM_A_IMGB_DUMMY.':<br /> <img src="../components/com_simpleboard/uploaded/dummy.gif">' : '' ;
      echo '</div>';
      echo '<table><tr>';
      for($i = 0; $i < count($uploaded); $i++) {
         $j=$i+1;
         //get the corresponding posting
         $query = $type ? "SELECT mesid FROM #__sb_attachments where filelocation='$map/components/com_simpleboard/uploaded/images/$uploaded[$i]'" : "SELECT mesid FROM #__sb_attachments where filelocation='$map/components/com_simpleboard/uploaded/files/$uploaded[$i]'";
         $database->setQuery($query);
         $mesid=$database->loadResult();
         //get the catid for the posting
         $database->setQuery("SELECT catid FROM #__sb_messages where id='$mesid'");
         $catid=$database->loadResult();
         echo $mesid == '' ? '<td bgcolor="#FF8787">' : '<td>';
         echo '<table border=1><tr><td height="90" width="130" align="center">';
         echo $type ? '<a href="'.$uploaded_path.'/'.$uploaded[$i].'" target="_blank" title="'._COM_A_IMGB_ENLARGE.'" alt="'._COM_A_IMGB_ENLARGE.'"><img src="'.$uploaded_path .'/'. $uploaded[$i].'" width="80" heigth="80" border="0"></a>' : '<a href="'.$uploaded_path.'/'.$uploaded[$i].'" title="'._COM_A_IMGB_DOWNLOAD.'" alt="'._COM_A_IMGB_DOWNLOAD.'"><img src="../components/com_simpleboard/emoticons/file.png" width="32" heigth="32" border="0"></a>';
         echo '</td></tr><tr><td align="middle">';
         //echo '<input type="radio" name="newAvatar" value="gallery/'.$uploaded[$i].'">';
         echo '<br /><small>';
         echo '<strong>'._COM_A_IMGB_NAME.': </strong> '.$uploaded[$i].'<br />';
         echo '<strong>'._COM_A_IMGB_SIZE.': </strong> '.filesize($uploaded_path.'/'.$uploaded[$i]).' bytes<br />';
         $type ? list($width, $height) = @getimagesize($uploaded_path.'/'.$uploaded[$i]): '' ;
         echo $type ? '<strong>'._COM_A_IMGB_DIMS.': </strong> '.$width.'x'.$height.'<br />' : '' ;
         echo $type ? '<a href="index2.php?option='.$option.'&task=replaceImage&OxP=1&img='.$uploaded[$i].'">'._COM_A_IMGB_REPLACE.'</a><br />' : '';
         echo $type ? '<a href="javascript:decision(\''._COM_A_IMGB_CONFIRM.'\',\'index2.php?option='.$option.'&task=replaceImage&OxP=2&img='.$uploaded[$i].'\')">'._COM_A_IMGB_REMOVE.'</a><br />' : '<a href="javascript:decision(\''._COM_A_IMGB_CONFIRM.'\',\'index2.php?option='.$option.'&task=deleteFile&fileName='.$uploaded[$i].'\')">'._COM_A_IMGB_REMOVE.'</a><br />';
         if ($mesid != ''){
            echo '<a href="../index.php?option='.$option.'&func=view&catid='.$catid.'&id='.$mesid.'#'.$mesid.'" target="_blank">'._COM_A_IMGB_VIEW.'</a>';
         } else {
            echo _COM_A_IMGB_NO_POST;
         }

         echo '</td></tr></table>';
         echo '</td>';
         if (function_exists('fmod')) {
             if (!fmod(($j),5)){echo '</tr><tr align="center" valign="middle">';}
         } else {
             if (!fmodReplace(($j),5)){echo '</tr><tr align="center" valign="middle">';}
         }
      }
      echo '</tr></table>';
      ?>
      <p><?php echo _COM_A_SB_BY ?>
         <font class="small"><a href="http://tsmf.jigsnet.com" target="_blank">Two Shoes Module Factory</a></font>
         <br />
         <font class="small"><?php echo _COM_A_VERSION ?>: <?php echo $sbConfig['version']; ?></font>
      </p>
   <?php
   }
} //end class
?>