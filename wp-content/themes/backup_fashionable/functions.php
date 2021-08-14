<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Theme Actions
 *
 * This is where theme functions are hooked into the appropriate hooks / filters.
 *
 * @since 	1.0.0
 * @author 	WooThemes
 */

// Load language file
add_action( 'after_setup_theme', 'woo_child_theme_textdomain' );

// Enqueue Styles
add_action( 'wp_enqueue_scripts', 'woo_child_enqueue', 30 );
add_action( 'wp_enqueue_scripts', 'woo_google_fonts' );

// Move things around
add_action( 'init', 'woo_layout_adjust', 10 );
add_action( 'wp', 'woo_homepage_template_layout' );
add_action( 'woo_main_before', 'woo_move_things_around', 10 );

// Homepage
add_action( 'homepage', 'woo_display_hero', 10 );
add_action( 'homepage', 'woo_display_product_categories', 20 );
add_action( 'homepage', 'woo_display_featured_products', 30 );
add_action( 'homepage', 'woo_display_recent_products', 40 );

// Top Navigation
add_action( 'woo_top_menu_after', 'woo_display_phone_number', 15 );

// Fixed Header
add_action( 'wp', 'woo_fixed_header', 10 );

// Output Custom Fonts
add_action( 'wp_head', 'woo_custom_fonts_output', 10 );

// Setting overrides
add_filter( 'option_woo_options', 'woo_custom_theme_overrides' );

/**
 * Setup My Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 *
 * @since  	1.0.3
 * @return 	void
 * @author 	WooThemes
 */
function woo_child_theme_textdomain() {
	load_child_theme_textdomain( 'fashionable',  get_stylesheet_directory() . '/lang' );
}

/**
 * Child Theme Enqueues
 *
 * Enqueues Custom Fonts and Stylesheet files.
 *
 * @since  	1.0.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_child_enqueue() {
	// Load Theme Stylesheet
	wp_enqueue_style( 'fashionable', get_stylesheet_directory_uri() . '/css/fashionable.css' );
}

/**
 * Add Vollkorn and PT Sans Google Fonts
 * @return string stylesheet
 * @since  1.0.0
 */
function woo_google_fonts() {
	wp_enqueue_style( 'Vollkorn', '//fonts.googleapis.com/css?family=Vollkorn:400italic,700italic,400,700' );
	wp_enqueue_style( 'PT Sans', '//fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic' );
}

/**
 * Adjust layout
 *
 * Moves elements from their default location
 *
 * @since  	1.0.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_layout_adjust() {
	if ( is_woocommerce_activated() ) {
		remove_action( 'woo_nav_inside', 'woo_add_nav_cart_link', 20);
		add_action( 'woo_top_menu_after', 'woo_add_nav_cart_link', 10);
	}

	// Subscribe links in navigation
	remove_action( 'woo_nav_inside','woo_nav_subscribe', 25 );
	// Search in navigation
	remove_action( 'woo_nav_inside','woo_nav_search', 25 );
	// Side Navigation wrappers
	remove_action( 'woo_nav_inside', 'woo_nav_sidenav_start', 15 );
	remove_action( 'woo_nav_inside', 'woo_nav_sidenav_end', 30 );
}

/**
 * Move things around
 *
 * Moves elements from their default location
 *
 * @since  	1.0.2
 * @return 	void
 * @author 	WooThemes
 */
function woo_move_things_around() {
	// Remove Sidebar from Homepage template
	if ( is_page_template( 'template-homepage.php' ) ) {
		remove_action( 'woo_main_after', 'woocommerce_get_sidebar', 10 );
	}
}

