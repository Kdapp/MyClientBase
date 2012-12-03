<?php
//
// Visuex - PayPal to MCB Addon
// Author: Michael Tunnell (michael@visuex.com)
// Version: 1.0
// Released: 01/02/2012
//
// Version Check: http://visuex.com/projects/viewdownload/13-myclientbase/22-paypal-to-mcb-addon
//

//
// User Configurable Options
//
$image_filename		= "btn_paynowCC_LG.gif";	// Filename of the desired image...whether PayPal hosted or not. (include image extension = .gif, .jpg, .png, etc.)
$customertopaypal	= true;							// Sends Customer data, such as name and address, to the PayPal payment system. - Default = true (change to false to disable)
$button_alignment	= "right";						// Alignment of the PayPal button. Options = left, center, right.
$logo_url			= "";							// URL to your company logo - Default = empty (disabled) - PayPal's Maximum Image Size = 150 x 90 (in pixels)
$paypal_page_style	= "";							// Defines the page style of the PayPal checkout page. (not MCB related) - Default = empty (let PayPal set it) - Options = paypal (standard), primary (your style you set as primary in PayPal) or page_style_name (use the custom payment page style from your account profile that has the specified name)
$language_page_code	= "US";							// Language Code for the PayPal checkout page (not MCB related) - Default = English (United States)

//
// Do NOT Edit Below this line!
//
$pp_email = $_GET['pp_email'];$currency_code = $_GET['currency_code'];$balance = $_GET['balance'];$invnum = $_GET['invnum'];$client_name = $_GET['client_/name'];$client_addresso = $_GET['client_addresso'];
$client_addresst = $_GET['client_addresst'];$client_city = $_GET['client_city'];$client_state = $_GET['client_state'];$client_zip = $_GET['client_zip'];$lang_invoice = $_GET['lang_invoice'];
if (!$_SERVER['HTTPS']) { $server_protocol = "http://"; } else { $server_protocol = "https://"; } if ($logo_url) { $logo_url = "&amp;image_url=".$logo_url; } if ($paypal_page_style) { $paypal_page_style = "&amp;page_style=".$paypal_page_style; }
if ($customertopaypal) { $customertopaypal = "&amp;first_name=".$client_name."&amp;address1=".$client_addresso."&amp;address2=".$client_addresst."&amp;city=".$client_city."&amp;state=".$client_state."&amp;zip=".$client_zip; }
$base_url = $server_protocol.$_SERVER['SERVER_NAME'].preg_replace("/(.*)assets\/(.*)/", "\\1", $_SERVER['PHP_SELF']); $image_url = $base_url."assets/templates/addons/visuex_paypal/images/".$image_filename;
$return_url_cancel	= urlencode($base_url."index.php/client_center/merchant_cancel"); $return_url_success = urlencode($base_url."index.php/client_center/merchant_return"); $pp_notify_url = urlencode($base_url."index.php/payments/payment_handler/merchant_response");
$link_constants = "&amp;currency_code=".$currency_code."&amp;business=".urlencode($pp_email)."&amp;amount=".$balance."&amp;lc=".$language_page_code."&amp;item_name=".$lang_invoice."+%23+".$invnum."&amp;item_number=".$invnum."&amp;cancel_return=".$return_url_cancel."&amp;return=".$return_url_success."&amp;notify_url=".$pp_notify_url."&amp;no_shipping=1&amp;custom=11";
if ($button_alignment == "center") { $button_alignment_style = ""; echo "<center>";} else { $button_alignment_style = "style=\"float:$button_alignment;\"";}
echo "<a class=\"visuex_paypal\" $button_alignment_style href=\"https://www.paypal.com/cgi-bin/webscr?rm=2&amp;cmd=_xclick".$link_constants.$logo_url.$paypal_page_style.$customertopaypal."\" target=\"_blank\"><img src=\"".$image_url."\" border=\"0\" alt=\"Button to pay MyClientBase invoice with PayPal\" /></a>";
if ($button_alignment == "left" || $button_alignment == "right") { echo "<div style=\"clear:both;\"></div>";}
if ($button_alignment == "center") { echo "</center>";}
?>
