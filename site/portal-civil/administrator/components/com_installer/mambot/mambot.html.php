<?php
/**
* @version $Id: mambot.html.php 6072 2006-12-20 02:09:09Z robs $
* @package Joomla / Tradução idioma pt-br 27-07-2007 /por: Lena - www.facilhost.com.br
* @subpackage Installer
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* @package Joomla / Tradução idioma pt-br 27-07-2007 /por: Lena - www.facilhost.com.br
* @subpackage Installer
*/
class HTML_mambot {
/**
* Displays the installed non-core Mambot
* @param array An array of mambot object
* @param strong The URL option
*/
	function showInstalledMambots( &$rows, $option ) {
		?>
		<table class="adminheading">
		<tr>
			<th class="install">
			Plugins Instalados
			</th>
		</tr>
		<tr>
			<td>
			Apenas são exibidos os plugins que podem ser desinstalados - alguns plugins são parte do sistema e não podem ser removidos.
			<br /><br />
			</td>
		</tr>
		</table>
		<?php
		if ( count( $rows ) ) { ?>
			<form action="index2.php" method="post" name="adminForm">
			<table class="adminlist">
			<tr>
				<th width="20%" class="title">
				Plugin
				</th>
				<th width="10%" class="title">
				Tipo
				</th>
				<!--
				Currently Unsupported
				<th width="10%" align="left">
				Client
				</th>
				-->
				<th width="10%" align="left">
				Autor
				</th>
				<th width="5%" align="center">
				Versão
				</th>
				<th width="10%" align="center">
				Data
				</th>
				<th width="15%" align="left">
				E-mail do Autor
				</th>
				<th width="15%" align="left">
				URL do Autor
				</th>
			</tr>
			<?php
			$rc = 0;
			$n = count( $rows );
			for ($i = 0; $i < $n; $i++) {
				$row =& $rows[$i];
				?>
				<tr class="<?php echo "row$rc"; ?>">
					<td>
					<input type="radio" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);">
					<span class="bold">
					<?php echo $row->name; ?>
					</span>
					</td>
					<td>
					<?php echo $row->folder; ?>
					</td>
					<!--
					Currently Unsupported
					<td>
					<?php //echo $row->client_id == "0" ? 'Site' : 'Administrator'; ?>
					</td>
					-->
					<td>
					<?php echo @$row->author != '' ? $row->author : "&nbsp;"; ?>
					</td>
					<td align="center">
					<?php echo @$row->version != '' ? $row->version : "&nbsp;"; ?>
					</td>
					<td align="center">
					<?php echo @$row->creationdate != '' ? $row->creationdate : "&nbsp;"; ?>
					</td>
					<td>
					<?php echo @$row->authorEmail != '' ? $row->authorEmail : "&nbsp;"; ?>
					</td>
					<td>
					<?php echo @$row->authorUrl != "" ? "<a href=\"" .(substr( $row->authorUrl, 0, 7) == 'http://' ? $row->authorUrl : 'http://'.$row->authorUrl). "\" target=\"_blank\">$row->authorUrl</a>" : "&nbsp;";?>
					</td>
				</tr>
				<?php
				$rc = 1 - $rc;
			}
			?>
			</table>

			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="option" value="com_installer" />
			<input type="hidden" name="element" value="mambot" />
			</form>
			<?php
		} else {
			?>
			Não há plugins personalizados instalados.
			<?php
		}
	}
}
?>