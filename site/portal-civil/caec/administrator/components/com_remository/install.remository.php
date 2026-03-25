<?php
// Install ReMOSitory 3.21

// Part of Remository, copyright (c) Martin Brampton 2005.
// For further information please refer to http://www.remository.com

//Remository Downloads Directory Creation

// Don't allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_absolute_path;

require_once($mosConfig_absolute_path.'/components/com_remository/remository.class.php');

function com_install()
{
	function permission_all_from_dir($Dir){
		// delete everything in the directory
		if ($handle = @opendir($Dir)) {
			while (($file = readdir($handle)) !== false) {
				if ($file == '.' || $file == '..') continue;
				$newpath = $Dir.$file;
				if (is_dir($newpath)) permission_all_from_dir($newpath.'/');
				else @chmod($newpath,0644);
			}
		}
		@closedir($handle);
		@chmod($Dir,0755);
	}
	global $mosConfig_absolute_path, $mosConfig_live_site;
    require_once($mosConfig_absolute_path.'/components/com_remository/com_remository_settings.php');
    
    if (!file_exists($mosConfig_absolute_path.'/administrator/images/approve.png')) copy($mosConfig_absolute_path.'/components/com_remository/images/approve.png', $mosConfig_absolute_path.'/administrator/images/approve.png');
    if (!file_exists($mosConfig_absolute_path.'/administrator/images/approve_f2.png')) copy($mosConfig_absolute_path.'/components/com_remository/images/approve_f2.png', $mosConfig_absolute_path.'/administrator/images/approve_f2.png');

	$repository = new remositoryRepository();
	foreach (get_class_vars(get_class($repository)) as $k=>$v) {
		eval('if (isset($'.$k.')) $repository->'.$k.' = $'.$k.';');
	}

	if (file_exists($mosConfig_absolute_path.'/classes')) {
		permission_all_from_dir($mosConfig_absolute_path.'/components/com_remository/');
		permission_all_from_dir($mosConfig_absolute_path.'/administrator/components/com_remository/');
	}
	$settingok = @chmod($mosConfig_absolute_path.'/components/com_remository/com_remository_settings.php', 0707);
	if (!file_exists($repository->Down_Path)){
		if (!($downisok = @mkdir($repository->Down_Path, 0707))) {
			$repository->Down_Path = $mosConfig_absolute_path.'/components/com_remository_files/downloads/';
			$downisok = @mkdir($Down_Path, 0707);
		}
	}
	else $downisok = @chmod ($repository->Down_Path,0707);
	if (!file_exists($repository->Up_Path)){
		if (!($upisok = @mkdir($repository->Up_Path, 0707))) {
			$repository->Up_Path = $mosConfig_absolute_path.'/components/com_remository_files/downloads/uploads/';
			$upisok = @mkdir($repository->Up_Path, 0707);
		}
	}
	else $upisok = @chmod ($repository->Up_Path,0707);
	
	echo 'Download path is: '.$repository->Down_Path.'<br/>';
	if (file_exists($repository->Down_Path)) {
		echo 'This directory exists.<br/>';
		if(!is_writable($repository->Down_Path))echo 'But it is not writable by Remository, which will prevent it working.<br/>';
		else {
			echo 'And it is writable by Remository, which should therefore function correctly<br/>';
			copy($mosConfig_absolute_path.'/components/com_remository/index.html', $repository->Down_Path.'/index.html');
		}
	}
	else echo 'This directory does not exist, preventing Remository from functioning<br/>';

	echo 'Upload path is: '.$repository->Up_Path.'<br/>';
	if (file_exists($repository->Up_Path)) {
		echo 'This directory exists.<br/>';
		if(!is_writable($repository->Up_Path))echo 'But it is not writable by Remository, which will prevent it working.<br/>';
		else {
			echo 'And it is writable by Remository, which should therefore function correctly<br/>';
			copy($mosConfig_absolute_path.'/components/com_remository/index.html', $repository->Up_Path.'/index.html');
		}
	}
	else echo 'This directory does not exist, preventing Remository from functioning<br/>';

	if (!$downisok OR !$upisok OR !$settingok) {
		?>
		<p>
		The Remository installation may have detected a problem.  Please ensure that the following are correct:
		<ul>
		<li>The Remository settings are contained in a file called com_remository_settings.php that is located
		in the directory /components/com_remository/ (relative to the Mambo root) and this file should be
		writeable by Remository.  This rarely fails, but please check for errors above.</li>
		<li>To handle a file repository, there must be a downloads directory that is writeable by Remository.
		The normal default for this is to locate it as /downloads/ but if that fails, Remository attempts to
		locate it as /components/com_remository_files/downloads/.  If this has failed, or if you want to hold the
		files elsewhere, please make sure that a directory has been created and that its permissions are set
		so that Remository can write to it.</li>
		<li>To handle user uploads, an upload directory is required that is writeable by Remository.  It is
		normally called /uploads/ and located as a subdirectory of the downloads directory.</li>
		</ul>
		<?php
	}

	$config = "<?php\n";
	$config .= $repository->getVarText();
	$config .= "?>";
	if ($fp = fopen($mosConfig_absolute_path.'/components/com_remository/com_remository_settings.php', "w")) {
		fputs($fp, $config, strlen($config));
		fclose ($fp);
	}
	$sql = "ALTER TABLE #__downloads_files CHANGE submitdate submitdate DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'";
	remositoryRepository::doSQL($sql);
	$sql = "ALTER TABLE #__downloads_files CHANGE filedate filedate DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'";
	remositoryRepository::doSQL($sql);
	$sql = "ALTER TABLE #__downloads_temp CHANGE submitdate submitdate DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'";
	remositoryRepository::doSQL($sql);
	$sql = "ALTER TABLE #__downloads_temp CHANGE filedate filedate DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'";
	remositoryRepository::doSQL($sql);
	$sql = "ALTER TABLE #__downloads_log CHANGE date date DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'";
	remositoryRepository::doSQL($sql);
	$sql = "ALTER TABLE #__downloads_reviews CHANGE date date DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00'";
	remositoryRepository::doSQL($sql);

	?>
	<h2>You are strongly recommended to look at the Release notes in read_me.txt</h2>
	<p>
	More documentation can be found at <a href="http://www.remository.com" target="_blank">the Remository
	web site</a>.
	</p><p>
	Before Remository can be used, you must create at least one file category.  Use the admin interface
	via the pull down menus to select "Show Categories" and then use the "Add" option.  It is also advisable
	to use the pull down menus to select "Configuration" for Remository and review all the configuration
	options.  The default options should suffice to get you started.
	</p><p>
	Before a user can access Remository, you will have to add the component to a menu.  This
	is done through the admin interface, making sure that you select the "Component" option for the menu
	item and select "Remository" from the list of available components.
	</p>
	<?php

	return true;
}



?>
