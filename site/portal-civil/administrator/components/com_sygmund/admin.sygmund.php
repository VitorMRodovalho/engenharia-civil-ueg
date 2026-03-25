<?php
##########################################
# SygMund  -  Professor Component        #
# Copyright (C) 2005  by  SygMund        #
# Homepage   : www.mamboportal.com       #
# Version    : 1.0 beta 6                #
# License    : GNU GPL Public License    #
##########################################

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
require_once( $mosConfig_absolute_path."/administrator/components/com_sygmund/class.sygmund.php");
require_once( $mainframe->getPath( 'admin_html' ) );

$cid = mosGetParam( $_POST, 'cid', array(0) );

switch ($act) {
  case "settings":
    $task = "settings";
    break;
}

switch ($task) {
  case "new":
    editStaff( $option, 0 );
    break;

  case "edit":
    editStaff( $option, $cid[0] );
    break;

  case "save":
    saveStaff( $option );
    break;

  case "remove":
    removeStaff( $cid, $option );
    break;

  case "publish":
    publishStaff( $cid, 1, $option );
    break;

  case "unpublish":
    publishStaff( $cid, 0, $option );
    break;

  case "cancel":
    cancelStaff( $option );
    break;

  case "orderup":
    orderStaff( $cid[0], -1, $option );
    break;

  case "orderdown":
    orderStaff( $cid[0], 1, $option );
    break;

  case "settings":
    showConfig( $option );
    break;

  case "settings":
    showConfig( $option );
    break;

  case "savesettings":
    saveConfig ($option, $as_showphone, $as_showfax, $as_showmail, $as_showwebsite, $as_showlocation, $as_showstatus, $as_shownick, $as_showbiolink, $as_showpicture, $as_showage, $as_showlist);
    break;

  default:
    showStaff( $option );
    break;
}

echo "<br /><b>SygMund 1.0 beta 6</b><br />&copy Copyright 2003 by <a href='http://www.oton.pro.br' target='_blank'>SygMund</a>";

# Main functions start here ##################################################

function showStaff( $option ) {
  global $database, $mainframe;

  $catid = $mainframe->getUserStateFromRequest( "catid{$option}", 'catid', 0 );
  $limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 10 );
  $limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );
  $search = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
  $search = $database->getEscaped( trim( strtolower( $search ) ) );

  $where = array();

  if ($catid > 0) {
    $where[] = "a.catid='$catid'";
  }
  if ($search) {
    $where[] = "LOWER(a.name) LIKE '%$search%'";
  }

  # Get the total number of records
  $database->setQuery( "SELECT count(*) FROM #__sygmund AS a"
    . (count( $where ) ? "\nWHERE " . implode( ' AND ', $where ) : "")
  );
  $total = $database->loadResult();
  echo $database->getErrorMsg();

  include_once( "includes/pageNavigation.php" );
  $pageNav = new mosPageNav( $total, $limitstart, $limit  );

  $database->setQuery( "SELECT a.*, cc.name AS category"
    . "\nFROM #__sygmund AS a"
    . "\nLEFT JOIN #__categories AS cc ON cc.id = a.catid"
    . (count( $where ) ? "\nWHERE " . implode( ' AND ', $where ) : "")
    . "\nORDER BY a.catid, a.ordering"
    . "\nLIMIT $pageNav->limitstart,$pageNav->limit"
  );

  $rows = $database->loadObjectList();
  if ($database->getErrorNum()) {
    echo $database->stderr();
    return false;
  }

  # Get list of categories
  $categories[] = mosHTML::makeOption( '0', 'Selecione Categoria' );
  $categories[] = mosHTML::makeOption( '-1', '- All Categories' );
  $database->setQuery( "SELECT id AS value, title AS text FROM #__categories"
    . "\nWHERE section='com_sygmund' ORDER BY ordering" );
  $categories = array_merge( $categories, $database->loadObjectList() );

  $clist = mosHTML::selectList( $categories, 'catid', 'class="inputbox" size="1" onchange="document.adminForm.submit();"',
    'value', 'text', $catid );

  HTML_sygmund::showStaff( $option, $rows, $clist, $search, $pageNav );
}

##############################################################################

