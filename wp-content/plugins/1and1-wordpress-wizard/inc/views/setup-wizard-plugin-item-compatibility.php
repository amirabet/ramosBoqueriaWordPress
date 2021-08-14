<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Plugin_Item {

	static public function get_plugin_item( $plugin, $popup_id, $plugin_default_checked ) {
		?>

		<div class="oneandone-selectable-item">
			<h3 class="oneandone-plugin-type"><?php echo self::get_category_name( $plugin->category ); ?></h3>

			<div class="oneandone-plugin-description" onclick="showBox(<?php echo htmlspecialchars( json_encode( $popup_id ) ); ?>, '')">
				<?php echo $plugin->short_description; ?>
			</div>

	  	    <span class="oneandone-plugin-more-details" onclick="showBox(<?php echo htmlspecialchars( json_encode( $popup_id ) ); ?>, '')">
	  	    	<?php esc_html_e( 'More information', '1and1-wordpress-wizard' ) ?>
			</span>

			<h3 class="oneandone-plugin-name"><?php echo $plugin->name; ?></h3>

			<div class="oneandone-install-checkbox<?php echo $plugin_default_checked == true ? ' checked' : ''; ?>">
				<label for="plugin-<?php echo $plugin->slug; ?>">
				 <input id="plugin-<?php echo $plugin->slug; ?>" name="plugins[]" value="<?php echo $plugin->slug; ?>" type="checkbox" <?php echo $plugin_default_checked == true ? 'checked' : ''; ?>>
					<?php _e( 'Install', '1and1-wordpress-wizard' ); ?>
				</label>
			</div>

			<div id="<?php echo $popup_id; ?>" style="display:none">
				<h3 class="oneandone-popup-plugin-name"><?php echo $plugin->name; ?></h3>
				<h4 class="oneandone-popup-plugin-author"><?php printf( esc_html( 'By %s', '1and1-wordpress-wizard' ), $plugin->author ); ?></h4>

				<div><?php echo links_add_target( $plugin->description, '_blank' ); ?></div>
			</div>

		</div>
	<?php
	}

	private static function get_category_name( $category_name ) {
		include_once One_And_One_Wizard::get_inc_dir_path() . 'plugin-categories-catalog.php';

		return One_And_One_Plugin_Categories_Catalog::get_plugin_category_name( $category_name );
	}

}
