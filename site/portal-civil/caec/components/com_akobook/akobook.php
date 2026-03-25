<?php
/**
* AkoBook - A Mambo Guestbook Component
* @version 3.41
* @package AkoBook
* @copyright (C) 2003, 2004 by Arthur Konze - All rights reserved!
* @license http://www.konze.de/content/view/8/26/ Copyrighted Commercial Software
**/

# Don't allow direct linking
  defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

# Variables - Don't change anything here!!!
  require($mosConfig_absolute_path."/administrator/components/com_akobook/config.akobook.php");
  $akoversion       = "V3.45";

# Functions of AkoBook
  function GuestbookHeader($viewlink) {
    global $Itemid, $database, $ab_allowentry, $mosConfig_lang, $mosConfig_mbf_content;
    $mname = new mosMenu( $database );
    $mname->load($Itemid);
    # Check for Mamblefish and use translated Menuname instead
    if( $mosConfig_mbf_content ) {
      $mname = MambelFish::translate( $mname, 'menu', $mosConfig_lang);
    }
    $menuname = $mname->name;
    echo "<TABLE WIDTH='100%' CELLPADDING='4' CELLSPACING='4' BORDER='0' ALIGN='center' class='contentpaneopen'>";
    echo "<TR><TD CLASS='componentheading'>$menuname</TD></TR>";
    echo "<TR><TD ALIGN='RIGHT'><ul>";
    if ($viewlink) {
      echo"<li> <a href='".sefRelToAbs("index.php?option=com_akobook&Itemid=$Itemid")."'>"._GUESTBOOK_VIEW."</a></li>";
    } elseif ($ab_allowentry) {
      echo"<li> <a href='".sefRelToAbs("index.php?option=com_akobook&Itemid=$Itemid&func=sign")."'>"._GUESTBOOK_SIGN."</a></li>";
    }
    echo "</ul></TD></TR><TR><TD>";
    return;
  }

  function AkoParse($message) {
    global $smiley, $ab_bbcodesupport, $ab_picsupport, $ab_smiliesupport, $mosConfig_live_site;
    # Convert BB Code to HTML commands
    if ($ab_bbcodesupport) {
      $matchCount = preg_match_all("#\[code\](.*?)\[/code\]#si", $message, $matches);
      for ($i = 0; $i < $matchCount; $i++) {
        $currMatchTextBefore = preg_quote($matches[1][$i]);
        $currMatchTextAfter = htmlspecialchars($matches[1][$i]);
        $message = preg_replace("#\[code\]$currMatchTextBefore\[/code\]#si", "<b>Code:</b><HR>$currMatchTextAfter<HR>", $message);
      }
      $message = preg_replace("#\[quote\](.*?)\[/quote]#si", "<b>Quote:</b><HR><BLOCKQUOTE>\\1</BLOCKQUOTE><HR>", $message);
      $message = preg_replace("#\[b\](.*?)\[/b\]#si", "<B>\\1</B>", $message);
      $message = preg_replace("#\[i\](.*?)\[/i\]#si", "<I>\\1</I>", $message);
      $message = preg_replace("#\[u\](.*?)\[/u\]#si", "<U>\\1</U>", $message);
      $message = preg_replace("#\[url\](http://)?(.*?)\[/url\]#si", "<A HREF=\"http://\\2\" TARGET=\"_blank\">\\2</A>", $message);
      $message = preg_replace("#\[url=(http://)?(.*?)\](.*?)\[/url\]#si", "<A HREF=\"http://\\2\" TARGET=\"_blank\">\\3</A>", $message);
      $message = preg_replace("#\[email\](.*?)\[/email\]#si", "<A HREF=\"mailto:\\1\">\\1</A>", $message);
      if ($ab_picsupport) $message = preg_replace("#\[img\](.*?)\[/img\]#si", "<IMG SRC=\"\\1\">", $message);
      $matchCount = preg_match_all("#\[list\](.*?)\[/list\]#si", $message, $matches);
      for ($i = 0; $i < $matchCount; $i++) {
        $currMatchTextBefore = preg_quote($matches[1][$i]);
        $currMatchTextAfter = preg_replace("#\[\*\]#si", "<LI>", $matches[1][$i]);
        $message = preg_replace("#\[list\]$currMatchTextBefore\[/list\]#si", "<UL>$currMatchTextAfter</UL>", $message);
      }
      $matchCount = preg_match_all("#\[list=([a1])\](.*?)\[/list\]#si", $message, $matches);
      for ($i = 0; $i < $matchCount; $i++) {
        $currMatchTextBefore = preg_quote($matches[2][$i]);
        $currMatchTextAfter = preg_replace("#\[\*\]#si", "<LI>", $matches[2][$i]);
        $message = preg_replace("#\[list=([a1])\]$currMatchTextBefore\[/list\]#si", "<OL TYPE=\\1>$currMatchTextAfter</OL>", $message);
      }
    }
    # Convert CR and LF to HTML BR command
    $message = preg_replace("/(\015\012)|(\015)|(\012)/","&nbsp;<br />", $message);
    # Convert smilies to images
    if ($ab_smiliesupport) {
      foreach ($smiley as $i=>$sm) {
        $message = str_replace ("$i", "<IMG src='$mosConfig_live_site/components/com_akobook/images/$sm' BORDER='0' ALT='$i'>", $message);
      }
    }
    return $message;
  }

  function GuestbookFooter() {
    global $akoversion;
    # STOP! THE REMOVAL OF THE POWERED BY LINE IS NOT ALLOWED.
    # IF YOU WANT TO REMOVE IT, CONTACT ME AT www.konze.de FOR DETAILS!
    echo "</TD></TR></TABLE><center><span class='small'>Powered by <a href='http://www.mamboportal.com/' target='_blank'>AkoBook $akoversion</a></span></center>";
    return;
  }

  function is_email($email){
    $rBool=false;
    if(preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $email)) {
      $rBool=true;
    }
    return $rBool;
  }

  function textwrap($text, $width = 75) {
   if ($text) return preg_replace("/([^\n\r ?&\.\/<>\"\\-]{".$width."})/i"," \\1\n",$text);
  }

