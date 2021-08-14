<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

include_once 'site-type.php';

class One_And_One_Site_Type_Catalog {

	private $site_types = array();

	public function __construct() {
		$catalog    = new One_And_One_Catalog();
		$site_types = $catalog->get_site_types();

		foreach ( $site_types as $site_type ) {
			$this->site_types[] = new One_And_One_Site_Type(
				$site_type->id,
				esc_html( $site_type->name ),
				esc_url( $site_type->image ),
				esc_html( $site_type->description )
			);
		}
	}

	public function get_all_site_types() {
		return $this->site_types;
	}

}