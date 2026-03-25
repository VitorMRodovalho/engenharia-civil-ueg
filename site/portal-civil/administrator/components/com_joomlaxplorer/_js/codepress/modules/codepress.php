<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
/*
 * CodePress - Real Time Syntax Highlighting Editor written in JavaScript -  http://codepress.fermads.net/
 *
 * Copyright (C) 2006 Fernando M.A.d.S. <fermads@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify it under the terms 
 * of the GNU Lesser General Public License as published by the Free Software Foundation.
 *
 * Read the full licence: http://www.opensource.org/licenses/lgpl-license.php
 *
 * Very simple implementation of server side script to open files and send to CodePress interface
 */

/*
 * root directory of files to open/edit from server
 * there are 2 ways to set the file directory. See examples below, edit and comment/uncomment the appropriate
 */

function send_codepress($dir, $item) {
	global $mosConfig_absolute_path, $mosConfig_live_site;
	$code = "";
	$engine = mosGetParam( $_GET, 'engine', 'older' );
	$language = mosGetParam( $_GET, 'language', 'generic' );
	$codepress_url = $mosConfig_live_site.'/administrator/components/com_joomlaxplorer/_js/codepress';
	
    $full_file = get_abs_item($dir, basename($item) );

	if(file_exists($full_file)) {
	    $code = file_get_contents($full_file);
	    $code = preg_replace("/&/","&amp;",$code);
	    $code = preg_replace("/</","&lt;",$code);
	    $code = preg_replace("/>/","&gt;",$code);
		//$code = preg_replace("/\r\n/","<br>",$code); // opera and khtml engines
	}
	while( @ob_end_clean() );

	echo '<?xml version="1.0" encoding="UTF-8"?>';
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>CodePress - Real Time Syntax Highlighting Editor written in JavaScript</title>
	<meta name="description" content="CodePress source code editor window" />
	<link type="text/css" href="<?php echo $codepress_url ?>/themes/default/codepress.css?timestamp=<?=time()?>" rel="stylesheet" />
	<link type="text/css" href="<?php echo $codepress_url ?>/languages/<?=$language?>.css?timestamp=<?=time()?>" rel="stylesheet" id="cp-lang-style" />
	<script type="text/javascript" src="<?php echo $codepress_url ?>/engines/<?=$engine?>.js?timestamp=<?=time()?>"></script>
	<script type="text/javascript" src="<?php echo $codepress_url ?>/languages/<?=$language?>.js?timestamp=<?=time()?>"></script>
</head>
<? 
if ($engine == "gecko") echo "<body id='code'>".$code."</body>";
else if($engine == "msie") echo "<body><pre id='code'>".$code."</pre></body>";
?>
</html>
	
	<?php
	exit;
}
?>