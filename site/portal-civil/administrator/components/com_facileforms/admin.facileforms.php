<?php
/**
* FacileForms - A Joomla Forms Application
* @version 1.4.5
* @package FacileForms
* @copyright (C) 2004-2006 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

global $errors, $errmode;
global $ff_mospath, $ff_admpath, $ff_compath, $ff_request;
global $ff_mossite, $ff_admsite, $ff_admicon, $ff_comsite;
global $ff_config, $ff_compatible, $ff_install;

if (!isset($ff_compath)) { // joomla!
	// ensure user has access to this function
	if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
		| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_facileforms' ))) {
		mosRedirect( 'index2.php', _NOT_AUTH );
	} // if

	// get paths
	$comppath = '/components/com_facileforms';
	$ff_admpath = dirname(__FILE__);
	$ff_mospath = str_replace('\\','/',dirname(dirname(dirname($ff_admpath))));
	$ff_admpath = str_replace('\\','/',$ff_admpath);
	$ff_compath = $ff_mospath.$comppath;

	require_once($ff_mospath.'/administrator/includes/menubar.html.php');
	require_once($ff_admpath.'/toolbar.facileforms.php');
} // if

$errors = array();
$errmode = 'die';   // die or log

// compatibility check
if (!$ff_compatible) {
	echo '<h1>'._FACILEFORMS_INCOMPATIBLE.'</h1>';
	exit;
} // if

// load ff parameters
$ff_request = array();
reset($_REQUEST);
while (list($prop, $val) = each($_REQUEST))
	if (is_scalar($val) && substr($prop,0,9)=='ff_param_')
		$ff_request[$prop] = $val;

if ($ff_install) {
	$act = 'installation';
	$task = 'step2';
} // if

$ids = mosGetParam($_REQUEST, 'ids', array());

switch($act) {
	case 'installation':
		require_once($ff_admpath.'/admin/install.php');
		break;
	case 'configuration':
		require_once($ff_admpath.'/admin/config.php');
		break;
	case 'managemenus':
		require_once($ff_admpath.'/admin/menu.php');
		break;
	case 'manageforms':
		require_once($ff_admpath.'/admin/form.php');
		break;
	case 'editpage':
		require_once($ff_admpath.'/admin/element.php');
		break;
	case 'managescripts':
		require_once($ff_admpath.'/admin/script.php');
		break;
	case 'managepieces':
		require_once($ff_admpath.'/admin/piece.php');
		break;
	case 'run':
		require_once($ff_admpath.'/admin/run.php');
		break;
	default:
		require_once($ff_admpath.'/admin/record.php');
		break;
} // switch

// some general purpose functions for admin

function isInputElement($type)
{
	switch ($type) {
		case 'Static Text/HTML':
		case 'Rectangle':
		case 'Image':
		case 'Tooltip':
		case 'Query List':
		case 'Regular Button':
		case 'Graphic Button':
		case 'Icon':
			return false;
		default:
			break;
	} // switch
	return true;
} // isInputElement

function isVisibleElement($type)
{
	switch ($type) {
		case 'Hidden Input':
			return false;
		default:
			break;
	} // switch
	return true;
} // isVisibleElement

function _ff_query($sql, $insert = 0)
{
	global $database, $errors;
	$id = null;
	$database->setQuery($sql);
	$database->query();
	if ($database->getErrorNum()) {
		if ($errmode=='log')
			$errors[] = $database->getErrorMsg();
		else
			die($database->stderr());
	} // if
	if ($insert) $id = $database->insertid();
	return $id;
} // _ff_query

function _ff_select($sql)
{
	global $database, $errors;
	$database->setQuery($sql);
	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		if ($errmode=='log')
			$errors[] = $database->getErrorMsg();
		else
			die($database->stderr());
	} // if
	return $rows;
} // _ff_select

function _ff_selectValue($sql)
{
	global $database, $errors;
	$database->setQuery($sql);
	$value = $database->loadResult();
	if ($database->getErrorNum()) {
		if ($errmode=='log')
			$errors[] = $database->getErrorMsg();
		else
			die($database->stderr());
	} // if
	return $value;
} // _ff_selectValue

function protectedComponentIds()
{
	$rows = _ff_select(
		"select id, parent from #__components ".
		"where `option`='com_facileforms' ".
		"and admin_menu_link in (".
			"'option=com_facileforms&act=managerecs',".
			"'option=com_facileforms&act=managemenus',".
			"'option=com_facileforms&act=manageforms',".
			"'option=com_facileforms&act=managescripts',".
			"'option=com_facileforms&act=managepieces',".
			"'option=com_facileforms&act=configuration'".
		") ".
		"order by id"
	);
	$parent = 0;
	$ids = array();
	if (count($rows)) foreach ($rows as $row) {
		if ($parent==0) {
			$parent = 1;
			$ids[] = $row->parent;
		} // if
		$ids[] = $row->id;
	} // foreach
	return implode($ids,',');
} // protectedComponentIds

function addComponentMenu($row, $parent)
{
	$admin_menu_link = '';
	if ($row->name!='') {
		$admin_menu_link =
			'option=com_facileforms'.
			'&act=run'.
			'&ff_name='.$row->name;
		if ($row->page!=1) $admin_menu_link .= '&ff_page='.$row->page;
		if ($row->frame==1) $admin_menu_link .= '&ff_frame=1';
		if ($row->border==1) $admin_menu_link .= '&ff_border=1';
		if ($row->params!='') $admin_menu_link .= $row->params;
	} // if
	if ($parent==0) $ordering = 0; else $ordering = $row->ordering;
	return _ff_query(
		"insert into #__components (".
			"id, name, link, menuid, parent, ".
			"admin_menu_link, admin_menu_alt, `option`, ".
			"ordering, admin_menu_img, iscore, params".
		") ".
		"values (".
			"'', '".mysql_escape_string($row->title)."', '', 0, $parent, ".
			"'$admin_menu_link', '".mysql_escape_string($row->title)."', 'com_facileforms', ".
			"'$ordering', '$row->img', 1, ''".
		")",
		true
	);
} // addComponentMenu

function updateComponentMenus()
{
	// remove unprotected menu items
	$protids = protectedComponentIds();
	_ff_query(
		"delete from #__components ".
		"where `option`='com_facileforms' ".
		"and id not in ($protids)"
	);

	// add published menu items
	$rows = _ff_select(
		"select ".
			"m.id as id, ".
			"m.parent as parent, ".
			"m.ordering as ordering, ".
			"m.title as title, ".
			"m.img as img, ".
			"m.name as name, ".
			"m.page as page, ".
			"m.frame as frame, ".
			"m.border as border, ".
			"m.params as params, ".
			"m.published as published ".
		"from #__facileforms_compmenus as m ".
			"left join #__facileforms_compmenus as p on m.parent=p.id ".
		"where m.published=1 ".
			"and (m.parent=0 or p.published=1) ".
		"order by ".
			"if(m.parent,p.ordering,m.ordering), ".
			"if(m.parent,m.ordering,-1)"
	);
	$parent = 0;
	if (count($rows)) foreach ($rows as $row) {
		if ($row->parent==0)
			$parent = addComponentMenu($row, 0);
		else
			addComponentMenu($row, $parent);
	} // foreach
} // updateComponentMenus

function dropPackage($id)
{
	// drop package settings
	_ff_query("delete from #__facileforms_packages where id = binary '$id'");

	// drop backend menus
	$rows = _ff_select("select id from #__facileforms_compmenus where package = binary '$id'");
	if (count($rows)) foreach ($rows as $row)
		_ff_query("delete from #__facileforms_compmenus where id=$row->id or parent=$row->id");
	updateComponentMenus();

	// drop forms
	$rows = _ff_select("select id from #__facileforms_forms where package = binary '$id'");
	if (count($rows)) foreach ($rows as $row) {
		_ff_query("delete from #__facileforms_elements where form = $row->id");
		_ff_query("delete from #__facileforms_forms where id = $row->id");
	} // if

	// drop scripts
	_ff_query("delete from #__facileforms_scripts where package = binary '$id'");

	// drop pieces
	_ff_query("delete from #__facileforms_pieces where package = binary '$id'");
} // dropPackage

function savePackage($id, $name, $title, $version, $created, $author, $email, $url, $description, $copyright)
{
	$cnt = _ff_selectValue("select count(*) from #__facileforms_packages where id=binary '$id'");
	if (!$cnt) {
		_ff_query(
			"insert into #__facileforms_packages ".
					"(id, name, title, version, created, author, ".
					 "email, url, description, copyright) ".
			"values ('$id', '$name', '$title', '$version', '$created', '$author', ".
					"'$email', '$url', '$description', '$copyright')"
		);
	} else {
		_ff_query(
			"update #__facileforms_packages ".
				"set name='$name', title='$title', version='$version', created='$created', author='$author', ".
				"email='$email', url='$url', description='$description', copyright='$copyright' ".
			"where id = binary '$id'"
		);
	} // if
} // savePackage

function relinkScripts(&$oldscripts)
{
	if (count($oldscripts))
		foreach ($oldscripts as $row) {
			$newid = _ff_selectValue("select max(id) from #__facileforms_scripts where name = '".$row->name."'");
			if ($newid) {
				_ff_query("update #__facileforms_forms set script1id=$newid where script1id=$row->id");
				_ff_query("update #__facileforms_forms set script2id=$newid where script2id=$row->id");
				_ff_query("update #__facileforms_elements set script1id=$newid where script1id=$row->id");
				_ff_query("update #__facileforms_elements set script2id=$newid where script2id=$row->id");
				_ff_query("update #__facileforms_elements set script3id=$newid where script3id=$row->id");
			} // if
		} // foreach
} // relinkScripts

function relinkPieces(&$oldpieces)
{
	if (count($oldpieces))
		foreach ($oldpieces as $row) {
			$newid = _ff_selectValue("select max(id) from #__facileforms_pieces where name = '".$row->name."'");
			if ($newid) {
				_ff_query("update #__facileforms_forms set piece1id=$newid where piece1id=$row->id");
				_ff_query("update #__facileforms_forms set piece2id=$newid where piece2id=$row->id");
				_ff_query("update #__facileforms_forms set piece3id=$newid where piece3id=$row->id");
				_ff_query("update #__facileforms_forms set piece4id=$newid where piece4id=$row->id");
			} // if
		} // foreach
} // relinkPieces

?>