function editStaff( $option, $uid ) {
  global $database, $my, $mosConfig_absolute_path, $mosConfig_live_site;

  $row = new mosAkostaff( $database );
  # Load the row from the db table
  $row->load( $uid );

  # Get list of categories
  $categories[] = mosHTML::makeOption( '0', 'Selecione Categoria' );
  $database->setQuery( "SELECT id AS value, name AS text FROM #__categories"
    . "\nWHERE section='$option' ORDER BY ordering" );
  $categories = array_merge( $categories, $database->loadObjectList() );

  if (count( $categories ) < 1) {
    mosRedirect( "index2.php?option=categories&section=$option",
      'You must add a category for this section first.' );
  }

  $clist = mosHTML::selectList( $categories, 'catid', 'class="inputbox" size="1"',
    'value', 'text', intval( $row->catid ) );

  if (!$uid) {
    # Initialise new record
    $row->published = 0;
  }

  # Build the html select list for ordering
  $order = mosGetOrderingList( "SELECT ordering AS value, name AS text"
    . "\nFROM #__sygmund"
    . "\nWHERE catid='$row->catid' ORDER BY ordering"
  );
  $olist = mosHTML::selectList( $order, 'ordering', 'class="inputbox" size="1"',
    'value', 'text', intval( $row->ordering ) );

  # Create Picture List
  $imgFiles = mosReadDirectory( "$mosConfig_absolute_path/images/stories" );
  $images = array( mosHTML::makeOption( '', 'Select Image') );
  foreach ($imgFiles as $file) {
    if (eregi( "bmp|gif|jpg|png", $file )) {
      $images[] = mosHTML::makeOption( $file );
    }
  }
  $imagelist = mosHTML::selectList( $images, 'picture', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.forms[0].picture.options[selectedIndex].value!='') {document.imagelib2.src='../images/stories/' + document.forms[0].picture.options[selectedIndex].value} else {document.imagelib2.src='../images/M_images/blank.png'}\"",
  'value', 'text', $row->picture );

  $stati[] = mosHTML::makeOption( '0', 'Ativo' );
  $stati[] = mosHTML::makeOption( '1', 'Inativo' );
  $stati[] = mosHTML::makeOption( '2', 'Retirado' );
  $stati[] = mosHTML::makeOption( '3', 'Honour-half' );
  #$stati = array ("Active","Inactive","Retired","Honour-half");
  $statuslist = mosHTML::selectList( $stati, 'status', 'class="inputbox" size="1"', 'value', 'text', intval( $row->status ) );

  HTML_sygmund::editStaff( $option, $row, $clist, $olist, $imagelist, $statuslist );
}

##############################################################################

function saveStaff( $option ) {
  global $database, $my;

  $row = new mosAkostaff( $database );
  if (!$row->bind( $_POST )) {
    echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
    exit();
  }

  #$row->date = date( "Y-m-d H:i:s" );
  if (!$row->store()) {
    echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
    exit();
  }
  $row->updateOrder( "catid='$row->catid'" );

  mosRedirect( "index2.php?option=$option" );
}

##############################################################################

function removeStaff( $cid, $option ) {
  global $database;
  if (!is_array( $cid ) || count( $cid ) < 1) {
    echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
    exit;
  }
  if (count( $cid )) {
    $cids = implode( ',', $cid );
    $database->setQuery( "DELETE FROM #__sygmund WHERE id IN ($cids)" );
    if (!$database->query()) {
      echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
    }
  }
  mosRedirect( "index2.php?option=$option" );
}

##############################################################################

function publishStaff( $cid=null, $publish=1,  $option ) {
  global $database, $my;
  $catid = mosGetParam( $_POST, 'catid', array(0) );
  if (!is_array( $cid ) || count( $cid ) < 1) {
    $action = $publish ? 'publish' : 'unpublish';
    echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
    exit;
  }
  $cids = implode( ',', $cid );
  $database->setQuery( "UPDATE #__sygmund SET published='$publish'WHERE id IN ($cids)" );
  if (!$database->query()) {
    echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
    exit();
  }
  mosRedirect( "index2.php?option=$option" );
}

##############################################################################

function orderStaff( $uid, $inc, $option ) {
  global $database;
  $row = new mosAkostaff( $database );
  $row->load( $uid );
  $row->move( $inc, "published >= 0" );
  mosRedirect( "index2.php?option=$option" );
}

##############################################################################

function cancelStaff( $option ) {
  mosRedirect( "index2.php?option=$option" );
}

##############################################################################

