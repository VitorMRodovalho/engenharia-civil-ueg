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

class listAboutHTML extends remositoryAdminHTML {
	
	function aboutLine ($title, $value) {
		?>
		<tr>
			<td>
				<?php echo $title; ?>
			</td>
			<td>
				<?php echo $value; ?>
			</td>
		</tr>
		<?php
	}

	function view ($ReMOSver) {
		$this->formStart(_DOWN_ABOUT);
		echo '</table><table cellpadding="4" cellspacing="0" border="0" width="100%" class="">';
		$this->aboutLine(_DOWN_TITLE_ABOUT, _DOWN_ABOUT_DESCRIBE);
		$this->aboutLine(_DOWN_VERSION_ABOUT, $ReMOSver);
		$this->aboutLine(_DOWN_AUTHOR_ABOUT,'Martin Brampton');
		$this->aboutLine(_DOWN_WEBSITE_ABOUT,"<a href='http://www.black-sheep-research.com'>www.black-sheep-research.com</a>");
		$this->aboutLine(_DOWN_EMAIL_ABOUT,'martin@remository.com');
		echo '</table></form>';
	}
}

?>