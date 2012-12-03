<?php

$directory = "";
$images = glob($directory . "*.gif");
if (!$_SERVER['HTTPS']) { $server_protocol = "http://"; } else { $server_protocol = "https://"; }
$base_url = $server_protocol.$_SERVER['SERVER_NAME'].preg_replace("/(.*)assets\/(.*)/", "\\1", $_SERVER['PHP_SELF']);
$image_url = $base_url."assets/templates/addons/visuex_paypal/images/";
echo "<center>";
foreach($images as $image) {
echo "<div style=\"width:260px;height:180px;float:left;margin:0 25px 50px 0;\"><img src=\"".$image_url.$image."\" /><br /><br /><input size=\"20\" value=\"".$image."\" /></div>";
}
echo "</center>";
?>
