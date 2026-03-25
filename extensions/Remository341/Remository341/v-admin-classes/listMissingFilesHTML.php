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

class listMissingFilesHTML extends remositoryAdminHTML {
	
	function columnHeads ($files) {
		$this->listHeadingStart(count($files));
		$this->headingItem('15%', _DOWN_NAME_TITLE);
		$this->headingItem('25%', _DOWN_PARENT_CAT);
		$this->headingItem('25%', _DOWN_PARENT_FOLDER);
		$this->headingItem('20%', _DOWN_DATE);
		$this->headingItem('30%', '');
		echo '</tr>';
	}
	
	function listLine ($file, $i, $k) {
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td width="5">
				<input type="checkbox" id="cb<?php echo $i;?>" name="cfid[]" value="<?php echo $file->id; ?>" onclick="isChecked(this.checked);" />
			</td>
			<td width="30%" align="left">
				<a href="index2.php?option=com_remository&amp;act=<?php echo $_REQUEST['act']; ?>&amp;task=edit&cfid=<?php echo $file->id; ?>">
					<?php echo $file->filetitle; ?>
				</a>
			</td>
			<td width="25%" align="left"><?php echo $file->getCategoryName();?></td>
			<td width="25%" align="left"><?php echo $file->getFamilyNames();?></td>
			<td width="30%" align="left"><?php echo $file->filedate;?></td>
			<td width="40%" align="center">&nbsp;</td>
		</tr>
		<?php
	}

	function view ( &$files) {
		$this->formStart(_DOWN_MISSING_TITLE);
		$this->columnHeads($files);
		$k = 0;
		foreach ($files as $i=>$file) {
			$this->listLine($file, $i, $k);
			$k = 1 - $k;
		}
		if (count($files) == 0) {
			echo '<div class="message">'._DOWN_NONE_MISSING.'</div>';
		}
		$this->listFormEnd(false);
	}
}

?>