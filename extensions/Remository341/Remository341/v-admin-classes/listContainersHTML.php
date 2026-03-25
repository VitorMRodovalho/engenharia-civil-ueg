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

class listContainersHTML extends remositoryAdminHTML {
	
	function columnHeads ($containers) {
		$this->listHeadingStart(count($containers));
		$this->headingItem('12%', _DOWN_NAME_TITLE);
		if ($this->clist) { 
			$this->headingItem('3%', 'ID');
			$this->headingItem('12%', _DOWN_PARENT_CAT);
			$this->headingItem('15%', _DOWN_PARENT_FOLDER);
		}
		$this->headingItem('6%', _DOWN_PUB1);
		$this->headingItem('6%', _DOWN_RECORDS);
		$this->headingItem('10%', _DOWN_VISITORS);
		$this->headingItem('10%', _DOWN_REG_USERS);
		$this->headingItem('12%', _DOWN_GROUP);
		$this->headingItem('12%', _DOWN_STORAGE_STATUS);
		echo '</tr>';
	}
	
	function filecount ($container) {
		if ($container->filecount) {
			$link = "<a href='index2.php?option=com_remository&amp;act=files&amp;task=list&amp;containerid=$container->id'>";
			$link .= $container->filecount;
			$link .= '</a>';
			return $link;
		}
		else return '0';
	}
	
	function listLine ($container, $i, $k) {
		$interface =& remositoryInterface::getInstance();
		?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<input type="checkbox" id="cb<?php echo $i;?>" name="cfid[]" value="<?php echo $container->id; ?>" onclick="isChecked(this.checked);" />
					</td>
					<td align="left">
							<a href="<?php echo $this->editLink($container->id); ?>">
							<?php echo $container->name; ?>
						</a>
					</td>
					<?php if ($this->clist) { ?>
					<td align="left"><?php echo $container->id; ?></td>
					<td align="left"><?php echo $container->getCategoryName();?></td>
					<td align="left"><?php echo $container->getFamilyNames();?></td>
					<?php }
					if ($container->published==1) { ?>
					<td align="left"><img src="<?php echo $interface->getCfg('live_site'); ?>/administrator/images/publish_g.png" border="0" alt="Published" /></td>
					<?php } else { ?>
					<td align="left"><img src="<?php echo $interface->getCfg('live_site'); ?>/administrator/images/publish_x.png" border="0" alt="Published" /></td>
					<?php } ?>
					<td align="left"><?php echo $this->filecount($container); ?></td>
					<td align="left">
					<?php
					if ($container->registered & 2) echo $this->repository->RemositoryImageURL('download_trans.gif').'/';
					else echo '-/';
					if ($container->registered & 1) echo $this->repository->RemositoryImageURL('add_file.gif');
					else echo '-';
     				?>
					</td>
					<td align="left">
					<?php
					if ($container->userupload & 2) echo $this->repository->RemositoryImageURL('download_trans.gif').'/';
					else echo '-/';
					if ($container->userupload & 1) echo $this->repository->RemositoryImageURL('add_file.gif');
					else echo '-';
     				?>
					</td>
					<td align="left"><?php echo remositoryGroup::getName($container->groupid); ?></td>
					<td align="left">
					<?php
					if ($container->filepath) {
						clearstatcache();
						if (file_exists($container->filepath)) {
							if (is_writeable($container->filepath)) echo _DOWN_FILE_SYSTEM_OK;
							else echo '<font color="red">'._DOWN_NOT_WRITEABLE.'</font>';
						}
						else echo '<font color="red">'._DOWN_DIRECTORY_NON_EXISTENT.'</font>';
					}
					else echo _DOWN_DATABASE;
					?>
					</td>
				</tr>
		<?php
	}

	// was showContainersHTML
	function view ($containers, $descendants, $search='')  {
		$this->formStart(_DOWN_CONTAINERS);
		$this->listHeader($descendants, $search);
		echo '</table>';
		$this->columnHeads($containers);
		$k = 0;
		foreach ($containers as $i=>$container) {
			$this->listLine($container, $i, $k);
			$k = 1 - $k;
		}
		$this->listFormEnd();
	}
}

?>