/**
 * Top Navigation
 *
 * Overrides default top menu and adds a new action
 *
 * @since  	1.0.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_top_navigation() {
	if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'top-menu' ) ) {
?>
	<div id="top">
		<div class="col-full">
			<?php
				echo '<h3 class="top-menu">' . woo_get_menu_name( 'top-menu' ) . '</h3>';
				wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'top-nav', 'menu_class' => 'nav top-navigation fl', 'theme_location' => 'top-menu' ) );
			?>

			<?php do_action( 'woo_top_menu_after' ); ?>
		</div>
	</div><!-- /#top -->
<?php
	}
} // End woo_top_navigation()

/**
 * Phone Number
 *
 * Displays phone number in Top Navigation
 *
 * @since  	1.0.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_display_phone_number() {
 	$settings = array(
					'top_phone_number'	=> ''
				);

	$settings = woo_get_dynamic_values( $settings );

	if ( isset( $settings['top_phone_number'] ) && '' != $settings['top_phone_number'] ) {
		echo '<ul class="phone-number"><li><i class="fa fa-phone"></i>' . esc_attr( stripslashes( $settings['top_phone_number'] ) ) . '</li></ul>';
	}
}


/**
 * WooCommerce Product Category
 *
 * Adds category description and image to Product Category shortcode.
 *
 * @since  	1.0.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_home_after_subcategory( $category ) {
	// Output category description
	if ( '' != $category->description ) {
		echo wpautop( esc_attr( $category->description ) );
	}
	// Output category link button
	if ( get_term_link( $category ) ) {
		echo '<a class="button" href="' . esc_url( get_term_link( $category ) ) . '">' . __( 'Browse', 'fashionable' ) . '</a>';
	}
}

/**
 * WooCommerce Featured Products
 *
 * Adds description to output of Featured Products.
 *
 * @since  	1.0.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_home_featured_description() {
	global $product;
	// Product featured?
	$featured = get_post_meta( $product->id, '_featured', TRUE );
	if ( isset( $featured ) && 'yes' == $featured ) {
		the_excerpt();
	}
}

/**
 * Homepage template layout
 *
 * Removes / adds some WooCommerce elements on the homepage template
 *
 * @since  	1.0.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_homepage_template_layout() {
	if ( is_page_template( 'template-homepage.php' ) ) {
		if ( is_woocommerce_activated() ) {
			add_filter( 'woocommerce_subcategory_count_html', '__return_false' );
			add_action( 'woocommerce_after_subcategory_title', 'woo_home_after_subcategory' );
		}
	}
}

/**
 * Theme Overrides
 *
 * Updates Theme Options dynamically to match the styling of the Child Theme.
 *
 * @since  	1.0.0
 * @return 	array
 * @author 	WooThemes
 */

