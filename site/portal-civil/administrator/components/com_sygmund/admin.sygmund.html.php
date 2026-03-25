<?php
##########################################
# SygMund  -  Professor Component        #
# Copyright (C) 2005  by  SygMund        #
# Homepage   : www.mamboportal.com       #
# Version    : 1.0 beta 6                #
# License    : GNU GPL Public License    #
##########################################

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_sygmund {

  function showStaff( $option, &$rows, &$clist, &$search, &$pageNav ) {
    global $my;
?>
    <form action="index2.php" method="post" name="adminForm">
    <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%" class="sectionname">
        <img src="components/com_sygmund/images/logo.png">
      </td>
      <td nowrap="nowrap">Mostrar:</td>
      <td>
        <?php echo $pageNav->writeLimitBox(); ?>
      </td>
      <td>Procurar:</td>
      <td>
        <input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
      </td>
      <td width="right">
        <?php echo $clist;?>
      </td>
    </tr>
    </table>

    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
        <th width="20">
          <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
        </th>
        <th class="title" width="25%" nowrap="nowrap">Nome</th>
        <th width="25%" align="left" nowrap="nowrap">Cargo</th>
        <th width="25%" align="left" nowrap="nowrap">E-Mail</th>
        <th width="25%" align="left" nowrap="nowrap">Categoria</th>
        <th width="10%" nowrap="nowrap">Publicar</th>
        <th colspan="2">Reordenar</th>
      </tr>
<?php
    $k = 0;
    for ($i=0, $n=count( $rows ); $i < $n; $i++) {
      $row = &$rows[$i];
?>
      <tr class="<?php echo "row$k"; ?>">
        <td width="20">
          <input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" />
        </td>
        <td width="25%">
          <a href="#edit" onclick="return listItemTask('cb<?php echo $i;?>','edit')">
            <?php echo $row->name; ?>
          </a>
        </td>
        <td width="25%"><?php echo $row->position; ?></td>
        <td width="25%"><a href="mailto:<?php echo $row->email; ?>"><?php echo $row->email; ?></a></td>
        <td width="25%"><?php echo $row->category; ?></td>
        <?php
          $task = $row->published ? 'unpublish' : 'publish';
          $img = $row->published ? 'publish_g.png' : 'publish_x.png';
        ?>
        <td width="10%" align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a></td>
        <td>
        <?php if (($i > 0 || ($i+$pageNav->limitstart > 0)) && $row->catid == @$rows[$i-1]->catid) { ?>
        <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderup')">
        <img src="images/uparrow.png" width="12" height="12" border="0" alt="Move Up">
        </a>
        <?php    } else { echo "&nbsp;"; } ?>
      </td>
      <td>
      <?php    if (($i < $n-1 || $i+$pageNav->limitstart < $pageNav->total-1) && $row->catid == @$rows[$i+1]->catid) { ?>
        <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderdown')">
        <img src="images/downarrow.png" width="12" height="12" border="0" alt="Move Down">
        </a>
        <?php    } else { echo "&nbsp;"; } ?>
      </td>
      <?php    $k = 1 - $k; } ?>
      </tr>
      <tr>
        <th align="center" colspan="9">
          <?php echo $pageNav->writePagesLinks(); ?></th>
      </tr>
      <tr>
        <td align="center" colspan="9">
          <?php echo $pageNav->writePagesCounter(); ?></td>
      </tr>
    </table>
    <input type="hidden" name="option" value="<?php echo $option;?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    </form>
<?php
}

##############################################################################

function editStaff( $option, &$row, &$clist, &$olist, &$imagelist, &$statuslist ) {
?>
<!-- import the calendar script -->
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
      if (form.name.value == ""){
        alert( "Staffmember must have a name" );
      } else if (form.catid.value == "0"){
        alert( "You must select a category." );
      } else if (form.position.value == ""){
        alert( "Staffmember should have a position" );
      } else {
        submitform( pressbutton );
      }
    }
    </script>
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
        <img src="components/com_sygmund/images/logo.png">
    </tr>
  </table>

  <form action="index2.php" method="post" name="adminForm" id="adminForm">

  <script language="javascript" src="js/dhtml.js"></script>
  <table cellpadding="3" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="" class="tabpadding">&nbsp;</td>
      <td id="tab1" class="offtab" onclick="dhtml.cycleTab(this.id)">Informações Pessoais</td>
      <td id="tab2" class="offtab" onclick="dhtml.cycleTab(this.id)">Informações de Contato</td>
      <td id="tab3" class="offtab" onclick="dhtml.cycleTab(this.id)">Biografia</td>
      <td id="tab4" class="offtab" onclick="dhtml.cycleTab(this.id)">Informações de Status</td>
      <td width="90%" class="tabpadding">&nbsp;</td>
    </tr>
  </table>

  <div id="page1" class="pagetext">
    <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
      <tr>
        <td width="20%" align="right">Nome:</td>
        <td width="80%">
          <input class="inputbox" type="text" name="name" size="50" maxlength="100" value="<?php echo htmlspecialchars( $row->name, ENT_QUOTES );?>" />
        </td>
        <td rowspan="5" valign="top">
        <?php
          if ($row->picture) {
            $jsimg = "../images/stories/$row->picture";
          } else {
            $jsimg="../images/M_images/blank.png";
          }
          echo "<img src='$jsimg' name='imagelib2' width='80' height='80' border='2' alt='Picture Preview' />";
        ?>
        </td>
      </tr>
      <tr>
        <td width="20%" align="right">Cargo:</td>
        <td width="80%">
          <input class="inputbox" type="text" name="position" size="50" maxlength="100" value="<?php echo htmlspecialchars( $row->position, ENT_QUOTES );?>" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Categoria:</td>
        <td>
          <?php echo $clist; ?>
        </td>
      </tr>
      <tr>
        <td width="20%" align="right">Disciplina:</td>
        <td width="80%">
          <input class="inputbox" type="text" name="nick" size="50" maxlength="100" value="<?php echo htmlspecialchars( $row->nick, ENT_QUOTES );?>" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Idade:</td>
        <td>
          <input class="inputbox" type="text" name="age" value="<?php echo $row->age; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Ícone:</td>
        <td>
          <?php echo $imagelist; ?>
        </td>
      </tr>
    </table>
  </div>
  <div id="page2" class="pagetext">
    <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
      <tr>
        <td valign="top" align="right">Telefone:</td>
        <td>
          <input class="inputbox" type="text" name="tel" value="<?php echo $row->tel; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Fax:</td>
        <td>
          <input class="inputbox" type="text" name="fax" value="<?php echo $row->fax; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">E-Mail:</td>
        <td>
          <input class="inputbox" type="text" name="email" value="<?php echo $row->email; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Website:</td>
        <td>
          <input class="inputbox" type="text" name="website" value="<?php echo $row->website; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Endereço:</td>
        <td>
          <input class="inputbox" type="text" name="place" value="<?php echo $row->place; ?>" size="50" maxlength="100" />
        </td>
      </tr>
    </table>
  </div>
  <div id="page3" class="pagetext">
    <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
      <tr>
        <td valign="top" align="right">Biografia:</td>
        <td>
          <textarea class="inputbox" id="biography" name="biography" cols="70" rows="15" style="width:500; height:200"><?php echo htmlspecialchars( $row->biography, ENT_QUOTES );?></textarea>
        </td>
      </tr>
    </table>
  </div>
  <div id="page4" class="pagetext">
    <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
      <tr>
        <td valign="top" align="right">Status:</td>
        <td>
          <?php echo "$statuslist"; ?>
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Staff In:</td>
        <td>
          <input class="inputbox" type="text" name="staff_in" id="staff_in" value="<?php echo $row->staff_in; ?>" size="46" maxlength="100" />
          <input type="reset" class="button" value="..." onClick="return showCalendar('staff_in', 'y-mm-dd');">
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Staff Out:</td>
        <td>
          <input class="inputbox" type="text" name="staff_out" id="staff_out" value="<?php echo $row->staff_in; ?>" size="46" maxlength="100" />
          <input type="reset" class="button" value="..." onClick="return showCalendar('staff_out', 'y-mm-dd');">
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Ordenar por:</td>
        <td>
          <?php echo $olist; ?>
        </td>
      </tr>

    </table>
  </div>
  <script language="javascript" type="text/javascript">dhtml.cycleTab('tab1');</script>

    <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
    <input type="hidden" name="option" value="<?php echo $option;?>" />
    <input type="hidden" name="task" value="" />
    </form>




  <script language="JavaScript1.2" defer="defer">
    editor_generate('biography');
  </script>
<?php
  }
}
?>