function showConfig( $option ) {
  global $mosConfig_absolute_path;
  require($mosConfig_absolute_path."/administrator/components/com_sygmund/config.sygmund.php");

  $yesno[] = mosHTML::makeOption( '0', 'Não' );
  $yesno[] = mosHTML::makeOption( '1', 'Sim' );
  $listdisp[] = mosHTML::makeOption( '0', 'Ver Cartão' );
  $listdisp[] = mosHTML::makeOption( '1', 'Ver Lista' );
  $listdisp[] = mosHTML::makeOption( '2', 'Ver Categoria' );
  $namdisp[] = mosHTML::makeOption( '0', 'Somente Nome' );
  $namdisp[] = mosHTML::makeOption( '1', 'Somente Diciplina' );
  $namdisp[] = mosHTML::makeOption( '2', 'Nome (Disciplina)' );
  $namdisp[] = mosHTML::makeOption( '3', 'Disciplina (Nome)' );
?>
    <script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
      var form = document.adminForm;
      if (pressbutton == 'cancel') {
        submitform( pressbutton );
        return;
      }
      if (form.as_showphone.value == ""){
        alert( "Please check phones!" );
      } else {
        submitform( pressbutton );
      }
    }
    </script>

  <form action="index2.php" method="POST" name="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
  <tr>
    <td width="100%" class="sectionname">
        <img src="components/com_sygmund/images/logo.png">
    </td>
  </tr>
  </table>

<script language="javascript" src="js/dhtml.js"></script>
<table cellpadding="3" cellspacing="0" border="0" width="100%">
  <tr>
    <td width="" class="tabpadding">&nbsp;</td>
    <td id="tab1" class="offtab" onclick="dhtml.cycleTab(this.id)">Comunicação</td>
    <td id="tab2" class="offtab" onclick="dhtml.cycleTab(this.id)">Miscelânea</td>
    <td id="tab3" class="offtab" onclick="dhtml.cycleTab(this.id)">Mostrar</td>
    <td width="90%" class="tabpadding">&nbsp;</td>
  </tr>