function woo_custom_theme_overrides( $options ) {
	$headings 	= 'Vollkorn';
	$body 		= 'PT Sans';

	if ( ! isset( $options['woo_child_theme_overrides'] ) ) {
		$options['woo_child_theme_overrides'] = 'true';
	}

	if ( 'false' != $options['woo_child_theme_overrides'] ) {

		// Enable Custom Styling
		$options['woo_style_disable'] = 'true';

		// Misc
		$options['woo_font_text'] 	= array( 'size' => '.85', 'unit' => 'em', 'face' => $body, 'style' => 'normal', 'color' => '#999999' );
		$options['woo_font_h1'] 	= array( 'size' => '3.9', 'unit' => 'em', 'face' => $headings, 'style' => '400', 'color' => '#222222' );
		$options['woo_font_h2'] 	= array( 'size' => '2.8', 'unit' => 'em', 'face' => $headings, 'style' => '400', 'color' => '#222222' );
		$options['woo_font_h3'] 	= array( 'size' => '1.9', 'unit' => 'em', 'face' => $headings, 'style' => '400', 'color' => '#222222' );
		$options['woo_font_h4'] 	= array( 'size' => '1.4', 'unit' => 'em', 'face' => $headings, 'style' => '400', 'color' => '#222222' );
		$options['woo_font_h5'] 	= array( 'size' => '1.2', 'unit' => 'em', 'face' => $headings, 'style' => '400', 'color' => '#222222' );
		$options['woo_font_h6'] 	= array( 'size' => '1', 'unit' => 'em', 'face' => $headings, 'style' => '400', 'color' => '#222222' );

		// Top Navigation
		$options['woo_top_nav_font'] = array( 'size' => '1', 'unit' => 'em', 'face' => $body, 'style' => 'normal', 'color' => '#ffffff' );

		// Header
		$options['woo_font_logo'] 				= array( 'size' => '3.1', 'unit' => 'em', 'face' => $headings, 'style' => '400', 'color' => '#222222' );
		$options['woo_font_desc'] 				= array( 'size' => '.9', 'unit' => 'em', 'face' => $body, 'style' => 'normal', 'color' => '#999999' );
		$options['woo_header_bg'] 				= '#ffffff';
		$options['woo_header_padding_bottom'] 	= '60';
		$options['woo_header_padding_top'] 		= '60';
		$options['woo_nav_rss']					= 'false';
		$options['woo_subscribe_email']			= 'false';

		// Primary Navigation
		$options['woo_nav_font'] 		= array( 'size' => '1', 'unit' => 'em', 'face' => $body, 'style' => 'normal', 'color' => '#222222' );
		$options['woo_nav_bg'] 			= '#ffffff';
		$options['woo_nav_hover_bg'] 	= '#ffffff';

		// Posts / Pages
		$options['woo_font_post_title'] = array( 'size' => '2', 'unit' => 'em', 'face' => $headings, 'style' => '400', 'color' => '#222222' );
		$options['woo_font_post_meta'] 	= array( 'size' => '.9', 'unit' => 'em', 'face' => $body, 'style' => 'normal', 'color' => '#999999' );
		$options['woo_font_post_text'] 	= array( 'size' => '1', 'unit' => 'em', 'face' => $body, 'style' => 'normal', 'color' => '#999999' );
		$options['woo_font_post_more'] 	= array( 'size' => '1', 'unit' => 'em', 'face' => $body, 'style' => 'normal', 'color' => '#999999' );
		$options['woo_pagenav_font'] 	= array( 'size' => '1', 'unit' => 'em', 'face' => $body, 'style' => 'normal', 'color' => '#999999' );

		// Post Author
		$options['woo_post_author_border_top'] 		= array( 'width' => '0', 'style' => 'solid', 'color' => '' );
		$options['woo_post_author_border_bottom'] 	= array( 'width' => '0', 'style' => 'solid', 'color' => '' );
		$options['woo_post_author_border_lr'] 		= array( 'width' => '0', 'style' => 'solid', 'color' => '' );
		$options['woo_post_author_border_radius'] 	= '0px';
		$options['woo_post_author_bg'] 				= '#ffffff';

		// Archives
		$options['woo_archive_header_font'] = array( 'size' => '1', 'unit' => 'em', 'face' => $headings, 'style' => '400', 'color' => '#222222' );

		// Widgets
		$options['woo_widget_font_title'] 	= array( 'size' => '1.4', 'unit' => 'em', 'face' => $headings, 'style' => '400', 'color' => '#222222' );
		$options['woo_widget_font_text'] 	= array( 'size' => '1', 'unit' => 'em', 'face' => $body, 'style' => 'normal', 'color' => '#999999' );
		$options['woo_widget_title_border'] = array( 'width' => '0', 'style' => 'solid', 'color' => '#00000' );

		// Footer
		$options['woo_footer_font'] 	  = array( 'size' => '1', 'unit' => 'em', 'face' => $body, 'style' => 'normal', 'color' => '#999999' );
		$options['woo_footer_bg'] 		  = '#ffffff';
		$options['woo_footer_border_top'] = array( 'width' => '1px', 'style' => 'solid', 'color' => '#e6e6e6' );

		// Full Width
		$options['woo_footer_full_width'] 	= 'false';

		// Cart
		$options['woo_header_cart_link'] 	= 'true';
		$options['woo_header_cart_total'] 	= 'true';
	}

	return $options;
}

/**
 * Add Custom Options
 *
 * Add custom options for this Child Theme.
 *
 * @since  	1.0.0
 * @return 	array
 * @author 	WooThemes
 */
