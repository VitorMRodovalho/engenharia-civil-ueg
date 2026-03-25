<?php
/**
* @version $Id: admin.dbadmin.html.php,v 1.4 2004/07/20 11:50:12 rcastley Exp $
* @package Mambo_4.5
* @copyright (C) 2000 - 2004 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* @package Mambo_4.5
*/
class HTML_dbadmin
{
	function backupIntro( $tablelist, $p_option )
	{
	?>
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="100%" class="sectionname"><img src="images/backup.png" align="middle">Database Backup</td>
		</tr>
		</table>
		<form action="index2.php?option=com_dbadmin&task=doBackup" method="post">
		<table border="0" align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
		</tr>
		<tr>
			<td>Where would you like to back up your Database Tables to?<br /> <br />
				<input type="radio" name="OutDest" value="screen" />
					Display Results on the Screen<br /> 
				<input type="radio" name="OutDest" value="remote"/>
					Download to a file on my local computer<br /> 
				<input type="radio" name="OutDest" value="local" / checked="checked" >
					Store the file in the backup directory on the server
			</td>
			<td>&nbsp;</td>
			<td>What format would you like to save them as?<br /> <br />
			<?php if (function_exists('gzcompress'))
			{
			?>
			<input type="radio" name="OutType" value="zip" />As a Zip file<br />
			<?php
			}
			if (function_exists('bzcompress'))
			{
			?>
			<input type="radio" name="OutType" value="bzip" />As a BZip file<br />
			<?php
			}
			if (function_exists('gzencode'))
			{
			?>
			<input type="radio" name="OutType" value="gzip" />As a GZip file<br />
			<?php
			}
			?>
			<input type="radio" name="OutType" value="sql" checked="checked" /> As a SQL (plain text) file
			<br />
			<input type="radio" name="OutType" value="html" /> As formatted HTML </td>
		</tr>
		<tr>
		<td> <p>What do you want to back up?<br /><br />
			<input type="radio" name="toBackUp" value="data" />Data Only<br />
			<input type="radio" name="toBackUp" value="structure" />Structure Only<br />
			<input type="radio" name="toBackUp" value="both" checked="checked" />Data and Structure </p>
		</td>
		<td>&nbsp;</td>
		<td> <p align="left">Which Database Tables would you like to back up?<br />
          Please note, it is highly recommended you select ALL your tables.</p>
		  <?php echo $tablelist; ?>
		</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="center">&nbsp;<br /> <input type="submit" value="Backup the Selected Tables" class="button" /></td>
		</tr>
	</table>
	</form>
	<?php
	}
	
	function restoreIntro($enctype,$uploads_okay,$local_backup_path)
	{
	?>
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="100%" class="sectionname"><img src="images/dbrestore.png" align="middle">Database Restore</td>
		</tr>
		</table>
		<table border="0" align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
		<form action="index2.php?option=com_dbadmin&task=doRestore" method="post" <?php echo $enctype;?>>
		<tr>
			<th class="title" colspan="3">Existing Backups</th>
		</tr>
		<?php
	if (isset($local_backup_path))
	{
		if ($handle = @opendir($local_backup_path))
		{
		?>
		<tr><td>&nbsp;</td><td><b>Backup File Name</b></td><td><b>Created Date/Time</b></td></tr>
		<?php
		while ($file = @readdir($handle))
		{
			if (is_file($local_backup_path . "/" . $file))
			{
				if (eregi(".\.sql$",$file) || eregi(".\.bz2$",$file) || eregi(".\.gz$",$file) || eregi(".\.zip$",$file))
				{
					echo "\t\t<tr><td align=\"center\"><input type=\"radio\" name=\"file\" value=\"$file\"></td><td>$file</td><td>" . date("m/d/y H:i:sa", filemtime($local_backup_path . "/" . $file)) . "</td></tr>\n";
				}
			}
		}
		}
		else
		{
			echo "\t\t<tr><td colspan=\"3\" class=\"error\">Error!<br />Invalid or non-existant backup path in your configuration file : <br />" . $local_backup_path . "/" . $file . "</td></tr>\n";
		}
		@closedir($handle);
	}
	else
	{
		echo "\t\t<tr><td colspan=\"3\" class=\"error\">Error!<br />Backup path in your configuration file has not been configured.</td></tr>\n";
	}
	if ($uploads_okay)
	{
		?>
		<tr>
			<td colspan="3"><br />Or alternatively, if you've downloaded a backup to your computer, you can restore from a local file :</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><br /><input type="file" name="upfile" class="button"></td>
			<td>&nbsp;</td>
		</tr>
		<?php
	}
		?>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;<br />
			<input type="submit" class="button" value="Perform the Restore" />&nbsp;&nbsp; <input type="reset" class="button" value="Reset" /></td>
			<td>&nbsp;</td>
		</tr>
		</form>
	</table>
	<?php
	}
	function showDbAdminMessage($message,$title,$option,$task)
	{
		global $PHP_SELF;
		?>
			<table border="0" cellpadding="4" cellspacing="0" width="100%" class="adminlist">
		<tr>
			<th width="55%" class="title"><?php echo $title; ?></th>
		</tr>
		<tr>
			<td align="left"><b><?php echo $message; ?></td>
		</tr>
		</table>
		<?php
	}

	function xquery( $sql='', $msg='', $rows=null, $option ) {
?>
<form action="index2.php" method="post" name="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%" class="sectionname"><img src="images/query.png" align="middle">Execute Query</td> 
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
  </table>
 <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
	<tr>
		<td>SQL:</td>
	</tr>
	<tr>
		<td><textarea name="sql" rows="10" cols="80" class="inputbox"><?php echo $sql;?></textarea></td>
	</tr>
	<tr>
		<td>
			<input type="submit" value="Execute Query" class="button" />
			<input type="button" value="Clear Query" class="button" onclick="document.adminForm.sql.value=''" />
			<input type="checkbox" name="batch" value="1" /> Batch Mode
		</td>
	</tr>
<?php	if ($msg) { ?>
	<tr>
		<td><?php echo $msg;?></td>
	</tr>
<?php	} ?>
<?php	
		if (is_array( $rows ) && count( $rows ) > 0) {
			$n = count( $rows );
?>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="2" border="1">
				<tr>
					<th>#</th>
<?php		foreach($rows[0] as $key => $value) { ?>
					<th><?php echo $key;?></th>
<?php 		} ?>
				<tr>
<?php		for ($i=0; $i < $n; $i++) {
				echo "\n	<tr>";
				echo "\n		<td>$i</td>";
				foreach($rows[$i] as $key => $value) {
					echo "\n		<td>$value</td>";
				}
				echo "\n	</tr>";
			}
?>
			</table>
		</td>
	</tr>
<?php	} ?>
 </table>
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="task" value="xquery" />
</form>
<?php
	}
}
?>
