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

class listUnlinkedHTML extends remositoryAdminHTML {
	
	function columnHeads ($count) {
		$this->listHeadingStart($count);
		$this->headingItem('35%', 'Path');
		$this->headingItem('45%', '');
		echo '</tr>';
	}
	
	function orphanLine ($fullpath, $parm, $i, $k) {
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td width="5">
				<input type="checkbox" id="cb<?php echo $i;?>" name="cfid[]" value="<?php echo $fullpath; ?>" onclick="isChecked(this.checked);" />
			</td>
			<td width="60%" align="left">
		<?php
		if ($parm) {
			?>
					<a href="index2.php?option=com_remository&amp;act=files&amp;task=addfile&amp;orphanpath=<?php echo $fullpath.$parm; ?>"><?php echo $fullpath; ?></a>
			<?php
		}
		else echo $fullpath;
		?>
			</td>
			<td width="40%"><td>
		</tr>
		<?php
	}

	function view( &$OrphanDownloads, &$OrphanUploads ) {
		$this->formStart('Orphans');
		$count = count($OrphanDownloads)+count($OrphanUploads);
		$this->columnHeads($count);
		?>
	  	</table>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<?php
		$k = 0;
		$i = 0;
		foreach ($OrphanDownloads as $fullpath=>$containers) {
			$container_list = implode(',',$containers);
			$parm = "&amp;containers=$container_list";
			$this->orphanLine ($fullpath, $parm, $i, $k);
			$k = 1 - $k;
			$i++;
		} 
		foreach ($OrphanUploads as $fullpath=>$ignore) {
			$this->orphanLine ($fullpath, null, $i, $k);
			$k = 1 - $k;
			$i++;
		}
		$this->listFormEnd(false);
	}
}

?>