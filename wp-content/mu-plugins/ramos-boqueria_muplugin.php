<?php
/*
Plugin Name: Ramos Boqueria
Plugin URI: http://www.720.cat
Description: Personalitzacions d'scripts, estils i markup per www.ramosboqueria.com
Version: 1.0
Author: 720.cat
Author URI: http://www.720.cat
License: GPL2
//
// Shortcode share buttons
<span class="social_share_hook">[hr][/hr][fbshare type="button" width="100" float="left"][twitter style="none" hashtag="#ramosBoqueria" float="left"][google_plusone size="standard" annotation="none" float="left"][divider_flat]</span>
*/
//
//******************************************************************************************************************************
//******************************************************************************************************************************
// FRONTEND ********************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//
//******************************************************************************************************************************
// Scripts & Styles ************************************************************************************************************
//******************************************************************************************************************************
// JS **********************//
function ramos_scripts() {
	// register script template ( $handle, $src, $deps, $ver, $in_footer ); ****************************************************
	// JS **********************//
	// Registre d'script
	wp_register_script('masonry-js', plugin_dir_url( __FILE__ ) . 'ramos_boqueria/masonry.min.js', array('jquery'), false, true);
	// Carrega d'script
	global $wp_query;
	if ( (is_archive() && !is_woocommerce()) || is_home() || (is_search() && 0 !== $wp_query->found_posts && !is_woocommerce())){
		wp_enqueue_script('masonry-js');
	}
}
add_action('wp_enqueue_scripts', 'ramos_scripts', 99);
// CSS *********************//
function ramos_frontend_styles() {
	// register script template ( $handle, $src, $deps, $ver, $in_footer ); ****************************************************
	// CSS *********************//
	// Registre d'estils
	wp_register_style('ramosboqueria-css-min', plugin_dir_url( __FILE__ ) . 'ramos_boqueria/fashionable_ramos.min.css');
	wp_register_style('ramosboqueria-css', plugin_dir_url( __FILE__ ) . 'ramos_boqueria/fashionable_ramos.css');
	// Carrega estils
	wp_enqueue_style('ramosboqueria-css');
}
add_action('wp_enqueue_scripts', 'ramos_frontend_styles', 999);
//******************************************************************************************************************************
// UI ELEMENTS *****************************************************************************************************************
//******************************************************************************************************************************
//
// TEXT DOMAIN *****************************************************************************************************************
//
/*function my_text_strings( $translated_text, $text, $domain ) {
	switch ( $translated_text ) {
		case 'Sale!' :
			$translated_text = __( 'Clearance!', 'woocommerce' );
			break;
		case 'Add to cart' :
			$translated_text = __( 'Add to basket', 'woocommerce' );
			break;
		case 'Related Products' :
			$translated_text = __( 'Check out these related products', 'woocommerce' );
			break;
	}
	return $translated_text;
}
add_filter( 'gettext', 'my_text_strings', 20, 3 );*/
//
// PRODUCT *********************************************************************************************************************
//
// IMAGE ON CATEGORY PAGES *//
/*function woocommerce_category_image() {
    if ( is_product_category() ){
	    global $wp_query;
	    $cat = $wp_query->get_queried_object();
	    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
	    $image = wp_get_attachment_url( $thumbnail_id );
	    if ( $image ) {
		    echo '<img src="' . $image . '" alt="" />';
		}
	}
}
add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );*/
//
// SHAREBUTTONS ************//
// The function
function add_share_after_post() { 
    // Only execute this if we are viewing a single post
    if ( is_single() || is_product()) { 
		global $post;
        $browser_title_encoded = urlencode( trim( wp_title( '', false, 'right' ) ) );
		$page_title_encoded = urlencode( get_the_title() );
		$page_url_encoded = urlencode( get_permalink() );
		$thematic_postfooter_share = 'SHARE';
		if ( has_post_thumbnail() ) {
			$page_img = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		}else{
			$page_img = plugin_dir_url( __FILE__ ) . 'ramos_boqueria/nofeatured_img.gif';
		}
		$sharebutton = '<span class="social_share_hook">';
		$sharebutton .= '<h6>Share: </h6>';
		//$sharebutton .= '<li id="share_php">';
		//Facebook
		$sharebutton .= '<a href="http://www.facebook.com/sharer.php?u=' . $page_url_encoded . '&amp;t=' . $browser_title_encoded . '" target="_blank" title="' . $thematic_postfooter_share . ' ' . get_the_title() . '" class="woo-sc-button silver"><i class="fa fl fa-facebook"></i> Facebook</a> ';
		//Twitter
		$sharebutton .= '<a href="http://twitter.com/share?text=' . $page_title_encoded . '&amp;url=' . $page_url_encoded . '" target="_blank" title="' . $thematic_postfooter_share . ' ' . get_the_title() . ' " class="woo-sc-button silver">Twitter<i class="fa fl fa-twitter"></i></a> ';
		//Google+
		$sharebutton .= '<a href="http://plus.google.com/share?url=' . $page_url_encoded . '" target="_blank" title="' . $thematic_postfooter_share . ' ' . get_the_title() . ' " class="woo-sc-button silver">Google+<i class="fa fl fa-google-plus"></i></a> ';
		//LinkedIn
		//$sharebutton .= '<a href="http://www.linkedin.com/shareArticle?mini=true&url=' . $page_url_encoded . '&title=' . $page_title_encoded . '" target="_blank" title="' . //$thematic_postfooter_share . '  ' . get_the_title() . ' " class="button button-primary button_social_li animated">LinkedIn<i class="fa fl fa-linkedin"></i></a>';
		//Pinterest
		$sharebutton .= '<a href="http://pinterest.com/pin/create/button/?url=' . $page_url_encoded . '&media=' . $page_img . '&description=' . $page_title_encoded . '" target="_blank" title="' . $thematic_postfooter_share . '  ' . get_the_title() . ' " class="woo-sc-button silver">Pinterest<i class="fa fl fa-pinterest"></i></a> ';
		//$sharebutton .= '</li>';
		$sharebutton .= '</span>';
		//echo '<section>';
		echo $sharebutton;
    } 
}
// This will add the function to the woo_post_inside_after hook
add_action( 'woo_post_inside_after', 'add_share_after_post' );
add_action( 'woocommerce_single_product_summary', 'add_share_after_post',55);
/**/
// HOME ************************************************************************************************************************
//
/* HOME LOOP */
/* Categories (3x columnes) */
function woo_display_product_categories3x() {
	if ( is_woocommerce_activated() ): ?>
	<section class="product-categories home-section">
		<div class="col-full">
			<?php
				$product_categories_limit 		= apply_filters( 'woo_template_product_categories_limit', $limit = 9 );
				$product_categories_columns 	= apply_filters( 'woo_template_product_categories_columns', $columns = 3 );
				echo do_shortcode( '[product_categories number="' . $product_categories_limit . '" columns="' . $product_categories_columns . '" parent="0"]' );
			?>
		</div>
	</section>
	<?php endif;
}
add_action( 'homepage', 'woo_display_product_categories3x', 23 );
/* Categories (4x columnes) */
function woo_display_product_categories4x() {
	if ( is_woocommerce_activated() ): ?>
	<section class="product-categories product-categories4 home-section">
		<div class="col-full">
			<?php
				$product_categories_limit 		= apply_filters( 'woo_template_product_categories_limit', $limit = 8 );
				$product_categories_columns 	= apply_filters( 'woo_template_product_categories_columns', $columns = 4 );
				echo do_shortcode( '[product_categories number="' . $product_categories_limit . '" columns="' . $product_categories_columns . '" parent="0"]' );
			?>
		</div>
	</section>
	<?php endif;
}
add_action( 'homepage', 'woo_display_product_categories4x', 21 );
/* Categories (5x columnes) */
function woo_display_product_categories5x() {
	if ( is_woocommerce_activated() ): ?>
	<section class="product-categories product-categories5 home-section">
		<div class="col-full">
			<?php
				$product_categories_limit 		= apply_filters( 'woo_template_product_categories_limit', $limit = 10 );
				$product_categories_columns 	= apply_filters( 'woo_template_product_categories_columns', $columns = 5 );
				echo do_shortcode( '[product_categories number="' . $product_categories_limit . '" columns="' . $product_categories_columns . '" parent="0"]' );
			?>
		</div>
	</section>
	<?php endif;
}
add_action( 'homepage', 'woo_display_product_categories5x', 22 );
/* Top Selled Products */
function woo_display_popular_products8() {
	if ( is_woocommerce_activated() ): ?>
	<section class="popular-products home-section">
		<div class="col-full">
			<?php
				$popular_products_limit 		= apply_filters( 'woo_template_best_selling_products_limit', $limit = 8 );
				$popular_products_columns 	= apply_filters( 'woo_template_best_selling_products_columns', $columns = 4 );
				echo do_shortcode( '[best_selling_products per_page="' . $popular_products_limit . '" columns="' . $popular_products_columns . '"]' );
			?>
		</div>
	</section>
	<?php endif;
}
add_action( 'homepage', 'woo_display_popular_products8', 43 );
/* Top Rated Products */
function woo_display_liked_products4() {
	if ( is_woocommerce_activated() ): ?>
	<section class="liked-products home-section">
		<div class="col-full">
			<?php
				$liked_products_limit 		= apply_filters( 'woo_template_top_rated_products_limit', $limit = 4 );
				$liked_products_columns 	= apply_filters( 'woo_template_top_rated_products_columns', $columns = 4 );
				echo do_shortcode( '[top_rated_products per_page="' . $liked_products_limit . '" columns="' . $liked_products_columns . '"  orderby="menu_order"]' );
			?>
		</div>
	</section>
	<?php endif;
}
add_action( 'homepage', 'woo_display_liked_products4', 42 );
/* Newest Products */
function woo_display_newest_products4() {
	if ( is_woocommerce_activated() ): ?>
	<section class="recent-products home-section">
		<div class="col-full">
			<?php
				$recent_products_limit 		= apply_filters( 'woo_template_recent_products_limit', $limit = 4 );
				$recent_products_columns 	= apply_filters( 'woo_template_recent_products_columns', $columns = 4 );
				echo do_shortcode( '[recent_products per_page="' . $recent_products_limit . '" columns="' . $recent_products_columns . '"]' );
			?>
		</div>
	</section>
	<?php endif;
}
add_action( 'homepage', 'woo_display_newest_products4', 41 );
/* Custom Order Products */
function woo_display_allcustom_products() {
	if ( is_woocommerce_activated() ): ?>
	<section class="custom-products home-section">
		<div class="col-full">
			<?php
				$custom_products_columns 	= apply_filters( 'woo_template_products_columns', $columns = 4 );
				echo do_shortcode( '[products columns="' . $custom_products_columns . '" orderby="menu_order"]' );
			?>
		</div>
	</section>
	<?php endif;
}
add_action( 'homepage', 'woo_display_allcustom_products', 44 );
//
// 404 PAGE ********************************************************************************************************************
//
function extra_content_404( $content ) { 
    if (is_404()) {
		$search_404 = '<div class="col2-set" id="double_search"><div class="col-1">';
		$search_404 .= '<header><br/><br/><h2>' . __( 'Search products', 'woocommerce' ) . '</h2></header>';
		$search_404 .= '<div class="woo-sc-box normal full">';
		$search_404 .= '<form role="search" method="get" id="searchform" action="' . get_bloginfo('url') . '"><div><label class="screen-reader-text" for="s">Search for:</label><input type="text" value="" name="s" id="s" placeholder=""><button type="submit" id="searchsubmit" class="fa fa-search submit" name="submit" value="Search"></button><input type="hidden" name="post_type" value="product"></div></form>';
		$search_404 .= '</div>';
		$search_404 .= '</div><div class="col-2">';
		$search_404 .= '<header><br/><br/><h2>' . __( 'Search', 'woocommerce' ) . ' ' . __( 'Website', 'woocommerce' ) . '</h2></header>';
		$search_404 .= '<div class="woo-sc-box normal full">';
		$search_404 .= '<form method="get" class="searchform" action="' . get_bloginfo('url') . '"><div><input type="text" class="field s" name="s" value=""><button type="submit" class="fa fa-search submit" name="submit" value="Search"></button></div></form>';
		$search_404 .= '</div>';
		$search_404 .= '</div></div>';
		if ( is_woocommerce_activated() ):
			$content_404 = '<header><br/><br/><h2>' . __( 'Continue Shopping', 'woocommerce' ) . '</h2></header>';
			// MOSTREM CATEGORIES
			$content_404 .= '<section class="product-categories product-categories4 404-section">';
			$content_404 .= '<div class="col-full">';
				$product_categories_limit 		= apply_filters( 'woo_template_product_categories_limit', $limit = 4 );
				$product_categories_columns 	= apply_filters( 'woo_template_product_categories_columns', $columns = 4 );
			$content_404 .= do_shortcode( '[product_categories number="' . $product_categories_limit . '" columns="' . $product_categories_columns . '" parent="0"]' );		
			$content_404 .= '</div>';
			$content_404 .= '</section>';
			// MOSTREM PRODUCTES
			$content_404 .= '<section class="recent-products 404-section">';
			$content_404 .= '<div class="col-full">';
				$popular_products_limit 		= apply_filters( 'woo_template_best_selling_products_limit', $limit = 8 );
				$popular_products_columns 	= apply_filters( 'woo_template_best_selling_products_columns', $columns = 4 );
			$content_404 .= do_shortcode( '[best_selling_products per_page="' . $popular_products_limit . '" columns="' . $popular_products_columns . '"]' );
			$content_404 .= '</div>';
			$content_404 .= '</section>';
		$content = $content . $search_404 . $content_404;
		else:
		//$content = $content . $search404;
		$content = $content . $search_404;
		endif;
	}
    return $content;
}
add_filter( 'woo_404_content', 'extra_content_404' ); 
//
// EMPTY SEARCH NO RESULTS *****************************************************************************************************
//
function extra_search_empty( $content ) { 
    if (is_search()) {
		$search_title = '<h1 class="title entry-title">' . sprintf( __( 'Search results for &quot;%s&quot;', 'woothemes' ), get_search_query() ) . '</h1>';
		$search_content_open = '<p class="woocommerce-info">';
		$search_content_close = '</p>';
		$search_searchers = '<div class="col2-set" id="double_search"><div class="col-1">';
		$search_searchers .= '<header><br/><h2>' . __( 'Search products', 'woocommerce' ) . '</h2></header>';
		$search_searchers .= '<div class="woo-sc-box normal full">';
		$search_searchers .= '<form role="search" method="get" id="searchform" action="' . get_bloginfo('url') . '"><div><label class="screen-reader-text" for="s">Search for:</label><input type="text" value="" name="s" id="s" placeholder=""><button type="submit" id="searchsubmit" class="fa fa-search submit" name="submit" value="Search"></button><input type="hidden" name="post_type" value="product"></div></form>';
		$search_searchers .= '</div>';
		$search_searchers .= '</div><div class="col-2">';
		$search_searchers .= '<header><br/><h2>' . __( 'Search', 'woocommerce' ) . ' ' . __( 'Website', 'woocommerce' ) . '</h2></header>';
		$search_searchers .= '<div class="woo-sc-box normal full">';
		$search_searchers .= '<form role="search" method="get" class="searchform" action="' . get_bloginfo('url') . '"><div><label class="screen-reader-text" for="s">Search for:</label><input type="text" class="field s" name="s" value=""><button type="submit" id="searchsubmit2" class="fa fa-search submit" name="submit" value="Search"></button></div></form>';
		$search_searchers .= '</div>';
		$search_searchers .= '</div></div>';
		if ( is_woocommerce_activated() ):
			$content_extra_search = '<header><br/><br/><h2>' . __( 'Continue Shopping', 'woocommerce' ) . '</h2></header>';
			// MOSTREM CATEGORIES
			$content_extra_search .= '<section class="product-categories product-categories4 404-section">';
			$content_extra_search .= '<div class="col-full">';
				$product_categories_limit 		= apply_filters( 'woo_template_product_categories_limit', $limit = 4 );
				$product_categories_columns 	= apply_filters( 'woo_template_product_categories_columns', $columns = 4 );
			$content_extra_search .= do_shortcode( '[product_categories number="' . $product_categories_limit . '" columns="' . $product_categories_columns . '" parent="0"]' );		
			$content_extra_search .= '</div>';
			$content_extra_search .= '</section>';
			// MOSTREM PRODUCTES
			$content_extra_search .= '<section class="recent-products 404-section">';
			$content_extra_search .= '<div class="col-full">';
				$popular_products_limit 		= apply_filters( 'woo_template_best_selling_products_limit', $limit = 8 );
				$popular_products_columns 	= apply_filters( 'woo_template_best_selling_products_columns', $columns = 4 );
			$content_extra_search .= do_shortcode( '[best_selling_products per_page="' . $popular_products_limit . '" columns="' . $popular_products_columns . '"]' );
			$content_extra_search .= '</div>';
			$content_extra_search .= '</section>';
		$content = $search_title . $search_content_open . $content . $search_content_close . $search_searchers . $content_extra_search;
		else:
		$content = $search_title . $search_content_open . $content . $search_content_close . $search_searchers;
		endif;
	}
    return $content;
}
add_filter( 'woo_noposts_message', 'extra_search_empty' ); 
//add_filter( 'woo_noproducts_message', 'extra_search_empty' ); -> NO FUNCIONA NO TÉ HOOK. cAL BUSCAR PLA B // do_action( 'woocommerce_after_shop_loop' );
function betterempty_searchresults() {
    if (is_search()) {
        global $wp_query;
        if ($wp_query->post_count === 0 && is_woocommerce()) {
			$search_searchers = '<div class="col2-set" id="double_search"><div class="col-1">';
			$search_searchers .= '<header><br/><h2>' . __( 'Search products', 'woocommerce' ) . '</h2></header>';
			$search_searchers .= '<div class="woo-sc-box normal full">';
			$search_searchers .= '<form role="search" method="get" id="searchform" action="' . get_bloginfo('url') . '"><div><label class="screen-reader-text" for="s">Search for:</label><input type="text" value="" name="s" id="s" placeholder=""><button type="submit" id="searchsubmit" class="fa fa-search submit" name="submit" value="Search"></button><input type="hidden" name="post_type" value="product"></div></form>';
			$search_searchers .= '</div>';
			$search_searchers .= '</div><div class="col-2">';
			$search_searchers .= '<header><br/><h2>' . __( 'Search', 'woocommerce' ) . ' ' . __( 'Website', 'woocommerce' ) . '</h2></header>';
			$search_searchers .= '<div class="woo-sc-box normal full">';
			$search_searchers .= '<form role="search" method="get" class="searchform" action="' . get_bloginfo('url') . '"><div><label class="screen-reader-text" for="s">Search for:</label><input type="text" class="field s" name="s" value=""><button type="submit" id="searchsubmit2" class="fa fa-search submit" name="submit" value="Search"></button></div></form>';
			$search_searchers .= '</div>';
			$search_searchers .= '</div></div>';
			$content_extra_search = '<header><br/><br/><h2>' . __( 'Continue Shopping', 'woocommerce' ) . '</h2></header>';
			// MOSTREM CATEGORIES
			$content_extra_search .= '<section class="product-categories product-categories4 404-section">';
			$content_extra_search .= '<div class="col-full">';
				$product_categories_limit 		= apply_filters( 'woo_template_product_categories_limit', $limit = 4 );
				$product_categories_columns 	= apply_filters( 'woo_template_product_categories_columns', $columns = 4 );
			$content_extra_search .= do_shortcode( '[product_categories number="' . $product_categories_limit . '" columns="' . $product_categories_columns . '" parent="0"]' );		
			$content_extra_search .= '</div>';
			$content_extra_search .= '</section>';
			// MOSTREM PRODUCTES
			$content_extra_search .= '<section class="recent-products 404-section">';
			$content_extra_search .= '<div class="col-full">';
				$popular_products_limit 		= apply_filters( 'woo_template_best_selling_products_limit', $limit = 8 );
				$popular_products_columns 	= apply_filters( 'woo_template_best_selling_products_columns', $columns = 4 );
			$content_extra_search .= do_shortcode( '[best_selling_products per_page="' . $popular_products_limit . '" columns="' . $popular_products_columns . '"]' );
			$content_extra_search .= '</div>';
			$content_extra_search .= '</section>';
			$content = $search_searchers . $content_extra_search;
			echo $content;
        }
    }
}
add_action( 'woocommerce_after_main_content', 'betterempty_searchresults' );
//
// SINGLE SEARCH RESULTS ( = 1) -> REDIRECCIONA DIRECTAMENT AL POST *******************************************************************
//
function redirect_single_post() {
    if (is_search()) {
        global $wp_query;
        if ($wp_query->post_count == 1 && $wp_query->max_num_pages == 1) {
            wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
            exit;
        }
    }
}
add_action('template_redirect', 'redirect_single_post');
//
// WOOCOMMERCE ******************************************************************************************************************************
//
// TEMPLATES **********************************************************************************************
// EMAILS TRANSACCIONALS ******************************
// Indica que es busqui primer al directori del plugin 
function myplugin_woocommerce_locate_template( $template, $template_name, $template_path ) {
  global $woocommerce;
  $_template = $template;
  if ( ! $template_path ) $template_path = $woocommerce->template_url;
  $plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/ramos_boqueria/woocommerce/'; 
  // Look within passed path within the theme - this is priority [ your theme / template path / template name ] & [ your theme / template name ]
  $template = locate_template(
    array( $template_path . $template_name , $template_name )
  );
 // Modification: Get the template from this plugin, if it exists [ your plugin / woocommerce / template name ]
  if ( ! $template && file_exists( $plugin_path . $template_name ) )
    $template = $plugin_path . $template_name;
  // Use default template [ default path / template name ]
  if ( ! $template )
    $template = $_template;
  // Return what we found
  return $template;
}
add_filter( 'woocommerce_locate_template', 'myplugin_woocommerce_locate_template', 10, 3 );
//
// EMAILS TRANSACCIONALS DE WOOCOMMERCE *********************
// ESTILS CSS
//
// Hack to Preview all email templates // No funciona amb templates dins del plugin
/**
* Preview WooCommerce Emails.
* @author WordImpress.com
* @url https://github.com/WordImpress/woocommerce-preview-emails
* If you are using a child-theme, then use get_stylesheet_directory() instead
*/
//$preview = plugin_dir_path( __FILE__ ) . '/ramos_boqueria/woocommerce/emails/woo-preview-emails.php';
//if(file_exists($preview)) {require $preview;}
/**/
//
// Hard Coded CSS (Update friendly!) NO COMPATIBLE AMB CUSTOM TEMPLATES
/*function add_css_to_email() {
echo '
<style type="text/css">
#wrapper {}
#template_header_image{background:#000; max-width: 600px;}
#template_header_image p{margin:0;}
#template_header_image p img{display:block; width:100%; height:auto;}
#template_container {
    box-shadow: none !important;
    border-radius: 0 !important;
	border:1px solid #999;
	border-top-color:#897846!important;
	max-width: 600px;
	width:100%;
}
#template_header {
    border-radius: 0!important;
    font-weight: bold;
    font-family: serif;
	padding:24px 12px!important;
	max-width: 600px;
	width:100%;
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
    -webkit-border-radius: 6px;
}
#template_footer #credit {
    border:0;
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
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 14px;
    line-height: 150%;
}
h1 {
    display: block;
    font-family: serif;
    font-size: 30px;
    font-weight: 300;
    line-height: 150%;
    margin: 0;
    padding: 24px 36px;
}
h2 {
    display: block;
    font-family: serif;
    font-size: 18px;
    font-weight: bold;
    line-height: 130%;
    margin: 16px 0 8px;
}

h3 {
    display: block;
    font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
    font-size: 16px;
    font-weight: bold;
    line-height: 130%;
    margin: 16px 0 8px;
}
a {
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
</style>
';
}
add_action('woocommerce_email_header', 'add_css_to_email');*/
/**/
// SHIPPING  **********************************************************************************************
// NOU MÈTODE D'ENVIAMENT ******************************
/*function add_spanish_flat_rate( $method, $rate ) {
	$new_rate          = $rate;
	$new_rate['id']    .= ':' . 'spanish_flat_rate'; // Append a custom ID
	$new_rate['label'] = 'Spanish Shipping'; // Rename to 'Rushed Shipping'
	$new_rate['cost']  += 2; // Add $2 to the cost
	// Add it to WC
	$method->add_rate( $new_rate );
}
add_action( 'woocommerce_flat_rate_shipping_add_rate', 'add_spanish_flat_rate', 10);*/
/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	function your_shipping_method_init() {
		if ( ! class_exists( 'WC_Spanish_Delivery' ) ) {
			class WC_Spanish_Delivery extends WC_Shipping_Flat_Rate {
			/**
			 * Constructor
			 */
			public function __construct() {
				$this->id                 = 'spanish_delivery';
				$this->method_title       = __( 'Spain Flat Rate', 'woocommerce' );
				$this->method_description = __( 'Spain Flat Rate Shipping lets you charge a fixed rate for shipping.', 'woocommerce' );
				$this->init();
				add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
			}
			/**
			 * Initialise settings form fields
			 */
			public function init_form_fields() {
				parent::init_form_fields();
				$this->form_fields['availability'] = array(
					'title'			=> __( 'Availability', 'woocommerce' ),
					'type'			=> 'select',
					'class'         => 'wc-enhanced-select',
					'description'	=> '',
					'default'		=> 'including',
					'options'		=> array(
						'including' => __( 'Selected countries', 'woocommerce' ),
						'excluding' => __( 'Excluding selected countries', 'woocommerce' ),
					)
				);
			}
			/**
			 * is_available function.
			 *
			 * @param array $package
			 * @return bool
			 */
			public function is_available( $package ) {
				if ( "no" === $this->enabled ) {
					return false;
				}
				if ( 'including' === $this->availability ) {
					if ( is_array( $this->countries ) && ! in_array( $package['destination']['country'], $this->countries ) ) {
						return false;
					}
				} else {
					if ( is_array( $this->countries ) && ( in_array( $package['destination']['country'], $this->countries ) || ! $package['destination']['country'] ) ) {
						return false;
					}
				}
				return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', true, $package );
			}
		}
		}
	}
	add_action( 'woocommerce_shipping_init', 'your_shipping_method_init' );

	function add_spanish_shipping_method( $methods ) {
		$methods[] = 'WC_Spanish_Delivery';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'add_spanish_shipping_method' );
}
/**/
/**/
//******************************************************************************************************************************
//******************************************************************************************************************************
// BACKEND *********************************************************************************************************************
//******************************************************************************************************************************
//******************************************************************************************************************************
//
//******************************************************************************************************************************
// LOGIN  **********************************************************************************************************************
//******************************************************************************************************************************
// Personalizar pagina de Login
function custom_login() {
    //Añade una hoja de estilos CSS y condicionales para IE
    echo '<link rel="stylesheet" type="text/css" href="' . plugin_dir_url( __FILE__ ) . 'ramos_boqueria/custom-admin.min.css" />';
}
add_action('login_head', 'custom_login');
//
//******************************************************************************************************************************
// ADMIN  **********************************************************************************************************************
//******************************************************************************************************************************
//
/* <HEAD> ADMIN ****************************************************************************************************************/
//
// Favicon a l'Admin
//
function admin_favicon() {
	echo '<!-- IE 9 and before ICON ><!-->' . "\n";
	echo '<!--[if (lt IE 9)|(IE 9)]>' . "\n";
	echo '<link rel="shortcut icon"  href="' . plugin_dir_url( __FILE__ ) . 'ramos_boqueria/favicon.ico" /> <![endif]-->' . "\n";
	echo '<!-- Default ShortCut Icon ><!-->' . "\n";
	echo '<link rel="shortcut icon"  href="' . plugin_dir_url( __FILE__ ) . 'ramos_boqueria/favicon.png" />' . "\n";
	echo '<!-- Apple Touch Icon ><!-->' . "\n";
	echo '<link rel="apple-touch-icon"  sizes="57x57" href="' . plugin_dir_url( __FILE__ ) . 'ramos_boqueria/tile114.png"/>' . "\n";
	echo '<link rel="apple-touch-icon"  sizes="114x114" href="' . plugin_dir_url( __FILE__ ) . 'ramos_boqueria/tile114.png"/>' . "\n";
	echo '<link rel="apple-touch-icon"  sizes="72x72" href="' . plugin_dir_url( __FILE__ ) . 'ramos_boqueria/tile144.png"/>' . "\n";
	echo '<link rel="apple-touch-icon"  sizes="144x144" href="' . plugin_dir_url( __FILE__ ) . 'ramos_boqueria/tile144.png"/>' . "\n";
	echo '<!-- ie10 metaTags For Tile ><!-->' . "\n";
	echo '<meta name="msapplication-TileColor" content="#000000" />' . "\n";
	echo '<meta name="msapplication-TileImage" content="' . plugin_dir_url( __FILE__ ) . 'ramos_boqueria/tile144.png"/>' . "\n";
}
add_action('admin_head', 'admin_favicon');
//
// Carreguem els scripts al <head> i al <footer>
//
function admin_custom_styles($hook) {
	// Registre d'estils
	wp_register_style('customadmin-css', plugin_dir_url( __FILE__ ) . 'ramos_boqueria/custom-admin.min.css', array('woocommerce_admin_menu_styles'), false);
	//Carrega estils
	wp_enqueue_style('customadmin-css');
}
add_action('admin_enqueue_scripts', 'admin_custom_styles',9);
//
/* FOOTER ADMIN **************************************************************************************************************************************/
//
// Inclou el Logo de 720º enlloc del de WordPress 
//
function my_footer_version() {
	return '<span id="footer_dash_brand"><b>RAMOS Boqueria 1939</b></span>';
}
add_filter( 'update_footer', 'my_footer_version', 11 );
//
/* Final HEAD & FOOTER ******************************************************************/
//
/* WP BAR *****************************************************************************/
//Modificacio de la Barra superior Flotant
//
// Es personalitza el LOGO mitjançant css
//
//
/* DASHBOARD *****************************************************************************/
//
//
// Elimina Panells del Dashboard
//
/*function remove_dashboard_meta() {
	remove_action('welcome_panel', 'wp_welcome_panel');
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
}
add_action( 'admin_init', 'remove_dashboard_meta' );*/
//
/* WIDGETS D'ESCRIPTORI (ACCESSOS DIRECTES) *****************************
*************************************************************************/
//
// La resta de Dashboard Widgets d'acces directe estan al seu corresponent mu-plugin
//
/* Escriptori > Perfil > Crear Widget ************/ //Anulat
/*function reg_ini_profile() {
	if (function_exists('qtrans_getLanguage')){
		if( qtrans_getLanguage() == 'es' ){
			$title = 'Perfil';
		}elseif( qtrans_getLanguage() == 'ca' ){
			$title = 'Perfil';
		}else{ //english 
			$title = 'Profile';
		}
	}else{//Not activeQtrans
		$title = 'Perfil';
	}
	wp_add_dashboard_widget('reg_ini_profile', $title, 'wdgt_ini_profile');
}
add_action('wp_dashboard_setup', 'reg_ini_profile');
/* Escriptori > Perfil > Editar Widget ************/ //Anulat
/*function wdgt_ini_profile() {
	if (function_exists('qtrans_getLanguage')){
		if( qtrans_getLanguage() == 'es' ){
			$tag = "Edita tu Perfil de Usuario";
		}elseif( qtrans_getLanguage() == 'ca' ){
			$tag = "Edita el teu Perfil d'Usuari";
		}else{ //english 
			$tag = "Edit your User Profile";
		}
	}else{//Not activeQtrans
		$tag = "Edita tu Perfil de Usuario";
	}
	echo '<a class="wdgt_dash wdgt_profile" href="' . admin_url('profile.php') . '"><b>' . $tag . '</b></a>';
}
//
//
// COLUMNES DE LES PÀGINES D'EDICIO ***********************************************
//*********************************************************************************
//
/* COLUMNA MINIATURA IMATGE DESTACADA (a tots els post types & pages)*************/
// Add the column
function tcb_add_post_thumbnail_column($cols){
  $cols['tcb_post_thumb'] = __('Imagen');
  return $cols;
}
// Grab featured-thumbnail size post thumbnail and display it.
function tcb_display_post_thumbnail_column($col, $id){
  switch($col){
    case 'tcb_post_thumb':
      if( function_exists('the_post_thumbnail') )
	    if(has_post_thumbnail()){
          echo the_post_thumbnail( array(50,50) );
      	}else{
		  echo '<img src="' . plugin_dir_url( __FILE__ ) . 'ramos_boqueria/nofeatured_img.gif" />';
		}
	  else
        echo 'xxx';
      break;
  }
}
// Add the posts and pages columns filter. They can both use the same function.
add_filter('manage_posts_columns', 'tcb_add_post_thumbnail_column', 5);
// Hook into the posts an pages column managing. Sharing function callback again.
add_action('manage_posts_custom_column', 'tcb_display_post_thumbnail_column', 5, 2);
//
/* POST TYPE POSTS *************************************************************/
//
// Amaguem la data, els comentaris i l'autor
function post_edit_columns($columns){
	unset($columns['author']);
	unset($columns['date']);
	return $columns;
}
add_filter("manage_post_posts_columns", "post_edit_columns");
//
//
//
//
/* PAGINES *************************************************************/
// Thumbnail a les pagines 
add_filter('manage_pages_columns', 'tcb_add_post_thumbnail_column', 5);
add_action('manage_pages_custom_column', 'tcb_display_post_thumbnail_column', 5, 2);
// Amaguem Data, Autor i Comentaris
function pages_edit_columns($columns){
	unset($columns['comments']);
	unset($columns['author']);
	unset($columns['date']);
	return $columns;
}
add_filter("manage_edit-page_columns", "pages_edit_columns");
//
//
// METABOXES D'EDICIO *****************************************************************
//*************************************************************************************
//
// AMAGA BOX D'EDICIO A LES PAGINES D'EDICIO DE POSTS *********************************
//
/*function my_remove_meta_boxes() {
//Post
	//Els metabox dels posts es configuren al plugin custom-admin-postypes
//Link
  remove_meta_box('linktargetdiv', 'link', 'normal');
  remove_meta_box('linkxfndiv', 'link', 'normal');
  remove_meta_box('linkadvanceddiv', 'link', 'normal');
}
add_action( 'admin_menu', 'my_remove_meta_boxes' );*/
//* ***********************************************************************************************************************************
//
// CONFIGURACIÓ D'USUARIS
//
//*************************************************************************************************************************************
//
/* CONFINGURACIONS GENERALS ***********************************************************************************************************/
//
// Permet a múltiples usuaris compartir direcció d'email 
// Amb direccio GMAil pots afegir un + al final del mail i el que vulguis, els missatges arribaran.
// Ex: ola@gmail.com == ola+kease@gmail.com
//
/* PERFIL USUARI *************************************************************************/
// Camps d'informació par als usuaris
function user_fields_extra( $contactmethods ) {
    //Para quitar los que no queremos
    unset($contactmethods['aim']);  
    unset($contactmethods['jabber']);  
    unset($contactmethods['yim']); 
    return $contactmethods;
}
add_filter('user_contactmethods','user_fields_extra',10,1);
//
// MOSTRAR ELS CAMPS DE CONTACTE D'EMAIL PER EMPRESA I FORMULARIS *****************************************************************
//
function my_show_extra_profile_fields( $user ) {  
	$editor_id = 4; // ID de l'Usuari - Client
	if ($user->ID == $editor_id || current_user_can( 'edit_user', $user->ID )) { //La ID de l'usuari Editor (client) ?>
        <h3>eMails de Empresa</h3>
        <table class="form-table">
            <tr>
                <th><label for="company_email">eMail de Contacte</label></th>
                <td>
                    <input type="text" name="company_email" id="company_email" value="<?php echo esc_attr( get_the_author_meta( 'company_email', $user->ID ) ); ?>" /><br />
                    <span class="description">La direcció de correu electrònic per a comunicacions legals.</span>
                </td>
            </tr>
			<tr>
                <th><label for="company_phone">Telèfon de Contacte</label></th>
                <td>
                    <input type="text" name="company_phone" id="company_phone" value="<?php echo esc_attr( get_the_author_meta( 'company_phone', $user->ID ) ); ?>" /><br />
                    <span class="description">Número de telèfon amb prefix Internacional.</span>
                </td>
            </tr>
			<tr>
                <th><label for="company_adress1">Direcció Postal de l'Empresa 1</label></th>
                <td>
                    <input type="text" name="company_adress1" id="company_adress1" value="<?php echo esc_attr( get_the_author_meta( 'company_adress1', $user->ID ) ); ?>" /><br />
                    <span class="description">Indicar Carrer, número i pis.</span>
                </td>
            </tr>
			<tr>
                <th><label for="company_adress2">Direcció Postal de l'Empresa 2</label></th>
                <td>
                    <input type="text" name="company_adress2" id="company_adress2" value="<?php echo esc_attr( get_the_author_meta( 'company_adress2', $user->ID ) ); ?>" /><br />
                    <span class="description">Indicar Codi Postal, Població, Província i País.</span>
                </td>
            </tr>
			<tr>
                <th><label for="company_cif">CIF / NIF</label></th>
                <td>
                    <input type="text" name="company_cif" id="company_cif" value="<?php echo esc_attr( get_the_author_meta( 'company_cif', $user->ID ) ); ?>" /><br />
                    <span class="description">Indicar número i Lletra</span>
                </td>
            </tr>
        </table>
<?php }
}
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );
//
// GUARDAR A LA BBDD ELS CAMPS DE CONTACTE D'EMAIL PER EMPRESA I FORMULARIS *****************************************************************
// $editor_id -> Variable amb la ID d'usuari del CLIENT
//
function my_save_extra_profile_fields( $user_id ) {
	$editor_id = 4; // ID de l'Usuari - Client
	if ($user_id == $editor_id || current_user_can( 'edit_user', $user->ID )) {
		//eMail de Contacto
		update_user_meta( $user_id, 'company_email', $_POST['company_email'] );
		//Telefono de Contacto
		update_user_meta( $user_id, 'company_phone', $_POST['company_phone'] );
		//Telefono de Contacto
		update_user_meta( $user_id, 'company_adress1', $_POST['company_adress1'] );
		//Telefono de Contacto
		update_user_meta( $user_id, 'company_adress2', $_POST['company_adress2'] );
		//Telefono de Contacto
		update_user_meta( $user_id, 'company_cif', $_POST['company_cif'] );
	}
}
add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );
//
?>