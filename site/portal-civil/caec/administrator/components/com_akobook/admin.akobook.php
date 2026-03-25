<?
/**
* AkoBook - A Mambo Guestbook Component
* @version 3.4
* @package AkoBook
* @copyright (C) 2003, 2004 by Arthur Konze - All rights reserved!
* @license http://www.konze.de/content/view/8/26/ Copyrighted Commercial Software
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
require_once( $mosConfig_absolute_path."/administrator/components/com_akobook/class.akobook.php");
require_once( $mainframe->getPath( 'admin_html' ) );

switch ($task) {
  case "publish":
    publishGuestbook( $gbid, 1, $option );
    break;

  case "unpublish":
    publishGuestbook( $gbid, 0, $option );
    break;

  case "new":
    editGuestbook( $option, $database, 0 );
    break;

  case "edit":
    editGuestbook( $option, $database, $gbid[0] );
    break;

  case "remove":
    removeGuestbook( $database, $gbid, $option );
    break;

  case "save":
    saveGuestbook( $option, $database );
    break;

  case "config":
    showConfig( $option );
    break;

  case "savesettings":
    saveConfig ($option, $ab_offline, $ab_offline_message, $ab_autopublish, $ab_notify, $ab_notify_email, $ab_thankuser, $ab_perpage, $ab_sorting, $ab_showrating, $ab_maxvoting, $ab_allowentry, $ab_anonentry, $ab_bbcodesupport, $ab_smiliesupport, $ab_picsupport, $ab_showmail, $ab_showhome, $ab_showloca, $ab_showicq, $ab_showaim, $ab_showmsn);
    break;

  case "about":
    showAbout();
    break;

  case "language":
    showLanguage($option);
    break;

  case "savelanguage":
    saveLanguage($file, $filecontent, $option);
    break;

  default:
    showGuestbook( $option, $database );
    break;
}
echo "<p><font class='small'>&copy; Copyright 2003, 2004 by <a href='http://www.mamboportal.com/' target='_blank'>Arthur Konze</a><br />Version: 3.45(non official)</font></p>";

function showGuestbook ( $option, &$db ) {
  $search = trim( strtolower( mosGetParam( $_POST, 'search', '' ) ) );
  $limit = intval( mosGetParam( $_POST, 'limit', 10 ) );
  $limitstart = intval( mosGetParam( $_POST, 'limitstart', 0 ) );

  $where = array();

  if ($search) {
    $where[] = "LOWER(gbtext) LIKE '%$search%'";
  }

  // get the total number of records
  $db->setQuery( "SELECT count(*) FROM #__akobook AS a".(count( $where ) ? "\nWHERE " . implode( ' AND ', $where ) : "") );
  $total = $db->loadResult();
  echo $db->getErrorMsg();


  $db->setQuery( "SELECT * FROM #__akobook"
    . (count( $where ) ? "\nWHERE " . implode( ' AND ', $where ) : "")
    . "\nORDER BY gbid DESC"
    . "\nLIMIT $limitstart,$limit"
  );

  $rows = $db->loadObjectList();
  if ($db->getErrorNum()) {
    echo $db->stderr();
    return false;
  }

  include_once("includes/pageNavigation.php");
  $pageNav = new mosPageNav( $total, $limitstart, $limit  );

  HTML_Guestbook::showGuestbookEntries( $option, $rows, $search, $pageNav );
}

function removeGuestbook( &$db, $cid, $option ) {
  if (count( $cid )) {
    $cids = implode( ',', $cid );
    $db->setQuery( "DELETE FROM #__akobook WHERE gbid IN ($cids)" );
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
    }
  }
  mosRedirect( "index2.php?option=$option" );
}

function publishGuestbook( $cid=null, $publish=1,  $option ) {
  global $database;

  if (!is_array( $cid ) || count( $cid ) < 1) {
    $action = $publish ? 'publish' : 'unpublish';
    echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
    exit;
  }

  $cids = implode( ',', $cid );

  $database->setQuery( "UPDATE #__akobook SET published='$publish' WHERE gbid IN ($cids)" );
  if (!$database->query()) {
    echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
    exit();
  }

  mosRedirect( "index2.php?option=$option" );
}

function editGuestbook( $option, &$db, $gbid ) {
  global $mosConfig_absolute_path, $mosConfig_live_site;

  $row = new mosAkobook( $db );

  if ($gbid) {
    $db->setQuery( "SELECT * FROM #__akobook WHERE gbid = $gbid" );
    $rows = $db->loadObjectList();;
    $row = $rows[0];
  } else {
    // initialise new record
    $row->published = 0;
  }

// make the select list for the image positions
  $yesno[] = mosHTML::makeOption( '0', 'No' );
  $yesno[] = mosHTML::makeOption( '1', 'Yes' );

// build the html select list
  $publist = mosHTML::selectList( $yesno, 'published', 'class="inputbox" size="2"', 'value', 'text', $row->published );

  HTML_Guestbook::editGuestbook( $option, $row, $publist );
}

function saveGuestbook( $option, &$db ) {
  global $my;

  $row = new mosAkobook( $db );
  if (!$row->bind( $_POST )) {
    echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
    exit();
  }
  $row->_tbl_key = "gbid";

  if (!$row->store()) {
   echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
    exit();
  }

  mosRedirect( "index2.php?option=$option" );
}

############################################################################
############################################################################

function showConfig( $option ) {
  global $mosConfig_absolute_path;
  require($mosConfig_absolute_path."/administrator/components/com_akobook/config.akobook.php");
?>
    <script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
      var form = document.adminForm;
      if (pressbutton == 'cancel') {
        submitform( pressbutton );
        return;
      }
      if (form.ab_perpage.value == ""){
        alert( "You must set entries per page greater 0!" );
      } else {
        submitform( pressbutton );
      }
    }
    </script>
  <form action="index2.php" method="post" name="adminForm" id="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
  <tr>
    <td width="100%" class="sectionname">
        <img src="components/com_akobook/images/logo.png" align="absmiddle" style="margin-right:10px;">
        <font style="color: #FF9E31;font-size : 18px;font-weight: bold;text-align: left;">AkoBook</font>
    </td>
  </tr>
  </table>
  <?php
  $akogbtabs = new mosTabs( 0 );
  $akogbtabs->startPane( "ako_guestbook" );
    $akogbtabs->startTab("Backend","Backend-page");
    ?>
    <table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminForm">
      <tr align="center" valign="middle">
        <td width="20%" align="left" valign="top"><strong><?php echo "Guestbook offline:" ?></strong></td>
        <td width="20%" align="left" valign="top">
        <?php
          $yesno[] = mosHTML::makeOption( '0', 'No' );
          $yesno[] = mosHTML::makeOption( '1', 'Yes' );
          echo mosHTML::yesnoRadioList( 'ab_offline', 'class="inputbox"', $ab_offline );
        ?>
        </td>
        <td width="60%" align="left" valign="top"><?php echo "Switch the guestbook frontend offline." ?></td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Offline message:</strong></td>
        <td align="left" valign="top">
          <?php $ab_offline_message = stripslashes("$ab_offline_message"); ?>
          <textarea class="inputbox" cols="30" rows="5" name="ab_offline_message"><?php echo "$ab_offline_message"; ?></textarea>
          </td>
        <td align="left" valign="top">Message, which is presented to the frontend users.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Autopublish entries:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_autopublish', 'class="inputbox"', $ab_autopublish );
        ?>
        </td>
        <td align="left" valign="top">Autopublish new entries to the guestbook.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Notify webmaster:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_notify', 'class="inputbox"', $ab_notify );
        ?>
        </td>
        <td align="left" valign="top">Notify webmaster when new entries arrive.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Webmaster's email:</strong></td>
        <td align="left" valign="top"><input type="text" name="ab_notify_email" value="<? echo "$ab_notify_email"; ?>"></td>
        <td align="left" valign="top">Email address, where notifications are send to.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Thank user:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_thankuser', 'class="inputbox"', $ab_thankuser );
        ?>
        </td>
        <td align="left" valign="top">Send 'Thank You' mail to the user.</td>
      </tr>
    </table>
    <?php
    $akogbtabs->endTab();
    $akogbtabs->startTab("Frontend","Frontend-page");
    ?>
    <table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminForm">
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Entries per Page:</strong></td>
        <td align="left" valign="top"><input type="text" name="ab_perpage" value="<? echo "$ab_perpage"; ?>"></td>
        <td align="left" valign="top">Number of entries shown per page.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Guestbook sorting:</strong></td>
        <td align="left" valign="top">
        <?php
          $gbsorting[] = mosHTML::makeOption( 'DESC', 'New entries first' );
          $gbsorting[] = mosHTML::makeOption( 'ASC', 'New entries last' );
          $mc_ab_sorting = mosHTML::selectList( $gbsorting, 'ab_sorting', 'class="inputbox" size="2"', 'value', 'text', $ab_sorting );
          echo $mc_ab_sorting;
        ?>
        </td>
        <td align="left" valign="top">Sorting of new guestbook entries.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Highest rating:</strong></td>
        <td align="left" valign="top"><input type="text" name="ab_maxvoting" value="<? echo "$ab_maxvoting"; ?>"></td>
        <td align="left" valign="top">Highest possible website rating.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Allow Entries:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_allowentry', 'class="inputbox"', $ab_allowentry );
        ?>
        </td>
        <td align="left" valign="top">Allow the users to write new entries.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Anonymous Entries:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_anonentry', 'class="inputbox"', $ab_anonentry );
        ?>
        </td>
        <td align="left" valign="top">Allow unregistered users to write entries.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Allow BBCode:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_bbcodesupport', 'class="inputbox"', $ab_bbcodesupport );
        ?>
        </td>
        <td align="left" valign="top">Allow the use of simple BBCode in entries.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Allow Smilies:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_smiliesupport', 'class="inputbox"', $ab_smiliesupport );
        ?>
        </td>
        <td align="left" valign="top">Allow the use of Smilies in entries.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Allow Pictures:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_picsupport', 'class="inputbox"', $ab_picsupport );
        ?>
        </td>
        <td align="left" valign="top">Allow the use of pictures in entries.</td>
      </tr>
    </table>
    <?php
    $akogbtabs->endTab();
    $akogbtabs->startTab("Fields","Fields-page");
    ?>
    <table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminForm">
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Show Email:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_showmail', 'class="inputbox"', $ab_showmail );
        ?>
        </td>
        <td align="left" valign="top">Let the user enter his email adress.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Show Homepage:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_showhome', 'class="inputbox"', $ab_showhome );
        ?>
        </td>
        <td align="left" valign="top">Let the user enter his homepage.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Show Location:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_showloca', 'class="inputbox"', $ab_showloca );
        ?>
        </td>
        <td align="left" valign="top">Let the user enter his location.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Show ICQ:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_showicq', 'class="inputbox"', $ab_showicq );
        ?>
        </td>
        <td align="left" valign="top">Let the user enter his ICQ adress.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Show AIM:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_showaim', 'class="inputbox"', $ab_showaim );
        ?>
        </td>
        <td align="left" valign="top">Let the user enter his AIM adress.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Show MSN:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_showmsn', 'class="inputbox"', $ab_showmsn );
        ?>
        </td>
        <td align="left" valign="top">Let the user enter his MSN adress.</td>
      </tr>
      <tr align="center" valign="middle">
        <td align="left" valign="top"><strong>Show Rating:</strong></td>
        <td align="left" valign="top">
        <?php
          echo mosHTML::yesnoRadioList( 'ab_showrating', 'class="inputbox"', $ab_showrating );
        ?>
        </td>
        <td align="left" valign="top">Let the user rate your website.</td>
      </tr>
    </table>
    <?php
    $akogbtabs->endTab();
  $akogbtabs->endPane();
  ?>
  <input type="hidden" name="id" value="">
  <input type="hidden" name="task" value="">
  <input type="hidden" name="option" value="<?php echo $option; ?>">
</form>
<?php
}

function saveConfig ($option, $ab_offline, $ab_offline_message, $ab_autopublish, $ab_notify, $ab_notify_email, $ab_thankuser, $ab_perpage, $ab_sorting, $ab_showrating, $ab_maxvoting, $ab_allowentry, $ab_anonentry, $ab_bbcodesupport, $ab_smiliesupport, $ab_picsupport, $ab_showmail, $ab_showhome, $ab_showloca, $ab_showicq, $ab_showaim, $ab_showmsn) {
  $configfile = "components/com_akobook/config.akobook.php";
  @chmod ($configfile, 0766);
  $permission = is_writable($configfile);
  if (!$permission) {
    mosRedirect("index2.php?option=$option&task=config", "Config file not writeable!");
    break;
  }

  $ab_offline_message = addslashes("$ab_offline_message");

  $config = "<?php\n";
  $config .= "\$ab_offline = \"$ab_offline\";\n";
  $config .= "\$ab_offline_message = \"$ab_offline_message\";\n";
  $config .= "\$ab_autopublish = \"$ab_autopublish\";\n";
  $config .= "\$ab_notify = \"$ab_notify\";\n";
  $config .= "\$ab_notify_email = \"$ab_notify_email\";\n";
  $config .= "\$ab_thankuser = \"$ab_thankuser\";\n";
  $config .= "\$ab_perpage = \"$ab_perpage\";\n";
  $config .= "\$ab_sorting = \"$ab_sorting\";\n";
  $config .= "\$ab_showrating = \"$ab_showrating\";\n";
  $config .= "\$ab_maxvoting = \"$ab_maxvoting\";\n";
  $config .= "\$ab_allowentry = \"$ab_allowentry\";\n";
  $config .= "\$ab_anonentry = \"$ab_anonentry\";\n";
  $config .= "\$ab_bbcodesupport = \"$ab_bbcodesupport\";\n";
  $config .= "\$ab_smiliesupport = \"$ab_smiliesupport\";\n";
  $config .= "\$ab_picsupport = \"$ab_picsupport\";\n";
  $config .= "\$ab_showmail = \"$ab_showmail\";\n";
  $config .= "\$ab_showhome = \"$ab_showhome\";\n";
  $config .= "\$ab_showloca = \"$ab_showloca\";\n";
  $config .= "\$ab_showicq = \"$ab_showicq\";\n";
  $config .= "\$ab_showaim = \"$ab_showaim\";\n";
  $config .= "\$ab_showmsn = \"$ab_showmsn\";\n";
  $config .= "?>";

  if ($fp = fopen("$configfile", "w")) {
    fputs($fp, $config, strlen($config));
    fclose ($fp);
  }
  mosRedirect("index2.php?option=$option&task=config", "Settings saved");
}

############################################################################
############################################################################

function showAbout() {
  # Show about screen to user
  HTML_Guestbook::showAbout();
}

############################################################################
############################################################################

function showLanguage($option) {

  global $mosConfig_absolute_path, $mosConfig_lang;

  if (file_exists($mosConfig_absolute_path.'/components/com_akobook/languages/'.$mosConfig_lang.'.php')) {
    $file = $mosConfig_absolute_path.'/components/com_akobook/languages/'.$mosConfig_lang.'.php';
  } else {
    $file = $mosConfig_absolute_path.'/components/com_akobook/languages/english.php';
  }
  @chmod ($file, 0766);
  $permission = is_writable($file);
  if (!$permission) {
    echo "<center><h1><font color=red>Warning...</FONT></h1><BR>";
    echo "<B>Your css file is <#__root>/components/com_simpleboard/forum.css</b><BR>";
    echo "<B>You need to chmod this to 766 in order for the config to be updated</B></center><BR><BR>";
  }

  HTML_Guestbook::showLanguage($file,$option);
}

function saveLanguage($file, $filecontent, $option) {

  @chmod ($file, 0766);
  $permission = is_writable($file);
  if (!$permission) {
    mosRedirect("index2.php?option=$option&task=language", "File not writeable!");
    break;
  }

  if ($fp = fopen( $file, "w")) {
    fputs($fp,stripslashes($filecontent));
    fclose($fp);
    mosRedirect( "index2.php?option=$option&task=language", "Language file saved" );
  }
}

?>