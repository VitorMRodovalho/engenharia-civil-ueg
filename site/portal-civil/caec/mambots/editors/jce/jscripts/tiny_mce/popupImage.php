<?php
//Redirect to index2.php?option=com_mosce

define( '_VALID_MOS', 1 );
include ("../../../../../configuration.php" );
include ( $mosConfig_absolute_path."/includes/joomla.php" );

$mode = mosGetParam( $_GET, 'mode', 'basic' );
$title = mosGetParam( $_GET, 'title' );
$alt = mosGetParam( $_GET, 'alt' );

$img = mosGetParam( $_GET, 'img' );
$src = mosGetParam( $_GET, 'src' );

$img = ( $src ) ? $src : $img;
mosRedirect( "$mosConfig_live_site/index2.php?option=com_mosce&task=popup&img=$img&mode=$mode&title=$title&alt=$alt" );
?>
