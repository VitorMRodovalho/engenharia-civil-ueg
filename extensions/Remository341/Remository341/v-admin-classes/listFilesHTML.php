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

class listFilesHTML extends remositoryAdminHTML {

	function columnHeads ($files) {
		$this->listHeadingStart(count($files));
		$this->headingItem('15%', _DOWN_NAME_TITLE);
		$this->headingItem('25%', _DOWN_PARENT_CAT);
		$this->headingItem('25%', _DOWN_PARENT_FOLDER);
		$this->headingItem('10%', _DOWN_PUB1);
		$this->headingItem('10%', _DOWN_DOWNLOADS_SORT);
		$this->headingItem('30%', '');
		echo '</tr>';
	}
	
	function containerLink ($file) {
		$parent = $file->getContainer();
		if ($parent) {
			$grandparent = $parent->getParent();
			if ($grandparent) $linkid = $grandparent->id;
			else $linkid = $parent->id;
		}
		else $linkid = 0;
		$link = '';
		if ($linkid) $link .= "<a href='index2.php?option=com_remository&amp;act=containers&amp;task=list&amp;parentid=$linkid'>";
		$link .= $file->getFamilyNames();
		if ($linkid) $link .= '</a>';
		return $link;
	}

	function listLine ($file, $i, $k) {
		$interface =& remositoryInterface::getInstance();
		?>
				<tr class="<?php echo "row$k"; ?>">
					<td width="5">
						<input type="checkbox" id="cb<?php echo $i;?>" name="cfid[]" value="<?php echo $file->id; ?>" onclick="isChecked(this.checked);" />
					</td>
					<td width="30%" align="left">
							<a href="<?php echo $this->editLink($file->id, $file->containerid); ?>">
							<?php echo $file->filetitle; ?>
						</a>
					</td>
					<td width="25%" align="left"><?php echo $file->getCategoryName();?></td>
					<td width="25%" align="left"><?php echo $this->containerLink($file);?></td>
					<?php if ($file->published==1) { ?>
					<td width="10%" align="center"><img src="<?php echo $interface->getCfg('live_site'); ?>/administrator/images/publish_g.png" border="0" alt="Published" /></td>
					<?php } else { ?>
					<td width="10%" align="center"><img src="<?php echo $interface->getCfg('live_site'); ?>/administrator/images/publish_x.png" border="0" alt="Published" /></td>
					<?php } ?>
					<td width="5%" align="left"><?php echo $file->downloads;?></td>
					<td width="40%" align="center">&nbsp;</td>
				</tr>
		<?php
	}

	function view (&$files, $descendants, $search='')  {
		$this->formStart(_DOWN_FILES);
		$this->listHeader($descendants, $search);
		echo '</table>';
		$this->columnHeads($files);
		$k = 0;
		foreach ($files as $i=>$file) {
			$this->listLine($file, $i, $k);
			$k = 1 - $k;
		}
		$this->listFormEnd();
	}
	
}

?>