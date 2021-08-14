<?php
/**
 * Email Styles
 *
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$bg              = get_option( 'woocommerce_email_background_color' );
$body            = get_option( 'woocommerce_email_body_background_color' );
$base            = get_option( 'woocommerce_email_base_color' );
$base_text       = wc_light_or_dark( $base, '#202020', '#ffffff' );
$text            = get_option( 'woocommerce_email_text_color' );

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
?>
#wrapper {
    background-color: <?php echo esc_attr( $bg ); ?>;
    margin: 0;
    padding: 70px 0 70px 0;
    -webkit-text-size-adjust: none !important;
    width: 100%;
}
#template_header_image{background:#000; max-width: 600px;}
#template_header_image p{margin:0;}
#template_header_image p img{display:block; width:100%; height:auto;}
#template_container {
    background-color: <?php echo esc_attr( $body ); ?>;
	box-shadow: none !important;
    border-radius: 0 !important;
	border:1px solid #999;
	border-top-color:#897846!important;
	max-width: 600px;
}
#template_header {
    background-color: <?php echo esc_attr( $base ); ?>;
    border-radius: 0!important;
    color: <?php echo esc_attr( $base_text ); ?>;
    border-bottom: 0;
    font-weight: bold;
    line-height: 100%;
    vertical-align: middle;
    font-family: serif;
	padding:24px 12px!important;
	max-width: 600px;
}
#header_wrapper{
	padding:0;
}
#template_header h1 {
    font-family: serif;
	text-align:center!important;
	padding:0;
	color:#f4d57d;
}
#template_footer {
    background:#FFF;
	border-top:1px solid #CCC;
}
#template_footer td {
    padding: 0;
    -webkit-border-radius: 0;
}

#template_footer #credit {
    border:0;
    color: <?php echo esc_attr( $base_lighter_40 ); ?>;
    line-height:125%;
	font-family: sans-serif;
    font-size:15px;
    line-height:125%;
    text-align:center;
	color:#555;
    padding: 12px 24px;
}
#template_footer #credit a{
	color:#111;
    text-decoration:none;
	line-height:150%;
}
#template_footer #credit a b{
	font-family:Serif;
	font-size:18px;
}
#template_footer #credit span a{
	color:#3f729b;
	font-size:14px;
}
#body_content {
    background-color: <?php echo esc_attr( $body ); ?>;
}
#body_content table td {
    padding: 24px;
}
#body_content table td td {
    padding: 12px;
}
#body_content table td th {
    padding: 12px;
}

#body_content p {
    margin: 0 0 16px;
}

#body_content_inner {
    color: <?php echo esc_attr( $text_lighter_20 ); ?>;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 14px;
    line-height: 150%;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h1 {
    color: <?php echo esc_attr( $base ); ?>;
    display: block;
    font-family: serif;
    font-size: 30px;
    font-weight: 300;
    line-height: 150%;
    margin: 0;
    padding: 24px 36px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
    text-shadow: 0 1px 0 <?php echo esc_attr( $base_lighter_20 ); ?>;
    -webkit-font-smoothing: antialiased;
}

h2 {
    color: <?php echo esc_attr( $base ); ?>;
    display: block;
    font-family: sans-serif;
    font-size: 18px;
    font-weight: bold;
    line-height: 130%;
    margin: 16px 0 8px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h3 {
    color: <?php echo esc_attr( $base ); ?>;
    display: block;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 16px;
    font-weight: bold;
    line-height: 130%;
    margin: 16px 0 8px;
    text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

a {
    color: <?php echo esc_attr( $base ); ?>;
    font-weight: normal;
    text-decoration: underline;
}

img {
    border: none;
    display: inline;
    font-size: 14px;
    font-weight: bold;
    height: auto;
    line-height: 100%;
    outline: none;
    text-decoration: none;
    text-transform: capitalize;
}
<?php
