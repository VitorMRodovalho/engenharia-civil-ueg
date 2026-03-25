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

class remositoryUserHTML {
	var $controller = '';
	var $repository = '';
	var $interface = '';
	var $remUser = '';
	var $submitok = false;
	var $submit_text = '';
	var $orderby = 2;
	var $mainpicture = '';

	function remositoryUserHTML (&$controller) {
		$this->controller = $controller;
		$this->interface =& remositoryInterface::getInstance();
		$this->repository = $controller->repository;
		$this->mainpicture = $this->repository->headerpic;
		$thumb_width_x = $this->repository->Small_Image_Width + 40;
		$thumb_height_y = $this->repository->Small_Image_Height + 50;
		
		$css = <<<end_css
<style type='text/css'>
/* Remository specific CSS requiring variables */
#remositorypageheading {
	background-image:	url($this->mainpicture);
}
.remositoryfilesummary
{
	margin-right:	{$thumb_width_x}px;
}
.remositorydelthumb {
	height:		{$thumb_height_y}px;
}
/* End of variable Remository CSS */
</style>
end_css;

		$this->interface->addCustomHeadTag($css);
		
		$basepath = $this->interface->getCfg('live_site');
		$css = "\n<link href='$basepath/components/com_remository/remository.css' rel='stylesheet' type='text/css'>";
		$this->interface->addCustomHeadTag($css);
		
