<?php

// Part of Remository, copyright (c) Martin Brampton 2005.
// For further information please refer to http://www.remository.com

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* @package MOS
* @version $Revision: 3.24 $
*/

class remositoryPage {
	var $baseurl = '';
	var $itemcount = 0;
	var $itemsperpage = 10;
	var $startItem = 1;
	var $currentpage = 1;
	var $pagetotal = 1;

	function remositoryPage (&$container, &$remUser, $itemsperpage, $page, $orderby) {
		global $Itemid;
		$this->baseurl = "index.php?option=com_remository&Itemid=$Itemid&func=select&id=$container->id&orderby=$orderby&page=";
		$this->itemcount = $container->getFilesCount('', $remUser);
		$this->itemsperpage = $itemsperpage;
		$this->startItem = 1;
		$this->finishItem = $itemsperpage;
		$this->pagetotal = ceil($this->itemcount/$this->itemsperpage);
		$this->setPage($page);
	}

	function setPage ($currentpage) {
		$this->currentpage = $currentpage;
		$basecount = ($currentpage - 1) * $this->itemsperpage;
		$this->startItem = $basecount;
	}

	function pageTitle ($page, $special=null) {
		echo 'title="';
		if ($special) echo $special;
		else echo 'Show results ';
		$finish = $page * $this->itemsperpage;
		$start = $finish - $this->itemsperpage + 1;
		if ($finish > $this->itemcount) $finish = $this->itemcount;
		echo $start.' to '.$finish.' of '.$this->itemcount.'"';
	}

	function showNavigation ($tabclass) {
		if ($this->itemcount <= $this->itemsperpage) return;
		?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" >
		<tr>
		<td> </td>
		<td align="right">
		<table cellpadding="3" cellspacing="1" border="1">
		<tr>
		<td align="right" class="<?php echo $tabclass[1].'">';
		$lowpage = $this->currentpage - _PAGE_SPREAD;
		if ($lowpage < 1) $lowpage = 1;
		$highpage = $this->currentpage + _PAGE_SPREAD;
		if ($highpage > $this->pagetotal) $highpage = $this->pagetotal;
		echo 'Page '.$this->currentpage.' of '.$this->pagetotal.'</td>';
		$previous = $this->currentpage - 1;
		if ($previous) {
			$url = sefRelToAbs($this->baseurl.$previous);
			echo "\n<td class='".$tabclass[0]."'><a href='$url' ";
			$this->pageTitle($previous,'Prev page - Results ');
			echo '> &lt;</a></td>';
		}
		$page = $lowpage;
		while ($page <= $highpage) {
			if ($page == $this->currentpage) echo "\n<td><b>".$page.'</b></td>';
			else {
				$url = sefRelToAbs ($this->baseurl.$page);
				echo "\n<td class='".$tabclass[0]."'><a href='$url' ";
				$this->pageTitle($page);
				echo "> $page</a></td>";
			}
			$page++;
		}
		$next = $this->currentpage + 1;
		if ($next <= $this->pagetotal) {
			$url = sefRelToAbs($this->baseurl.$next);
			echo "\n<td class='".$tabclass[0]."'><a href='$url' ";
			$this->pageTitle($next,'Next page - Results ');
			echo '> &gt;</a></td>';
		}
		if ($this->pagetotal > $highpage) {
			$url = sefRelToAbs($this->baseurl.$this->pagetotal);
			echo "\n<td class='".$tabclass[0]."'><a href='$url'";
			$this->pageTitle($this->pagetotal,'Last page - Results ');
			echo '> &raquo;</a></td>';
		}
		?>
		</tr>
		</table>
		</td></tr></table>
		<?php
	}

	function startItem () {
		return $this->startItem;
	}

}

/**
* Utility class for writing the HTML for downloads
*/

class HTML_downloads {

	function filesHeaderHTML( $parent )
	{
		$repository = new remositoryRepository ('GLOBAL');
		?>
		<br/><?php echo $repository->RemositoryFunctionURL(); echo $repository->RemositoryImageURL('gohome.png'); echo '&nbsp;'._MAIN_DOWNLOADS; ?></a>&nbsp;
		<?php
		if ($parent) $parent->showPathway();
	}

