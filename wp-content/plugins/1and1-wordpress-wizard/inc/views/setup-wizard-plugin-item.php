<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Plugin_Item {

	static public function get_plugin_item( $plugin, $popup_id, $plugin_default_checked ) {
		$plugins_allowedtags = array(
			'a' => array( 'href' => array(),'title' => array(), 'target' => array() ),
			'abbr' => array( 'title' => array() ),'acronym' => array( 'title' => array() ),
			'code' => array(), 'pre' => array(), 'em' => array(),'strong' => array(),
			'ul' => array(), 'ol' => array(), 'li' => array(), 'p' => array(), 'br' => array()
		);

		$details_link = esc_url( admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin->slug . '&amp;TB_iframe=true&amp;width=772&amp;height=598' ) );

		$author = wp_kses( $plugin->author, $plugins_allowedtags );
		if ( ! empty( $author ) ) {
			$author = ' <cite>' . sprintf( __( 'By %s' ), $author ) . '</cite>';
		}
		?>

		<div class="plugin-card">
			<div class="plugin-card-top">
				<?php if ( isset( $plugin->icon ) ) { ?>
				<a href="<?php echo $details_link; ?>" class="thickbox">
					<img src="<?php echo esc_attr( $plugin->icon ); ?>" class="plugin-icon">
				</a>
				<?php } ?>

				<div class="action-links">
					<ul class="plugin-action-buttons">
						<li class="oneandone-install-checkbox<?php echo $plugin_default_checked == true ? ' checked' : ''; ?>">
							<label for="plugin-<?php echo $plugin->slug; ?>">
							 <input id="plugin-<?php echo $plugin->slug; ?>" name="plugins[]" value="<?php echo $plugin->slug; ?>" type="checkbox" <?php echo $plugin_default_checked == true ? 'checked' : ''; ?>>
								<?php _e( 'Install', '1and1-wordpress-wizard' ); ?>
							</label>
						</li>
						<li>
							<?php
							$name = strip_tags( $plugin->name . ' ' . $plugin->version );

							/* translators: 1: Plugin name and version. */
							echo '<a href="' . esc_url( $details_link ) . '" class="thickbox" aria-label="' . esc_attr( sprintf( __( 'More information about %s' ), $name ) ) . '" data-title="' . esc_attr( $name ) . '">' . __( 'More Details' ) . '</a>';
							?>
						</li>
					</ul>
				</div>

				<div class="name column-name">
					<h4>
						<a href="<?php echo $details_link; ?>" class="thickbox">
							<?php echo strip_tags( $plugin->name ); ?>
						</a>
					</h4>
				</div>

				<div class="desc column-description">
					<p><?php echo strip_tags( $plugin->short_description ); ?></p>
					<p class="authors"><?php echo $author; ?></p>
				</div>
			</div>

			<div class="plugin-card-bottom">
				<div class="vers column-rating">
					<?php wp_star_rating( array( 'rating' => $plugin->rating, 'type' => 'percent', 'number' => $plugin->num_ratings ) ); ?>
					<span class="num-ratings">(<?php echo number_format_i18n( $plugin->num_ratings ); ?>)</span>
				</div>
				<div class="column-updated">
					<strong><?php _e( 'Last Updated:' ); ?></strong>
					<span title="<?php echo esc_attr( $plugin->last_updated ); ?>">
						<?php printf( __( '%s ago' ), human_time_diff( strtotime( $plugin->last_updated ) ) ); ?>
					</span>
				</div>
				<div class="column-downloaded">
					<?php echo sprintf( _n( '%s download', '%s downloads', $plugin->downloaded ), number_format_i18n( $plugin->downloaded ) ); ?>
				</div>
				<div class="column-compatibility">
					<?php
					if ( ! empty( $plugin->tested ) && version_compare( substr( $GLOBALS['wp_version'], 0, strlen( $plugin->tested ) ), $plugin->tested, '>' ) ) {
						echo  __( '<strong>Untested</strong> with your install ');
					} elseif ( ! empty( $plugin->requires ) && version_compare( substr( $GLOBALS['wp_version'], 0, strlen( $plugin->requires ) ), $plugin->requires, '<' ) ) {
						echo __( '<strong>Incompatible</strong> with your install ');
					} else {
						echo __( '<strong>Compatible</strong> with your install ');
					}
					?>
				</div>
			</div>
		</div>

	<?php
	}

	private static function get_category_name( $category_name ) {
		include_once One_And_One_Wizard::get_inc_dir_path() . 'plugin-categories-catalog.php';

		return One_And_One_Plugin_Categories_Catalog::get_plugin_category_name( $category_name );
	}

}
