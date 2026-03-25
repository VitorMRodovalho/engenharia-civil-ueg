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

class listConfigHTML extends remositoryAdminHTML {
	
	function configTextBox ($title, $name) {
		?>
		<tr>
			<td width="500"><?php echo $title; ?></td>
	        <td> <input class="inputbox" type="text" name="<?php echo $name; ?>" size="50" value="<?php echo $this->repository->$name; ?>" /></td>
	      </tr>
		<?php
	}
	
	function configYesNoBox ($listitem, $yesno) {
		$variablename = $listitem->variablename;
		?>
		<tr>
			<td width="500">
				<?php echo $listitem->description; ?>
			</td>
			<td>
				<?php echo $this->repository->selectList($yesno, $listitem->variablename, 'class=\"inputbox\" size=\"1\"', $this->repository->$variablename); ?>
			</td>
	    </tr>
	    <?php
	}
	
	function view( $newlist, $yesno ) {
		$this->formStart(_DOWN_CONFIG_TITLE);
		?>
		</table>
		<script type="text/javascript" src="js/dhtml.js"></script>
		<table cellpadding="3" cellspacing="0" border="0" width="100%">
	  		<tr>
	    		<td width="" class="tabpadding">&nbsp;</td>
	    		<td id="tab1" class="offtab" onclick="dhtml.cycleTab(this.id)"><?php echo _DOWN_CONFIG_TITLE1; ?></td>
	    		<td id="tab2" class="offtab" onclick="dhtml.cycleTab(this.id)"><?php echo _DOWN_CONFIG_TITLE2; ?></td>
	    		<td id="tab3" class="offtab" onclick="dhtml.cycleTab(this.id)"><?php echo _DOWN_CONFIG_TITLE3; ?></td>
	    		<td id="tab4" class="offtab" onclick="dhtml.cycleTab(this.id)"><?php echo _DOWN_CONFIG_TITLE4; ?></td>
	    		<td width="90%" class="tabpadding">&nbsp;</td>
	  		</tr>
		</table>
		<form action="index2.php" method="POST" name="adminForm">
		<div id="page1" class="pagetext">
	    <table cellpadding="2" cellspacing="4" border="0" width="100%" class="adminform">
	    <?php
	    $this->configTextBox(_DOWN_CONFIG1, 'tabclass');
		$this->configTextBox(_DOWN_CONFIG2, 'tabheader');
		$this->configTextBox(_DOWN_CONFIG19, 'headerpic');
		$this->configTextBox(_DOWN_CONFIG4, 'Down_Path');
		$this->configTextBox(_DOWN_CONFIG5, 'Up_Path');
		$this->configTextBox(_DOWN_CONFIG21, 'Large_Text_Len');
		$this->configTextBox(_DOWN_CONFIG22, 'Small_Text_Len');
		$this->configTextBox(_DOWN_CONFIG23, 'Small_Image_Width');
		$this->configTextBox(_DOWN_CONFIG24, 'Small_Image_Height');
		$this->configTextBox(_DOWN_CONFIG36, 'Large_Image_Width');
		$this->configTextBox(_DOWN_CONFIG37, 'Large_Image_Height');
		$this->configTextBox(_DOWN_CONFIG6, 'MaxSize');
		$this->configTextBox(_DOWN_CONFIG7, 'Max_Up_Per_Day');
		$this->configTextBox(_DOWN_CONFIG8, 'Max_Up_Dir_Space');
		$this->configTextBox(_DOWN_CONFIG30, 'Favourites_Max');
		$this->configTextBox(_DOWN_CONFIG35, 'Max_Thumbnails');
		$this->configTextBox(_DOWN_CONFIG31, 'Date_Format');
		$this->configTextBox(_DOWN_CONFIG32, 'Default_Version');
		?>
	    </table>
		</div>
		<div id="page2" class="pagetext">
	    <table cellpadding="2" cellspacing="0" border="0" width="100%" class="adminform">
	    <?php
	    $this->configTextBox(_DOWN_CONFIG9, 'ExtsOk');
		foreach ($newlist as $listitem) {
			$this->configYesNoBox($listitem, $yesno);
		}
		$this->configTextBox(_DOWN_CONFIG17, 'Sub_Mail_Alt_Addr');
		$this->configTextBox(_DOWN_CONFIG18, 'Sub_Mail_Alt_Name');
		?>
	    </table>
		</div>
		<div id="page3" class="pagetext">
	    <table cellpadding="2" cellspacing="0" border="0" width="100%" class="adminform">
	    <?php
		$this->fileInputArea(_DOWN_DOWNLOAD_TEXT_BOX, '', 'download_text', $this->repository->download_text, 50, 100);
		?>
	    </table>
		</div>
		<div id="page4" class="pagetext">
	    <table cellpadding="2" cellspacing="0" border="0" width="100%" class="adminform">
	    <?php
		echo 'stuff to configure appearance of pages - not yet working';
		?>
	    </table>
		</div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="act" value="<?php echo $_REQUEST['act']; ?>" />
		<input type="hidden" name="option" value="com_remository" />
		</form>
		<script language="javascript" type="text/javascript">
			dhtml.cycleTab('tab1');
		</script>
		<?php
	}
}

?>