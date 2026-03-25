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

class remositoryFileInfoHTML extends remositoryUserHTML {
	
	function showComment (&$legend, &$comment) {
		echo "\n\t\t\t<dt>$legend</dt>";
		$legend = '';
		if ($this->tabcnt == 0) $class = 'remositorylight';
		else $class='remositorydark';
		echo "\n\t\t\t<dd class='$class'><em>$comment->name $comment->date</em> $comment->comment</dd>";
		$this->tabcnt = ($this->tabcnt+1) % 2;
	}
	
	function commentBox ($file) {
		$action = $this->repository->RemositoryBasicFunctionURL('fileinfo',$file->id);
   		echo "<form method='post' action='$action'>";
		echo "\n\t\t\t<dt>"._DOWN_YOUR_COMM; 
		echo '<p><em>'._DOWN_MAX_COMM.'</em></p>';
		echo '</dt><dd>';
		echo "<textarea class='inputbox' name='comment' rows='2' cols='35'></textarea>";
		echo "\n\t\t\t<input class='button' type='submit' name='submit_comm' value='"._DOWN_LEAVE_COMM."' />";
		echo "\n\t\t\t<input type='hidden' name='id' value='$file->id' />";
		echo "\n\t\t\t</form></dd>";
	}
	
	function URLDisplay ($text, $value) {
		echo "\n\t\t\t<dt>$text</dt>";
		echo "\n\t\t\t<dd><a href='$value'>"._DOWN_CLICK_TO_VISIT.'</a></dd>';
	}

	function videoPlayer ($link, $title) {
		?>
		<!-- id=MediaPlayer1 -->
		<object id=mediaplayer1 type=application/x-oleobject
                  height="24" width="320"
                  classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6">
		<param name="url" value="<?php echo $link ?>">
		<param name="animationatstart" value="true">
		<param name="transparentatstart" value="true">
		<param name="autostart" value="false">
		<param name="showcontrols" value="true">

		<embed type="application/x-mplayer2" name="mediaplayer"  autostart="false" loop="false"  width="384" height="364"
       		src="<?php echo $link ?>" showcontrols="true">
		</embed>
		</object>
		<?php
	}
	
	function audioPlayer ($link, $title) {
		?>
		<!-- id=FlashMediaPlayer1 -->
		<br />When the controls apppear, please click on the play icon<br /><br />
		<object type="application/x-shockwave-flash" width="400" height="15"
		data="http://musicplayer.sourceforge.net/xspf_player_slim.swf?song_url=<?php echo $link ?>&song_title=<?php echo urlencode($title) ?>">
		<param name="movie" 
		value="http://musicplayer.sourceforge.net/xspf_player_slim.swf?song_url=<?php echo $link ?>&song_title=<?php echo urlencode($title) ?>" />
		</object>
		<?php
	}
	
