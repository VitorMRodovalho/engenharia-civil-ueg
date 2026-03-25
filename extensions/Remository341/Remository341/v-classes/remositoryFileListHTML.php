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

class remositoryFileListHTML extends remositoryUserHTML {
	var $tabcnt=0;
	
	function folderListHeading($container){
		$cname = htmlspecialchars($container->name);
		echo "\n\t<div id='remositorycontainer'>";
		echo "\n\t\t<h2>$cname ";
		// The following three lines create the RSS link for the container - remove if not required
		$rssurl = $this->repository->RemositoryFunctionURL('rss', $container->id);
		$this->interface->addCustomHeadTag("<link rel='alternate' type='application/rss+xml' title='RSS - "._DOWN_NEWEST." - $cname' href='$rssurl' />");
		echo $rssurl.$this->repository->RemositoryImageURL('feed-icon-32x32.gif').' RSS</a>';
		// End of RSS code
		echo '</h2>';
		echo "\n\t\t<p>".$container->description.'</p>';
		echo "\n\t<!-- End of remositorycontainer -->";
		echo "\n\t</div>";
	}
	
	function fileListHeading($orderby, $idparm) {
		$downfiles = _DOWN_FILES;
		$downorder = _DOWN_ORDER_BY;
		$ordername = array ('zero', _DOWN_ID, _DOWN_FILE_TITLE_SORT, _DOWN_DOWNLOADS_SORT, _DOWN_SUB_DATE_SORT);
		for ($by = 1; $by <= 4; $by++) {
			if ($orderby<>$by) $option[] = "\n\t\t\t".$this->repository->RemositoryFunctionURL('select',$idparm,null,$by).$ordername[$by].'</a>';
			else $option[] = $ordername[$by];
		}
		echo "\n\t<div id='remositoryfilelisthead'>";
		echo "\n\t\t<h3>$downfiles</h3>";
		echo "\n\t\t<span id='remositoryorderby'><em>$downorder </em>";
		echo implode (' | ', $option);
		echo "\n\t\t</span>";
		echo "\n\t<!-- End of remositoryfilelisthead -->";
		echo "\n\t</div>";
	}
	
	function displayContainer ($container) {
		echo "\n\t\t<tr>";
		echo "\n\t\t\t<td><h3>".$this->repository->RemositoryFunctionURL('select', $container->id);
		if ($container->icon == '') echo $this->repository->RemositoryImageURL('folder_icons/folder_yellow.gif');
		else echo $this->repository->RemositoryImageURL('folder_icons/'.$container->icon);
		echo ' '.$container->name.'</a></h3></td>';
		echo "\n\t\t\t<td>($container->foldercount/$container->filecount)</td>";
		echo "\n\t\t</tr>";
		if ($container->description) {
			echo "\n\t\t<tr class='remositoryfolderinfo'>";
			echo "\n\t\t\t<td>$container->description</td>";
			echo "\n\t\t</tr>";
		}
	}

	function fileListHTML( &$container, &$folders, &$files, &$page ) {
		if ($container->id) {
			$container->setMetaData();
			$this->pathwayHTML($container->getParent());
		}
		$this->mainPageHeading($container->id);
		if ($container->id) $this->folderListHeading($container);
		if ($folders){
			$title = _DOWN_CONTAINERS;
			$ff = _DOWN_FOLDERS_FILES;
			echo "\n\t<div id='remositorycontainerlist'>";
		    echo "\n\t\t<table>";
		    echo "\n\t\t<thead><tr>";
		    echo "\n\t\t\t<th id='remositorycontainerhead'>$title</th>";
		    echo "\n\t\t\t<th>$ff</th>";
		    echo "\n\t\t</tr></thead><tbody>";
			foreach ($folders as $folder) {
				$this->displayContainer($folder);
 				$this->tabcnt = ($this->tabcnt+1) % 2;
			}
			echo "\n\t\t</tbody></table>";
			echo "\n\t<!-- End of remositorycontainerlist -->";
			echo "\n\t</div>\n";
		}
		if ($files){
			$this->tabcnt = 0;
			$downlogo = $this->repository->RemositoryImageURL('download_trans.gif');
			$this->fileListHeading($this->orderby, $container->id);
			$page->showNavigation();
			echo "\n\t<div id='remositoryfilelisting'>";
			foreach ($files as $file) {
				$this->fileListing ($file, $container, $downlogo, $this->remUser);
				$this->tabcnt = ($this->tabcnt+1) % 2;
			}
			echo "\n\t<!-- End of remositoryfilelisting -->";
			echo "\n\t</div>\n";
			?>
			<script type="text/javascript">
			function download(url){window.location = url}
			</script>
			<?php
		}
		$this->filesFooterHTML ();
		$this->remositoryCredits();
	}
}

class remositoryPage {
	var $baseurl = '';
	var $itemcount = 0;
	var $itemsperpage = 10;
	var $startItem = 1;
	var $currentpage = 1;
	var $pagetotal = 1;

	function remositoryPage (&$container, &$remUser, $itemsperpage, $page, $orderby) {
		$interface =& remositoryInterface::getInstance();
		$itemid = $interface->getCurrentItemid();
		$this->baseurl = "index.php?option=com_remository&Itemid=$itemid&func=select&id=$container->id&orderby=$orderby&page=";
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

	function showNavigation () {
		if ($this->itemcount <= $this->itemsperpage) return;
		$lowpage = $this->currentpage - _PAGE_SPREAD;
		if ($lowpage < 1) $lowpage = 1;
		$highpage = $this->currentpage + _PAGE_SPREAD;
		if ($highpage > $this->pagetotal) $highpage = $this->pagetotal;
		echo "\n\t<div class='remositorypagenav'>";
		$previous = $this->currentpage - 1;
		if ($previous) {
			$url = sefRelToAbs($this->baseurl.$previous);
			echo "\n\t\t<a href='$url' ";
			$this->pageTitle($previous,'Prev page - Results ');
			echo '> &lt;</a>';
		}
		$page = $lowpage;
		while ($page <= $highpage) {
			if ($page == $this->currentpage) echo "\n\t\t<span class='remositorylight'>Page $this->currentpage of $this->pagetotal</span>";
			else {
				$url = sefRelToAbs ($this->baseurl.$page);
				echo "\n\t\t<a href='$url' ";
				$this->pageTitle($page);
				echo "> $page</a>";
			}
			$page++;
		}
		$next = $this->currentpage + 1;
		if ($next <= $this->pagetotal) {
			$url = sefRelToAbs($this->baseurl.$next);
			echo "\n\t\t<a href='$url' ";
			$this->pageTitle($next,'Next page - Results ');
			echo '> &gt;</a>';
		}
		if ($this->pagetotal > $highpage) {
			$url = sefRelToAbs($this->baseurl.$this->pagetotal);
			echo "\n\t\t<a href='$url'";
			$this->pageTitle($this->pagetotal,'Last page - Results ');
			echo '> &raquo;</a>';
		}
		echo "\n\t<!-- End of remositorypagenav -->";
		echo "\n\t</div>";
	}

	function startItem () {
		return $this->startItem;
	}

}

?>