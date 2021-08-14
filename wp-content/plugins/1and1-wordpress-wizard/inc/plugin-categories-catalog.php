<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Plugin_Categories_Catalog {

	private static $plugin_category = array();

	private static function load_categories() {
		self::$plugin_category[ 'anti_spam' ]          = esc_html__( 'Spam Protection', '1and1-wordpress-wizard' );
		self::$plugin_category[ 'content_management' ] = esc_html__( 'Content Management', '1and1-wordpress-wizard' );
		self::$plugin_category[ 'comment_management' ] = esc_html__( 'Comment Management', '1and1-wordpress-wizard' );
		self::$plugin_category[ 'seo' ]                = esc_html__( 'Search Engine Optimization', '1and1-wordpress-wizard' );
		self::$plugin_category[ 'forms' ]              = esc_html__( 'Forms', '1and1-wordpress-wizard' );
		self::$plugin_category[ 'caching' ]            = esc_html__( 'Caching', '1and1-wordpress-wizard' );
	}

	public static function get_plugin_category_name( $plugin_category ) {
		if ( ! self::$plugin_category ) {
			self::load_categories();
		}

		if ( isset( self::$plugin_category[ $plugin_category ] ) ) {
			return self::$plugin_category[ $plugin_category ];
		} else {
			return "";
		}
	}

}