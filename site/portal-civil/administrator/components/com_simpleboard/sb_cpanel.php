<?php
/**
* @version $Id: sb_cpanel.php,v 1.1 2005/07/27 10:09:39 jigsjdg Exp $
* @package Mambo_4.5
* @copyright (C) 2000 - 2004 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
?>
<style>
/* standard form style table */
table.thisform {
	background-color: #F7F8F9;
	border: solid 1px #d5d5d5;
	width: 100%;
	padding: 10px;
	border-collapse: collapse;
}
table.thisform tr.row0 {
	background-color: #F7F8F9;
}
table.thisform tr.row1 {
	background-color: #eeeeee;
}
table.thisform th {
	font-size: 15px;
	font-weight:normal;
	font-variant:small-caps;
	padding-top: 6px;
	padding-bottom: 2px;
	padding-left: 4px;
	padding-right: 4px;
	text-align: left;
	height: 25px;
	color: #666666;
	background: url(../images/background.gif);
	background-repeat: repeat;
}
table.thisform td {
	padding: 3px;
	text-align: left;
	border: 1px;
	border-style:solid;
	border-bottom-color:#EFEFEF;
	border-right-color:#EFEFEF;
	border-left-color:#EFEFEF;
	border-top-color:#EFEFEF;	
}

table.thisform2 {
	background-color: #F7F8F9;
	border: solid 1px #d5d5d5;
	width: 100%;
	padding: 5px;
}
table.thisform2 td {
	padding: 5px;
	text-align: center;
	border: 1x;
	border-style: solid;
	border-bottom-color:#EFEFEF;
	border-right-color:#EFEFEF;
	border-left-color:#EFEFEF;
	border-top-color:#EFEFEF;
}
.thisform2 td:hover {
	background-color: #B5CDE8;
	border:	1px solid #30559C;
}
</style>
<table class="thisform">
   <tr class="thisform">
      <td width="50%" valign="top" class="thisform">
         <table width="100%" class="thisform2">
         <tr class="thisform2">
            <td align="center" height="100px" width="10" class="thisform2">
            <a href="index2.php?option=com_simpleboard&amp;task=showconfig" style="text-decoration:none;" title="<?php echo _COM_C_SBCONFIGDESC;?>">
            <img src="components/com_simpleboard/images/sbconfig.png" width="48px" height="48px" align="middle" border="0"/>
            <br />
            <?php echo _COM_C_SBCONFIG;?>
            </a>
            </td>
            
            <td align="center" height="100px" width="10" class="thisform2">
            <a href="index2.php?option=com_simpleboard&amp;task=showAdministration" style="text-decoration:none;" title="<?php echo _COM_C_FORUMDESC;?>">
            <img src="components/com_simpleboard/images/sbforumadm.png" width="48px" height="48px" align="middle" border="0"/>
            <br />
            <?php echo _COM_C_FORUM ;?>
            </a>
            </td>

            <td align="center" height="100px" width="10" class="thisform2">
            <a href="index2.php?option=com_simpleboard&amp;task=showprofiles" style="text-decoration:none;" title="<?php echo _COM_C_USERDESC;?>">
            <img src="components/com_simpleboard/images/sbuser.png" width="48px" height="48px" align="middle" border="0"/>
            <br />
            <?php echo _COM_C_USER;?>
            </a>
            </td>
            
         </tr>
         <tr class="thisform2">
            <td align="center" height="100px" width="10" class="thisform2">
            <a href="index2.php?option=com_simpleboard&amp;task=browseFiles" style="text-decoration:none;" title="<?php echo _COM_C_FILESDESC;?>">
            <img src="components/com_simpleboard/images/sbfiles.png" width="48px" height="48px" align="middle" border="0"/>
            <br />
            <?php echo _COM_C_FILES;?>
            </a>
            </td>
            
            <td align="center" height="100px" width="10" class="thisform2">
            <a href="index2.php?option=com_simpleboard&amp;task=browseImages" style="text-decoration:none;" title="<?php echo _COM_C_IMAGESDESC;?>">
            <img src="components/com_simpleboard/images/sbimages.png" width="48px" height="48px" align="middle" border="0"/>
            <br />
            <?php echo _COM_C_IMAGES ;?>
            </a>
            </td>
            
            <td align="center" height="100px" width="10" class="thisform2">
            <a href="index2.php?option=com_simpleboard&amp;task=showCss" style="text-decoration:none;" title="<?php echo _COM_C_CSSDESC;?>">
            <img src="components/com_simpleboard/images/sbcss.png" width="48px" height="48px" align="middle" border="0"/>
            <br />
            <?php echo _COM_C_CSS;?>
            </a>
            </td>
            
         </tr>
         <tr class="thisform2">
            <td align="center" height="100px" width="10" class="thisform2">
            <a href="index2.php?option=com_simpleboard&amp;task=pruneforum" style="text-decoration:none;" title="<?php echo _COM_C_PRUNETABDESC;?>">
            <img src="components/com_simpleboard/images/sbtable.png" width="48px" height="48px" align="middle" border="0"/>
            <br />
            <?php echo _COM_C_PRUNETAB;?>
            </a>
            </td>
            
            <td align="center" height="100px" width="10" class="thisform2">
            <a href="index2.php?option=com_simpleboard&amp;task=pruneusers" style="text-decoration:none;" title="<?php echo _COM_C_PRUNEUSERSDESC;?>">
            <img src="components/com_simpleboard/images/sbusers.png" width="48px" height="48px" align="middle" border="0"/>
            <br />
            <?php echo _COM_C_PRUNEUSERS ;?>
            </a>
            </td>         
            
            <td align="center" height="100px" width="10" class="thisform2">      
            <a href="http://www.tsmf.net" target="_blank" style="text-decoration:none;" title="<?php echo _COM_C_SUPPORTDESC;?>">
            <img src="components/com_simpleboard/images/sbtechsupport.png" width="48px" height="48px" align="middle" border="0"/>
            <br />
            <?php echo _COM_C_SUPPORT;?>
            </a>
            </td> 
            
         </tr>
         <tr class="thisform2">
            <td align="center" height="100px" width="10" class="thisform2">
            <a href="index2.php?option=com_simpleboard&amp;task=loadSample" style="text-decoration:none;" title="<?php echo _COM_C_LOADSAMPLEDESC;?>">
            <img src="components/com_simpleboard/images/sbloadsample.png" width="48px" height="48px" align="middle" border="0"/>
            <br />
            <?php echo _COM_C_LOADSAMPLE;?>
            </a>
            </td>
            
            <td align="center" height="100px" width="10" class="thisform2">
            <a href="index2.php?option=com_simpleboard&amp;task=upgradetables" style="text-decoration:none;" title="<?php echo _COM_C_UPGRADEDESC;?>">
            <img src="components/com_simpleboard/images/sbupgrade.png" width="48px" height="48px" align="middle" border="0"/>
            <br />
            <?php echo _COM_C_UPGRADE.''.$version ;?>
            </a>
            </td>         
            
            <td align="center" height="100px" width="10" class="thisform2">      
            <a href="http://www.tsmf.net" target="_blank" style="text-decoration:none;">
            <?php //<img src="components/com_simpleboard/images/sbtechsupport.png" width="48px" height="48px" align="middle" border="0"/> ;?>
            <br />
            &nbsp;
            <?php //echo _COM_C_SUPPORT;?>
            </a>
            </td> 
            
         </tr>         
         </table>
      </td>
      <td width="50%" valign="top" align="center">
      <table border="1" width="100%" class="thisform">
         <tr class="thisform">
            <th class="cpanel" colspan="2">Simpleboard Forum Component.</th></td>
         </tr>
         <tr class="thisform"><td bgcolor="#FFFFFF" colspan="2"><br />
      <div style="width=100%" align="center">
      <img src="components/com_simpleboard/images/logo.png" align="middle" alt="Simpleboard 1.0 logo"/>
      <br /><br /></div>  
      </td></tr>       
         <tr class="thisform">
            <td width="120" bgcolor="#FFFFFF">Installed version:</td>
            <td bgcolor="#FFFFFF"><?php echo $version;?></td>
         </tr>
         <tr class="thisform">
            <td bgcolor="#FFFFFF">Copyright:</td>
            <td bgcolor="#FFFFFF">&copy; 2003-2005 the Two Shoes Mambo Factory</td>
         </tr>		  
         <tr class="thisform">
            <td bgcolor="#FFFFFF">License:</td>
            <td bgcolor="#FFFFFF"><a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU GPL</a></td>
         </tr>
         <tr class="thisform">
            <td valign="top" bgcolor="#FFFFFF">TSMF Team:</td>
            <td bgcolor="#FFFFFF">
				Jan de Graaff - PM<br />
				Tomislav (Riba) Ribicic - PL<br />
				Arno (Jick) Zijlstra - Dev<br />
				Niels (Progster) Vandekeybus - Dev; F<br />
				Jon (Intel352) Langevin - Dev<br />
				Thomas (mmyto) Despoix - Dev, SA<br />
				Iosif 'Afonic' Chatzimichail - F<br />
				Kathy (PixelBunnyiP) Strickland - F; Doc<br/>
				<br />
				<font size="1">PM: Project Manager; PL: Project Lead; Dev: Developer; F: Forum Moderation; SA: Software Architect; Doc: Documentation</font>
			</td>
         </tr>		
		 <tr class="thisform">
		 	<td bgcolor="#FFFFFF">Support TSMF:</td>
			<td bgcolor="#FFFFFF"><form action="https://www.paypal.com/cgi-bin/webscr" target="_blank" method="post">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="sales@jigsnet.com">
				<input type="hidden" name="item_name" value="Two Shoes Mambo Factory Support">
				<input type="hidden" name="item_number" value="TSMF">
				<input type="hidden" name="no_note" value="1">
				<input type="hidden" name="currency_code" value="EUR">
				<input type="hidden" name="tax" value="0">  
				<input type="image" src="components/com_simpleboard/images/paypalbutton.png" border="0" name="submit" alt="Please support the TSMF - Make donations with PayPal - it's fast, free and secure!">
				</form></td>
		 </tr> 
      </table>
      </td>
   </tr>
</table>
<!--
            </td>
            <td align="center" height="100px" width="10" class="thisform">
            &nbsp;
            </td>
-->            
