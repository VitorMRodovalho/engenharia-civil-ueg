<?php
/**
* Letterman Newsletter Component
* 
* @package Mambo_4.5.1
* @subpackage Letterman
Authors:
* @copyright Soeren Eberhardt <soeren@mambo-phpshop.net>
    (who just needed an easy and *working* Newsletter component for Mambo 4.5.1 and mixed up Newsletter and YaNC)
* @copyright Mark Lindeman <mark@pictura-dp.nl> 
    (parts of the Newsletter component by Mark Lindeman; Pictura Database Publishing bv, Heiloo the Netherland)
* @copyright Adam van Dongen <adam@tim-online.nl>
    (parts of the YaNC component by Adam van Dongen, www.tim-online.nl)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_letterman {
	function shownewsletter( &$rows, $search, $pageNav, $option ) {

		  ?>
  <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>		  
	<script language="Javascript" src="../includes/js/overlib_mini.js"></script>
	<form action="index2.php" method="post" name="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
	<tr>
		<td width="100%" class="sectionname"><?php echo LM_NM ?></td>
		<td nowrap><?php echo _PN_DISPLAY_NR ?></td>
		<td>
			<?php echo $pageNav->writeLimitBox(); ?>
		</td>
		<td><?php echo _SEARCH_TITLE ?>:</td>
		<td>
			<input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
		</td>
	</tr>
	</table>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th class="title" width="200"><?php echo _E_SUBJECT ?></th>
			<th class="title" width="200"><?php echo LM_MESSAGE ?></th>
			<th class="title" width="100"><?php echo _E_CREATED ?></th>
			<th class="title" width="100"><?php echo LM_LAST_SENT ?></th>
			<th class="title" width="65"><?php echo LM_SEND_NOW ?></th>
			<th class="title" width="65"><?php echo _CMN_PUBLISHED ?>?</th>
			<th class="title" width="154"><?php echo LM_CHECKED_OUT ?></th>
		</tr>
		<?php
		$k = 0;
		$i = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
            $message = $row->message;
            $message = strip_tags($message);
            if (strlen($message) > 80) $message = substr($message, 0 , 78) . " ...";
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onClick="isChecked(this.checked);"></td>
				<td><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','edit')"><?php echo $row->subject; ?></a></td>
                <td><?php echo $message; ?></td>
                <td><?php echo $row->created; ?></td>
                <td><?php echo $row->send; ?></td>
              <?php
			$task = $row->published ? 'unpublish' : 'publish';
			$now = date( "Y-m-d h:i:s" );
			if ($now <= $row->publish_up && $row->published == "1") {
				$img = 'publish_y.png';
			} else if (($now <= $row->publish_down || $row->publish_down == "0000-00-00 00:00:00") && $row->published == "1") {
				$img = 'publish_g.png';
			} else if ($now > $row->publish_down && $row->published == "1") {
				$img = 'publish_r.png';
			} elseif ($row->published == "0") {
				$img = "publish_x.png";
			}
			$times = '';
			if (isset($row->publish_up)) {
				if ($row->publish_up == '0000-00-00 00:00:00') {
					$times .= "<tr><td>Start: Always</td></tr>";
				} else {
					$times .= "<tr><td>Start: $row->publish_up</td></tr>";
				}
			}
			if (isset($row->publish_down)) {
				if ($row->publish_down == '0000-00-00 00:00:00') {
					$times .= "<tr><td>".LM_NO_EXPIRY."</td></tr>";
				} else {
					$times .= "<tr><td>"._E_FINISH_PUB.": ".$row->publish_down."</td></tr>";
				}
			}
?>
                <td align="center"><a href="#sendNow" onclick="return listItemTask('cb<?php echo $i; ?>','sendNow')"><img src="../includes/js/ThemeOffice/mail.png" border="0" /></a></td>
				<td align="center"><a href="javascript: void(0);" onmouseover="return overlib('<table border=0 width=100% height=100%><?php echo $times; ?></table>', CAPTION, 'Publish Information', BELOW, RIGHT);" onmouseout="return nd();"  onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a></td>
                <td><?php echo $row->editor != "" ? $row->editor : "&nbsp;";?></td>
				<?php
					$k = 1 - $k;

			}?>
		</tr>
		<tr>
			<th align="center" colspan="8">
				<?php echo $pageNav->writePagesLinks(); ?></th>
		</tr>
		<tr>
			<td align="center" colspan="8">
				<?php echo $pageNav->writePagesCounter(); ?></td>
		</tr>
		</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
	</form>
	<?php
	}
	
	function sendNewsletter( &$row, $option , $grouplist, $admin_email ) {
	  ?>
	  <script language="javascript" type="text/javascript">
		  function submitbutton(pressbutton) {
			  var form = document.adminForm;
			  if (pressbutton == 'cancel') {
				  submitform( pressbutton );
				  return;
			  }
			  // do field validation
			  if (getSelectedValue('adminForm','sendto') < 0){
				  alert( "Please select a group" );
			  } else if (confirm ("<?php echo LM_WARNING_SEND_NEWSLETTER ?>")) {
				  submitform( 'sendMail' );
			  }
		  }
	  </script>	  
	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>		  
	<script language="Javascript" src="../includes/js/overlib_mini.js"></script>
	  <table cellpadding="4" cellspacing="0" border="0" width="100%">
		  <tr>
			<td width="100%"><span class="sectionname"><?php echo LM_SEND_NEWSLETTER ?></span></td>
		  </tr>
		</table>
	  <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="adminForm">

		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
            <tr>
                <td width="250"><strong><?php echo LM_SEND_TO_GROUP ?>:</strong></td>
                <td width="85%"><?php echo $grouplist; ?></td>
            </tr>
            <tr>
                <td width="250"><strong><?php echo LM_CONFIRMED_ACCOUNTS_ONLY ?></strong></td>
                <td width="85%"><input type="checkbox" name="confirmed_accounts" value="1">
				<?php echo mosToolTip( LM_CONFIRMED_ACCOUNTS_ONLY_TIP ) ?></td>
            </tr>
			<tr>
				<td><strong><?php echo LM_MAIL_FROM ?>:</strong></td>
				<td><input class="inputbox" type="text" name="mailfrom" size="25" value="<?php echo $admin_email; ?>" style="width:200px" ></td>
			</tr>
			<tr>
				<td><strong><?php echo LM_REPLY_TO ?>:</strong></td>
				<td><input class="inputbox" type="text" name="replyto" size="25" value="<?php echo $admin_email; ?>" style="width:200px" ></td>
			</tr>
            <tr>
                <td width="250"><strong><?php echo LM_DISABLE_TIMEOUT ?>:</strong></td>
                <td width="85%"> <input type="checkbox" checked="checked" name="disable_timeout" value="1">
				<?php echo mosToolTip( LM_DISABLE_TIMEOUT_TIP ) ?></td>
            </tr>
			<tr><td colspan="2"><hr/></td></tr>
			<tr>
				<td><strong><?php echo _E_SUBJECT ?></strong></td>
				<td><?php echo $row->subject; ?></td>
			</tr>
			<tr>
				<td valign="top"><strong><?php echo LM_MSG_HTML ?>:</strong></td>
				<td valign="top"><?php echo $row->html_message; ?></td>
			</tr>
			<tr>
				<td valign="top"><strong><?php echo LM_MSG ?>:</strong></td>
				<td valign="top"><?php echo htmlspecialchars($row->html_message); ?></td>
			</tr>
			<tr>
				<td valign="top"><strong><?php echo LM_TEXT_MSG ?>:</strong></td>
				<td valign="top"><?php echo htmlspecialchars($row->message); ?></td>
			</tr>
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			</form>
		</table>
<?php
	}
	
	function editNewsletter( &$row, &$publist, $option , $glist ) {
		if( function_exists( "botTinymceEditorInit" ))
		  $savetext = "tinyMCE.triggerSave();\n";
		else
		  $savetext = "";
	  ?>
	  <link rel="stylesheet" type="text/css" media="all" href="../includes/js/calendar/calendar-mos.css" title="green" />
	  <script type="text/javascript" src="../includes/js/calendar/calendar.js"></script>
	  <script type="text/javascript" src="../includes/js/calendar/lang/calendar-en.js"></script>
	  <script language="javascript" src="js/dhtml.js"></script>

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
			if (form.subject.value == ""){
				alert( "Newsletter must have a subject" );
			} 
			else {
				<?php echo $savetext ?>
				submitform( pressbutton );
			}
		}
		</script>
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
		  <td width="100%"><span class="sectionname"><?php echo $row->id ? _E_EDIT : _E_ADD; echo ": ".LM_NEWSLETTER_ITEM; ?></span></td>
		</tr>
	  </table>
	<form action="index2.php" method="post" name="adminForm">

		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
			<tr>
				<td width="200"><div style="font-weight:bold;text-align:right"><?php echo _E_SUBJECT ?></div></td>
				<td><input class="inputbox" type="text" name="subject" size="25" value="<?php echo $row->subject; ?>" style="width:500px" ></td>
			</tr>

			<tr>
				<td valign="top"><div style="font-weight:bold;text-align:right"><?php echo LM_MSG_HTML.": </div><br/>".LM_NAME_TAG_USAGE ?></td>
				<td><?php
				editorArea( "html_message", str_replace('&','&amp;',$row->html_message), "html_message", 500, 300, 70, 20 );
			  
				?>
				</td>
			</tr>

			<tr>
				<td valign="top"><div style="font-weight:bold;text-align:right"><?php echo LM_TEXT_MSG.": </div><br/><br/>".LM_NAME_TAG_USAGE ?></td>
				<td><textarea name="message" cols="70" rows="20" style="width:500px; height:300px;"><?php echo str_replace('&','&amp;',$row->message); ?></textarea>
				</td>
			</tr>
			<tr>
				<td valign="top"><div style="font-weight:bold;text-align:right"><?php echo _CMN_PUBLISHED ?>:</div></td>
				<td>
					<?php echo $publist; ?>
				</td>
			</tr>
            <tr>
                <td><div style="font-weight:bold;text-align:right"><?php echo _E_STATE; ?></div></td>
                <td><?php 
				if ($row->published == "1") {
				  echo _CMN_PUBLISHED;
				} 
				else {
				  echo _CMN_UNPUBLISHED;
				}
                        ?>
                    </td>
            </tr>
            <tr>
                <td><div style="font-weight:bold;text-align:right"><?php echo _E_ACCESS_LEVEL; ?></div></td>
                <td> <?php echo $glist; ?> </td>
            </tr>
            <tr>
                <td><div style="font-weight:bold;text-align:right"><?php echo _E_START_PUB; ?></div></td>
                <td><input class="inputbox" type="text" name="publish_up" id="publish_up" size="25" maxlength="19" value="<?php echo $row->publish_up; ?>" />
                <input name="reset" type="reset" class="button" onClick="return showCalendar('publish_up', 'y-mm-dd');" value="..." />
                </td>
            </tr>
            <tr>
                <td><div style="font-weight:bold;text-align:right"><?php echo _E_FINISH_PUB; ?></div></td>
                <td><input class="inputbox" type="text" name="publish_down" id="publish_down" size="25" maxlength="19" value="<?php echo $row->publish_down; ?>" />
                    <input name="reset2" type="reset" class="button" onClick="return showCalendar('publish_down', 'y-mm-dd');" value="..." />
                </td>
            </tr>
		</table>


	<?php if (!$row->id) { ?>
            <input type="hidden" name="created" value="<?php echo date('Y-m-d H:i:s'); ?>">
	<?php }
		  else { ?>
			<input type="hidden" name="id" value="<?php echo $row->id; ?>">
	<?php }
	?>
			<input type="hidden" name="task" value="">
			<input type="hidden" name="option" value="<?php echo $option; ?>">
			</form>
	<?php 
  }
  
  function showSubscriberOverview() {
	global $mosConfig_offset, $mosConfig_list_limit, $mainframe, $database, $mosConfig_absolute_path, $option, $my, $lang;
			
	$orderby = mosGetParam($_REQUEST, 'orderby', 'subscriber_name');
	$sort = mosGetParam($_REQUEST, 'ordering', 'ASC');
	
	$search = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search = $database->getEscaped( trim( strtolower( $search ) ) );
	  
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );
	
	$q_registered = "SELECT * FROM #__letterman_subscribers, #__users WHERE user_id = id";
	$database->setQuery( $q_registered );
	$registered_subscribers = $database->loadObjectList();
	
	if( !empty( $search ) ) {
	  $q_search = "WHERE (subscriber_name LIKE '%$search%' OR subscriber_email LIKE '%$search%')";
	}
	else {
	  $q_search = "";
	}
	$q_all = "SELECT * FROM #__letterman_subscribers $q_search ORDER BY $orderby $sort";
	$q_search = "SELECT * FROM #__letterman_subscribers $q_search ORDER BY $orderby $sort LIMIT ".$limitstart.", ".$limit;
	$database->setQuery( $q_all );
	$subscribers = $database->loadObjectList();
	
	$total = sizeof( $subscribers );
	require_once( $mosConfig_absolute_path . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );		
	
	$database->setQuery( $q_search );
	$rows = $database->loadObjectList();	
?>
    <script language="JavaScript">
    <!-- 
    
    function reorder(order, sort){
      document.adminForm.orderby.value=order;
      document.adminForm.ordering.value=sort;
      submitbutton('subscribers');
    }
    //-->
    </script>

    <form action="index2.php" method="post" name="adminForm">
    
    <table class="adminheading">
		<tr>
			<th class="user"><?php echo LM_SELECT_SUBSCRIBER ?>:</th>
			<td><?php echo _FILTER ?>:</td>
			<td>
			<input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
			</td>
		</tr>
		</table>
    
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
    <tr>
      <th width="2%" class="title">#</th>
      <th width="2%" class="title"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
      <th width="30%" class="title"><a href="javascript:reorder('subscriber_name', '<?php if($sort == "ASC" && $orderby == "subscriber_name") { echo "DESC"; } else { echo "ASC"; } ?>');"><?php echo LM_SUBSCRIBER_NAME ?></a></th>
      <th width="35%" class="title"><a href="javascript:reorder('subscriber_email', '<?php if($sort == "ASC" && $orderby == "subscriber_email") { echo "DESC"; } else { echo "ASC"; } ?>');"><?php echo LM_SUBSCRIBER_EMAIL ?></a></th>
      <th width="20%" class="title"><a href="javascript:reorder('subscribe_date', '<?php if($sort == "ASC" && $orderby == "subscribe_date") { echo "DESC"; } else { echo "ASC"; } ?>');"><?php echo LM_SIGNUP_DATE ?></a></th>
      <th width="10%" class="title"><a href="javascript:reorder('confirmed', '<?php if($sort == "ASC" && $orderby == "confirmed") { echo "DESC"; } else { echo "ASC"; } ?>');"><?php echo LM_CONFIRMED ?>?</a></th>
    </tr>
<?php
	$i = $k = 0;
  	foreach( $rows as $row ) {
?>
			<tr class="<?php echo "row$k"; ?>">
	      <td><?php echo $i+1+$pageNav->limitstart;?></td>
	      <td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->subscriber_id; ?>" onclick="isChecked(this.checked);" /></td>
	      <td><a href="#edit" onclick="return listItemTask('cb<?php echo $i++;?>','editSubscriber')">
	        <?php echo $row->subscriber_name; ?> </a> </td>
	      <td><?php echo $row->subscriber_email; ?></td>
	      <td><?php echo $row->subscribe_date; ?></td>
	      <td>
	      <?php 
	      $img = $row->confirmed ? 'tick.png' : 'publish_x.png';
	      echo "<img src=\"images/$img\" width=\"12\" height=\"12\" border=\"0\" alt=\"$img\" />";
	      ?>
	      </td>
	    </tr>
<?php
    	$k = 1 - $k;
	}
?>
	</table>
		<?php echo $pageNav->getListFooter(); ?>
	  <input type="hidden" name="option" value="<?php echo $option;?>" />
	  <input type="hidden" name="task" value="subscribers" />
	  <input type="hidden" name="boxchecked" value="0" />
	  <input type="hidden" name="orderby" value="<?php echo $orderby; ?>" />
	  <input type="hidden" name="ordering" value="<?php echo $sort; ?>" />
	</form>
<?php

  }
  
	function editSubscriber( $id){ 
	  global $database, $option;
	  
	  $row = new mosLettermanSubscribers( $database );
	  // load the row from the db table
	  $row->load( $id );
	  
?>
	<form action="index2.php" method="post" name="adminForm">
		<div class="sectionname" align="left"><?php echo ($id != 0) ? _E_EDIT : _E_ADD; echo " ".LM_SUBSCRIBER; ?>:</div><br /><br />
    
    <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
      <tr>
        <td><?php echo LM_SIGNUP_DATE ?>:</td>
        <td><?php echo $row->subscribe_date; ?></td>
      </tr>
      <tr>
				<td width="100"><?php echo LM_SUBSCRIBER_NAME ?>:</td>
				<td width="85%"><input type="text" name="subscriber_name" class="inputbox" size="40" value="<?php echo $row->subscriber_name; ?>" /></td>
			</tr>
      <tr>
				<td width="100"><?php echo LM_SUBSCRIBER_EMAIL ?>:</td>
				<td width="85%"><input type="text" name="subscriber_email" class="inputbox" size="40" value="<?php echo $row->subscriber_email; ?>" /></td>
			</tr>
			<tr>
				<td width="100"><?php echo LM_CONFIRMED ?>?:</td>
				<td width="85%">
				  <select name="confirmed" class="inputbox"
					<option value="1"<?php if($row->confirmed=="1"){ echo " checked=\"checked\""; }?> >
					<?php echo _CMN_YES ?></option>
					<option value="0"<?php if($row->confirmed=="0"){ echo " checked=\"checked\""; }?> >
					<?php echo _CMN_NO ?></option>
				</td>
			</tr>    
    </table>
    
    <input type="hidden" name="subscriber_id" value="<?php echo $id ?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value="" />
	</form>
<?php
	}
	function assignUsers( $rows ) {
	  
	  ?>
	  <div class="sectionname" align="left"><?php echo LM_USERS_TO_SUBSCRIBERS; ?>:</div><br /><br />
	  <form action="index2.php" method="post" name="adminForm">
	  <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
      <tr>
        <td valign="top">
  <?php
	  if( $rows ) { ?>
		<div align="right"><?php echo _CMN_SELECT ?>:</div></td>
		  <td><select size="10" multiple="multiple" name="selectedUsers[]" class="inputbox"><?php 
			foreach( $rows as $user ) {
			  echo "<option value=\"".$user->id."\">".$user->name." (".$user->username.")</option>\n";
			}
			  ?>
			</select></td>
<?php
	  }
	  else { ?>
	  No User you could select!</td>
	  <?php
	  }
	  ?>
		</tr>
		</table>

	  <input type="hidden" name="option" value="com_letterman" />
	  <input type="hidden" name="task" value="" />
	  </form><?php
	}

	
	function saveSubscriber() { 
	  global $database;
	  
	  $selectedUsers = mosGetParam( $_POST, 'selectedUsers'  );
	  
	  if( is_array( $selectedUsers )) {
		$_REQUEST['mosmsg'] = LM_SUBSCRIBER_SAVED;
		foreach( $selectedUsers as $user_id ) {
		  $q = "SELECT email, name FROM #__users WHERE id='$user_id'";
		  $database->setQuery( $q );
		  $database->loadObject( $user );
		  $subscriber =& new mosLettermanSubscribers($database);
		  $subscriber->user_id = $user_id;
		  $subscriber->subscriber_name = $user->name;
		  $subscriber->subscriber_email = $user->email;
		  $subscriber->confirmed = "1";
		  $subscriber->subscribe_date = date( "Y:m:d h:i:s", time() );
		  if( !$subscriber->store() ) {
			$_REQUEST['mosmsg'] .= "Error storing User ".$user->name;
		  }
		}
		mosRedirect( "index2.php?option=com_letterman&task=subscribers", $_REQUEST['mosmsg']);
	  }
	  elseif( !empty( $_POST['subscriber_email'] )) {
		$row = new mosLettermanSubscribers( $database );
		// load the row from the db table
		if (!$row->bind( $_POST )) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
  
		$row->subscribe_date = date( "Y-m-d H:i:s" );
	  
		if (!$row->check()) {
		  echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		  exit();
		}
	  
		  if (!$row->store()) {
		  echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		  exit();
		}
		
		if(empty($row->subscriber_id)){
			$row->subscriber_id = $database->insertid();
		}
		
		//$error = $database->getErrorMsg();
		
		if(!empty($error)){
			echo "<script> alert('".LM_ERROR_EMAIL_ALREADY_ONLIST."'); location.href = 'index2.php?option=com_letterman&task=subscribers'; </script>\n";
			exit();
		}
		else {
		  mosRedirect( "index2.php?option=com_letterman&task=subscribers", LM_SUBSCRIBER_SAVED);
		}
	  }
	  else {
		  mosRedirect( "index2.php?option=com_letterman&task=subscribers" );
	  }
	}
	
	function deleteSubscribers(){ 
	  global $database;
	  
	  $cid = implode(", ", $_REQUEST['cid']);
	  $count = sizeof( $_REQUEST['cid']);
	  $query = "DELETE FROM #__letterman_subscribers WHERE subscriber_id IN (" . $cid . ")";
	  $database->setQuery($query);
	  $database->query();
	  
	  $error = $database->getErrorMsg();
	  if(!empty($error)){
		  echo "<script> alert('". $error ."'); </script>\n";
		  exit();
	  }
	  $msg = str_replace( "{X}", $count, LM_SUBSCRIBERS_DELETED);
	  mosRedirect( "index2.php?option=com_letterman&task=subscribers", $msg);
	}
	
	function deleteSubscriber( $id){ 
	  global $database;
	  //subscribers
	  $query = "DELETE FROM #__newsletter_subscribers WHERE subscriber_id = " . $id;
	  $database->setQuery($query);
	  $database->query();
	  
	  $error = $database->getErrorMsg();
	  if(!empty($error)){
		  echo "<script> alert('". $error ."'); </script>\n";
		  exit();
	  }
	  mosRedirect( "index2.php?option=com_letterman&task=subscribers", LM_SUBSCRIBER_DELETED);
	}
	function header() {
	  echo "<span class=\"sectionname\"><img align=\"center\" alt=\"letterman logo\" src=\"components/com_letterman/letterman.png\" /></span>";
	}
	function footer() {
	  ?>
		<div align="center">
		  <a href="http://www.mambo-phpShop.net/" target="_blank">Letterman Component &copy;2005 Soeren Eberhardt</a>
		</div>
	  <?php
	}
}
?>