	function frontHTML( &$categories, $submitok, $submit_text, &$repository, &$remUser )
	{

		global $Itemid;

		$tabclass_arr = $repository->getTableClasses();
		$mainpicture = $repository->headerpic;
		if (!remositoryContainer::getContainerCount()) {
			?><br/><?php echo _DOWN_NO_CATS;
			return;
		}
			
		if ($categories) {
			$tabcnt = 0;
			?>
			<table width='100%' border="0" cellpadding="0" cellspacing="0">
			<?php if (_DOWNLOADS_TITLE != '' OR $mainpicture != '') { ?>
				<tr>
					<td width='100%' align='center'>
					<?php if ($mainpicture != '') { ?>
					<img src='<?php echo $repository->headerpic; ?>' border='0' align='middle' alt='Header'/>
					<?php }
					if (_DOWNLOADS_TITLE != '') { ?>
					<h5>&nbsp;<?php echo _DOWNLOADS_TITLE; ?></h5>
					<?php } ?>
					</td>
				</tr>
				<?php } ?>
				<tr class="<?php echo $tabclass_arr[1]; ?>">
					<td width="80%">
					<b><?php echo _DOWN_CATEGORY; ?></b>
					</td>
					<td width="20%" align="right">
					<b><?php echo _DOWN_FOLDERS_FILES; ?></b>
					</td>
				</tr>
				<tr>
					<td>
					&nbsp;
					</td>
				</tr>
			<?php
			foreach ($categories as $category)
			{
				?>
				<tr class="<?php echo $tabclass_arr[$tabcnt]; ?>">
					<td width="80%">
						<div class="componentheading">
	   	       			<?php echo $repository->RemositoryFunctionURL('select',$category->id);
						if ($category->icon=='') echo $repository->RemositoryImageURL('folder_icons/cgeneric.png');
						else echo $repository->RemositoryImageURL('folder_icons/'.$category->icon);
						echo '&nbsp;'.$category->name; ?></a></div>
					</td>
					<td align="right">
						&nbsp;(<?php echo $category->foldercount.'/'.$category->filecount; ?>)
					</td>
				</tr>
				<tr class="<?php echo $tabclass_arr[$tabcnt]; ?>">
					<td colspan="2">
						<?php echo $category->description; ?>
					</td>
				</tr>
				<?php
				$tabcnt = ($tabcnt + 1) % 2;
			};
			?>
		</table>

		<br/>&nbsp;<br/>&nbsp;<br/>
		<table width='100%' border='0' cellspacing='0' cellpadding='0' class='<?php echo $tabclass_arr[1]; ?>'>
			<tr>
				<td width='50%'><?php echo $repository->RemositoryFunctionURL('search'); echo $repository->RemositoryImageURL('search.gif',16,15); echo _DOWN_SEARCH; ?></a>
				</td>
				<td width='50%'>
				<?php
				if ($remUser->isAdmin() OR $repository->Allow_User_Sub) {
					if ($submitok) echo $repository->RemositoryFunctionURL('addfile');
					echo $repository->RemositoryImageURL('add_file.gif');
					if ($submitok) echo _SUBMIT_FILE_BUTTON.'</a>'; else echo $submit_text;
				} ?>
				</td>
			</tr>
		</table><br/>
		<?php	
	  	} else echo '<br/>'._DOWN_NO_VISITOR_CATS.'<br/>&nbsp;<br/>';
		?>
		<table width='100%' border='0' cellspacing='0' cellpadding='0' ><tr><td width='100%' align='center'><span class="small"><a href="http://www.remository.com" target="_blank">Remository 3.24.</a> is technology by <a href="http://www.black-sheep-research.com" target="_blank">Black Sheep Research</a></span></td></tr></table>
		<?php
	}

function downloadHTML( &$file, &$repository, $userid ) {
	
		global $mosConfig_live_site, $mosConfig_absolute_path, $Itemid;

		if (($file->licenseagree) AND ($file->license<>'')){
			$chk=$repository->makeCheck($file->id,'downloadagree');
			?>
			<script type="text/javascript">
				//"Accept terms" form submission- By Dynamic Drive
				//For full source code and more DHTML scripts, visit http://www.dynamicdrive.com
				//This credit MUST stay intact for use
				var checkobj
				function agreesubmit(el){
				checkobj=el
				if (document.all||document.getElementById){
				for (i=0;i<checkobj.form.length;i++){  //hunt down submit button
				var tempobj=checkobj.form.elements[i]
				if(tempobj.type.toLowerCase()=="submit")
				tempobj.disabled=!checkobj.checked
				}
				}
				}
			</script>
			<form name="agreeform" method="POST" action="index.php?option=com_remository&amp;Itemid=<?php echo $Itemid; ?>&amp;id=<?php echo $file->id; ?>&amp;func=downloadagree&amp;da=<?php echo $chk; ?>">
		<?php echo htmlspecialchars($file->license); ?><br/>
			<input name="agreecheck" type="checkbox" onClick="agreesubmit(this)"><b><?php echo _DOWN_LICENSE_CHECKBOX; ?></b><br/>
			<input class="button" type="Submit" value="<?php echo _DOWNLOAD; ?>" disabled>
			<input type="hidden" name="da" value="<?php echo $repository->makeCheck($file->id,'downloadagree'); ?>">
			</form>
			<script>
			document.forms.agreeform.agreecheck.checked=false
			</script>
			<?php
		} else {
			$chk=$repository->makeCheck($file->id,'startdown');
			header("Location: $mosConfig_live_site/components/com_remository/com_remository_startdown.php?id=$file->id&chk=$chk&userid=$userid");
		}
	}

