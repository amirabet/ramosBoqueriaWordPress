<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

add_thickbox();

class One_And_One_Site_Selection_Step {

	static public function get_site_selection() {
		?>

		<form action="<?php echo esc_url( add_query_arg( array( 'setup_action' => 'choose_appearance' ) ) ); ?>" method="post">
			<?php wp_nonce_field( 'choose_appearance' ) ?>

			<div class="wrap">

				<?php
				include_once( One_And_One_Wizard::get_views_dir_path() . 'setup-wizard-header.php' );
				One_And_One_Wizard_Header::get_wizard_header(1); ?>

				<h3 class="clear"><?php esc_html_e( 'Step 1 - Selecting website type', '1and1-wordpress-wizard' ); ?></h3>

				<p><?php esc_html_e( 'Here you can select the desired website type.', '1and1-wordpress-wizard' ); ?></p>
				<br/>

				<div class="oneandone-site-type-browser">
					<?php
					include_once( One_And_One_Wizard::get_inc_dir_path() . 'site-types-catalog.php' );

					$site_type_catalog = new One_And_One_Site_Type_Catalog();
					$site_types        = $site_type_catalog->get_all_site_types();

					if ( $site_types ) {
						foreach ( $site_types as $site_type ) {
							?>
							<div class="oneandone-site-selection">
								<div class="oneandone-site-type-picture">
									<img src="<?php echo $site_type->get_pic_url() ?>" alt=""/>
								</div>
								<span class="oneandone-site-type-description"><h3><?php echo $site_type->get_name(); ?></h3><p><?php echo $site_type->get_description(); ?></p></span>

								<h3 class="oneandone-site-type-name"><?php echo $site_type->get_name(); ?></h3>

								<div class="oneandone-site-type-actions">
									<input type="submit" name="sitetype[<?php echo $site_type->get_id(); ?>]" value="<?php esc_attr_e( 'Select', '1and1-wordpress-wizard' ); ?>"
										   class="button button-primary"/>
								</div>
							</div>
						<?php
						}
					}
					else {
						echo '<strong style="font-size:14px;">';
						esc_html_e( "The website types couldn't get retrieved. Please refresh the page.", '1and1-wordpress-wizard' );
						echo '</strong>';
					}
					?>
				</div>
				<br class="clear"/>
			</div>
		</form>

		<script>
		jQuery(document).ready(function($) {
			$('.oneandone-site-type-browser').on( 'click' , '.oneandone-site-selection', function() {
				$( '.button-primary', this ).trigger('click');
			});

			$('.oneandone-site-type-browser').on( 'click' , '.button-primary', function(evt) {
				evt.stopPropagation();
			});
		});
		</script>

	<?php
	}

}