</table>

  <div id="page1" class="pagetext">
  <table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminForm">
    <TR>
      <td class="sectionname" colspan="3"><div align="center">Configurações de Comunicação</div></td>
    </TR>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo "Mostrar Fone:" ?></strong></td>
      <td align="left" valign="top">
      <?php
        $yn_as_showphone = mosHTML::selectList( $yesno, 'as_showphone', 'class="inputbox" size="2"', 'value', 'text', $as_showphone );
        echo $yn_as_showphone;
      ?>
      </td>
      <td align="left" valign="top"><?php echo "Mostrar número de telefone." ?></td>
    </tr>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo "Mostrar Fax:" ?></strong></td>
      <td align="left" valign="top">
      <?php
        $yn_as_showfax = mosHTML::selectList( $yesno, 'as_showfax', 'class="inputbox" size="2"', 'value', 'text', $as_showfax );
        echo $yn_as_showfax;
      ?>
      </td>
      <td align="left" valign="top"><?php echo "Mostrar número do Fax." ?></td>
    </tr>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo "Mostrar Email:" ?></strong></td>
      <td align="left" valign="top">
      <?php
        $yn_as_showmail = mosHTML::selectList( $yesno, 'as_showmail', 'class="inputbox" size="2"', 'value', 'text', $as_showmail );
        echo $yn_as_showmail;
      ?>
      </td>
      <td align="left" valign="top"><?php echo "Mostrar endereço de e-mail." ?></td>
    </tr>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo "Mostrar Website:" ?></strong></td>
      <td align="left" valign="top">
      <?php
        $yn_as_showwebsite = mosHTML::selectList( $yesno, 'as_showwebsite', 'class="inputbox" size="2"', 'value', 'text', $as_showwebsite );
        echo $yn_as_showwebsite;
      ?>
      </td>
      <td align="left" valign="top"><?php echo "Mostrar Endereço do website." ?></td>
    </tr>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo "Mostrar Endereço:" ?></strong></td>
      <td align="left" valign="top">
      <?php
        $yn_as_showlocation = mosHTML::selectList( $yesno, 'as_showlocation', 'class="inputbox" size="2"', 'value', 'text', $as_showlocation );
        echo $yn_as_showlocation;
      ?>
      </td>
      <td align="left" valign="top"><?php echo "Mostrar Endereço." ?></td>
    </tr>
  </table>
  </div>
  <!-- PAGE 2 -->
  <div id="page2" class="pagetext">
  <table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminForm">
    <TR>
      <td class="sectionname" colspan="3"><div align="center">Configurações de Miscelânea</div></td>
    </TR>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo "Mostrar Status:" ?></strong></td>
      <td align="left" valign="top">
      <?php
        $yn_as_showstatus = mosHTML::selectList( $yesno, 'as_showstatus', 'class="inputbox" size="2"', 'value', 'text', $as_showstatus );
        echo $yn_as_showstatus;
      ?>
      </td>
      <td align="left" valign="top"><?php echo "Mostrar status ativo." ?></td>
    </tr>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo "Mostrar Biografia:" ?></strong></td>
      <td align="left" valign="top">
      <?php
        $yn_as_showbiolink = mosHTML::selectList( $yesno, 'as_showbiolink', 'class="inputbox" size="2"', 'value', 'text', $as_showbiolink );
        echo $yn_as_showbiolink;
      ?>
      </td>
      <td align="left" valign="top"><?php echo "Mostrar link para biografia." ?></td>
    </tr>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo "Mostrar Imagem:" ?></strong></td>
      <td align="left" valign="top">
      <?php
        $yn_as_showpicture = mosHTML::selectList( $yesno, 'as_showpicture', 'class="inputbox" size="2"', 'value', 'text', $as_showpicture );
        echo $yn_as_showpicture;
      ?>
      </td>
      <td align="left" valign="top"><?php echo "Mostrar Imagem do cadastrado." ?></td>
    </tr>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo "Mostrar Idade:" ?></strong></td>
      <td align="left" valign="top">
      <?php
        $yn_as_showage = mosHTML::selectList( $yesno, 'as_showage', 'class="inputbox" size="2"', 'value', 'text', $as_showage );
        echo $yn_as_showage;
      ?>
      </td>
      <td align="left" valign="top"><?php echo "Mostrar idade do professor." ?></td>
    </tr>
  </table>
  </div>
  <!-- PAGE 3 -->
  <div id="page3" class="pagetext">
  <table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminForm">
    <TR>
      <td class="sectionname" colspan="3"><div align="center">Configurações de Apresentação</div></td>
    </TR>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo "Main Display:" ?></strong></td>
      <td align="left" valign="top">
      <?php
        $yn_as_showlist = mosHTML::selectList( $listdisp, 'as_showlist', 'class="inputbox" size="3"', 'value', 'text', $as_showlist );
        echo $yn_as_showlist;
      ?>
      </td>
      <td align="left" valign="top"><?php echo "Como o layout principal deve parecer." ?></td>
    </tr>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo "Mostrar Apelido - Nome:" ?></strong></td>
      <td align="left" valign="top">
      <?php
        $yn_as_shownick = mosHTML::selectList( $namdisp, 'as_shownick', 'class="inputbox" size="4"', 'value', 'text', $as_shownick );
        echo $yn_as_shownick;
      ?>
      </td>
      <td align="left" valign="top"><?php echo "Como deve aparecer o nome - disciplina do professor ." ?></td>
    </tr>
  </table>
  </div>

  <script language="javascript" type="text/javascript">dhtml.cycleTab('tab1');</script>
  <input type="hidden" name="option" value="<?php echo $option; ?>">
  <input type="hidden" name="task" value="">
</form>
<?php
}

##############################################################################

function saveConfig ($option, $as_showphone, $as_showfax, $as_showmail, $as_showwebsite, $as_showlocation, $as_showstatus, $as_shownick, $as_showbiolink, $as_showpicture, $as_showage, $as_showlist) {
  $configfile = "components/com_sygmund/config.sygmund.php";
  @chmod ($configfile, 0766);
  $permission = is_writable($configfile);
  if (!$permission) {
    $mosmsg = "Config file not writeable!";
    mosRedirect("index2.php?option=$option&act=settings",$mosmsg);
    break;
  }
  $config = "<?php\n";
  $config .= "\$as_showphone = \"$as_showphone\";\n";
  $config .= "\$as_showfax = \"$as_showfax\";\n";
  $config .= "\$as_showmail = \"$as_showmail\";\n";
  $config .= "\$as_showwebsite = \"$as_showwebsite\";\n";
  $config .= "\$as_showlocation = \"$as_showlocation\";\n";
  $config .= "\$as_showstatus = \"$as_showstatus\";\n";
  $config .= "\$as_shownick = \"$as_shownick\";\n";
  $config .= "\$as_showbiolink = \"$as_showbiolink\";\n";
  $config .= "\$as_showpicture = \"$as_showpicture\";\n";
  $config .= "\$as_showage = \"$as_showage\";\n";
  $config .= "\$as_showlist = \"$as_showlist\";\n";
  $config .= "?>";
  if ($fp = fopen("$configfile", "w")) {
    fputs($fp, $config, strlen($config));
    fclose ($fp);
  }
  mosRedirect("index2.php?option=$option&task=settings", "Settings saved");
}

?>