	function selectContainerHTML( &$container, &$folders, &$files, $orderby, &$remUser, &$page )
	{
		global $Itemid, $mosConfig_live_site;

		$repository = new remositoryRepository ('GLOBAL');
		$tabclass_arr = $repository->getTableClasses();
		$idparm = $container->id;
		?>
		<br/><div class='sectiontableheader'><?php echo htmlspecialchars($container->name); ?></div>
		<?php echo $container->description; ?><br/><br/><?php
		if ($folders){
			$tabcnt = 0;
			?>
			<table width='100%' border="0" cellpadding="0" cellspacing="0" >
			<tr class="<?php echo $tabclass_arr[1]; ?>">
				<td width="80%">
					<b><?php echo _DOWN_FOLDERS; ?></b>
				</td>
				<td width="30%" align="right">
					<b><?php echo _DOWN_FOLDERS_FILES; ?></b>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<?php
			foreach ($folders as $folder)
			{
  			?>
				<tr class="<?php echo $tabclass_arr[$tabcnt]; ?>">
					<td width="80%"><div class="componentheading">
         			<?php echo $repository->RemositoryFunctionURL('select',$folder->id);
					if ($folder->icon=='') echo $repository->RemositoryImageURL('folder_icons/generic.png');
					else echo $repository->RemositoryImageURL('folder_icons/'.$folder->icon);
  					echo '&nbsp;'.htmlspecialchars($folder->name); ?></a></div>
					</td>
					<td align="right">
							&nbsp;<?php echo '('.$folder->foldercount.'/'.$folder->filecount.')'; ?>
					</td>
				</tr>
				<tr class="<?php echo $tabclass_arr[$tabcnt]; ?>">
					<td colspan="2">
						<?php echo $folder->description; ?>
					</td>
				</tr>
				<?php
				$tabcnt = ($tabcnt+1) % 2;
			}
			?>
			<tr>
			<td align="center" colspan="2">
				<br/>&nbsp;<hr/>
			</td>
			</tr>
			</table>
			<?php
		}
		if ($files){
			$tabcnt = 0;
			?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr class="<?php echo $tabclass_arr[1]; ?>">
				<td width="100%">
					<b><?php echo _DOWN_FILES; ?></b>
				</td>
			</tr>
			<tr class="<?php echo $tabclass_arr[1]; ?>">
				<td>
					<i><?php
					echo _DOWN_ORDER_BY;
					?></i>
					<?php if ($orderby<>1) {echo $repository->RemositoryFunctionURL('select',$idparm,null,'1'); echo _DOWN_ID; ?></a><?php } else { echo _DOWN_ID;} ?> |
					<?php if ($orderby<>2) {echo $repository->RemositoryFunctionURL('select',$idparm,null,'2'); echo _DOWN_FILE_TITLE_SORT; ?></a><?php } else { echo _DOWN_FILE_TITLE_SORT;} ?> |
					<?php if ($orderby<>3) {echo $repository->RemositoryFunctionURL('select',$idparm,null,'3'); echo _DOWN_DOWNLOADS_SORT; ?></a><?php } else { echo _DOWN_DOWNLOADS_SORT;} ?> |
					<?php if ($orderby<>4) {echo $repository->RemositoryFunctionURL('select',$idparm,null,'4'); echo _DOWN_SUB_DATE_SORT; ?></a><?php } else { echo _DOWN_SUB_DATE_SORT;} ?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			</table>
			<?php
			$downlogo = $repository->RemositoryImageURL('download_trans.gif');
			$page->showNavigation($tabclass_arr);
			foreach ($files as $file) {
				?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="<?php echo $tabclass_arr[$tabcnt]; ?>">
				<tr>
					<td width ="100%" valign="top" colspan="3">
					<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="60%">&nbsp;<br/>
						<?php echo $repository->RemositoryFunctionURL('fileinfo',$file->id);
						if ($file->icon=='') echo $repository->RemositoryImageURL('file_icons/generic.png');
						else echo $repository->RemositoryImageURL('file_icons/'.$file->icon);
						echo '<b>&nbsp;'.htmlspecialchars($file->filetitle); ?></b></a><br/>&nbsp;
					</td>
					<td align="right" valign="top">&nbsp;<br/> <?php
					if ($repository->Enable_List_Download AND $container->isDownloadable($remUser)) {
						$downlink = $repository->RemositoryFunctionURL('download',$file->id);
						if ($file->islocal) $addon = ' rel="nofollow">';
						else $addon = ' rel="nofollow" target="_blank">';
						$downlink = substr($downlink,0,strlen($downlink)-1).$addon;
						echo $downlink.'<b>'._DOWNLOAD.'</b>&nbsp;'.$downlogo.'</a><br/>&nbsp;';
					}
					?>
					</td></tr></td>
					</table>
				</tr>
        		<tr>
					<td width="10%">&nbsp;</td>
					<?php if ($file->screenurl<>'') { ?>
	          			<td width="10%" valign="middle" align="right" class="bordering">
		     			<?php
						if ($repository->Enable_List_Download) echo $downlink;
	          			?>
	            		<img src="<?php echo $file->screenurl.'" '; if ($repository->Small_Image_Width) echo 'width="'.$repository->Small_Image_Width.'" '; if ($repository->Small_Image_Height) echo 'height="'.$repository->Small_Image_Height.'" '; ?> border="0" align="middle" alt="thumbnail" />
						<?php
						if ($repository->Enable_List_Download) echo '</a>';
					} else { ?>
        	  			<td width="5%" valign="middle" align="right">
						<?php echo $repository->RemositoryImageURL('blank.gif', $repository->Small_Image_Width, $repository->Small_Image_Height);
					 } ?>
          			</td>
					<td valign="top">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<?php if ($remUser->isAdmin()) {?>
						<tr>
							<td width="30%" valign="top" align="right">
							<b>	<?php echo _DOWN_PUB; ?> </b>&nbsp;&nbsp;
							</td>
							<td>
								<?php if (($file->published)==1) {$pub=_YES;}else{$pub=_NO;}
								echo $pub; ?>
							</td>
						</tr>
						<?php } ?>
						<?php if (($file->smalldesc<>'') OR (($file->description<>'') AND ($file->autoshort))) { ?>
						<tr>
							<td width="30%" valign="top" align="right">
							<b>	<?php echo _DOWN_DESC_SMALL; ?> </b> &nbsp;&nbsp;
							</td>
							<td valign="top">
								<?php
										$sdesc = '';
										if (($file->description<>'') AND ($file->autoshort)) {
											$slen = 0;
											$slen = strlen($file->description);
											if ($slen>=($repository->Small_Text_Len-4)){
												$sdesc=substr($file->description,0,$repository->Small_Text_Len-4).'...';
											} else {
												$sdesc=$file->description;
											}
										} elseif ($file->smalldesc<>'') {
											$sdesc = $file->smalldesc;
										}
										echo $sdesc; ?>
							</td>
						</tr>
						<?php }
						if ($file->submitdate<>'') infoDisplay (_DOWN_SUB_DATE, date ($repository->Date_Format, revertFullTimeStamp($file->submitdate)));
						if ($file->filesize<>'') infoDisplay (_DOWN_FILE_SIZE, $file->filesize);
						infoDisplay (_DOWN_DOWNLOADS, $file->downloads);
						if ($repository->Allow_Votes) voteDisplay($file, $repository, false);
						?>
					</table>
				</td>
				</tr>
			</table>
				<?php
				$tabcnt = ($tabcnt+1) % 2;
			}
			$page->showNavigation($tabclass_arr);
		}
	}


