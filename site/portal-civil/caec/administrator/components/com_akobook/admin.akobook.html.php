<?php
/**
* AkoBook - A Mambo Guestbook Component
* @version 3.4
* @package AkoBook
* @copyright (C) 2003, 2004 by Arthur Konze - All rights reserved!
* @license http://www.konze.de/content/view/8/26/ Copyrighted Commercial Software
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_guestbook {

  function showGuestbookEntries( $option, &$rows, &$search, &$pageNav ) {

    $entrylenght   = "70";
    $commentlenght = "40";

    # Table header
    ?>
    <form action="index2.php" method="post" name="adminForm">
    <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%">
        <img src="components/com_akobook/images/logo.png" align="absmiddle" style="margin-right:10px;">
        <font style="color: #FF9E31;font-size : 18px;font-weight: bold;text-align: left;">AkoBook</font>
      </td>
      <td nowrap="nowrap">Display #</td>
      <td>
        <?php echo $pageNav->writeLimitBox(); ?>
      </td>
      <td>Search:</td>
      <td>
        <input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
      </td>
    </tr>
    </table>
    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
        <th width="2%" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
        <th class="title"><div align="center">Author</div></th>
        <th class="title"><div align="left">Entry</div></th>
        <th class="title"><div align="center">Date</div></th>
        <th class="title"><div align="center">Vote</div></th>
        <th class="title"><div align="center">Comment</div></th>
        <th class="title"><div align="center">Published</div></th>
      </tr>
      <?php
    $k = 0;
    for ($i=0, $n=count( $rows ); $i < $n; $i++) {
      $row = &$rows[$i];
      echo "<tr class='row$k'>";
      echo "<td width='5%'><input type='checkbox' id='cb$i' name='gbid[]' value='$row->gbid' onclick='isChecked(this.checked);' /></td>";
      $signtime = strftime("%c",$row->gbdate);
      echo "<td align='center'><a href=\"index2.php?option=".$option."&task=edit&id=cb".$i."&gbid[]=".$row->gbid."\">$row->gbname</a></td>";
      if(strlen($row->gbtext) > $entrylenght) {
        $row->gbtext  = substr($row->gbtext,0,$entrylenght-3);
        $row->gbtext .= "...";
      }
      echo "<td align='left'>$row->gbtext</td>";
      echo "<td align='center'>$signtime</td>";
      echo "<td align='center'>$row->gbvote</td>";
      if(strlen($row->gbcomment) > $commentlenght) {
        $row->gbcomment  = substr($row->gbcomment,0,$commentlenght-3);
        $row->gbcomment .= "...";
      }
      echo "<td align='center'>";
      if ($row->gbcomment <> "") {
        echo "<img src='images/tick.png'>";
      } else {
        echo "&nbsp;";
      }
      echo "</td>";

      $task = $row->published ? 'unpublish' : 'publish';
      $img = $row->published ? 'publish_g.png' : 'publish_x.png';
      ?>
        <td width="10%" align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a></td>
    </tr>
    <?php    $k = 1 - $k; } ?>
    <tr>
      <th align="center" colspan="7">
        <?php echo $pageNav->writePagesLinks(); ?></th>
    </tr>
    <tr>
      <td align="center" colspan="7">
        <?php echo $pageNav->writePagesCounter(); ?></td>
    </tr>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  </form>
<?php
}

function editGuestbook( $option, &$row, &$publist ) {
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
      // do field validation
      if (form.gbname.value == ""){
        alert( "Entry must have an author." );
      } else if (form.gbtext.value == ""){
        alert( "Entry should have some message." );
      } else {
        submitform( pressbutton );
      }
    }
    </script>

    <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%">
        <img src="components/com_akobook/images/logo.png" align="absmiddle" style="margin-right:10px;">
        <font style="color: #FF9E31;font-size : 18px;font-weight: bold;text-align: left;">AkoBook</font>
      </td>
    </tr>
    </table>
    <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
    <form action="index2.php" method="post" name="adminForm" id="adminForm">
      <tr>
        <th colspan="2" class="title" >
          <?php echo $row->gbid ? 'Edit' : 'Add';?> Guestbook Entry
        </th>
      </tr>
      <tr>
        <td width="20%" align="right">Name:</td>
        <td width="80%">
          <input class="inputbox" type="text" name="gbname" size="50" maxlength="100" value="<?php echo htmlspecialchars( $row->gbname, ENT_QUOTES );?>" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">E-Mail:</td>
        <td>
          <input class="inputbox" type="text" name="gbmail" value="<?php echo $row->gbmail; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Homepage:</td>
        <td>
          <input class="inputbox" type="text" name="gbpage" value="<?php echo $row->gbpage; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Location:</td>
        <td>
          <input class="inputbox" type="text" name="gbloca" value="<?php echo $row->gbloca; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">ICQ:</td>
        <td>
          <input class="inputbox" type="text" name="gbicq" value="<?php echo $row->gbicq; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">AIM:</td>
        <td>
          <input class="inputbox" type="text" name="gbaim" value="<?php echo $row->gbaim; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">MSN:</td>
        <td>
          <input class="inputbox" type="text" name="gbmsn" value="<?php echo $row->gbmsn; ?>" size="50" maxlength="100" />
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Vote:</td>
        <td>
          <select style='width:100px;' class='inputbox' size='1' name='gbvote'>
            <?php
      $selitem = $row->gbvote ? $row->gbvote : floor($ab_maxvoting/2) + 1;
      for ($i=1; $i<=$ab_maxvoting; $i++) {
        echo "<option";
        if ($i==$selitem) echo " selected";
        echo ">$i</option>";
      }
            ?>
          </select>
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Entry:</td>
        <td>
          <textarea class="inputbox" cols="50" rows="6" name="gbtext" style="width=500px" width="500"><?php echo htmlspecialchars( $row->gbtext, ENT_QUOTES );?></textarea>
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Comment:</td>
        <td>
          <textarea class="inputbox" cols="50" rows="3" name="gbcomment" style="width=500px" width="500"><?php echo htmlspecialchars( $row->gbcomment, ENT_QUOTES );?></textarea>
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Published:</td>
        <td>
          <?php echo $publist; ?>
        </td>
      </tr>

    </table>

    <input type="hidden" name="gbdate" value="<?php echo time(); ?>" />
    <input type="hidden" name="gbid" value="<?php echo $row->gbid; ?>" />
    <input type="hidden" name="option" value="<?php echo $option;?>" />
    <input type="hidden" name="task" value="" />
    </form>
<?php
  }

function showAbout() {
?>
    <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%">
        <img src="components/com_akobook/images/logo.png" align="absmiddle" style="margin-right:10px;">
        <font style="color: #FF9E31;font-size : 18px;font-weight: bold;text-align: left;">AkoBook</font>
      </td>
    </tr>
    <tr>
      <td>
        <p><b>Program</b><br>
        AkoBook was one of the first guestbook solutions for Mambo. It comes with a lot
        features like support for user votes, locations and all major pagers. It has been
        translated into many different languages and is currently the most used guestbook
        for Mambo. If you have any wishes or have found a bug, please contact the author by
        mail: <a href="mailto:webmaster@mamboportal.com">webmaster@mamboportal.com</a></p>
        <p><b>Author</b><br>
        Arthur Konze is one of the early eighties home computer hackers. He started with
        assembler coding on homecomputers like the Apple 2 and the Commodore C16. A few
        years later he get in touch with modem based computer networks like fido. He
        started with Internet in 1989 and concentrated on webdesign after the boom years.
        Currently he is the publisher of Mamboportal.com, which is one of the biggest
        Mambo communities worldwide.</p>
        <p><b>Warranty</b><br>
        This program is distributed in the hope that it will be useful, but WITHOUT ANY
        WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
        PARTICULAR PURPOSE.<P>
      </td>
    </tr>
    </table>
<?php
  }

function showLanguage($file, $option) {
  $file = stripslashes($file);
  $f=fopen($file,"r");
  $content = fread($f, filesize($file));
  $content = htmlspecialchars($content);
  ?>
  <form action="index2.php?" method="post" name="adminForm" class="adminForm" id="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%">
        <img src="components/com_akobook/images/logo.png" align="absmiddle" style="margin-right:10px;">
        <font style="color: #FF9E31;font-size : 18px;font-weight: bold;text-align: left;">AkoBook</font>
      </td>
    </tr>
  </table>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
     <tr>
       <th colspan="4">Path: <?php echo $file; ?></td> </tr>
     <tr>
       <td> <textarea cols="80" rows="20" name="filecontent"><?php echo $content; ?></textarea>
       </td>
     </tr>
     <tr>
       <td class="error">Please note: The file must be writable to save your changes.</td>
     </tr>
  </table>
  <input type="hidden" name="file" value="<?php echo $file; ?>" />
  <input type="hidden" name="option" value="<?php echo $option; ?>">
  <input type="hidden" name="task" value="">
  <input type="hidden" name="boxchecked" value="0">
  </form>
 <?

}//end function showCss


# End of class
}
?>