		$this->remUser = $controller->remUser;
		$this->submitok = $controller->submitok;
		$this->submit_text = $controller->submit_text;
		$this->orderby = $controller->orderby;
	}

	function fileOutputBox ($title, $value, $suppressHTML=false) {
		echo "\n\t\t\t<dt>$title</dt>";
		echo "<dd>";
		if ($suppressHTML) echo htmlspecialchars($value); 
		else echo $value;
		echo '</dd>';
	}
	
	function mainPageHeading ($belowTop) {
		$title = _DOWNLOADS_TITLE;
		if ($title OR $this->mainpicture) {
			if ($belowTop) $headlevel = 'h3';
			//else $headlevel = 'h2';
			//echo "\n\t<div id='remositorypageheading'>";
			// if ($this->mainpicture != '') echo "\n\t\t<img src='$this->mainpicture' alt='Header'/>";
			//echo "\n\t\t<$headlevel>$title ";
			// The following three lines create RSS links - remove if not required
			//$rssurl = $this->repository->RemositoryFunctionURL('rss');
			//$this->interface->addCustomHeadTag("<link rel='alternate' type='application/rss+xml' title='RSS - "._DOWN_NEWEST."' href='$rssurl' />");
			//echo $rssurl.$this->repository->RemositoryImageURL('feed-icon-32x32.gif')." RSS</a>";
			// End of RSS link code
			echo "</$headlevel>";
			echo "\n\t<!-- End of remositorypageheading-->";
			echo "\n\t</div>\n";
		}
	}

	function remositoryCredits () {
		echo "\n\t<div id='remositorycredits'>";
		echo "\n\t\t<a href='http://www.remository.com'>Remository 3.40.</a> ";
		echo "\n\t<!-- End of remositorycredits-->";
		echo "\n\t</div>\n";
	}

	function pathwayHTML ($parent) {
		echo "\n\t<div id='remositorypathway'>";
		echo "\n\t\t".$this->repository->RemositoryFunctionURL().$this->repository->RemositoryImageURL('gohome.gif').' '._MAIN_DOWNLOADS.'</a>';
		if ($parent) {
			echo "\n\t\t";
			$parent->showPathway();
		}
		echo "\n\t<!-- End of remositorypathway-->";
		echo "\n\t</div>\n";
	}

	function filesFooterHTML () {
		echo "\n\t<div id='remositoryfooter'>";
		echo "\n\t\t<div id='left'>";
		echo $this->repository->RemositoryFunctionURL('search'); 
		echo $this->repository->RemositoryImageURL('search.gif'); 
		echo _DOWN_SEARCH.'</a></div>';
		
		echo "\n\t\t<div id='right'>";
		if ($this->submitok) {
			$idparm = remositoryRepository::GetParam($_REQUEST, 'id', 0);
			echo $this->repository->RemositoryFunctionURL('addfile', $idparm);
		}
		echo $this->repository->RemositoryImageURL('add_file.gif');
		if ($this->submitok) echo _SUBMIT_FILE_BUTTON.'</a>'; 
		else echo $this->submit_text;
		echo '</div>';
		echo "\n\t<!-- End of remositoryfooter-->";
		echo "\n\t</div>\n";
	}

	function fileListing ($file, $container, $downlogo, $remUser, $showContainer = false) {
		$thumbnails = new remositoryThumbnails($file);
		$downlink = $this->repository->RemositoryFunctionURL('fileinfo',$file->id);
		if ($file->icon == '') $downlink .= $this->repository->RemositoryImageURL('stuff1.gif');
		else $downlink .= $this->repository->RemositoryImageURL('file_icons/'.$file->icon);
		$downlink .= $file->filetitle.'</a>';
		if ($this->repository->Enable_List_Download AND is_object($container) AND $container->isDownloadable($this->remUser)) {
				$downlink .= $file->downloadLink(0).' '.$downlogo.' '._DOWNLOAD.'</a>';
		}
		if ($showContainer AND is_object($container)) $downlink .= ' ('.remositoryRepository::RemositoryFunctionURL('select', $container->id).$container->name.'</a>)';
		echo "\n\t\t<div class='remositoryfileblock'>";
		echo "\n\t\t\t<h3>$downlink</h3>";
		echo "\n\t\t\t<div class='remositoryonethumb'>";
		echo "\n\t\t\t".$thumbnails->displayOneThumbnail();
		echo "\n\t\t\t<!-- End of remositoryonethumb -->";
		echo "\n\t\t\t</div>";
		echo "\n\t\t\t<div class='remositoryfilesummary'><dl>";
		
		if ($remUser->isAdmin()) $this->fileOutputBox(_DOWN_PUB, ($file->published == 1 ? _YES : _NO), false);

		// The following lines show file details - comment out any that are not wanted (use // in front)
		if ($file->smalldesc<>'') $this->fileOutputBox(_DOWN_DESC_SMALL, $file->smalldesc, !$file->autoshort);
		if ($file->submitdate<>'') $this->fileOutputBox(_DOWN_SUB_DATE, date ($this->repository->Date_Format, $this->controller->revertFullTimeStamp($file->submitdate)));
		if ($file->filesize<>'') $this->fileOutputBox(_DOWN_FILE_SIZE, $file->filesize);
		$this->fileOutputBox(_DOWN_DOWNLOADS, $file->downloads);

		if ($this->repository->Allow_Votes) $this->voteDisplay($file, false);
		echo "\n\t\t\t<!-- End of remositoryfilesummary -->";
		echo "\n\t\t\t</dl></div>";
		echo "\n\t\t<!-- End of remositoryfileblock -->";
		echo "\n\t\t</div>";
	}

	function voteDisplay (&$file, $entry) {
		echo "\n";
		?>
			<dt><?php echo _DOWN_RATING; ?></dt>
			<dd>
				<div><?php echo $this->repository->RemositoryImageURL('stars/'.$file->evaluateVote().'.gif',64,12);
					echo _DOWN_VOTES;
					echo round($file->vote_count); ?></div>
		<?php
		if ($entry AND $this->remUser->isLogged() AND !$file->userVoted($this->remUser)) {
			$formurl = sefRelToAbs('index.php?option=com_remository&Itemid='.$this->interface->getCurrentItemid().'&func=fileinfo&id='.$file->id);
			?>
				<div>
					<form method="post" action="<?php echo $formurl; ?>">
						<select name="user_rating" class="inputbox">
							<option value="0">?</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
						<input class="button" type="submit" name="submit_vote" value="<?php echo _DOWN_RATE_BUTTON; ?>" />
						<input type="hidden" name="id" value="<?php echo $file->id; ?>" />
					</form>
				</div>
			<?php
		}
		echo "\n\t\t\t\t</dd>";
	}

	// Not presently used in Remository, but kept here for potential value of the code
	function multiOptionList ($name, $title, $options, $current, $tooltip=null) {
		$alternatives = explode(',',$options);
		$already = explode(',', $current);
		?>
		<tr>
	    <td width="30%" valign="top" align="right">
	  	<b><?php echo $title; ?></b>&nbsp;
	    </td>
	    <td valign="top">
		<?php
		foreach ($alternatives as $one) {
			if (in_array($one,$already)) $mark = 'checked="checked"';
			else $mark = '';
			$value = $name.'_'.$one;
			echo "<input type=\"checkbox\" name=\"$value\" $mark />$one";
		}
		if ($tooltip) echo '&nbsp;'.tooltip($tooltip);
		echo '</td></tr>';
	}

	function tooltip ($text) {
		return '<a href="javascript:void(0)"  onmouseover="return escape('."'".$text."'".')">'.RemositoryRepository::RemositoryImageURL('tooltip.png').'</a>';
	}

}

?>