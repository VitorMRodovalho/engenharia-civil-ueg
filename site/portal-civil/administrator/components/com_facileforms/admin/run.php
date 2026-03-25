<?php
/**
* FacileForms - A Joomla Forms Application
* @version 1.4.5
* @package FacileForms
* @copyright (C) 2004-2006 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

// process form parameters
$formid = mosGetParam($_REQUEST,'ff_form',null);
if ($formid!=null) {
	$database->setQuery(
		"select * from #__facileforms_forms ".
		"where id=$formid ".
		"and published=1 ".
		"and (runmode=0 or runmode=2)"
	);
	$forms = $database->loadObjectList();
	if (count($forms) < 1) { echo 'Form '.$formid.' not found!'; exit; }
	$form = $forms[0];
} else {
	$formname = mosGetParam($_REQUEST,'ff_name','');
	if ($formname == '') {
		echo 'Parameter <em>&ff_name=FormName</em> missing in URL.';
		exit;
	} // if
	$database->setQuery(
		"select * from #__facileforms_forms ".
		"where name='".$formname."' ".
		"and published=1 ".
		"and (runmode=0 or runmode=2)".
		"order by ordering, id"
	);
	$forms = $database->loadObjectList();
	if (count($forms) < 1) { echo 'Form <em>'.$formname.'</em> not found!'; exit; }
	$form = $forms[0];
} // if

$page    = mosGetParam($_REQUEST,'ff_page',1);
$inframe = mosGetParam($_REQUEST,'ff_frame',0);
$border  = mosGetParam($_REQUEST,'ff_border',0);
$task    = mosGetParam($_REQUEST,'ff_task','view');

if ($inframe) {
	// create url for the frame
	$url =
		$ff_mossite.'/index2.php'
			.'?option=com_facileforms'
			.'&amp;Itemid=1'
			.'&amp;ff_form='.$form->id
			.'&amp;ff_frame=1'
			.'&amp;ff_runmode='._FF_RUNMODE_BACKEND;
	if ($page != 1) $url .= '&amp;ff_page='.$page;
	if ($border) $url .= '&amp;ff_border=1';
	reset($ff_request);
	while (list($prop, $val) = each($ff_request))
		$url .= '&amp;'.$prop.'='.urlencode($val);

	// prepare iframe width
	$framewidth = 'width="'.$form->width;
	if ($form->widthmode) $framewidth .= '%" '; else $framewidth .= '" ';

	// prepare iframe height
	$frameheight = '';
	if (!$form->heightmode) $frameheight = 'height="'.$form->height.'" ';

	// assemble iframe parameters
	$params =   'id="ff_frame'.$form->id.'" '.
				'src="'.$url.'" '.
				$framewidth.
				$frameheight.
				'frameborder="'.$border.'" '.
				'allowtransparency="true" '.
				'scrolling="no"';

	// emit frame code
	echo "<iframe ".$params.">\n".
		 "<p>Sorry, your browser cannot display frames!</p>\n".
		 "</iframe>\n".
		 "</div>\n";
} else {
	// process inline
	$database->setQuery("select id from #__users where lower(username)=lower('$my->username')");
	$id = $database->loadResult();
	if ($id) $my->load($id);
	require_once($ff_compath.'/facileforms.process.php');
	if ($task == 'view') {
		echo '<div id="overDiv" style="position:absolute;visibility:hidden;z-index:1000;"></div>'."\n";
		$divstyle = 'width:'.$form->width;
		$divstyle .= ($form->widthmode) ? '%;' : 'px;';;
		if (!$form->heightmode) $divstyle .= 'height:'.$form->height.'px;';
		$tablestyle = ($divstyle=='') ? '' : ' style="'.$divstyle.'"';
		echo '<table cellpadding="0" cellspacing="0" border="'.$border.'"'.$tablestyle.'>'."\n".
			 "<tr><td>\n".
			 '<div style="left:0px;top:0px;'.$divstyle.'position:relative;">'."\n";
	} // if
	$curdir = getcwd();
	chdir($ff_mospath);
	$ff_processor = new HTML_facileFormsProcessor(
		_FF_RUNMODE_BACKEND, false, $form->id, $page, $option,
		null, $border
	);
	chdir($curdir);
	if ($task == 'submit')
		$ff_processor->submit();
	else {
		$ff_processor->view();
		echo "</div>\n</td></tr>\n</table>\n";
	} // if
} // if

?>