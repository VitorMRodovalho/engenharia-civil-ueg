<?php
// $Id: dbadmin, v 4.5.1 2004/10/06 13:08:06
function com_install()
{
?>
<div align="center"><p><strong>dbadmin 4.5.1 has not been completely installed!</strong></p></div>
<p>To enable the script completely, add this code to your administrator/index2.php file:</p>
<p><textarea cols="40" rows="8" name="code">
// check for database backup
if (($option == "com_dbadmin") && ($task == "doBackup")) {
	$OutDest = mosGetParam( $_REQUEST, "OutDest", '' );
	if ($OutDest == "remote") {
		include_once ("components/com_dbadmin/admin.dbadmin.php");
		exit();
	}
}
</textarea>
</p>
<p>
Add the code after this:
</p>
<p><textarea cols="40" rows="8" name="code">
// timeout old sessions
$past = time()-1800;
$database->setQuery( "DELETE FROM #__session WHERE time < '$past'" );
$database->query();
</textarea>
</p>

<?php
}
?>