function woo_options_add( $options ) {

	$headings 	= 'Vollkorn';
	$body 		= 'PT Sans';

	$shortname = 'woo';

	$options[] = array( 'name' 		=> __( 'Fashionable', 'fashionable' ),
						'icon' 		=> 'misc',
					    'type' 		=> 'heading' );

	$options[] = array( "name" 		=> __( 'Use Child Theme Custom Overrides', 'fashionable' ),
						"desc" 		=> __( 'Disable this option if you\'d like to setup your own typography and layout settings.', 'fashionable' ),
						"id" 		=> $shortname."_child_theme_overrides",
						"std" 		=> "true",
						"type" 		=> "checkbox" );

	$options[] = array( "name" 		=> __( 'Fixed Header', 'woothemes' ),
						"desc" 		=> __( 'Enable a "sticky" header. The header will stay fixed on the top as you scroll.', 'woothemes' ),
						"id" 		=> $shortname."_fixed_header",
						"std" 		=> "false",
						"type" 		=> "checkbox");

	$options[] = array( "name" 		=> __( 'Top Navigation - Phone Number', 'fashionable' ),
						"desc" 		=> __( 'Enter a phone number to be displayed to the right of your Top Navigation menu.', 'fashionable' ),
						"id" 		=> $shortname."_top_phone_number",
						"std" 		=> "",
						"type" 		=> "text");

	$options[] = array( "name" 		=> __( 'Hero - Custom Background', 'fashionable' ),
						"desc" 		=> __( 'Upload a background image for your hero section, or specify an image URL directly.', 'fashionable' ),
						"id" 		=> $shortname."_hero_bg",
						"std" 		=> "",
						"type" 		=> "upload" );

	$options[] = array( "name" 		=> __( 'Hero - Background Image Repeat', 'fashionable' ),
						"desc" 		=> __( 'Select how you want your background image to display.', 'fashionable' ),
						"id" 		=> $shortname."_hero_bg_image_repeat",
						"std" 		=> 'repeat-x',
						"type" 		=> "select",
						"options" 	=> array( "No Repeat" => "no-repeat", "Repeat" => "repeat","Repeat Horizontally" => "repeat-x", "Repeat Vertically" => "repeat-y" ) );

	$options[] = array( 'name' 		=> __( 'Hero - Background image position', 'fashionable' ),
						'desc' 		=> __( 'Select how you would like to position the background', 'fashionable' ),
						'id' 		=> $shortname . '_hero_bg_image_pos',
						'std' 		=> 'top left',
						'type' 		=> 'select',
						'options' 	=> array( "top left", "top center", "top right", "center left", "center center", "center right", "bottom left", "bottom center", "bottom right" ) );

	$options[] = array( "name" 		=> __( 'Hero - Background Attachment', 'fashionable' ),
	            		"desc" 		=> __( 'Select whether the background should be fixed or move when the user scrolls', 'fashionable' ),
	            		"id" 		=> $shortname."_hero_bg_image_attach",
	            		"std" 		=> "scroll",
	            		"type" 		=> "select",
	            		"options" 	=> array( "scroll","fixed" ) );

	$options[] = array( "name" 		=> __( 'Hero - Title', 'fashionable' ),
						"desc" 		=> __( 'Enter the Hero title.', 'fashionable' ),
						"id" 		=> $shortname."_hero_title",
						"std" 		=> "",
						"type" 		=> "text");

	$options[] = array( "name" 		=> __( 'Hero - Title Font Style', 'fashionable' ),
						"desc" 		=> __( 'Select typography for the hero title.', 'fashionable' ),
						"id" 		=> $shortname."_hero_title_font",
						"std" 		=> array('size' => '2.6','unit' => 'em', 'face' => $headings, 'style' => 'normal', 'color' => '#222222'),
						"type" 		=> "typography");

	$options[] = array( "name" 		=> __( 'Hero - Message', 'fashionable' ),
						"desc" 		=> __( 'Enter the Hero message.', 'fashionable' ),
						"id" 		=> $shortname."_hero_message",
						"std" 		=> "",
						"type" 		=> "textarea");

	$options[] = array( "name" 		=> __( 'Hero - Message Font Style', 'fashionable' ),
						"desc" 		=> __( 'Select typography for the hero message.', 'fashionable' ),
						"id" 		=> $shortname."_hero_message_font",
						"std" 		=> array('size' => '1.2','unit' => 'em', 'face' => $body,'style' => 'normal','color' => '#111111'),
						"type" 		=> "typography");

	$options[] = array( "name" 		=> __( 'Hero - Button', 'fashionable' ),
						"desc" 		=> __( 'Enter the Hero button text.', 'fashionable' ),
						"id" 		=> $shortname."_hero_button",
						"std" 		=> "",
						"type" 		=> "text");

	$options[] = array( "name" 		=> __( 'Hero - Button Link', 'fashionable' ),
						"desc" 		=> __( 'Enter the Hero button url.', 'fashionable' ),
						"id" 		=> $shortname."_hero_button_link",
						"std" 		=> "",
						"type" 		=> "text");

	return $options;
}

