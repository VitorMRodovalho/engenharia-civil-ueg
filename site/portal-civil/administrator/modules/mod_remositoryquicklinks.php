<?php
// Remository Quick Links v1.1.0
// Benjamin Searles
// Based on JosXP.com WebSite Validator Tool Module

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$manage_containers					= $params->def( 'manage_containers', 		1 );
$manage_files						= $params->def( 'manage_files', 			1 );
$manage_groups						= $params->def( 'manage_groups', 			1 );
$approve_uploads					= $params->def( 'approve_uploads', 			1 );
$configuration						= $params->def( 'configuration', 			1 );
$handle_unlinked					= $params->def( 'handle_unlinked', 			1 );
$bulk_add							= $params->def( 'bulk_add', 				1 );
$add_structure						= $params->def( 'add_structure', 			1 );
$list_missing						= $params->def( 'list_missing', 			1 );
$recalculate_counts					= $params->def( 'recalculate_counts', 		1 );
$reset_counts						= $params->def( 'reset_counts', 			1 );
$remove_logs						= $params->def( 'remove_logs', 				1 );
$check_thumbnails					= $params->def( 'check_thumbnails', 		1 );
$convert_320						= $params->def( 'convert_320', 				1 );
$convert_340						= $params->def( 'convert_340', 				1 );
$statistics							= $params->def( 'statistics', 				1 );
$about_remository					= $params->def( 'about_remository', 		1 );
$support							= $params->def( 'support', 					1 );

	$validator = array();
	$site = $mosConfig_live_site;
	$sep = "<tr><td colspan=\"2\"><img src=\"modules/remositoryquicklinks/sep.gif\" width=\"250\" height=\"11\"></td></tr>";
	
	$validator[0] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/cpanel.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><p align=\"center\"><strong>YOU HAVE NO MENU ITEMS PUBLISHED!</strong></p></td></tr>";
	$validator[1] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/categories.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=containers\" target=\"_parent\">Manage Containers</a></td></tr>";
	$validator[2] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/addedit.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=files\" target=\"_parent\">Manage Files</a></td></tr>";
	$validator[3] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/user.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=groups\" target=\"_parent\">Manage Groups</a></td></tr>";
	$validator[4] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/module.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=uploads\" target=\"_parent\">Approve Uploads</a></td></tr>";
	$validator[5] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/config.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=config\" target=\"_parent\">Configuration</a></td></tr>";
	$validator[6] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/langmanager.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=unlinked\" target=\"_parent\">Handle Unlinked Files</a></td></tr>";
	$validator[7] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/sections.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=ftp\" target=\"_parent\">Bulk Add Files From Server</a></td></tr>";
	$validator[8] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/sections.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=addstructure\" target=\"_parent\">Add Whole Structure From Server</a></td></tr>";
	$validator[9] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/searchtext.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=missing\" target=\"_parent\">List Missing Files</a></td></tr>";
	$validator[10] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/cpanel.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=counts\" target=\"_parent\">Recalculate File Counts</a></td></tr>";
	$validator[11] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/cpanel.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=downloads\" target=\"_parent\">Set All Download Counts To Zero</a></td></tr>";
	$validator[12] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/trash.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=prune\" target=\"_parent\">Remove Old Log File Entries</a></td></tr>";
	$validator[13] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/mediamanager.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=thumbs\" target=\"_parent\">Check Thumbnail Integrity</a></td></tr>";
	$validator[14] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/dbrestore.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=dbconvert\" target=\"_parent\">Convert Pre 3.20 Database</a></td></tr>";
	$validator[15] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/dbrestore.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=dbconvert2\" target=\"_parent\">Conver Pre 3.40 Database</a></td></tr>";
	$validator[16] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/impressions.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=stats\" target=\"_parent\">Statistics</a></td></tr>";
	$validator[17] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/credits.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=about\" target=\"_parent\">About Remository</a></td></tr>";
	$validator[18] = "<tr><td width=\"16px\"><img src=\"../components/com_remository/images/admin/support.png\" width=\"16px\" height=\"16px\"></td>"
					. "<td><a href=\"index2.php?option=com_remository&act=support\" target=\"_parent\">Support And Development</a></td></tr>";			
