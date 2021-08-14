<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}


class One_And_One_Site_Type {

	private $id;
	private $name;
	private $pic_url;
	private $description;

	public function __construct( $id, $name, $pic_filename, $description ) {
		$this->id          = $id;
		$this->name        = $name;
		$this->pic_url     = $pic_filename;
		$this->description = $description;
	}

	public function get_id() {
		return $this->id;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_pic_url() {
		return $this->pic_url;
	}

	public function get_description() {
		return $this->description;
	}

}