	// This is the function that creates the output for a file details page
	function fileinfoHTML( &$file, $autodown=0 ) {
		$is_video = in_array($file->filetype, array());
		$is_audio = in_array($file->filetype, array());
		$file->setMetaData();
		$thumbnails = new remositoryThumbnails($file);
		$submitter = new remositoryUser($file->submittedby,null);
		$this->pathwayHTML($file->getContainer());
		$forbidden = $file->downloadForbidden($this->remUser, $message);
		if ($forbidden AND !$this->repository->See_Files_no_download) {
			echo $message;
			return;
		}
		$downloadstuff = $file->filetitle.' ';
		if ($file->updatePermitted($this->remUser)) {
			$usersubmitstuff = $this->repository->RemositoryFunctionURL('userupdate',$file->id);
			$usersubmitstuff .= $this->repository->RemositoryImageURL('edit.gif',32,32);
			$usersubmitstuff .= _DOWN_UPDATE_SUB.'</a>';
		}
		else $usersubmitstuff = '';
		$thumbupdatestuff = '';
		if ($this->repository->Max_Thumbnails) {
			if ($this->remUser->isAdmin() OR
			($this->repository->Allow_User_Edit AND $this->remUser->isLogged() AND
			($this->remUser->id==$file->submittedby OR ($file->editgroup != 0 AND remositoryGroup::isUserMember($file->editgroup,$this->remUser))))) {
				$thumbupdatestuff = $this->repository->RemositoryFunctionURL('thumbupdate',$file->id);
				$thumbupdatestuff .= $this->repository->RemositoryImageURL('edit.gif',32,32);
				$thumbupdatestuff .= _DOWN_UPDATE_THUMBNAILS.'</a>';
			}
		}
		$thumbimages = $thumbnails->displayAllThumbnails();

		echo "\n\t<div id='remositoryfileinfo'>";
		if (!$forbidden) {
			echo "\n";
			?>
		<script type="text/javascript">
		function download(){window.location = <?php echo $file->downloadURL($autodown); ?>}
		</script>
			<?php
			$syndstyle = (remositoryRepository::GetParam($_GET, 'syndstyle', '') == 'yes');
		    if ($autodown == 1 AND !$syndstyle AND !$is_video AND !$is_audio) echo '<script type="text/javascript"> window.onload=download; </script>';
		    if ($autodown AND $syndstyle) {
				echo '<p><strong>In Firefox you can drag and drop the "Download" link to the "Install HTTP URL" box above, in IE you have to right click on "Download" and use "Copy shortcut" and paste into the box above, then click the "Upload URL & Install" button</strong></p>';
			}
			$downloadstuff .= $file->downloadLink($autodown);
			$downloadstuff .= $this->repository->RemositoryImageURL('download_trans.gif');
			$downloadstuff .= '<strong> '._DOWNLOAD.'</strong></a>';
		}
		echo "\n\t\t<h2>$downloadstuff</h2>";
		if ($autodown) {
		    echo '<h3>'._DOWN_THANK_YOU.htmlspecialchars($file->filetitle).'</h3>';
		    if (!$syndstyle) {
		    	if ($is_video) $this->videoPlayer($file->basicDownloadLink($autodown), $file->filetitle);
		    	elseif ($is_audio) $this->audioPlayer($file->basicDownloadLink($autodown), $file->filetitle);
		    	else echo '<h4>'._DOWN_WAIT_OR_CLICK.'</h4>';
		    }
		    if ($file->download_text) echo $file->download_text;
		    else echo $this->repository->download_text;
		}
		if ($thumbupdatestuff OR $thumbimages OR $usersubmitstuff) {
			echo "\n\t\t<div id='remositorythumbbox'>";
			echo "\n\t\t\t<div id='remositorycmdbox'>";
			echo "\n\t\t\t<p>"._DOWN_THUMBNAILS."</p>";
			if ($thumbupdatestuff) echo "\n\t\t\t<p class='remositorycommand'>$thumbupdatestuff</p>";
			if ($usersubmitstuff) echo "\n\t\t\t<p class='remositorycommand'>$usersubmitstuff</p>";
			echo '</div>';
			echo "$thumbimages";
			echo "\n\t\t<!-- End of remositorythumbbox -->";
			echo "\n\t\t</div>";
		}
		echo "\n\t\t<dl>";
		if ($this->remUser->isAdmin()) $this->fileOutputBox(_DOWN_PUB, ($file->published ? _YES : _NO));
		if ($file->description) $this->fileOutputBox(_DOWN_DESC, $file->description, false);
		if (($file->licenseagree==0) AND ($file->license<>'')) $this->fileOutputBox (_DOWN_LICENSE, $file->license);
		if ($file->submitdate<>'') $this->fileOutputBox (_DOWN_SUB_DATE, date ($this->repository->Date_Format, $this->controller->revertFullTimeStamp($file->submitdate)));
		if ($file->submittedby<>'') $this->fileOutputBox (_DOWN_SUB_BY, $submitter->fullname().' ('.$submitter->name.')');
		if ($file->filedate<>'') $this->fileOutputBox (_DOWN_FILE_DATE, date($this->repository->Date_Format,$this->controller->revertFullTimeStamp($file->filedate)));
		if ($file->fileauthor<>'') $this->fileOutputBox (_DOWN_FILE_AUTHOR, $file->fileauthor);
		if ($file->fileversion<>'') $this->fileOutputBox (_DOWN_FILE_VER, $file->fileversion);
		if ($file->filesize<>'') $this->fileOutputBox (_DOWN_FILE_SIZE, $file->filesize);
		if ($file->filetype<>'') $this->fileOutputBox (_DOWN_FILE_TYPE, $file->filetype);
		if ($file->filehomepage<>'') $this->URLDisplay (_DOWN_FILE_HOMEPAGE, $file->filehomepage);
		$this->fileOutputBox (_DOWN_DOWNLOADS, $file->downloads);
		if ($this->repository->Allow_Votes) $this->voteDisplay($file, true);
		// The following block of code provides the comment facility
		// If you want to replace it with Jom Comment, remove this block and replace it with:
		// $interface =& remositoryInterface::getInstance();
		// include_once($interface->getCfg('absolute_path').'/mambots/content/jom_comment_bot.php');
		// echo jomcomment($file->id, "com_remository");
		if ($this->repository->Allow_Comments) {
			$commentsdb = $file->getComments();
			if ($commentsdb){
				$this->tabcnt = 1;
				$legend = _DOWN_COMMENTS;
				foreach ($commentsdb as $comment) $this->showComment($legend, $comment);
			}
			else {
				$legend = $this->remUser->isLogged() ? _DOWN_FIRST_COMMENT : _DOWN_FIRST_COMMENT_NL;
				$this->fileOutputBox('', '<strong>'.$legend.'</strong>');
			}
			if ($this->remUser->isLogged()) $this->commentBox($file);
//			include_once('components/com_reviews/reviews.class.php');
//			include_once('components/com_reviews/reviews.html.php');
//			echo HTML_reviews::listItemCommentsHTML('com_remository',$file->id);
//			echo HTML_reviews::solicitCommentHTML('com_remository', $file->id, "&func=fileinfo&id=$file->id");
		}
		// End of code for Remository comment facility
		echo "\n\t</dl>";
		echo "\n\t<!-- End of remositoryfileinfo -->";
		echo "\n\t</div>";
		if ($file->plaintext) {
		echo "\n\t\t<div id='remositoryplaintext'>";
		  	highlight_string($file->getPlainText());
		  	echo "\n\t\t</div>";
		}
	}
}

?>