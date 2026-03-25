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

class listStatsHTML extends remositoryAdminHTML {
	
	function statHeader ($title2, $title3) {
		?>
		<tr>
			<td width="15%" align="left">
				<b><?php echo _DOWN_TOP_TITLE; ?> 5 <?php echo $title2; ?></b>
			</td>
			<td>&nbsp;</td>
			<td><?php echo $title3; ?></td>
		</tr>
		<?php
	}
	
	function statEntry ($name, $number) {
		?>
		<tr>
			<td width="1%">&nbsp;
				
			</td>
			<td width="40%" align="left">
				<?php echo $name; ?>
			</td>
			<td width="60%" align="left">
				<?php echo $number; ?>
			</td>
		</tr>
		<?php
	}

	function view (&$downloads, &$ratings, &$votes) {
		$this->formStart(_DOWN_STATS_TITLE);
		$this->statHeader(_DOWN_DOWNLOADS_SORT, _DOWN_DOWNLOADS_SORT);
		?>
		</table>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<?php
		foreach ($downloads as $download) {
			$this->statEntry ($download->filetitle, $download->downloads);
		}
		echo '<tr><td>&nbsp;</td></tr>';
		$this->statHeader (_DOWN_RATED_TITLE, _DOWN_RATING_TITLE);
        for ($i=0, $n=count( $ratings ); $i < $n; $i++) {
			$rate=split(",", $ratings[$i]);
			$this->statEntry ($rate[0], $rate[1]);
		}
		echo '<tr><td>&nbsp;</td></tr>';
		$this->statHeader (_DOWN_VOTED_ON, _DOWN_VOTES_TITLE);
		for ($i=0, $n=count( $votes ); $i < $n; $i++) {
			$vote=split(",", $votes[$i]);
			$this->statEntry ($vote[0], $vote[1]);
		}
		echo '</table>';
	}
}

?>