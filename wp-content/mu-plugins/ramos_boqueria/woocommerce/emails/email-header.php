<?php
/**
 * Email Header
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<!-- If you delete this meta tag, the ground will open and swallow you. -->
<meta name="viewport" content="width=device-width" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo get_bloginfo( 'name' ); ?></title>
		<style>
			@import url(http://fonts.googleapis.com/css?family=Lato:400,900);
			<?php 
			/*  This is normally not needed because 
			 *  WooCommerce inserts it into your templates
			 *  automatically. It's here so the styles
			 *  get applied to the preview correctly.
			 */			
			
			wc_get_template( 'emails/email-styles.php');
			
			/* Custom styles can be added here
			  * NOTE: Don't add inline comments in your styles, 
			  * they will break the template.
			  */
			$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			if (strpos($url,'admin-ajax.php') !== false){
			?>
			#template_container {
				max-width: 640px;
			}
			#template-selector form,
			#template-selector a.logo,
			#template-selector .template-row,
			#template-selector .order-row {
				display: block;
				margin: 0.75em 0;
			}
			
			#template-selector {
				background: #333;
				color: white;
				text-align: center;
				padding: 0 2rem 1rem 2rem;
				font-family: 'Lato', sans-serif;
				font-weight: 400;
				border: 4px solid #5D5D5D;
				border-width: 0 0 4px 0;
			}
			
			#template-selector a.logo {
				display: inline-block;
				position: relative;
				top: 1.5em;
				margin: 1em 0 2em;
			}
			#template-selector a.logo img {
				max-height: 5em;
			}
			
			#template-selector a.logo p {
				display: none;
				float: left;
				position: absolute;
				width: 16em;
				top: 4.5em;
				padding: 2em;
				left: -8em;
				background: white;
				opacity: 0;
				border: 2px solid #777;
				border-radius: 4px;
				font-size: 0.9em;
				line-height: 1.8;
				transition: all 500ms ease-in-out;
			}
			
			#template-selector a.logo:hover p {
				display: block;
				opacity: 1;
			}
			
			#template-selector a.logo p:after, #template-selector a.logo p:before {
				bottom: 100%;
				left: 50%;
				border: solid transparent;
				content: " ";
				height: 0;
				width: 0;
				position: absolute;
				pointer-events: none;
			}

			#template-selector a.logo p:after {
				border-color: rgba(255, 255, 255, 0);
				border-bottom-color: #ffffff;
				border-width: 8px;
				margin-left: -8px;
			}

			#template-selector a.logo p:before {
				border-color: rgba(119, 119, 119, 0);
				border-bottom-color: #777;
				border-width: 9px;
				margin-left: -9px;
			}

			#template-selector a.logo:hover p {
				display: block;
			}

			#template-selector span {
				font-weight: 900;
				display: inline-block;
				margin: 0 1rem;
			}

			#template-selector select,
			#template-selector input {
				background: #e3e3e3;
				font-family: 'Lato', sans-serif;
				color: #333;
				padding: 0.5rem 1rem;
				border: 0px;
			}

			#template-selector #order,
			#template-selector .choose-order {
				display: none;
			}

			@media screen and (min-width: 1100px) {
				#template-selector .template-row,
				#template-selector .order-row {
						display: inline-block;
				}

				#template-selector form {
					display: inline-block;
					line-height: 3;
				}

				#template-selector a.logo p {
					width: 16em;
					top: 4.5em;
					left: 0.25em;
				}
				
				#template-selector a.logo p:after, #template-selector a.logo p:before {
					left: 10%;
				}
			}
			<?php } ?>
		</style>
	</head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    	<div id="wrapper">
        	<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="90%">
            	<tr>
                	<td align="center" valign="top">
						<div id="template_header_image">
	                		<?php
	                			if ( $img = get_option( 'woocommerce_email_header_image' ) ) {
	                				echo '<p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name' ) . '" /></p>';
	                			}
	                		?>
						</div>
                    	<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container">
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Header -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header">
                                        <tr>
                                            <td>
                                            	<h1><?php echo $email_heading; ?></h1>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Header -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Body -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body">
                                    	<tr>
                                            <td valign="top" id="body_content">
                                                <!-- Content -->
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            <div id="body_content_inner">