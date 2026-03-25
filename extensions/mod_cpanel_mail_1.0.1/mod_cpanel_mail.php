<?php
/*
* @version $Id: mod_cpanel_mail.php 1.0
* @copyright Daniel Corrêa. All rights reserved.
* @license GNU/GPL.
* @website www.joomlaminas.org
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$domain = $params->get( 'domain', $mosConfig_live_site ); //email domain hosted
$port	= $params->get(	'port', '2095' ); //port connect
$o		= $params->get(	'orientation', ''); // orientation vertical or horizontal

$size = strlen($domain);
$pos = strrpos($domain, "/");

//Delete the last '/'
if ($size==$pos+1){
$ndomain = substr($domain,0,-1);
}else{
$ndomain = $domain;
}

if ($o==1){
	//make a form Horizontal
	echo "<form action='".$ndomain.":".$port."/login/' method='POST' target='_blank'>";
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	echo "  <tr>";
	echo "    <td width='7%'>". _USERNAME .": </td>";
	echo "    <td width='11%'><input type='text' name='user' size='16'></td>";
	echo "    <td width='7%'>"._PASSWORD.": </td>";
	echo "    <td width='11%'><input type='password' name='pass' size='16'></td>";
	echo "    <td width='62%'><input type='submit' value='"._BUTTON_LOGIN."'></td>";
	echo "  </tr>";
	echo "</table>";
	echo "</form>";
	}
else
	{
	//make a form Vertical
	echo "<form action='".$ndomain.":".$port."/login/' method='POST' target='_blank'>";
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	echo "  <tr>";
	echo "    <td>". _USERNAME ."</td>";
	echo "  </tr>";
	echo "  <tr>";
	echo "    <td><input type='text' name='user' size='16'></td>";
	echo "  </tr>";
	echo "  <tr>";
	echo "    <td>"._PASSWORD."</td>";
	echo "  </tr>";
	echo "  <tr>";
	echo "    <td><input type='password' name='pass' size='16'></td>";
	echo "  </tr>";
	echo "  <tr>";
	echo "    <td><input type='submit' value='"._BUTTON_LOGIN."'></td>";
	echo "  </tr>";
	echo "</table>";
	echo "</form>";
	}	
?>