	function fileinfoHTML( &$file, &$remUser ) {

		global $mainframe, $Itemid;

		$repository = new remositoryRepository ('GLOBAL');
		$tabclass_arr = $repository->getTableClasses();
		if ($file->published==1) {
			$pub=_YES;
			$canSetMetadata = array($mainframe, 'prependMetaTag');
			if (is_callable($canSetMetadata)) $mainframe->prependMetaTag('description', $file->description);
		}
		else $pub=_NO;
		$submitter = new remositoryUser($file->submittedby,null);

		?>
		<style type="text/css">
			.bordering{ border: 2px groove; }
		</style>
		<br/>&nbsp;
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width='30%' valign="top" align="right">
							<font size="2"/>
							<b><?php echo _DOWN_FILE_TITLE; ?></b>&nbsp;&nbsp;
						</td>
						<td align="left" valign="top"><?php echo $file->filetitle; ?>&nbsp;&nbsp;&nbsp;
							<?php
							$downlink = $repository->RemositoryFunctionURL('download',$file->id);
							if ($file->islocal) $addon = ' rel="nofollow">';
							else $addon = ' rel="nofollow" target="_blank">';
							$downlink = substr($downlink,0,strlen($downlink)-1).$addon;
							$downlogo = $repository->RemositoryImageURL('download_trans.gif');
							echo $downlink;
							echo $downlogo; ?> <b><?php echo _DOWNLOAD; ?></b></a><br/>
						</td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					</tr>
					<?php
					if ($remUser->isAdmin()) infoDisplay (_DOWN_PUB, $pub);
					if ($file->description<>'') infoDisplay (_DOWN_DESC, $file->description);
					if (($file->licenseagree==0) AND ($file->license<>'')) infoDisplay (_DOWN_LICENSE, $file->license);
					if ($file->submitdate<>'') infoDisplay (_DOWN_SUB_DATE, date ($repository->Date_Format, revertFullTimeStamp($file->submitdate)));
					if ($file->submittedby<>'') infoDisplay (_DOWN_SUB_BY, $submitter->fullname().' ('.$submitter->name.')');
					if ($file->filedate<>'') infoDisplay (_DOWN_FILE_DATE, date($repository->Date_Format,revertFullTimeStamp($file->filedate)));
					if ($file->fileauthor<>'') infoDisplay (_DOWN_FILE_AUTHOR, $file->fileauthor);
					if ($file->fileversion<>'') infoDisplay (_DOWN_FILE_VER, $file->fileversion);
					if ($file->filesize<>'') infoDisplay (_DOWN_FILE_SIZE, $file->filesize);
					if ($file->filetype<>'') infoDisplay (_DOWN_FILE_TYPE, $file->filetype);
					if ($file->filehomepage<>'') URLDisplay (_DOWN_FILE_HOMEPAGE, $file->filehomepage);
					infoDisplay (_DOWN_DOWNLOADS, $file->downloads);
					if ($file->screenurl<>'')
					{ ?>
					<tr>
					<td width="30%" valign="top" align="right">
						<b><?php echo _DOWN_SCREEN; ?></b>&nbsp;&nbsp;
					</td>
					<td>
						<a href="<?php echo $file->screenurl; ?>" target="_BLANK"><img src="<?php echo $file->screenurl.'" '; if ($repository->Small_Image_Width) echo 'width="'.$repository->Small_Image_Width.'" '; if ($repository->Small_Image_Height) echo 'height="'.$repository->Small_Image_Height.'" '; ?> border="0" align="middle" alt="screenurl"/></a>
					</td>
					</tr>
					<?php }
					if ($repository->Allow_Votes) voteDisplay($file, $repository, true);
					if ($repository->Allow_Comments) {
						$tabcnt = 1;
						$commentsdb = $file->getComments();
						if ($commentsdb){
						?>
        			<tr>
						<td width='30%' valign="top" align="right">
							<b><?php echo _DOWN_COMMENTS; ?></b>&nbsp;&nbsp;
						</td>
						<?php
							foreach ($commentsdb as $comment)
							{
							?>
								<td align="left" class="<?php echo $tabclass_arr[$tabcnt]; ?>">
									<i><?php echo $comment->name; ?> &nbsp;&nbsp;<?php echo $comment->date; ?></i>
								</td>
							</tr>
							<tr>
								<td width='30%' valign="top" align="right">
									&nbsp;&nbsp;
								</td>
								<td align="left" class="<?php echo $tabclass_arr[$tabcnt]; ?>">
									<?php echo $comment->comment; ?>
								</td>
							</tr>
							<tr>
								<td width='30%' valign="top" align="right">
									&nbsp;&nbsp;
								</td>
								<?php
							$tabcnt = ($tabcnt+1) % 2;
          					}
						echo '</tr>';        				}
        		if (!$commentsdb) { ?>
        		<tr>
 						<td width='30%' valign="top" align="right">
								&nbsp;&nbsp;
						</td>
 						<td align="left">
								&nbsp;<br/><b><?php if ($remUser->isLogged()) echo _DOWN_FIRST_COMMENT; else echo _DOWN_FIRST_COMMENT_NL; ?></b>
						</td>
				</tr>
				    <?php }
					}
        	if ($remUser->isLogged() && $repository->Allow_Comments) {
					?>
          		<form method="post" action="<?php echo $repository->RemositoryBasicFunctionURL('fileinfo',$file->id); ?>">
          			<tr>
									<td width="30%" valign="top" align="right">
				  					<b><?php echo _DOWN_YOUR_COMM; ?></b>&nbsp;&nbsp;
									</td>
									<td align="left" valign="top">
										<textarea class="inputbox" name="comment" rows="2" cols="35"></textarea>
									</td>
								</tr>
								<tr>
									<td width="30%" valign="top" align="right">
										&nbsp;
									</td>
          				<td align="left" valign="top">
				  					<input class="button" type="submit" name="submit_comm" value="<?php echo _DOWN_LEAVE_COMM; ?>"/>
				  					<br/><?php echo _DOWN_MAX_COMM; ?>
									</td>
								</tr>
								<input type="hidden" name="id" value="<?php echo $file->id; ?>"/>
							</form>
							</td>
						</tr>
        	<?php
					} ?>
			<tr>
				<td>
					&nbsp;
				</td>
				<td>
			<?php
			if ($remUser->isLogged() && ($remUser->id==$file->submittedby) && ($repository->Allow_User_Up) && ($repository->Allow_User_Edit)) { ?>
					  <br/>&nbsp;<br/>&nbsp;<br/><table width='100%' border='0' cellspacing='0' cellpadding='0' class='<?php echo $tabclass_arr[1]; ?>'><tr><td><?php echo $repository->RemositoryFunctionURL('userupdate',$file->id); echo $repository->RemositoryImageURL('edit.gif',17,15); echo _DOWN_UPDATE_SUB; ?></a></td></tr></table><br/>
			<?php } ?>
				</td>
			</tr>
		</table>
		<br/>&nbsp;<br/>
		<?php
	}