/**
 * Output Custom Fonts
 *
 * Outputs CSS for the new selectors included with this child theme.
 *
 * @since  	1.0.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_custom_fonts_output() {
	global $woo_options;
	$output = '';

	if ( isset( $woo_options['woo_hero_bg'] ) && '' != $woo_options['woo_hero_bg'] ) {
		$output .= '.home-section.hero {';
		$output .= 'background-image: url(' . esc_url( $woo_options['woo_hero_bg'] ) . ');';
		if ( isset( $woo_options['woo_hero_bg_image_repeat'] ) && '' != $woo_options['woo_hero_bg_image_repeat'] ) {
			$output .= 'background-repeat:' . $woo_options['woo_hero_bg_image_repeat'] . ';';
		}
		if ( isset( $woo_options['woo_hero_bg_image_pos'] ) && '' != $woo_options['woo_hero_bg_image_pos'] ) {
			$output .= 'background-position:' . $woo_options['woo_hero_bg_image_pos'] . ';';
		}
		if ( isset( $woo_options['woo_hero_bg_image_attach'] ) && '' != $woo_options['woo_hero_bg_image_attach'] ) {
			$output .= 'background-attachment:' . $woo_options['woo_hero_bg_image_attach'] . ';';
		}
		$output .= '}' . "\n";
	}

	if ( isset( $woo_options['woo_hero_title_font'] ) ) {
		$output .= '.home-section.hero h1.section-title { ' . woo_generate_font_css( $woo_options['woo_hero_title_font'], 1.2 ) . ' }' . "\n";
	}

	if ( isset( $woo_options['woo_hero_message_font'] ) ) {
		$output .= '.home-section.hero p { ' . woo_generate_font_css( $woo_options['woo_hero_message_font'], 1.45 ) . ' }' . "\n";
	}

	if ( isset( $output ) && '' != $output ) {
		$output = "\n<!-- Woo Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n<!-- /Woo Custom Styling -->\n\n";
		echo $output;
	}
}

/**
 * Display Hero.
 *
 * Displays hero section.
 *
 * @since  	1.0.0
 * @return 	void
 * @link 	http://www.woothemes.com/woocommerce/
 * @author 	WooThemes
 */
