<?php
/* AkoBook AutoScript-Protection
 *
 * @author: Dominik Paulus, [email]mail@dpaulus.de[/email]
 * 25.03.2005
 */

session_start('akobookcode');
session_register('code');

mt_srand((double)microtime()*1000000);
// Numerical code
$seccode = mt_rand(10000, 99999);
/* // Code with chars. But difficult to read.
$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$seccode = "";
for($i = 0; $i < 5; $i++)
	$seccode .= $chars[mt_rand(0, 35)];
*/
$_SESSION['code'] = $seccode;

// create image
header("Content-Type: image/png");
$im = imagecreate(60, 18) or die('Image create error!');
// Image colors
// imagecolorallocate($im, R, G, B) Only change R,G,B!
$bgcolor = imagecolorallocate($im, 255, 244, 234);
$fontcolor = imagecolorallocate($im, 255, 128, 0);
$linecolor = imagecolorallocate($im, 255, 200, 150);
$bordercolor = imagecolorallocate($im, 255, 128, 0);
// Grid
for($x=10; $x <= 100; $x+=10)
    imageline($im, $x, 0, $x, 50, $linecolor);
// Middleline
imageline($im, 0, 9, 100, 9, $linecolor);
// Border
imageline($im, 0, 0, 0, 50, $bordercolor);
imageline($im, 0, 0, 100, 0, $bordercolor);
imageline($im, 0, 17, 100, 17, $bordercolor);
imageline($im, 59, 0, 59, 17, $bordercolor);

imagestring($im, 5, 8, 1, $seccode, $fontcolor);
imagepng($im);
imagedestroy($im);
?>