?> 
<style>
#josRQL a {
	text-decoration:  none;
	font-weight: bold;
	border: none;
	color:#0A246A;
	display:block;
	padding: 3px;
	margin-bottom: 2px;
	vertical-align: middle;
	width:228px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
#josRQL a:hover {
	text-decoration:  none;
	border: none;
	background:#FF9900;
	color: #FFF;
}
td.josRQLHeader {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	background:#FF9900;
	color: #FFF;
	text-align: center;
	vertical-align: middle;
	height:20px;
	cursor:default;
}
</style>
<?php
	echo "<a href=\"index2.php?option=com_remository\" target=\"_parent\"><img id=\"josRQLImage\" src=\"../components/com_remository/images/admin/cpanel.png\" border=\"0\" width=\"22px\" height=\"22px\" align=\"middle\"";
		if ( ($manage_containers != 0) || ($manage_files != 0) || ($manage_groups != 0) || ($approve_uploads != 0) || ($configuration != 0) || ($handle_unlinked != 0) || ($bulk_add != 0) || ($add_structure != 0) || ($list_missing != 0) || ($recalculate_counts != 0) || ($reset_counts != 0) || ($remove_logs != 0) || ($check_thumbnails != 0) || ($convert_320 != 0) || ($convert_340 != 0) || ($statistics != 0) || ($about_remository != 0) || ($support != 0) ) {
			echo " onMouseOver=\"josPopup();\" style=\"cursor:pointer;\"";
		}
	echo "></a>";
?>
<div id="josRQL" style="visibility:hidden;padding:0px; margin:0px;border-top:1px solid #1854c2;border-bottom:1px solid #1854c2;  background:url(modules/remositoryquicklinks/rqlbg.gif) repeat-y;text-align:left;z-index:1000;top:100px;left:0px;width:260px; position:absolute;">
<table style="margin-left:4px;" border="0" cellpadding="0" cellspacing="0" width="170px">
<tr>
<td colspan="2" class="josRQLHeader">Remository Quick Links</td>
</tr>
<?php
	if ( ($manage_containers != 0) || ($manage_files != 0) || ($manage_groups != 0) || ($approve_uploads != 0) || ($configuration != 0) ) {
	echo $sep;
	}
?>
<?php
	if ( $manage_containers ) {
	echo $validator[1];
	}
?>
<?php
	if ( $manage_files ) {
	echo $validator[2];
	}
?>
<?php
	if ( $manage_groups ) {
	echo $validator[3];
	}
?>
<?php
	if ( $approve_uploads ) {
	echo $validator[4];
	}
?>
<?php
	if ( $configuration ) {
	echo $validator[5];
	}
?>
<?php
	if ( ($handle_unlinked != 0) || ($bulk_add != 0) || ($add_structure != 0) || ($list_missing != 0) ) {
	echo $sep;
	}
?>
<?php
	if ( $handle_unlinked ) {
	echo $validator[6];
	}
?>
<?php
	if ( $bulk_add ) {
	echo $validator[7];
	}
?>
<?php
	if ( $add_structure ) {
	echo $validator[8];
	}
?>
<?php
	if ( $list_missing ) {
	echo $validator[9];
	}
?>
<?php 
	if ( ($recalculate_counts != 0) || ($reset_counts != 0) || ($remove_logs != 0) || ($check_thumbnails != 0) ) {
	echo $sep;
	}
?>
<?php
	if ( $recalculate_counts ) {
	echo $validator[10];
	}
?>
<?php
	if ( $reset_counts ) {
	echo $validator[11];
	}
?>
<?php
	if ( $remove_logs ) {
	echo $validator[12];
	}
?>
<?php
	if ( $check_thumbnails ) {
	echo $validator[13];
	}