function woo_display_hero() {
 	$settings = array(
					'hero_title'		=> '',
					'hero_message' 		=> '',
					'hero_button' 		=> '',
					'hero_button_link' 	=> ''
				);

	$settings = woo_get_dynamic_values( $settings );

	if ( $settings['hero_title'] != '' || $settings['hero_message'] != '' || $settings['hero_button'] != '' || $settings['hero_button_link'] != '' ) {
	?>
		<section class="hero home-section">
			<div class="col-full">
				<div class="hero-container">
					<?php if ( isset( $settings['hero_title'] ) && '' != $settings['hero_title'] ): ?>
						<h1 class="section-title"><span><?php echo esc_attr( stripslashes( $settings['hero_title'] ) ); ?></span></h1>
					<?php endif; ?>

					<?php if ( isset( $settings['hero_message'] ) && '' != $settings['hero_message'] ): ?>
						<?php echo wpautop( esc_attr( stripslashes( $settings['hero_message'] ) ) ); ?>
					<?php endif; ?>

					<?php if ( isset( $settings['hero_button'] ) && '' != $settings['hero_button'] ): ?>
						<div class="cta">
							<a class="button" href="<?php echo esc_url( $settings['hero_button_link'] ); ?>"><?php echo esc_attr( $settings['hero_button'] ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php
	}
}

/**
 * Display Product Categories.
 *
 * Displays product categories using the WooCommerce product_categories shortcode.
 *
 * @since  	1.0.0
 * @return 	void
 * @uses  	do_shortcode()
 * @link 	http://www.woothemes.com/woocommerce/
 * @author 	WooThemes
 */
function woo_display_product_categories() {
?>
	<?php if ( is_woocommerce_activated() ): ?>
	<section class="product-categories home-section">
		<div class="col-full">
			<?php
				$product_categories_limit 		= apply_filters( 'woo_template_product_categories_limit', $limit = 3 );
				$product_categories_columns 	= apply_filters( 'woo_template_product_categories_columns', $columns = 3 );
				echo do_shortcode( '[product_categories number="' . $product_categories_limit . '" columns="' . $product_categories_columns . '" parent="0"]' );
			?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

/**
 * Display Featured Products.
 *
 * Displays products which have been set as “featured” using the WooCommerce featured_products shortcode.
 *
 * @since  	1.0.0
 * @return 	void
 * @uses  	do_shortcode()
 * @link 	http://www.woothemes.com/woocommerce/
 * @author 	WooThemes
 */
function woo_display_featured_products() {
?>
	<?php if ( is_woocommerce_activated() ): ?>
	<?php add_action( 'woocommerce_after_shop_loop_item_title', 'woo_home_featured_description', 15 ); ?>
	<section class="featured-products home-section">
		<div class="col-full">
			<?php
				$featured_products_limit 		= apply_filters( 'woo_template_featured_products_limit', $limit = 2 );
				$featured_products_columns 		= apply_filters( 'woo_template_featured_products_columns', $columns = 2 );
				echo do_shortcode( '[featured_products per_page="' . $featured_products_limit . '" columns="' . $featured_products_columns . '"]' );
			?>
		</div>
	</section>
	<?php remove_action( 'woocommerce_after_shop_loop_item_title', 'woo_home_featured_description', 15 ); ?>
	<?php endif; ?>
<?php
}

/**
 * Display Recent Products.
 *
 * Displays recent products using the WooCommerce recent_products shortcode.
 *
 * @since  	1.0.0
 * @return 	void
 * @uses  	do_shortcode()
 * @link 	http://www.woothemes.com/woocommerce/
 * @author 	WooThemes
 */
function woo_display_recent_products() {
?>
	<?php if ( is_woocommerce_activated() ): ?>
	<section class="recent-products home-section">
		<div class="col-full">
			<?php
				$recent_products_limit 		= apply_filters( 'woo_template_recent_products_limit', $limit = 8 );
				$recent_products_columns 	= apply_filters( 'woo_template_recent_products_columns', $columns = 4 );
				echo do_shortcode( '[recent_products per_page="' . $recent_products_limit . '" columns="' . $recent_products_columns . '"]' );
			?>
		</div>
	</section>
	<?php endif; ?>
<?php
}

/**
 * Fixed Header
 *
 * Adds necessary hooks, if the Fixed Header is enabled in the Settings.
 *
 * @since  	1.1.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_fixed_header() {

	$settings = woo_get_dynamic_values( array( 'fixed_header' => '' ) );

	if ( 'true' == $settings['fixed_header'] ) {
		add_action('woo_header_before', 'woo_fixed_header_before', 99);
		add_action('woo_nav_after', 'woo_add_fixed_header_after', 99);
		add_filter( 'woothemes_add_javascript', 'woo_load_fixed_header_script', 10 );
		add_filter( 'body_class', 'woo_fixed_header_class', 10 );
	}

}

/**
 * Opens the Fixed Header wrapper
 *
 * @since  	1.1.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_fixed_header_before() {
	echo '<div id="fixed-header" class="col-full">';
}

/**
 * Closes the Fixed Header wrapper
 *
 * @since  	1.1.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_add_fixed_header_after() {
	echo '</div>';
}

/**
 * Fixed Header Enqueue
 *
 * Enqueues the Fixed Header script file.
 *
 * @since  	1.1.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_load_fixed_header_script () {
	wp_enqueue_script( 'fixed-header', get_stylesheet_directory_uri() . '/includes/js/fixed-header.js', array( 'jquery' ) );
}

/**
 * Fixed Header Body Class
 *
 * Adds the Fixed Header class to the body.
 *
 * @since  	1.1.0
 * @return 	void
 * @author 	WooThemes
 */
function woo_fixed_header_class ( $classes ) {
	$classes[] = 'fixed-header';
	return $classes;
}