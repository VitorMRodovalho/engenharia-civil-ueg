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

class listCpanelHTML extends remositoryAdminHTML {
	var $site = '';
	var $path = '';
	
	function listCpanelHTML (&$controller, $limit, $clist) {
		remositoryAdminHTML::remositoryAdminHTML($controller, $limit, $clist);
		$interface =& remositoryInterface::getInstance();
		$this->site = $interface->getCfg('live_site');
	}
	
	function display ($service) {
		$link = $this->site.'/administrator/index2.php?option=com_remository&act='.$service[1];
		echo "\n\t<div class='remositorycpitem' style='height:95px; width:75px; padding:5px; border:1px solid #999; margin:2px; float:left'>";
		echo "\n\t\t<a href='$link'>";
		echo "\n\t\t<img style='border:0' src='$this->site/components/com_remository/images/admin/{$service[2]}' />";
		echo "\n\t\t<div>{$service[0]}</div></a>";
		echo "\n\t<!-- End of remositorycpitem-->";
		echo "\n\t</div>";
	}
	
	function view ($ReMOSver) {

		$basic = array (
			array(_DOWN_ADMIN_ACT_CONTAINERS, 'containers', 'categories.png'),
			array(_DOWN_ADMIN_ACT_FILES, 'files', 'addedit.png'),
			array(_DOWN_ADMIN_ACT_GROUPS, 'groups', 'user.png'),
			array(_DOWN_ADMIN_ACT_UPLOADS, 'uploads', 'module.png'),
			array(_DOWN_ADMIN_ACT_CONFIG, 'config', 'config.png')
		);

		$handlefiles = array (
			array(_DOWN_ADMIN_ACT_UNLINKED, 'unlinked', 'langmanager.png'),
			array(_DOWN_ADMIN_ACT_FTP, 'ftp', 'sections.png'),
			array(_DOWN_ADMIN_ACT_ADDSTRUCTURE, 'addstructure', 'sections.png'),
			array(_DOWN_ADMIN_ACT_MISSING, 'missing', 'searchtext.png')
		);
		
		$housekeeping = array (
			array(_DOWN_ADMIN_ACT_COUNTS, 'counts', 'cpanel.png'),
			array(_DOWN_ADMIN_ACT_DOWNLOADS, 'downloads', 'cpanel.png'),
			array(_DOWN_ADMIN_ACT_PRUNE, 'prune', 'trash.png'),
			array(_DOWN_ADMIN_ACT_THUMBS, 'thumbs', 'mediamanager.png')
		);
		
		$specials = array (
			array(_DOWN_ADMIN_ACT_DBCONVERT, 'dbconvert', 'dbrestore.png'),
			array(_DOWN_ADMIN_ACT_DBCONVERT2, 'dbconvert2', 'dbrestore.png')
		);
		
		$info = array (
			array(_DOWN_ADMIN_ACT_STATS, 'stats', 'impressions.png'),
			array(_DOWN_ADMIN_ACT_ABOUT, 'about', 'credits.png'),
			array(_DOWN_ADMIN_ACT_SUPPORT, 'support', 'support.png')
		);

		$this->formStart('Control Panel');
		echo '</table>';
		
		$repository = remositoryRepository::getInstance();
		if ($repository->Use_Database) $status = "<span style='color:green'>"._DOWN_DATABASE."</span>";
		else $status = _DOWN_FILE_SYSTEM;
		$legend = _DOWN_ADMIN_CPANEL_STORE;
		echo "\n<div style='font-weight:bold; padding:5px; margin:5px; border:1px solid #999'>$legend $status</div>";
		if (is_writeable($repository->Down_Path)) $status = "<span style='color:green'>"._DOWN_WRITEABLE."</span>";
		else $status = "<span style='color:red'>"._DOWN_NOT_WRITEABLE."</span>";
		$legend = _DOWN_ADMIN_CPANEL_FILESTORE;
		echo "\n<div style='font-weight:bold; padding:5px; margin:5px; border:1px solid #999'>$legend $repository->Down_Path $status</div>";
		if (is_writeable($repository->Up_Path)) $status = "<span style='color:green'>"._DOWN_WRITEABLE."</span>";
		else $status = "<span style='color:red'>"._DOWN_NOT_WRITEABLE."</span>";
		$legend = _DOWN_ADMIN_CPANEL_UPLOADS;
		echo "\n<div style='font-weight:bold; padding:5px; margin:5px; border:1px solid #999'>$legend $repository->Up_Path $status</div>";

		echo "\n<div id='remositorycpbasic' style='width:640px; padding:10px;'>";
		echo "\n\t<h3 style='float:left; width:150px'>"._DOWN_CPANEL_SUB_BASIC."</h3>";
		foreach ($basic as $service) $this->display($service);
		echo "\n<!-- End of remositorycpbasic -->";
		echo "\n</div>";

		echo "\n<div id='remositorycpfiles' style='clear:left; width:640px; padding:2px;'>";
		echo "\n\t<h3 style='float:left; width:150px'>"._DOWN_CPANEL_SUB_FILES."</h3>";
		foreach ($handlefiles as $service) $this->display($service);
		echo "\n<!-- End of remositorycpfiles -->";
		echo "\n</div>";

		echo "\n<div id='remositorycphkeep' style='clear:left; width:640px; padding:2px;'>";
		echo "\n\t<h3 style='float:left; width:150px'>"._DOWN_CPANEL_SUB_HKEEP."</h3>";
		foreach ($housekeeping as $service) $this->display($service);
		echo "\n<!-- End of remositorycphkeep -->";
		echo "\n</div>";

		echo "\n<div id='remositorycpspecial' style='clear:left; width:640px; padding:2px;'>";
		echo "\n\t<h3 style='float:left; width:150px'>"._DOWN_CPANEL_SUB_UTILS."</h3>";
		foreach ($specials as $service) $this->display($service);
		echo "\n<!-- End of remositorycpspecial -->";
		echo "\n</div>";

		echo "\n<div id='remositorycpinfo' style='clear:left; width:640px; padding:2px;'>";
		echo "\n\t<h3 style='float:left; width:150px'>"._DOWN_CPANEL_SUB_INFO."</h3>";
		foreach ($info as $service) $this->display($service);
		echo "\n<!-- End of remositorycpinfo -->";
		echo "\n</div>";

	}
}

?>