<?php
/**
* AkoBook - A Mambo Guestbook Component
* @version 3.45
* @package AkoBook
* @copyright (C) 2003, 2004 by Arthur Konze - All rights reserved!
* @license http://www.konze.de/content/view/8/26/ Copyrighted Commercial Software
**/

# Don't allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

# Don't allow passed settings
  if (($_REQUEST['is_editor'])) {
    print "<SCRIPT>document.location.href='../../index.php'</SCRIPT>\n";
    exit();
  }

# Check if Registered Users only
  if (!$ab_anonentry AND !$is_user) {
    echo _GUESTBOOK_ONLYREGISTERED;
  } else {

  # Add custom Pathway
  $mainframe->appendPathWay(_GUESTBOOK_SIGN);

  # Javascript for SmilieInsert and Form Check
    echo "<script language=\"Javascript\">";
    echo "  function x () {";
    echo "    return;";
    echo "  }";
    echo "  function gb_smilie(thesmile) {";
    echo "    document.gbookForm.gbtext.value += \" \"+thesmile+\" \";";
    echo "    document.gbookForm.gbtext.focus();";
    echo "  }";
    echo "  function validate(){";
    echo "    if ((document.gbookForm.gbname.value=='') || (document.gbookForm.gbtext.value=='')){";
    echo "      alert(\""._GUESTBOOK_VALIDATE."\");";
    echo "    } else {";
    echo "      document.gbookForm.action = 'index.php';";
    echo "      document.gbookForm.submit();";
    echo "    }";
    echo "  }";
    if ($ab_bbcodesupport) {
      echo "  function DoPrompt(action) {";
      echo "    var revisedMessage;";
      echo "    var currentMessage = document.gbookForm.gbtext.value;";
      echo "    if (action == \"url\") {";
      echo "      var thisURL = prompt(\"Enter the URL for the link you want to add.\", \"http://\");";
      echo "      var thisTitle = prompt(\"Enter the web site title\", \"Page Title\");";
      echo "      var urlBBCode = \"[URL=\"+thisURL+\"]\"+thisTitle+\"[/URL]\";";
      echo "      revisedMessage = currentMessage+urlBBCode;";
      echo "      document.gbookForm.gbtext.value=revisedMessage;";
      echo "      document.gbookForm.gbtext.focus();";
      echo "      return;";
      echo "    }";
      echo "    if (action == \"email\") {";
      echo "      var thisEmail = prompt(\"Enter the email address you want to add.\", \"\");";
      echo "      var emailBBCode = \"[EMAIL]\"+thisEmail+\"[/EMAIL]\";";
      echo "      revisedMessage = currentMessage+emailBBCode;";
      echo "      document.gbookForm.gbtext.value=revisedMessage;";
      echo "      document.gbookForm.gbtext.focus();";
      echo "      return;";
      echo "    }";
      echo "    if (action == \"bold\") {";
      echo "      var thisBold = prompt(\"Enter the text that you want to make bold.\", \"\");";
      echo "      var boldBBCode = \"[B]\"+thisBold+\"[/B]\";";
      echo "      revisedMessage = currentMessage+boldBBCode;";
      echo "      document.gbookForm.gbtext.value=revisedMessage;";
      echo "      document.gbookForm.gbtext.focus();";
      echo "      return;";
      echo "    }";
      echo "    if (action == \"italic\") {";
      echo "      var thisItal = prompt(\"Enter the text that you want to make italic.\", \"\");";
      echo "      var italBBCode = \"[I]\"+thisItal+\"[/I]\";";
      echo "      revisedMessage = currentMessage+italBBCode;";
      echo "      document.gbookForm.gbtext.value=revisedMessage;";
      echo "      document.gbookForm.gbtext.focus();";
      echo "      return;";
      echo "    }";
      echo "    if (action == \"underline\") {";
      echo "      var thisUndl = prompt(\"Enter the text that you want to be undelined.\", \"\");";
      echo "      var undlBBCode = \"[U]\"+thisUndl+\"[/U]\";";
      echo "      revisedMessage = currentMessage+undlBBCode;";
      echo "      document.gbookForm.gbtext.value=revisedMessage;";
      echo "      document.gbookForm.gbtext.focus();";
      echo "      return;";
      echo "    }";
      echo "    if (action == \"image\") {";
      echo "      var thisImage = prompt(\"Enter the URL for the image you want to display.\", \"http://\");";
      echo "      var imageBBCode = \"[IMG]\"+thisImage+\"[/IMG]\";";
      echo "      revisedMessage = currentMessage+imageBBCode;";
      echo "      document.gbookForm.gbtext.value=revisedMessage;";
      echo "      document.gbookForm.gbtext.focus();";
      echo "      return;";
      echo "    }";
      echo "    if (action == \"quote\") {";
      echo "      var quoteBBCode = \"[QUOTE]  [/QUOTE]\";";
      echo "      revisedMessage = currentMessage+quoteBBCode;";
      echo "      document.gbookForm.gbtext.value=revisedMessage;";
      echo "      document.gbookForm.gbtext.focus();";
      echo "      return;";
      echo "    }";
      echo "    if (action == \"code\") {";
      echo "      var codeBBCode = \"[CODE]  [/CODE]\";";
      echo "      revisedMessage = currentMessage+codeBBCode;";
      echo "      document.gbookForm.gbtext.value=revisedMessage;";
      echo "      document.gbookForm.gbtext.focus();";
      echo "      return;";
      echo "    }";
      echo "    if (action == \"listopen\") {";
      echo "      var liststartBBCode = \"[LIST]\";";
      echo "      revisedMessage = currentMessage+liststartBBCode;";
      echo "      document.gbookForm.gbtext.value=revisedMessage;";
      echo "      document.gbookForm.gbtext.focus();";
      echo "      return;";
      echo "    }";
      echo "    if (action == \"listclose\") {";
      echo "      var listendBBCode = \"[/LIST]\";";
      echo "      revisedMessage = currentMessage+listendBBCode;";
      echo "      document.gbookForm.gbtext.value=revisedMessage;";
      echo "      document.gbookForm.gbtext.focus();";
      echo "      return;";
      echo "    }";
      echo "    if (action == \"listitem\") {";
      echo "      var thisItem = prompt(\"Enter the new list item. Note that each list group must be preceeded by a List Close and must be ended with List Close.\", \"\");";
      echo "      var itemBBCode = \"[*]\"+thisItem;";
      echo "      revisedMessage = currentMessage+itemBBCode;";
      echo "      document.gbookForm.gbtext.value=revisedMessage;";
      echo "      document.gbookForm.gbtext.focus();";
      echo "      return;";
      echo "    }";
      echo "  }";
    }
    echo "</SCRIPT>";

    echo "<TABLE align='center' width='90%' CELLPADDING='0' CELLSPACING='4' BORDER='0'>";
    echo "<FORM NAME='gbookForm' ACTION='index.php' TARGET=_top METHOD='POST'>";

    $gbname = $my->username;
    $gbmail = $my->usermail;

  # Check if User is Admin and if he wants to edit
	if (($is_editor) AND ($gbid)) {
      echo "<INPUT TYPE='hidden' NAME='gbid' value='$gbid'>";
      $database->setQuery( "SELECT * FROM #__akobook WHERE gbid = $gbid" );
      $row = NULL;
      $database->loadObject( $row );
   }

$database->setQuery( "SELECT * FROM #__users WHERE id = '$my->id'" );
$dados = NULL;
$database->loadObject( $detailuser );


    echo "<INPUT TYPE='hidden' NAME='option' value='com_akobook'>";
    echo "<INPUT TYPE='hidden' NAME='Itemid' value='$Itemid'>";
    echo "<INPUT TYPE='hidden' NAME='func' value='entry'>";
	
if ($gbid){
$name = $row->gbname;
$email = $row->gbmail;
} else {
$name = $detailuser->name;
$email = $detailuser->email;
}	
    echo "<TR><TD WIDTH='130'>"._GUESTBOOK_ENTERNAME." <span class='small'>*</span></TD><TD><INPUT TYPE='text' NAME='gbname' style='width:245px;' class='inputbox' VALUE='$name'></TD></TR>";
    if ($ab_showmail) echo "<TR><TD WIDTH='130'>"._GUESTBOOK_ENTERMAIL."</TD><TD><INPUT TYPE='text' NAME='gbmail' style='width:245px;' class='inputbox' VALUE='$email'></TD></TR>";
    if ($ab_showhome) echo "<TR><TD WIDTH='130'>"._GUESTBOOK_ENTERPAGE."</TD><TD><INPUT TYPE='text' NAME='gbpage' style='width:245px;' class='inputbox' VALUE='$row->gbpage'></TD></TR>";
    if ($ab_showloca) echo "<TR><TD WIDTH='130'>"._GUESTBOOK_ENTERLOCA."</TD><TD><INPUT TYPE='text' NAME='gbloca' style='width:245px;' class='inputbox' VALUE='$row->gbloca'></TD></TR>";
    if ($ab_showicq)  echo "<TR><TD WIDTH='130'>"._GUESTBOOK_ENTERICQ."</TD><TD><INPUT TYPE='text' NAME='gbicq' style='width:245px;' class='inputbox' VALUE='$row->gbicq'></TD></TR>";
    if ($ab_showaim)  echo "<TR><TD WIDTH='130'>"._GUESTBOOK_ENTERAIM."</TD><TD><INPUT TYPE='text' NAME='gbaim' style='width:245px;' class='inputbox' VALUE='$row->gbaim'></TD></TR>";
    if ($ab_showmsn)  echo "<TR><TD WIDTH='130'>"._GUESTBOOK_ENTERMSN."</TD><TD><INPUT TYPE='text' NAME='gbmsn' style='width:245px;' class='inputbox' VALUE='$row->gbmsn'></TD></TR>";
    if ($ab_showrating) {
      echo "<TR><TD WIDTH='130'>"._GUESTBOOK_ENTERVOTE."</TD>";
      echo "<TD><select style='width:100px;' class='inputbox' size='1' name='gbvote'>";
      $selitem = $row->gbvote ? $row->gbvote : floor($ab_maxvoting/2) + 1;
      for ($i=1; $i<=$ab_maxvoting; $i++) {
        echo "<option";
        if ($i==$selitem) echo " selected";
        echo ">$i</option>";
      }
      echo "</select> ($ab_maxvoting - "._GUESTBOOK_VOTEGOOD.", 1 - "._GUESTBOOK_VOTEBAD.")</TD></TR>";
    } else {
      $middlerate = floor($ab_maxvoting/2) + 1;
      echo "<INPUT TYPE='hidden' NAME='gbvote' value='$middlerate'>";
    }

  # Switch for BB Code support
    if ($ab_bbcodesupport) {
      echo "<TR><TD WIDTH='130'> </TD><TD>";
      echo "<A href='javascript: x()' onClick='DoPrompt(\"url\");'><IMG src='components/com_akobook/images/bburl.gif' hspace='1' border='0' alt='Web Address'></A>";
      echo "<A href='javascript: x()' onClick='DoPrompt(\"email\");'><IMG src='components/com_akobook/images/bbemail.gif' hspace='1' border='0' alt='Email Address'></A>";
      if ($ab_picsupport) echo "<A href='javascript: x()' onClick='DoPrompt(\"image\");'><IMG src='components/com_akobook/images/bbimgsrc.gif' hspace='1' border='0' alt='Load Image from Web'></A>";
      echo "<A href='javascript: x()' onClick='DoPrompt(\"bold\");'><IMG src='components/com_akobook/images/bbbold.gif' hspace='1' border='0' alt='Bold Text'></A>";
      echo "<A href='javascript: x()' onClick='DoPrompt(\"italic\");'><IMG src='components/com_akobook/images/bbitalic.gif' hspace='1' border='0' alt='Italic Text'></A>";
      echo "<A href='javascript: x()' onClick='DoPrompt(\"underline\");'><IMG src='components/com_akobook/images/bbunderline.gif' hspace='1' border='0' alt='Underlined Text'></A>";
      echo "<A href='javascript: x()' onClick='DoPrompt(\"quote\");'><IMG src='components/com_akobook/images/bbquote.gif' hspace='1' border='0' alt='Quote'></A>";
      echo "<A href='javascript: x()' onClick='DoPrompt(\"code\");'><IMG src='components/com_akobook/images/bbcode.gif' hspace='1' border='0' alt='Code'></A>";
      echo "<A href='javascript: x()' onClick='DoPrompt(\"listopen\");'><IMG src='components/com_akobook/images/bblistopen.gif' hspace='1' border='0' alt='Open List'></A>";
      echo "<A href='javascript: x()' onClick='DoPrompt(\"listitem\");'><IMG src='components/com_akobook/images/bblistitem.gif' hspace='1' border='0' alt='List Item'></A>";
      echo "<A href='javascript: x()' onClick='DoPrompt(\"listclose\");'><IMG src='components/com_akobook/images/bblistclose.gif' hspace='1' border='0' alt='Close List'></A>";
      echo "</TD></TR>";
    }

    echo "<TR><TD WIDTH='130' valign='top'>"._GUESTBOOK_ENTERTEXT." <span class='small'>*</span><br /><br />";

  # Switch for Smilie Support
    if ($ab_smiliesupport) {
      $count=1;
      foreach ($smiley as $i=>$sm) {
        echo "<a href=\"javascript:gb_smilie('$i')\" title='$i'><img src='components/com_akobook/images/$sm' border='0'></a> ";
        if ($count%5==0) echo "<br>";
        $count++;
      }
    }

    echo "</TD><TD valign='top'><TEXTAREA style='width:245px;' ROWS='8' NAME='gbtext' class='inputbox' wrap='VIRTUAL'>$row->gbtext</TEXTAREA></TD></TR>";
	echo "<TR><TD WIDTH='130'>"._GUESTBOOK_ENTERCODE."<span class='small'>*</span></TD><TD><INPUT TYPE='text' NAME='gbcode' maxlength='5' style='width:60px;' class='inputbox' title='"._GUESTBOOK_CODEDESCRIPTION."'> <img src='./components/com_akobook/img.php' border='0' title='"._GUESTBOOK_CODEIMAGE."' alt='Code' align='absmiddle' /></TD></TR>";
    echo "<TR><TD WIDTH='130'><INPUT TYPE='button' NAME='send' VALUE='"._GUESTBOOK_SENDFORM."' class='button' onClick='validate()'></TD>";
    echo "<TD align='right'><input type='reset' value='"._GUESTBOOK_CLEARFORM."' name='reset' class='button'></TD></TR></FORM></TABLE>";
    echo "<center><span class='small'>* - "._GUESTBOOK_REQUIREDFIELD."</span></center>";

# Close RegUserOnly Check
  }

?>