	function searchResultsHTML( &$files, $repository ) {
	global $mosConfig_live_site, $Itemid;
		$tabclass_arr = $repository->getTableClasses();
		$tabcnt = 0;
		if (count($files) == 0) {
			echo "<br/>&nbsp;<br/>"._DOWN_SEARCH_NORES
						."<br/>&nbsp;<br/><table width='100%' border='0' cellspacing='0' cellpadding='0' class='".$tabclass_arr[1]."'><tr><TD width='50%'><a href=index.php?option=com_remository&Itemid=$Itemid&func=search><images src=\"$mosConfig_live_site/components/com_remository/images/search.gif\" width='16' height='15' border='0' align='absmiddle'> "._DOWN_SEARCH."</a></td></tr></table><br/>";
			return;
		}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>
			<tr class="<?php echo $tabclass_arr[1]; ?>">
				<td width="100%">
					<b><?php echo _DOWN_FILES; ?></b>
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>
		</table>
		<?php
		foreach ($files as $file) {
			?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="<?php echo $tabclass_arr[$tabcnt]; ?>">
				<tr>
					<td width="100%" valign="top" colspan="2">
						<b><?php if (!$file->icon) echo $repository->RemositoryImageURL('file_icons/generic.png');
						else echo $repository->RemositoryImageURL('file_icons/'.$file->icon);
						echo $repository->RemositoryFunctionURL('fileinfo',$file->id);
						echo '&nbsp;'.$file->filetitle;
						?></a></b>
					</td>
				</tr>
				<tr>
					<td width="30%" valign="top" align="right">
						<?php echo _DOWN_DESC; ?>&nbsp;&nbsp;
					</td>
					<td>
						<?php echo $file->description; ?>
					</td>
				</tr>
				<tr>
					<td width="30%" valign="top" align="right">
						<?php echo _DOWN_FILE_SIZE; ?>&nbsp;&nbsp;
					</td>
					<td>
						<?php echo $file->filesize; ?>
					</td>
				</tr>
				<tr>
					<td width="30%" valign="top" align="right">
						<?php echo _DOWN_DOWNLOADS; ?>&nbsp;&nbsp;
					</td>
					<td>
						<?php echo $file->downloads; ?>
					</td>
				</tr>
				<?php
				if ($repository->Allow_Votes) voteDisplay($file,$repository,false);
				?>
			</table>
			<?php
			$tabcnt = ($tabcnt+1) % 2;
		}
	}


