<?php
/**
* FacileForms - A Joomla Forms Application
* @version 1.4.4
* @package FacileForms
* @copyright (C) 2004 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

require_once($ff_admpath.'/admin/record.class.php');

switch($act) {
	case 'mngrecs':
		$recparams = array();
		$recparams['option']   = $option;
		$recparams['viewed']   = mosGetParam($_REQUEST, 'viewed', '');
		$recparams['exported'] = mosGetParam($_REQUEST, 'exported', '');
		$recparams['archived'] = mosGetParam($_REQUEST, 'archived', '');
		$recparams['formname'] = mosGetParam($_REQUEST, 'formname', '');

		switch ($task) {
			case 'savesettings' :
				facileFormsRecord::saveSettings($recparams);
				break;
			case 'save' :
				facileFormsRecord::save($recparams);
				break;
			case 'cancel':
				facileFormsRecord::cancel($recparams);
				break;
			case 'edit' :
				facileFormsRecord::edit($recparams, $ids[0]);
				break;
			case 'remove' :
				facileFormsRecord::del($recparams, $ids);
				break;
			case 'viewed' :
				facileFormsRecord::viewed($recparams, $ids);
				break;
			case 'exported' :
				facileFormsRecord::exported($recparams, $ids);
				break;
			case 'archived' :
				facileFormsRecord::archived($recparams, $ids);
				break;
			case 'expxml' :
				facileFormsRecord::expxml($recparams, $ids);
				break;
			case 'config' :
				$url = "index2.php?option=".$recparams['option']."&act=mngrecs";
				if ($recparams['formname']!='') $url .= "&formname=".$recparams['formname'];
				if ($recparams['viewed']  !='') $url .= "&viewed=".$recparams['viewed'];
				if ($recparams['exported']!='') $url .= "&exported=".$recparams['exported'];
				if ($recparams['archived']!='') $url .= "&archived=".$recparams['archived'];
				$ff_config->edit($option, $url);
				break;
			default:
				facileFormsRecord::listitems($recparams);
				break;
		} // switch
		break;

	default:
		$recparams = array();
		$recparams['option']   = $option;
		$recparams['formname'] = $ff_config->formname;
		$recparams['viewed']   = $ff_config->viewed;
		$recparams['exported'] = $ff_config->exported;
		$recparams['archived'] = $ff_config->archived;
		facileFormsRecord::listitems($recparams);
		break;
} // switch

?>