?>
<?php
	if ( ($convert_320 != 0) || ($convert_340 != 0) ) {
	echo $sep;
	}
?>
<?php
	if ( $convert_320 ) {
	echo $validator[14];
	}
?>
<?php
	if ( $convert_340 ) {
	echo $validator[15];
	}
?>
<?php
	if ( ($statistics != 0) || ($about_remository) != 0 || ($support != 0) ) {
	echo $sep;
	}
?>
<?php
	if ( $statistics ) {
	echo $validator[16];
	}
?>
<?php
	if ( $about_remository ) {
	echo $validator[17];
	}
?>
<?php
	if ( $support ) {
	echo $validator[18];
	}
?>
</table>
</div>

<script language="JavaScript">

function josRQLShowHide (st) {
	var tags = new Array("applet", "iframe", "select");
	for (var k = tags.length; k > 0; ) {
		var ar = document.getElementsByTagName(tags[--k]);
		var cc = null;
		for (var i = ar.length; i > 0;) {
			cc = ar[--i];
			cc.style.visibility = st;
		}
	}
}

function josRQLfindObj(theRQLObj, theRQLDoc)
{
  var p, i, foundRQLObj;
  
  if(!theRQLDoc) theRQLDoc = document;
  if( (p = theRQLObj.indexOf("?")) > 0 && parent.frames.length)
  {
    theRQLDoc = parent.frames[theRQLObj.substring(p+1)].document;
    theRQLObj = theRQLObj.substring(0,p);
  }
  if(!(foundRQLObj = theRQLDoc[theRQLObj]) && theRQLDoc.all) foundRQLObj = theRQLDoc.all[theRQLObj];
  for (i=0; !foundRQLObj && i < theRQLDoc.forms.length; i++) 
    foundRQLObj = theRQLDoc.forms[i][theRQLObj];
  for(i=0; !foundRQLObj && theRQLDoc.layers && i < theRQLDoc.layers.length; i++) 
    foundRQLObj = josRQLfindObj(theRQLObj,theRQLDoc.layers[i].document);
  if(!foundRQLObj && document.getElementById) foundRQLObj = document.getElementById(theRQLObj);
  
  return foundRQLObj;
}

var objRQL = josRQLfindObj('josRQL');

function findX(obj) {
	var curleft=0;
	if (obj.offsetParent) {
		while (obj.offsetParent) {
		curleft +=obj.offsetLeft;
		obj = obj.offsetParent;
		}
	}
	else if (obj.x)
	curleft += obj.x;
	return curleft;
}

function findY(obj) {
	var curtop=0;
	if (obj.offsetParent) {
		while (obj.offsetParent) {
		curtop +=obj.offsetTop;
		obj = obj.offsetParent;
		}
	}
	else if (obj.y)
	curtop += obj.y;
	return curtop;
}

document.onclick= check ;

function check(e) {
	var target = (e && e.target) || (event && event.srcElement);
	if (target.tagName == 'A') { 
		objRQL.style.visibility="hidden";
		josRQLShowHide('visible');
		return true;
	}
	if ( (target.tagName=='A') || (target == document.getElementById('josRQLImage')) ) {
		return true;
	}
	if (target != document.getElementById('josRQL')) {
		if (!checkParent(target)) {
			objRQL.style.visibility="hidden";
			josRQLShowHide('visible');
		}
	}
}

function checkParent(t) {
	while(t.parentNode) {
	if(t==document.getElementById('josRQL')) {
	return true;
	}
	t=t.parentNode;
	}
	return false;
}

function josPopup() {
	if (objRQL.style.visibility == 'visible') {
		objRQL.style.visibility = 'hidden';
		josRQLShowHide('visible');
		return true;
	}
	var objImg = josRQLfindObj('josRQLImage');
	var x = findX(objImg);
	var y = findY(objImg);
	objRQL.style.left = (x-178)+ 'px';
	objRQL.style.top = (y+22)+ 'px';
	objRQL.style.visibility = 'visible';
	josRQLShowHide('hidden');
}
</script>