	function searchBoxHTML($repository) {

		global $Itemid;

		$tabclass_arr = $repository->getTableClasses();
		?>
			<table width='100%' border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>
			<tr class="<?php echo $repository->tabheader; ?>">
				<td width='100%' align='center'>
					<?php echo _DOWN_SEARCH; ?>
				</td>
			</tr>
			</table>
			<form method="post" action="index.php?option=com_remository&amp;Itemid=<?php echo $Itemid; ?>&amp;func=search">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="<?php echo $tabclass_arr[0]; ?>">
						<tr>
							<td width="40%" align="right">
								<b><?php echo _DOWN_SEARCH_TEXT; ?></b>&nbsp;
							</td>
							<td>
								<input class="inputbox" type="text" name="search_text" />
								<input class="button" type="submit" name="submit" value="<?php echo _DOWN_SUB_BUTTON; ?>" />
							</td>
						</tr>
						<tr>
							<td width="40%" align="right">
								<b><?php echo _DOWN_SEARCH_FILETITLE; ?></b>&nbsp;
							</td>
							<td>
								<input type="checkbox" name="search_filetitle" value="1" checked="checked" />
							</td>
						</tr>
						<tr>
							<td width="40%" align="right">
								<b><?php echo _DOWN_SEARCH_FILEDESC; ?></b>&nbsp;
							</td>
							<td>
								<input type="checkbox" name="search_filedesc" value="1" checked="checked" />
							</td>
						</tr>
				</table>
				<br/>
				<input type="hidden" name="submit" value="submit" />
			</form>
	  <?php
	}

