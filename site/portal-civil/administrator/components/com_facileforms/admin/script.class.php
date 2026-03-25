<?php
/**
* FacileForms - A Joomla Forms Application
* @version 1.4.5
* @package FacileForms
* @copyright (C) 2004-2006 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

require_once($ff_admpath.'/admin/script.html.php');

class facileFormsScript
{
	function edit($option, $pkg, $ids)
	{
		global $database;
		$typelist = array();
		$typelist[] = array('Untyped',_FACILEFORMS_SCRIPTS_UNTYPED);
		$typelist[] = array('Element Init',_FACILEFORMS_SCRIPTS_ELEMENTINIT);
		$typelist[] = array('Element Action',_FACILEFORMS_SCRIPTS_ELEMENTACTION);
		$typelist[] = array('Element Validation',_FACILEFORMS_SCRIPTS_ELEMENTVALID);
		$typelist[] = array('Form Init',_FACILEFORMS_SCRIPTS_FORMINIT);
		$typelist[] = array('Form Submitted',_FACILEFORMS_SCRIPTS_FORMSUBMIT);
		$row = new facileFormsScripts($database);
		if (count($ids)){
			$row->load($ids[0]);
		} else {
			$row->type = $typelist[0];
			$row->package = $pkg;
			$row->published = 1;
		} // if
		HTML_facileFormsScript::edit($option, $pkg, $row, $typelist);
	} // edit

	function save($option, $pkg)
	{
		global $database;
		$row = new facileFormsScripts($database);
		// bind it to the table
		if (!$row->bind($_POST)) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		// store it in the db
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		mosRedirect(
			"index2.php?option=$option&act=managescripts&pkg=$pkg",
			_FACILEFORMS_SCRIPTS_SAVED);
	} // save

	function cancel($option, $pkg)
	{
		mosRedirect("index2.php?option=$option&act=managescripts&pkg=$pkg");
	} // cancel

	function copy($option, $pkg, $ids)
	{
		global $database;
		$total = count($ids);
		$row = new facileFormsScripts($database);
		if (count($ids)) foreach ($ids as $id) {
			$row->load(intval($id));
			$row->id       = NULL;
			$row->store();
		} // foreach
		$msg = $total.' '._FACILEFORMS_SCRIPTS_SUCCOPIED;
		mosRedirect("index2.php?option=$option&act=managescripts&pkg=$pkg&mosmsg=$msg");
	} // copy

	function del($option, $pkg, $ids)
	{
		global $database;
		if (count($ids)) {
			$ids = implode(',', $ids);
			$database->setQuery("delete from #__facileforms_scripts where id in ($ids)");
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			} // if
		} // if
		mosRedirect("index2.php?option=$option&act=managescripts&pkg=$pkg");
	} // del

	function publish($option, $pkg, $ids, $publish)
	{
		global $database, $my;
		$ids = implode( ',', $ids );
		$database->setQuery(
			"update #__facileforms_scripts set published='$publish' where id in ($ids)"
		);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		mosRedirect( "index2.php?option=$option&act=managescripts&pkg=$pkg" );
	} // publish

	function listitems($option, $pkg)
	{
		global $database;

		$database->setQuery(
			"select distinct binary package as name ".
			"from #__facileforms_scripts ".
			"where package is not null and package!='' ".
			"order by name"
		);
		$pkgs = $database->loadObjectList();
		if ($database->getErrorNum()) { echo $database->stderr(); return false; }
		$pkgok = $pkg=='';
		if (!$pkgok && count($pkgs)) foreach ($pkgs as $p) if ($p->name==$pkg) { $pkgok = true; break; }
		if (!$pkgok) $pkg = '';
		$pkglist = array();
		$pkglist[] = array($pkg=='', '');
		if (count($pkgs)) foreach ($pkgs as $p) $pkglist[] = array($p->name==$pkg, $p->name);

		$database->setQuery(
			"select * from #__facileforms_scripts ".
			"where package = binary '".mysql_escape_string($pkg)."' ".
			"order by type, name, id desc"
		);
		$rows = $database->loadObjectList();
		if ($database->getErrorNum()) { echo $database->stderr(); return false; }
		HTML_facileFormsScript::listitems($option, $rows, $pkglist);
	} // listitems

} // class facileFormsScript
?>