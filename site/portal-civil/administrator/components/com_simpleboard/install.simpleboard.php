<?php
//
// Copyright (C) 2003 Jan de Graaff
// All rights reserved.
//
// This program uses parts of the original Simpleboard Application
// 0.7.0b written by Josh Levine; http://www.joshlevine.net
//
// This source file is part of the SimpleBoard Component, a Mambo 4.5
// custom Component By Jan de Graaff - http://tsmf.jigsnet.com
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
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function com_install()
{
global $database;
//change SB menu icon
	$database->setQuery( "SELECT id FROM #__components WHERE admin_menu_link = 'option=com_simpleboard'" );
	$id = $database->loadResult();

	//add new admin menu images
	$database->setQuery( "UPDATE #__components SET admin_menu_img = '../administrator/components/com_simpleboard/images/sbmenu.png', admin_menu_link = 'option=com_simpleboard' WHERE id='$id'");
	$database->query();
	
?>
<center>
<table width="100%" border="0">
   <tr>
     <td width="10%"><img src="components/com_simpleboard/images/logo.png"></td>
     <td width="90%"><p><strong>Simpleboard Forum</strong> Component <em>for Mambo CMS</em> <br />
&copy; 2003 - 2019 by the Two Shoes Mambo Factory<br>
All rights reserved.
<br />
              <br />
         This Mambo 4.5.x Component has been released under the <a href="index2.php?option=com_admisc&amp;task=license">GNU/GPL</a>. </p>
        </td>
   </tr>
   <tr>
      <td><code>I N S T A L L :</code><br /><br />
          <font color="red">succesfull</font>
      </td>
      <td>
         <p><strong><font color="#FF8000">Upgrade instructions</font></strong> can be found on the Two Shoes Module Factory;
            They are always contained in the release notes on our website: <a href="http://www.tsmf.net/">tsmf.net</a><br />
            <br />
            All the documentation about <strong>Administration</strong> and
            <strong>Configuration</strong> can be found
            <a href="http://www.tsmf.net/content/section/2/27/" target="_blank">:: here ::</a></p>
         <p><strong><font color="#FF8000">Load Sample Data</font> </strong><br />
         If you don't know how to start, be sure to check out the "Load Sample Data" menu option or click <a href="index2.php?option=com_simpleboard&amp;task=loadSample">here</a>            </p></td>
   </tr>
   <tr>
      <td><p>Thank you for using Simpleboard!</p>
         <p><em>The TSMF team.</em> </p></td>
      <td>
         <p><strong> <font color="red">UPGRADE DATABASE:</font></strong>
            If you are upgrading it is very well possible that you should upgrade your database. <br>
            Please click on the link below to do so:<br>
            <br>
            <a href="index2.php?option=com_simpleboard&amp;task=upgradetables">Upgrade Database</a><br>
            <br>
            <strong>Please do it immediately before continuing!</strong>
</p>
         <p><strong><font color="#FF8000">Note for any version of Simpleboard: </font></strong><br>
        <font size="1">Simpleboard is free software and therefor provided 'as-is'. You haven't paid for it and you haven't paid for support. You even have paid to get you software that comes with a guarantee that it works...<br>
        The Two Shoes Mambo Factory, its subsidiaries, its developers, contributors and other volunteers and its parental legal entities (formally or informally) (these will further be referenced as 'TSMF') offer you Simpleboard for absolutely free for your own personal use, pleasure and education. The TSMF reserves the right to charge corporate or other commercial users of the Software for this or future versions or support on a paid basis. </font></p>
         <p><font size="1">Any Simpleboard version may contain errors, bugs and/or can cause  problems otherwise. While it is relatively safe to use this version, we do point you at this fact.<br>
          By installing this software, you have agreed to waive TSMF from whatever form of liability and/or responsibility for any problems and/or damages done by this software to you, your web environment or in any other way legallly, financially, socially, emotionally or whatever other ~ally you could possibly imagine and find a legal article for that favours your rights...<br>
          In short and slightly more human readable: Use this software at your own risk; we don't guarantee anything! And by clicking 'continue' below and using Simpleboard, it's your way of answering: &quot;Yeah, yeah, I know and I don't care... trust me, I know what I'm doing so it'll be my own fault if things go wrong&quot;...</font></p>
         <p><font size="1">Have fun with Simpleboard! We know we have!!!! </font></p></td>
   </tr>
</table>
</center>
<?php
}
?>