	function addfileHTML( &$remUser, &$repository, &$file, $filetemp=null, $filetemphash=null, $oldid=0 )
	{
		global $Itemid;
		if ($file->id == 0) {
			$file->fileversion = $repository->Default_Version;
			$file->filedate = date('Y-m-d H:i:s', time());
		}
		if (!$remUser->isLogged()) initEditor();
		?>
		<script type="text/javascript">
			function paste_strinL(strinL){
				var input=document.forms["adminForm"].elements["icon"];
				input.value=strinL;
			}

		function clearshort(){

				if (document.adminForm.autoshort.checked==true){
					if (document.adminForm.description.value!=""){
						if (document.adminForm.description.value.length>=(<?php echo $repository->Small_Text_Len-3; ?>)){
							document.adminForm.smalldesc.value=document.adminForm.description.value.substr(0,<?php echo $repository->Small_Text_Len-3; ?>) + "...";
						} else {
							document.adminForm.smalldesc.value=document.adminForm.description.value;
						}
					} else {
						document.adminForm.smalldesc.value="";
					}
					document.adminForm.smalldesc.disabled=true;
				} else {
					document.adminForm.smalldesc.value="";
					document.adminForm.smalldesc.disabled=false;
				}
			}
		</script>
		<script language="javascript" type="text/javascript">
        function submitbutton(pressbutton) {
                <?php getEditorContents( 'description', 'description' );
				getEditorContents ( 'smalldesc', 'smalldesc');
				getEditorContents ('license', 'license'); ?>
                submitform( pressbutton );
        }
        </script>
		<br/>
		<form name="adminForm" enctype="multipart/form-data" action="index.php?option=com_remository&amp;Itemid=<?php echo $Itemid; ?>&amp;func=savefile" method="post">
			<input type="hidden" name="task" value="uploadfile" />
			<input type="hidden" name="option" value="com_remository" />
			<input type="hidden" name="element" value="component" />
			<input type="hidden" name="client" value="" />
			<input type="hidden" name="oldid" value="<?php echo $file->id; ?>" />
		<?php

		if ($file) $containerID = $file->containerid;
		else $containerID = '';
		$clist = $repository->getSelectList(false,$containerID,'containerid','class="inputbox"',$remUser);
		if ($clist == '') {
			echo _DOWN_FILE_SUBMIT_NOCHOICES;
			return;
		}
		$iconList = remositoryFile::getIcons();
		$tabclass_arr = $repository->getTableClasses();
		if ($file->fileauthor == '') $file->fileauthor = $remUser->fullname();
		if ( $remUser->isAdmin() OR $repository->User_Remote_Files) {
			$remoteok = true;
			$instruct1 = _SUBMIT_INSTRUCT1;
			$instruct2 = _SUBMIT_INSTRUCT2;
		}
		else {
			$remoteok = false;
			$instruct1 = _SUBMIT_INSTRUCT3;
			$instruct2 = '&nbsp;';
		}
		?>

			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="<?php echo $tabclass_arr[0]; ?>">
	  			<tr>
	    			<th colspan="2"><?php echo _SUBMIT_HEADING; ?></th>
	  			</tr>
	  			<tr><td colspan="2">&nbsp;<br/><?php echo $instruct1; ?><br/>&nbsp;</td></tr>
	  			<tr>
	    			<td width="30%" valign="top" align="right"><b>
					<?php echo _SUBMIT_NEW_FILE; ?></b>
					</td>
					<td valign="top">
					<input class="text_area" name="userfile" type="file" />
					</td>
	  			</tr>
	  			<tr><td colspan="2">&nbsp;<br/><?php echo $instruct2; ?><br/>&nbsp;</td></tr>
				<?php if ($remoteok) { ?>
				<tr>
					<td width="30%" valign="top" align="right">
						<b><?php echo _DOWNLOAD_URL; ?></b>&nbsp;
					</td>
					<td valign="top">
						<?php if ($file->url){ ?>
							<input class="inputbox" type="text" name="url" size="50" value="<?php echo $file->url; ?>" />
						<?php } else { ?>
							<input class="inputbox" type="text" name="url" size="50" value="http://" />
						<?php } ?>
					</td>
				</tr><?php
					fileInputBox(_DOWN_FILE_DATE,'filedate',$file->filedate,25);
					fileInputBox(_DOWN_FILE_SIZE,'filesize',$file->filesize,25);
				}
				?>
			</table>
			<?php if ($remoteok) echo '<hr/>'; ?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="<?php echo $tabclass_arr[1]; ?>">
				<tr>
					<td width="30%" valign="top" align="right">
						<b><?php echo _DOWN_SUGGEST_LOC; ?></b>&nbsp;
					</td>
					<td valign="top">
						<?php echo $clist; ?>
					</td>
				</tr>
				<tr>
					<td width="30%" valign="top" align="right">
				  	<b><?php echo _DOWN_DESC; ?></b>&nbsp;<br/><i><?php echo _DOWN_DESC_MAX; ?></i>&nbsp;
				  </td>
				  <td valign="top">
				  	<?php editorArea( 'description', $file->description, 'description', 400, 300, 50, 100 ); ?>
				  </td>
				</tr>
				<tr>
					<td width="30%" valign="top" align="right">
				  	<b><?php echo _DOWN_DESC_SMALL; ?></b>&nbsp;<br/><i><?php echo _DOWN_DESC_SMALL_MAX; ?></i>&nbsp;
				  </td>
				  <td valign="top">
				  	<textarea class="inputbox" name="smalldesc" rows="3" cols="50"><?php echo $file->smalldesc; ?></textarea>
				  </td>
				</tr>
				<tr>
					<td width="30%" valign="top" align="right">
				  	<b><?php echo _DOWN_AUTO_SHORT; ?></b>&nbsp;
				  </td>
				  <td valign="top">
				  	<?php if ((($file->autoshort)==1) or ($file->autoshort=='')) { ?>
							<input type="checkbox" name="autoshort" checked="checked" onclick="clearshort()" value="1" />
				  		<script type="text/javascript">clearshort()</script>
						<?php } else { ?>
							<input type="checkbox" name="autoshort" onclick="clearshort()" value="1" />
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td width="30%" valign="top" align="right">
				  	<b><?php echo _DOWN_LICENSE; ?></b>&nbsp;<br/><i><?php echo _DOWN_DESC_MAX; ?></i>&nbsp;
				  </td>
				  <td valign="top">
				  	<textarea class="inputbox" name="license" rows="4" cols="50"><?php echo $file->license; ?></textarea>
				  </td>
				</tr>
				<tr>
					<td width="30%" align="right">
				  	<b><?php echo _DOWN_LICENSE_AGREE; ?></b>&nbsp;
				  </td>
				  <td>
				  	<?php if (($file->licenseagree)==1) { ?>
							<input type="checkbox" name="licenseagree" value="1" checked="checked" />
						<?php } else { ?>
							<input type="checkbox" name="licenseagree" value="1" />
						<?php } ?>
					</td>
				</tr>
					<?php
					fileInputBox(_DOWN_FILE_TITLE,'filetitle',$file->filetitle,25);
					fileInputBox(_DOWN_FILE_VER,'fileversion',$file->fileversion,25);
					fileInputBox(_DOWN_FILE_AUTHOR,'fileauthor',$file->fileauthor,25);
					fileInputBox(_DOWN_FILE_HOMEPAGE,'filehomepage',$file->filehomepage,50);
					fileInputBox(_DOWN_SCREEN,'screenurl',$file->screenurl,50);
					?>
					<tr>
						<td width="30%" valign="top" align="right">
							<b><?php echo _DOWN_ICON; ?></b>&nbsp;
						</td>
						<td valign="top">
							<input class="inputbox" type="text" name="icon" size="25" value="<?php echo $file->icon; ?>" />
					  	<table>
					  		<tr>
					  			<td>
										<?php echo $iconList; ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input class="button" type="submit" name="submit" value="<?php echo _SUBMIT_FILE_BUTTON; ?>" /><br/>
						</td>
					</tr>
				</table>
			<input type="hidden" name="func" value="savefile" />
			<input type="hidden" name="option" value="com_remository" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="filetempname" value="<?php echo $filetemp; ?>" />
			<input type="hidden" name="filetemphash" value="<?php echo $filetemphash; ?>" />
			<input type="hidden" name="oldid" value="<?php echo $file->id; ?>" />
		</form>

		<?php
	}
	
	function addFileDoneHTML (&$file, &$repository) {
	?>
	<br/>&nbsp;<br/>
	<?php echo _DOWN_ALL_DONE; ?>
	<br/>&nbsp;<br/>
	<?php
	if ($file->published) echo _DOWN_AUTOAPP;
	else echo _DOWN_UP_WAIT;
	echo '<br/>&nbsp;<br/>';
	echo $repository->RemositoryFunctionURL('addfile');
	echo $repository->RemositoryImageURL('add_file.gif').'&nbsp;';
	echo _DOWN_SUBMIT_ANOTHER.'</a><br/>&nbsp;<br/>';
	if ($file->published) {
		echo $repository->RemositoryFunctionURL('fileinfo',$file->id);
		echo $repository->RemositoryImageURL('file_icons/generic.png').'&nbsp;';
		echo _DOWN_SUBMIT_INSPECT;
	}
	?>
	</a><br/>
	<?php
	}

}

