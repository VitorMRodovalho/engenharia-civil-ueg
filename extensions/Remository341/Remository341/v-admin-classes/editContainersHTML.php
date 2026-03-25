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

class editContainersHTML extends remositoryAdminHTML {

	function selectList ($title, $selector, $redstar) {
		$this->inputTop ($title, $redstar);
		?>
			<td valign="top">
				<?php echo $selector; ?>
			</td>
		</tr>
		<?php
	}
	
	function permission ($title,$container,$updown,$name) {
		$this->inputTop($title, true);
		?>
					<td valign="top">
					<?php
					for ($i=0; $i<4; $i++) {
						echo '<input type="radio" name="'.$name.'" value="'.$i;
						if ($container->$name == $i) echo '" checked="checked" />';
						else echo '" />';
						echo $updown[$i];
					}
					?>
				    </td>
			</tr>
		<?php
	}
	
	function groupOptions ($object, $property) {
		?>
		<td valign="top">
			<select NAME="<?php echo $property; ?>" class="inputbox">
				<option value="0"><?php echo _GLOBAL; ?></option>
				<option value="1" <?php if ($object->$property) echo 'selected="selected"'; echo '>'._YES; ?></option>
			</select>
		</td>
		<?php
	}

	function view (&$container)
	{
		$oldpath = $container->filepath;
		$iconList = remositoryContainer::getIcons ();
		$this->commonScripts('description');
		echo '<br/>';
		$this->startEditHeader();
		$this->publishedBox($container);
		$this->fileInputBox(_DOWN_FOLDER_NAME, 'name', $container->name, 50);
		$this->fileInputArea(_DOWN_DESC, _DOWN_DESC_MAX, 'description', $container->description, 50, 100, true);
		$this->fileInputBox(_DOWN_KEYWORDS,'keywords',$container->keywords,50);
		$this->fileInputBox(_DOWN_WINDOW_TITLE,'windowtitle',$container->windowtitle,50);
		$this->fileInputBox(_DOWN_UP_ABSOLUTE_PATH,'filepath',$container->filepath,50);
		$this->tickBoxField($container, 'plaintext', _DOWN_UP_PLAIN_TEXT);
		$this->displayIcons($container, $iconList);
		$userupdown = array(_DOWN_UP_NEITHER, _DOWN_UP_UPLOAD_ONLY, _DOWN_UP_DOWNLOAD_ONLY, _DOWN_UP_BOTH);
		$this->permission(_DOWN_VISITORS_PERMITTED,$container,$userupdown,'registered');
		$this->permission(_DOWN_USERS_PERMITTED,$container,$userupdown,'userupload');
		$this->selectList(_DOWN_ACCESS_GROUP, remositoryGroup::getGroupSelector($container, 'groupid'), true);
		$this->selectList(_DOWN_EDITOR_GROUP, remositoryGroup::getGroupSelector($container, 'editgroup'), true);
		$this->inputTop(_DOWN_AUTO_FOR_USERS, true);
		$this->groupOptions($container, 'userauto');
		$this->inputTop(_DOWN_AUTO_FOR_ADMIN, true);
		$this->groupOptions($container, 'adminauto');
		$this->selectList(_DOWN_AUTO_USER_GROUP, remositoryGroup::getGroupSelector($container, 'autogroup'), true);
		$this->blankRow();
		$this->inputTop(_DOWN_FORCE_INHERIT);
		$this->yesNoList(null, _DOWN_INHERIT);
		$this->editFormEnd ($container->id, $oldpath);
	}
}

?>