# Check if AkoBook is offline
  if ($ab_offline == 1) {
    require($mosConfig_absolute_path."/components/com_akobook/offline.php");
  } else {

    # Needed Variables - Don't Change!
    $smiley[':)']     = "sm_smile.gif";    $smiley[':grin']  = "sm_biggrin.gif";
    $smiley[';)']     = "sm_wink.gif";     $smiley['8)']     = "sm_cool.gif";
    $smiley[':p']     = "sm_razz.gif";     $smiley[':roll']  = "sm_rolleyes.gif";
    $smiley[':eek']   = "sm_bigeek.gif";   $smiley[':upset'] = "sm_upset.gif";
    $smiley[':zzz']   = "sm_sleep.gif";    $smiley[':sigh']  = "sm_sigh.gif";
    $smiley[':?']     = "sm_confused.gif"; $smiley[':cry']   = "sm_cry.gif";
    $smiley[':(']     = "sm_mad.gif";      $smiley[':x']     = "sm_dead.gif";

    # Get the right language if it exists
    if (file_exists($mosConfig_absolute_path.'/components/com_akobook/languages/'.$mosConfig_lang.'.php')) {
      include($mosConfig_absolute_path.'/components/com_akobook/languages/'.$mosConfig_lang.'.php');
    } else {
      include($mosConfig_absolute_path.'/components/com_akobook/languages/english.php');
    }

    # Check for Editor rights
    $is_editor = (strtolower($my->usertype) == 'editor' || strtolower($my->usertype) == 'publisher' || strtolower($my->usertype) == 'manager' || strtolower($my->usertype) == 'administrator' || strtolower($my->usertype) == 'super administrator' );
    $is_user   = (strtolower($my->usertype) <> '');

    switch ($func) {
      #########################################################################################
      case 'deleteentry':
        GuestbookHeader(true);
        include('components/com_akobook/sub_deleteentry.php');
        break;
      #########################################################################################
      case 'comment':
        GuestbookHeader(true);
        include('components/com_akobook/sub_commententry.php');
        break;
      #########################################################################################
      case 'entry':
		session_start('akobookcode');
		session_register('code');
        # Clear any HTML
        $gbtext = strip_tags($gbtext);
        $gbname = strip_tags($gbname);
        $gbmail = strip_tags($gbmail);
        $gbloca = strip_tags($gbloca);
        $gbpage = strip_tags($gbpage);
        $gbvote = strip_tags($gbvote);
        $gbicq  = strip_tags($gbicq);
        $gbaim  = strip_tags($gbaim);
        $gbmsn  = strip_tags($gbmsn);
		$gbcode = strip_tags($gbcode);
        # Clear dangerous sql injections
        $gbname = mysql_escape_string($gbname);
        $gbmail = mysql_escape_string($gbmail);
        $gbloca = mysql_escape_string($gbloca);
        $gbpage = mysql_escape_string($gbpage);
        $gbvote = mysql_escape_string($gbvote);
        $gbtext = mysql_escape_string($gbtext);
        $gbicq  = mysql_escape_string($gbicq);
        $gbaim  = mysql_escape_string($gbaim);
        $gbmsn  = mysql_escape_string($gbmsn);
        # Check if entry was edited by editor
	if(isset($_SESSION['code']) && ($_SESSION['code'] != "") && ($_SESSION['code'] == $gbcode)) {
        if (($is_editor) AND ($gbid)) {
          $query1 = "UPDATE #__akobook SET gbname='$gbname', gbmail='$gbmail', gbloca='$gbloca', gbpage='$gbpage', gbvote='$gbvote', gbtext='$gbtext', gbicq='$gbicq', gbaim='$gbaim', gbmsn='$gbmsn' WHERE gbid=$gbid";
          $database->setQuery( $query1 );
          $database->query();
          echo "<SCRIPT> alert('"._GUESTBOOK_SAVED."'); document.location.href='index.php?option=com_akobook&Itemid=$Itemid';</SCRIPT>";
        } else {
          $gbdate = time();
          $gbip   = getenv('REMOTE_ADDR');
          $query2 = "INSERT INTO #__akobook SET gbname='$gbname',gbip='$gbip', gbdate='$gbdate', gbmail='$gbmail', gbloca='$gbloca', gbpage='$gbpage', gbvote='$gbvote', gbtext='$gbtext', gbicq='$gbicq', gbaim='$gbaim', gbmsn='$gbmsn'";
          if ($ab_autopublish) {
            $query2 .= ",published='1'";
          }
          $database->setQuery( $query2 );
          $database->query();
          if ($ab_notify AND is_email($ab_notify_email) ) {
            $gbmailtext = _GUESTBOOK_ADMINMAIL."\r\n\r\nName: ".$gbname."\r\nText: ".$gbtext."\r\n\r\n"._GUESTBOOK_MAILFOOTER;
            mail($ab_notify_email,_GUESTBOOK_ADMINMAILHEADER,$gbmailtext,"From: ".$ab_notify_email);
          }
          if ($ab_thankuser AND is_email($gbmail) ) {
            $gbmailtext = _GUESTBOOK_USERMAIL."\r\n\r\nName: ".$gbname."\r\nText: ".$gbtext."\r\n\r\n"._GUESTBOOK_MAILFOOTER;
            mail($gbmail,_GUESTBOOK_USERMAILHEADER,$gbmailtext,"From: ".$ab_notify_email);
          }
          echo "<SCRIPT> alert('"._GUESTBOOK_SAVED."'); document.location.href='index.php?option=com_akobook&Itemid=$Itemid';</SCRIPT>";
        }
	}else {
	echo "<SCRIPT> alert('"._GUESTBOOK_CODEWRONG."'); history.back();</SCRIPT>";
	}
        break;
      #########################################################################################
      case 'sign':
        if ($ab_allowentry) {
          GuestbookHeader(true);
          include('components/com_akobook/sub_writeentry.php');
          break;
        }
      #########################################################################################
      default:
        GuestbookHeader(false);
        # Feststellen der Anzahl der verfügbaren Datensätze
        $database->setQuery( "SELECT COUNT(gbid) AS amount FROM #__akobook WHERE published='1'" );
        $row = NULL;
        $database->loadObject( $row );
        $count = $row->amount;

        # Berechnen der Gesamtseiten
        $gesamtseiten = floor($count / $ab_perpage);
        $seitenrest   = $count % $ab_perpage;
        if ($seitenrest>0) {
         $gesamtseiten++;
        }
        # Feststellen der aktuellen Seite
        if (isset($startpage)) {
         if ($startpage>$gesamtseiten) {
           $startpage = $gesamtseiten;
         } else if ($startpage<1) {
           $startpage = 1;
         }
        } else {
         $startpage = 1;
        }
        echo "<p>"._GUESTBOOK_BEFOREENTRIES." $count "._GUESTBOOK_AFTERENTRIES."<br>";
        echo _GUESTBOOK_PAGES." ";
        # Ausgeben der Seite zurueck Funktion
        $seiterueck = $startpage - 1;
        if ($seiterueck>0) {
          echo "<a href=".sefRelToAbs("index.php?option=com_akobook&Itemid=$Itemid&startpage=$seiterueck")."><b>«</b></a> ";
          }
        #Ausgeben der einzelnen Seiten
         for ($i=1; $i <= $gesamtseiten; $i++) {
           if ($i==$startpage) {
             echo "$i ";
           } else {
             echo "<a href=".sefRelToAbs("index.php?option=com_akobook&Itemid=$Itemid&startpage=$i").">$i</a> ";
           }
         }
        # Ausgeben der Seite vorwärts Funktion
        $seitevor = $startpage + 1;
        if ($seitevor<=$gesamtseiten) {
          echo "<a href=".sefRelToAbs("index.php?option=com_akobook&Itemid=$Itemid&startpage=$seitevor")."><b>»</b></a> ";
          }
        # Limit und Seite Vor- & Rueckfunktionen
        $start = ( $startpage - 1 ) * $ab_perpage;
        echo "</p>";
        // Database Query
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='4'>";
        echo "<tr><td width='30%' height='20' class='sectiontableheader'>"._GUESTBOOK_NAME."</td>";
        echo "<td width='70%' height='20' class='sectiontableheader'>"._GUESTBOOK_ENTRY."</td></tr>";
        $line=1;

        $database->setQuery( "SELECT * FROM #__akobook"
        . "\nWHERE published = 1"
        . "\nORDER BY gbid $ab_sorting"
        . "\nLIMIT $start,$ab_perpage"
        );
        $rows = $database->loadObjectList();
        foreach ( $rows AS $row1) {
          $linecolor = ($line % 2) + 1;
          $row1->gbtext = stripslashes($row1->gbtext);
          $row1->gbname = stripslashes($row1->gbname);
          $row1->gbloca = stripslashes($row1->gbloca);
          $row1->gbname = textwrap($row1->gbname,20);
          $row1->gbloca = textwrap($row1->gbloca,30);
          echo "<tr class='sectiontableentry".$linecolor."'><td width='30%' valign='top'><a name='gb$row1->gbid'></a><b>$row1->gbname</b>";
          if ($row1->gbloca<>"" AND $ab_showloca) echo "<br /><span class='small'>"._GUESTBOOK_FROM." $row1->gbloca</span>";
          echo "</td>";
          $signtime = strftime("%c",$row1->gbdate + ($mosConfig_offset*60*60));
          $origtext = AkoParse($row1->gbtext);
          $origtext = textwrap($origtext,80);
          echo "<td width='70%' valign='top'><span class='small'>"._GUESTBOOK_SIGNEDON." $signtime<hr></span>$origtext";
          if ($row1->gbcomment<>"") {
            $origcomment = AkoParse($row1->gbcomment);
            echo "<hr><span class='small'><b>"._GUESTBOOK_ADMINSCOMMENT.":</b> $origcomment</span>";
          }
          echo "</td></tr>";
          echo "<tr class='sectiontableentry".$linecolor."'><td width='30%' valign='top'>";
          if ($row1->gbmail<>"" AND $ab_showmail) echo "<a href='mailto:$row1->gbmail'><img src='$mosConfig_live_site/components/com_akobook/images/email.gif' alt='$row1->gbmail' hspace='3' border='0'></a>";
          if ($row1->gbpage<>"" AND $ab_showhome) {
            # Check if URL is in right format
            if (substr($row1->gbpage,0,7)!="http://") $row1->gbpage="http://$row1->gbpage";
            echo "<a href='$row1->gbpage' target='_blank'><img src='$mosConfig_live_site/components/com_akobook/images/homepage.gif' alt='$row1->gbpage' hspace='3' border='0'></a>";
          }
          if ($row1->gbicq<>"" AND $ab_showicq) echo "<a href='mailto:$row1->gbicq@pager.icq.com'><img src='$mosConfig_live_site/components/com_akobook/images/icq.gif' alt='$row1->gbicq' hspace='3' border='0'></a>";
          if ($row1->gbaim<>"" AND $ab_showaim) echo "<a href='aim:goim?screenname=$row1->gbaim'><img src='$mosConfig_live_site/components/com_akobook/images/aim.gif' alt='$row1->gbaim' hspace='3' border='0'></a>";
          if ($row1->gbmsn<>"" AND $ab_showmsn) echo "<img src='$mosConfig_live_site/components/com_akobook/images/msn.gif' alt='$row1->gbmsn' hspace='3' border='0'></a>";
          if ($is_editor) echo "<img src='$mosConfig_live_site/components/com_akobook/images/ip.gif' alt='$row1->gbip' hspace='3' border='0'>";
          echo "</td>";
          echo "<td width='70%' valign='top'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
          if ($is_editor) {
            echo "<td align='left'><b>"._GUESTBOOK_ADMIN.":</b> ";
            echo "<a href='".sefRelToAbs("index.php?option=com_akobook&Itemid=$Itemid&func=sign&gbid=$row1->gbid")."'>"._GUESTBOOK_AEDIT."</a> - ";
            echo "<a href='".sefRelToAbs("index.php?option=com_akobook&Itemid=$Itemid&func=comment&gbid=$row1->gbid")."'>"._GUESTBOOK_ACOMMENT."</a> - ";
            echo "<a href='".sefRelToAbs("index.php?option=com_akobook&Itemid=$Itemid&func=deleteentry&gbid=$row1->gbid")."'>"._GUESTBOOK_ADELETE."</a></td>";
          }
          echo "<td align='right'>";
          if ($ab_showrating) {
            for($start=1;$start<=$ab_maxvoting;$start++) {
              $ratimg = $row1->gbvote>=$start ? 'rate1.gif' : 'rate0.gif';
              echo("<img src='$mosConfig_live_site/components/com_akobook/images/$ratimg'>");
            }
          }
          echo "</td></tr></table></td></tr>";
          $line++;
        }
        echo "</table>";
        break;
    }
    GuestbookFooter();

# Close Offline Tag
  }

?>
