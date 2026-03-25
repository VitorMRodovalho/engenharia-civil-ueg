<?php

/**************************************************************
* This file is part of Remository
* Copyright (c) 2006 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
* Remository started life as the psx-dude script by psx-dude@psx-dude.net
* It was enhanced by Matt Smith up to version 2.10
* Since then development has been primarily by Martin Brampton,
* with contributions from other people gratefully accepted
*/

// Don't allow direct linking
if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( 'Direct Access to this location is not allowed.' );

$remository_dir = str_replace('\\','/',dirname(__FILE__));
require_once($remository_dir.'/remository.interface.php');
$interface =& remositoryInterface::getInstance();
require_once($interface->getCfg('absolute_path').'/components/com_remository/remository.class.php');

function com_install()
{
	function permission_all_from_dir($Dir){
		// delete everything in the directory
		if ($handle = @opendir($Dir)) {
			while (($file = readdir($handle)) !== false) {
				if ($file == '.' || $file == '..') continue;
				$newpath = $Dir.$file;
				if (is_dir($newpath)) permission_all_from_dir($newpath.'/');
				else setFilePerms ($newpath);
			}
		}
		@closedir($handle);
		setDirPerms($Dir);
	}
	
	function setDirPerms ($dir) {
		$interface =& remositoryInterface::getInstance();
   		$origmask = @umask(0);
		if ($interface->getCfg('dirperms')) {
	    	$mode = octdec($interface->getCfg('dirperms'));
			$result = @chmod($dir, $mode);
		}
		else $result = @chmod($dir,0755);
		@umask($origmask);
		return $result;
	}
	
	function setFilePerms ($file) {
		$interface =& remositoryInterface::getInstance();
   		$origmask = @umask(0);
		if ($interface->getCfg('fileperms')) {
	    	$mode = octdec($interface->getCfg('fileperms'));
	    	$result = @chmod($file, $mode);
		}
		else $result = @chmod($file,0644);
		@umask($origmask);
		return $result;
	}
	
	function makeDefaultContainer () {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$database->setQuery("SELECT count(id) FROM #__downloads_containers");
		if (!$database->loadResult()) {
			$container = new remositoryContainer();
			$container->name = 'Sample';
			$container->description = 'Replace or modify this demonstration container as required';
			$container->published = 1;
			$container->saveValues();
		}
	}
	
	function makeMenuEntry () {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$database->setQuery("SELECT MIN(id) FROM `#__components` WHERE `option` = 'com_remository'");
		$remonum = intval($database->loadResult());
		$database->setQuery("SELECT count(*) FROM `#__menu` WHERE published > 0 AND link = 'index.php?option=com_remository'");
		if (!$database->loadResult()) {
			$database->setQuery("SELECT MAX(ordering) FROM `#__menu`");
			$ordering = intval($database->loadResult() + 1);
			$database->setQuery("INSERT INTO `#__menu` "
			." (`id`, `menutype`, `name`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`) "
			." VALUES (NULL , 'mainmenu', 'Remository', 'index.php?option=com_remository', 'components', '1', '0', $remonum, '0', $ordering, '0', '0000-00-00 00:00:00', '0', '0', '0', '0', '')");
			$database->query();
		}
		else {
			$database->setQuery("UPDATE #__menu SET componentid = $remonum WHERE link LIKE 'index.php?option=com_remository%'");
			$database->query();
		}
	}
	
	function convertDatabase () {
		$interface =& remositoryInterface::getInstance();
		$database =& $interface->getDB();
		$sql = 'ALTER TABLE `#__downloads_repository`'
		   	.' CHANGE `id` `id` int NOT NULL;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'DELETE FROM `#__downloads_repository WHERE id != 0';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_repository`'
		   	.' ADD `Use_Database` smallint NOT NULL default \'1\' AFTER `version`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_repository`'
		   	.' ADD `keywords` varchar(255) NOT NULL default \'\' AFTER `windowtitle`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_repository`'
		   	.' ADD `Large_Image_Width` smallint NOT NULL default \'600\' AFTER `Small_Image_Height`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_repository`'
		   	.' ADD `Large_Image_Height` smallint NOT NULL default \'600\' AFTER `Large_Image_Width`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_repository`'
		   	.' ADD `Max_Thumbnails` smallint NOT NULL default \'0\' AFTER `Favourites_Max`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_repository`'
		   	.' ADD `Allow_Large_Images` tinyint unsigned NOT NULL default \'1\' AFTER `Allow_Votes`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_repository`'
		   	.' ADD `download_text` text NOT NULL default \'\' AFTER `Time_Stamp`;';
		$database->setQuery($sql);
		$database->query();

		$sql = 'ALTER TABLE `#__downloads_files`'
			.' ADD `keywords` varchar(255) NOT NULL default \'\' AFTER `windowtitle`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_files`'
			.' ADD `userid` int NOT NULL default \'0\' AFTER `containerid`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_files`'
			.' ADD `download_text` text NOT NULL default \'\' AFTER `userupload`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_files`'
			.' ADD `chunkcount` int NOT NULL default \'0\' AFTER `isblob`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_files`'
			.' ADD `editgroup` smallint NOT NULL default \'0\' AFTER `groupid`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_files`'
			.' ADD `custom_1` varchar(255) NOT NULL default \'\' AFTER `editgroup`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_files`'
			.' ADD `custom_2` varchar(255) NOT NULL default \'\' AFTER `custom_1`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_files`'
			.' ADD `custom_3` text NOT NULL default \'\' AFTER custom_2;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_files`'
			.' ADD `custom_4` int NOT NULL default \'0\' AFTER custom_3;';
		$database->setQuery($sql);
		$database->query();

		$sql = 'ALTER TABLE `#__downloads_reviews`'
			.' ADD `keywords` varchar(255) NOT NULL default \'\' AFTER `windowtitle`;';
		$database->setQuery($sql);
		$database->query();

		$sql = 'ALTER TABLE `#__downloads_containers`'
			.' ADD `keywords` varchar(255) NOT NULL default \'\' AFTER `windowtitle`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_containers`'
			.' ADD `editgroup` smallint NOT NULL default \'0\' AFTER `groupid`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_containers`'
			.' ADD `adminauto` tinyint unsigned NOT NULL default \'0\' AFTER `editgroup`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_containers`'
			.' ADD `userauto` tinyint unsigned NOT NULL default \'0\' AFTER `adminauto`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_containers`'
			.' ADD `autogroup` smallint NOT NULL default \'0\' AFTER `userauto`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_containers`'
			.' ADD `userid` int NOT NULL default \'0\' AFTER `autogroup`;';
		$database->setQuery($sql);
		$database->query();

		$sql = 'ALTER TABLE `#__downloads_temp`'
			.' ADD `keywords` varchar(255) NOT NULL default \'\' AFTER `windowtitle`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_temp`'
			.' ADD `userid` int NOT NULL default \'0\' AFTER `containerid`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_temp`'
			.' ADD `download_text` text NOT NULL default \'\' AFTER `userupload`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_temp`'
			.' ADD `chunkcount` int NOT NULL default \'0\' AFTER `isblob`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_temp`'
			.' ADD `editgroup` smallint NOT NULL default \'0\' AFTER `groupid`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_temp`'
			.' ADD `custom_1` varchar(255) NOT NULL default \'\' AFTER `editgroup`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_temp`'
			.' ADD `custom_2` varchar(255) NOT NULL default \'\' AFTER `custom_1`;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_temp`'
			.' ADD `custom_3` text NOT NULL default \'\' AFTER custom_2;';
		$database->setQuery($sql);
		$database->query();
		$sql = 'ALTER TABLE `#__downloads_temp`'
			.' ADD `custom_4` int NOT NULL default \'0\' AFTER custom_3;';
		$database->setQuery($sql);
		$database->query();
	}
		
	$interface =& remositoryInterface::getInstance();
	$mosConfig_absolute_path = $interface->getCfg('absolute_path');
	$mosConfig_live_site = $interface->getCfg('live_site');
    
	if (is_writeable($mosConfig_absolute_path.'/administrator/images/')) {
    	if (!file_exists($mosConfig_absolute_path.'/administrator/images/approve.png')) copy($mosConfig_absolute_path.'/components/com_remository/images/approve.png', $mosConfig_absolute_path.'/administrator/images/approve.png');
    	if (!file_exists($mosConfig_absolute_path.'/administrator/images/approve_f2.png')) copy($mosConfig_absolute_path.'/components/com_remository/images/approve_f2.png', $mosConfig_absolute_path.'/administrator/images/approve_f2.png');
	}

    convertDatabase();
    
	$repository =& remositoryRepository::getInstance();

	if (file_exists($mosConfig_absolute_path.'/classes')) {
		permission_all_from_dir($mosConfig_absolute_path.'/components/com_remository/');
		permission_all_from_dir($mosConfig_absolute_path.'/administrator/components/com_remository/');
	}
	if (!file_exists($mosConfig_absolute_path.'/components/com_remository_files')) {
		@mkdir($mosConfig_absolute_path.'/components/com_remository_files', 0755);
		setDirPerms($mosConfig_absolute_path.'/components/com_remository_files');
	}
	$settingok = setFilePerms($mosConfig_absolute_path.'/components/com_remository/com_remository_settings.php');
	@mkdir($repository->Down_Path, 0755);
	$downisok = setDirPerms ($repository->Down_Path);
	@mkdir($repository->Up_Path, 0755);
	$upisok = setDirPerms ($repository->Up_Path);
	
	makeDefaultContainer();
	makeMenuEntry();
	
	?>
	<h3>You are strongly recommended to look at the Release notes in read_me.txt</h3>
	<h3>
	More information can be found at <a href="http://www.remository.com">the Remository	web site</a>.
	</h3>
	<h3>Support Remository</h3>
	<p>	Developing and maintaining Remository takes a lot of time and a certain amount of
		money to provide a development environment.  If you are gaining financial benefit
		from your use of Remository, please consider making a donation to support the project.
		Click on the button below to make an immediate payment:
	</p>
	<p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
	<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
	<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
	</form>
	</p>
	<?php

	return true;
}

?>