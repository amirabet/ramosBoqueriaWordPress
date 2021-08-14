<?php
/**
 * Template Name: Homepage
 *
 * Here we setup all logic and HTML that is required for the index template, used as both the homepage
 * and as a fallback template, if a more appropriate template file doesn't exist for a specific context.
 *
 * @package WooFramework
 * @subpackage Template
 */

get_header(); ?>

    <div id="content">

    	<?php woo_main_before(); ?>

		<?php do_action( 'homepage' ); ?>

		<?php woo_main_after(); ?>

    </div><!-- /.content -->

<?php get_footer(); ?>