	function infoDisplay ($text, $value, $font=0) {

		?> <tr><td width='30%' valign="top" align="right"><b>
		<?php if ($font != 0) {
			?><font size="
			<?php echo $font;
			?>">
		<?php }
		echo htmlspecialchars($text); ?>
		</b>&nbsp;&nbsp; </td><td align="left" valign="top">
		<?php echo $value; ?>
		</td></tr>
		<?php
	}
	
	function URLDisplay ($text, $value) {
		?> <tr><td width='30%' valign="top" align="right"><b>
		<?php
		echo $text; ?>
		</b>&nbsp;&nbsp; </td><td align="left" valign="top">
		<a href="<?php echo $value; ?>"><?php echo $value; ?></a>
		</td></tr>
		<?php
	}

	
	function voteDisplay (&$file, &$repository, $entry) {
		global $Itemid;
		?>
		<tr>
			<td width="30%" valign="top" align="right">
			<b>	<?php echo _DOWN_RATING; ?> </b>&nbsp;&nbsp;
			</td>
			<td align="left">
				<?php echo $repository->RemositoryImageURL('stars/'.$file->evaluateVote().'.gif',64,12);
				echo _DOWN_VOTES;
				echo round($file->votes->count); ?>&nbsp;<br/>&nbsp;
			</td>
		</tr>
		<?php
		if ($entry) {
			?>
			<tr>
				<td width="30%" valign="top" align="right">
					<b><?php echo _DOWN_YOUR_VOTE; ?></b>&nbsp;&nbsp;
				</td>
				<td align="left">
					<form method="post" action="index.php?option=com_remository&amp;Itemid=<?php echo $Itemid; ?>&amp;id=<?php echo $file->id; ?>&amp;func=fileinfo">
						<select NAME="user_rating" class="inputbox">
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
				</td>
			</tr>
			<?php
		}
	}
	
	function fileInputBox ($field, $name, $value, $width) {
		?>
		<tr>
		  	<td width="30%" valign="top" align="right">
				<b><?php echo $field; ?></b>&nbsp;
			</td>
			<td valign="top">
				<input class="inputbox" type="text" name="<?php echo $name; ?>" size="<?php echo $width; ?>" value="<?php echo $value; ?>" />
			</td>
		</tr>
		<?php
	}

	function revertFullTimeStamp($timestamp) {
		$subs = array (5,8,11,14,17);
		$parts = array();
		$max = strlen($timestamp);
	    $parts[] = substr($timestamp,0,4);
	    foreach ($subs as $i) {
			if ($i < $max) $parts[] = substr($timestamp,$i,2);
			else $parts[] = '00';
		}
	    $newdate = mktime($parts[3],$parts[4],$parts[5],$parts[1],$parts[2],$parts[0]);
	    return $newdate;
	}

?>

