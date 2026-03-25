<?php
/**
* AkoBook - A Mambo Guestbook Component
* @version 3.4
* @package AkoBook
* @copyright (C) 2003, 2004 by Arthur Konze - All rights reserved!
* @license http://www.konze.de/content/view/8/26/ Copyrighted Commercial Software
**/

# Don't allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

# Don't allow passed settings
if ($_REQUEST['is_editor']) {
  print "<SCRIPT>document.location.href='../../index.php'</SCRIPT>\n";
  exit();
}

# Main Part of Subfunction
  if ($is_editor){
    if ($submit) {
      $query1 = "DELETE FROM #__akobook WHERE gbid = $gbid";
      $database->setQuery( $query1 );
      $database->query();
      echo "<SCRIPT> alert('"._GUESTBOOK_DELMESSAGE."'); document.location.href='index.php?option=com_akobook&Itemid=$Itemid';</SCRIPT>";
    } else {
      $database->setQuery( "SELECT * FROM #__akobook WHERE gbid = $gbid" );
      $row = NULL;
      $database->loadObject( $row );
      #Show the Original Entry
      echo "<table width='100%' border='0' cellspacing='1' cellpadding='4'>";
      echo "<tr><td width='30%' height='20' class='sectiontableheader'>"._GUESTBOOK_NAME."</td>";
      echo "<td width='70%' height='20' class='sectiontableheader'>"._GUESTBOOK_ENTRY."</td></tr>";
      echo "<tr class='sectiontableentry1'><td width='30%' valign='top'><b>$row->gbname</b>";
      if ($row->gbloca<>"") echo "<br /><span class='small'>"._GUESTBOOK_FROM." $row->gbloca</span>";
      echo "</td>";
      $signtime = strftime("%c",$row->gbdate);
      $origtext = preg_replace("/(\015\012)|(\015)|(\012)/","&nbsp;<br />", $row->gbtext);
      foreach ($smiley as $i=>$sm) {
        $origtext = str_replace ("$i", "<IMG SRC='components/com_akobook/images/$sm' BORDER='0' ALT='$i'>", $origtext);
      }
      echo "<td width='70%' valign='top'><span class='small'>"._GUESTBOOK_SIGNEDON." $signtime<hr></span>$origtext</td></tr>";
      echo "<tr class='sectiontableentry1'><td width='30%' valign='top'>";
      if ($row->gbmail<>"") echo "<a href='mailto:$row1->gbmail'><img src='components/com_akobook/images/email.gif' alt='$row->gbmail' hspace='3' border='0'></a>";
      if ($row->gbpage<>"") echo "<a href='$row1->gbpage' target='_blank'><img src='components/com_akobook/images/homepage.gif' alt='$row->gbpage' hspace='3' border='0'></a>";
      if ($row->gbicq<>"") echo "<a href='mailto:$row->gbicq@pager.icq.com'><img src='$mosConfig_live_site/components/com_akobook/images/icq.gif' alt='$row->gbicq' hspace='3' border='0'></a>";
      if ($row->gbaim<>"") echo "<a href='aim:goim?screenname=$row->gbaim'><img src='$mosConfig_live_site/components/com_akobook/images/aim.gif' alt='$row->gbaim' hspace='3' border='0'></a>";
      if ($row->gbmsn<>"") echo "<img src='$mosConfig_live_site/components/com_akobook/images/msn.gif' alt='$row->gbmsn' hspace='3' border='0'></a>";
      echo "<img src='components/com_akobook/images/ip.gif' alt='$row->gbip' hspace='3' border='0'>";
      echo "</td><td width='70%' valign='top'>";
      if ($ab_showrating) {
        for($start=1;$start<=$ab_maxvoting;$start++) {
          $ratimg = $row->gbvote>=$start ? 'rate1.gif' : 'rate0.gif';
          echo("<img src='$mosConfig_live_site/components/com_akobook/images/$ratimg'>");
        }
      }
      echo "</td></tr></table>";
      echo "<FORM METHOD=POST ACTION='index.php?option=com_akobook&Itemid=$Itemid&func=deleteentry&gbid=$row->gbid'>";
      echo "<INPUT CLASS='button' TYPE='submit' NAME='submit' VALUE='"._GUESTBOOK_DELENTRY."'></FORM>";
    }
  } else {
    echo "<p><a href='index.php?option=com_akobook&Itemid=$Itemid'>Back</a>";
  }
?>