<?php
/**
* FacileForms - A Joomla Forms Application
* @version 1.4.4
* @package FacileForms
* @copyright (C) 2004-2005 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

require_once($ff_admpath.'/admin/record.html.php');

class facileFormsRecord
{
	function expxml(&$recparams, $ids)
	{
		global $database, $ff_compath, $ff_version, $mosConfig_fileperms;

		$xmlname = $ff_compath.'/exports/ffexport-'.date('YmdHis').'.xml';
		$existed = file_exists($xmlname);
		if ($existed) {
			$permission = is_writable($xmlname);
			if (!$permission) {
				echo "<script> alert('<?php echo _FACILEFORMS_RECORDS_XMLNORWRTBL; ?>'); window.history.go(-1);</script>\n";
				exit();
			} // if
		} // if

		$file= fopen($xmlname, "w");

		$ids = implode(',', $ids);
		$database->setQuery(
			"select * from #__facileforms_records where id in ($ids) order by id"
		);
		$recs = $database->loadObjectList();
		if ($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		} // if

		$xml  = '<?xml version="1.0" encoding="iso-8859-1" ?>'.nl().
				'<FacileFormsExport type="records" version="'.$ff_version.'">'.nl().
				indent(1).'<exportdate>'.date('Y-m-d H:i:s').'</exportdate>'.nl();

		$form = '';
		for($r = 0; $r < count($recs); $r++) {
			$rec = $recs[$r];
			$xml .= indent(1).'<record id="'.$rec->id.'">'.nl().
					indent(2).'<submitted>'.$rec->submitted.'</submitted>'.nl().
					indent(2).'<form>'.$rec->form.'</form>'.nl().
					indent(2).'<title>'.htmlspecialchars($rec->title).'</title>'.nl().
					indent(2).'<name>'.$rec->name.'</name>'.nl().
					indent(2).'<ip>'.$rec->ip.'</ip>'.nl().
					indent(2).'<browser>'.htmlspecialchars($rec->browser).'</browser>'.nl().
					indent(2).'<opsys>'.htmlspecialchars($rec->opsys).'</opsys>'.nl().
					indent(2).'<provider>'.$rec->provider.'</provider>'.nl().
					indent(2).'<viewed>'.$rec->viewed.'</viewed>'.nl().
					indent(2).'<exported>'.$rec->exported.'</exported>'.nl().
					indent(2).'<archived>'.$rec->archived.'</archived>'.nl();
			$database->setQuery(
				"select * from #__facileforms_subrecords where record = $rec->id order by id"
			);
			$subs = $database->loadObjectList();
			for($s = 0; $s < count($subs); $s++) {
				$sub = $subs[$s];
				$xml .= indent(2).'<subrecord id="'.$sub->id.'">'.nl().
						indent(3).'<element>'.$sub->element.'</element>'.nl().
						indent(3).'<name>'.$sub->name.'</name>'.nl().
						indent(3).'<title>'.htmlspecialchars($sub->title).'</title>'.nl().
						indent(3).'<type>'.$sub->type.'</type>'.nl().
						indent(3).'<value>'.htmlspecialchars($sub->value).'</value>'.nl().
						indent(2).'</subrecord>'.nl();
			} // for
			$xml .= indent(1).'</record>'.nl();
		} // for
		$xml .= '</FacileFormsExport>'.nl();
		fwrite($file, $xml);
		fclose($file);

		if (!$existed) {
			$filemode = NULL;
			if (isset($mosConfig_fileperms)) {
				if ($mosConfig_fileperms!='')
					$filemode = octdec($mosConfig_fileperms);
			} else
				$filemode = 0644;
			if (isset($filemode)) @chmod($xmlname, $filemode);
		} // if

		$database->setQuery(
			"update #__facileforms_records set exported=1 where id in ($ids)"
		);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		facileFormsRecord::listitems($recparams, $xmlname);
	} // expxml

	function saveSettings(&$recparams)
	{
		global $ff_config;

		$ff_config->viewed   = $recparams['viewed'];
		$ff_config->exported = $recparams['exported'];
		$ff_config->archived = $recparams['archived'];
		$ff_config->formname = $recparams['formname'];
		$ff_config->store();

		facileFormsRecord::cancel($recparams);
	} // saveSettings


	function save(&$recparams)
	{
		global $database;
		$id = mosGetParam($_REQUEST, 'record_id', '');
		if ($id != '') {
			$database->setQuery(
				"update #__facileforms_records set viewed=1 where id=$id"
			);
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			} // if
		} // if
		facileFormsRecord::cancel($recparams);
	} // save

	function cancel(&$recparams)
	{
		$url = "index2.php?option=".$recparams['option']."&act=mngrecs";
		if ($recparams['formname']!='') $url .= "&formname=".$recparams['formname'];
		if ($recparams['viewed']  !='') $url .= "&viewed=".$recparams['viewed'];
		if ($recparams['exported']!='') $url .= "&exported=".$recparams['exported'];
		if ($recparams['archived']!='') $url .= "&archived=".$recparams['archived'];
		mosRedirect($url);
	} // cancel

	function edit(&$recparams, $id)
	{
		global $database;
		$rec = new facileFormsRecords($database);
		$rec->load($id);
		$database->setQuery("select * from #__facileforms_subrecords where record=$id order by id");
		$subs = $database->loadObjectList();
		if ($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		} // if
		HTML_facileFormsRecord::edit($recparams, $rec, $subs);
	} // edit

	function del(&$recparams, $ids)
	{
		global $database;
		if (count($ids)) {
			$ids = implode(',', $ids);
			$database->setQuery("delete from #__facileforms_subrecords where record in ($ids)");
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			} // if
			$database->setQuery("delete from #__facileforms_records where id in ($ids)");
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			} // if
		} // if
		facileFormsRecord::cancel($recparams);
	} // del

	function viewed(&$recparams, $ids)
	{
		global $database;
		$ids = implode( ',', $ids );
		$database->setQuery(
			"update #__facileforms_records set viewed=1-viewed where id in ($ids)"
		);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		facileFormsRecord::cancel($recparams);
	} // viewed

	function exported(&$recparams, $ids)
	{
		global $database;
		$ids = implode( ',', $ids );
		$database->setQuery(
			"update #__facileforms_records set exported=1-exported where id in ($ids)"
		);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		facileFormsRecord::cancel($recparams);
	} // exported

	function archived(&$recparams, $ids)
	{
		global $database;
		$ids = implode( ',', $ids );
		$database->setQuery(
			"update #__facileforms_records set archived=1-archived where id in ($ids)"
		);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		facileFormsRecord::cancel($recparams);
	} // archived

	function listitems(&$recparams, $downloadfile='')
	{
		global $database;
		$sql = "select * from #__facileforms_records";
		$where = true;
		if ($recparams['formname']!='') {
			if ($where) { $sql .= " where"; $where = false; } else $sql .= " and";
			$sql .= " name='".$recparams['formname']."'";
		} // if
		if ($recparams['viewed']=='0' || $recparams['viewed']=='1') {
			if ($where) { $sql .= " where"; $where = false; } else $sql .= " and";
			$sql .= " viewed=".$recparams['viewed'];
		} // if
		if ($recparams['exported']=='0' || $recparams['exported']=='1') {
			if ($where) { $sql .= " where"; $where = false; } else $sql .= " and";
			$sql .= " exported=".$recparams['exported'];
		} // if
		if ($recparams['archived']=='0' || $recparams['archived']=='1') {
			if ($where) { $sql .= " where"; $where = false; } else $sql .= " and";
			$sql .= " archived=".$recparams['archived'];
		} // if
		$sql .= " order by id";
		$database->setQuery($sql);
		$rows = $database->loadObjectList();
		if ($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		} // if
		HTML_facileFormsRecord::listitems($recparams, $rows, $downloadfile);
	} // listitems

} // class facileFormsRecord
?>