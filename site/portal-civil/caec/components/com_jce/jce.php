<?php
/*
* JCE 1.0.0 for Joomla 1.0.x
* @version $Id: jce.php, v 1.0.0 2006/03/01 16:05:00 ryandemmer Exp $
* @package JCE 1.0.0
* @copyright (C) 2005 - 2006 Ryan Demmer
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Oficial website: http://www.cellardoor.za.net/jce/
*/
defined('_VALID_MOS') or die('Restricted Access.');

$task = mosGetParam( $_REQUEST, 'task' );

switch ( $task )
{
    case 'popup':
        showPopup();
    break;
    case 'plugin':
        $plugin = mosGetParam( $_GET, 'plugin' );
        $file = basename( mosGetParam( $_GET, 'file' ) );
        $path = 'mambots/editors/jce/jscripts/tiny_mce/plugins/'.$plugin.'/'.$file;
        if( file_exists( $mosConfig_absolute_path . DIRECTORY_SEPARATOR . $path ) ){
            include_once $mosConfig_absolute_path . DIRECTORY_SEPARATOR . $path;
        }else{
            echo 'File '.$path.' not Found';
        }
    break;
    case 'help':
        $plugin = mosGetParam( $_GET, 'plugin' );
        $file = basename( mosGetParam( $_GET, 'file' ) );
        $path = 'mambots/editors/jce/jscripts/tiny_mce/plugins/'.$plugin.'/docs/'.$file;
        if( file_exists( $mosConfig_absolute_path . DIRECTORY_SEPARATOR . $path ) ){
            include_once $mosConfig_absolute_path . DIRECTORY_SEPARATOR . $path;
        }else{
            echo 'File '.$path.' not Found';
        }
    break;
    case 'lib':
        $file = basename( mosGetParam( $_GET, 'file' ) );
        $path = 'mambots/editors/jce/jscripts/tiny_mce/libraries/'.$file;
        if( file_exists( $mosConfig_absolute_path . DIRECTORY_SEPARATOR . $path ) ){
            include_once $mosConfig_absolute_path . DIRECTORY_SEPARATOR . $path;
        }else{
            echo 'File '.$path.' not Found';
        }
    break;
}
function showPopup()
{
    global $mosConfig_live_site, $mosConfig_absolute_path, $template;

    $img = mosGetParam( $_REQUEST, 'img' );
    $title = mosGetParam( $_REQUEST, 'title', 'Image' );
    $mode = mosGetParam( $_REQUEST, 'mode', 'basic' );
    $right_click = mosGetParam( $_REQUEST, 'rightclick', '0' );
    $print = mosGetParam( $_REQUEST, 'print', '0' );
    $offsite = mosGetParam( $_REQUEST, 'offsite', '0' );
    $w = mosGetParam( $_REQUEST, 'w');
    $h = mosGetParam( $_REQUEST, 'h');
    
    if( !$offsite ) $img = $mosConfig_live_site.'/'.$img;
    ?>
    <style type="text/css">
        body{
            margin: 0px;
            padding: 0px;
        }
    </style>
    <?php if($right_click){?>
    <script type="text/javascript">
    function clickIE4(){
        if (event.button==2){
            return false;
        }
    }
    function clickNS4(e){
        if (document.layers||document.getElementById&&!document.all){
            if (e.which==2||e.which==3){
                return false;
            }
        }
    }
    if (document.layers){
        document.captureEvents(Event.MOUSEDOWN);
        document.onmousedown=clickNS4;
    }
    else if (document.all&&!document.getElementById){
        document.onmousedown=clickIE4;
    }
    document.oncontextmenu=new Function("return false");
    </script>
    <?php
    }
    switch( $mode ){
        case 'basic':
    ?>
            <img src="<?php echo $img;?>" width="<?php echo $w;?>" height="<?php echo $h;?>" title="<?php echo $title;?>" alt="<?php echo $title;?>" style="cursor:pointer;" onclick="window.close();" />
    <?php
        break;
        case 'advanced':
    ?>
            <table align="center" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td align="left" class="contentheading" style="width:<?php echo $w-18;?>px; margin-left: 5px;"><?php echo $title;?></td>
                    <td align="right" style="width:18px;" class="buttonheading">
				        <?php if($print){?>
                            <a href="javascript:;" onClick="window.print(); return false"><img src="<?php echo $mosConfig_live_site; ?>/images/M_images/printButton.png" width="16" height="16" alt="save" title="save" border="0" style="vertical-align:middle;"/></a>
                        <?php }?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><img src="<?php echo $img;?>" width="<?php echo $w;?>" height="<?php echo $h;?>" title="<?php echo $title;?>" alt="<?php echo $title;?>" style="cursor:pointer;" onclick="window.close();" /></td>
	           </tr>
            </table>
    <?php
        break;
    }
}
?>
