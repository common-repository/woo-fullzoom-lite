<?php

if( ! class_exists( 'WOOFULLZOOM' ) )  {
	class WOOFULLZOOM {
		private $options;

		public function __construct() {
			$this->options = get_option( 'woofz_options' );

			add_action( 'init', array( $this, 'load_textdomain' ) );
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'page_init' ) );

			if( $this->is_woocommerce_activated() && $this->options['enable'] == 1 ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_action( 'wp', array( $this, 'remove_product_thumbnail_gallery' ) );
				add_action( 'woocommerce_before_single_product_summary', array( $this, 'single_product_image' ), 20 );
				add_action( 'wp_head', array( $this, 'custom_style' ) );
			}
			
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}

		public function default_options() {
			$options = array(
				'enable' => '1',
				'slider' => '0',
				'thumbnails_position' => '1',
				'thumbnails_number' => '3',
				'preloader' => '1',
				'preloader_color' => '#cccccc',
				'thumbnails_border_width' => '1',
				'thumbnails_border_color' => '#cccccc',
				'active_thumbnails_border_color' => '#525252',
				'fullscreen_background_color' => '#ffffff',
			);
			
			return $options;
		}

		public function add_plugin_page() {
			add_options_page(
				'WOO FULLZOOM',
				'WOO FULLZOOM',
				'manage_options',
				'woofz',
				array( $this, 'create_admin_page' )
			);
		}

		public function create_admin_page() {
			?>
			<div class="wrap">
				<h2>WOO FULLZOOM</h2>
				<p>WOO FULLZOOM</p>
				<?php settings_errors(); ?>

				<form method="post" action="options.php">
					<?php
						settings_fields( 'option_group' );
						do_settings_sections( 'woofz-admin' );
						submit_button();
					?>
				</form>
			</div>
		<?php }

		public function page_init() {
			register_setting(
				'option_group',
				'woofz_options',
				array( $this, 'sanitize' )
			);

			add_settings_section(
				'setting_section',
				__( 'Settings', 'woo-fullzoom' ),
				array( $this, 'section_info' ),
				'woofz-admin'
			);

			add_settings_field(
				'enable',
				__( 'Enable', 'woo-fullzoom' ),
				array( $this, 'enable_callback' ),
				'woofz-admin',
				'setting_section'
			);

			add_settings_field(
				'slider',
				__( 'Thumbnails slider', 'woo-fullzoom' ),
				array( $this, 'slider_callback' ),
				'woofz-admin',
				'setting_section'
			);

			add_settings_field(
				'thumbnails_number',
				__( 'Thumbnails slider items', 'woo-fullzoom' ),
				array( $this, 'thumbnails_number_callback' ),
				'woofz-admin',
				'setting_section'
			);

			add_settings_field(
				'thumbnails_position',
				__( 'Thumbnails position', 'woo-fullzoom' ),
				array( $this, 'thumbnails_position_callback' ),
				'woofz-admin',
				'setting_section'
			);

			add_settings_field(
				'thumbnails_border_width',
				__( 'Thumbnails border width', 'woo-fullzoom' ),
				array( $this, 'thumbnails_border_width_callback' ),
				'woofz-admin',
				'setting_section'
			);

			add_settings_field(
				'preloader',
				__( 'Preloader', 'woo-fullzoom' ),
				array( $this, 'preloader_callback' ),
				'woofz-admin',
				'setting_section'
			);

			add_settings_field(
				'preloader_color',
				__( 'Preloader color', 'woo-fullzoom' ),
				array( $this, 'preloader_color_callback' ),
				'woofz-admin',
				'setting_section'
			);

			add_settings_field(
				'thumbnails_border_color',
				__( 'Thumbnails border color', 'woo-fullzoom' ),
				array( $this, 'thumbnails_border_color_callback' ),
				'woofz-admin',
				'setting_section'
			);

			add_settings_field(
				'active_thumbnails_border_color',
				__( 'Active thumbnails border color', 'woo-fullzoom' ),
				array( $this, 'active_thumbnails_border_color_callback' ),
				'woofz-admin',
				'setting_section'
			);

			add_settings_field(
				'fullscreen_background_color',
				__( 'Fullscreen background color', 'woo-fullzoom' ),
				array( $this, 'fullscreen_background_color_callback' ),
				'woofz-admin',
				'setting_section'
			);
		}

		public function sanitize( $input ) {
			$sanitary_values = array();
			if ( isset( $input['enable'] ) ) {
				$sanitary_values['enable'] = $input['enable'];
			}

			if ( isset( $input['slider'] ) ) {
				$sanitary_values['slider'] = $input['slider'];
			}

			if ( isset( $input['thumbnails_number'] ) ) {
				$sanitary_values['thumbnails_number'] = $input['thumbnails_number'];
			}

			if ( isset( $input['thumbnails_position'] ) ) {
				$sanitary_values['thumbnails_position'] = $input['thumbnails_position'];
			}

			if ( isset( $input['thumbnails_border_width'] ) ) {
				$sanitary_values['thumbnails_border_width'] = $input['thumbnails_border_width'];
			}

			if ( isset( $input['preloader'] ) ) {
				$sanitary_values['preloader'] = $input['preloader'];
			}

			if ( isset( $input['preloader_color'] ) ) {
				$sanitary_values['preloader_color'] = sanitize_text_field( $input['preloader_color'] );
			}

			if ( isset( $input['thumbnails_border_color'] ) ) {
				$sanitary_values['thumbnails_border_color'] = sanitize_text_field( $input['thumbnails_border_color'] );
			}

			if ( isset( $input['active_thumbnails_border_color'] ) ) {
				$sanitary_values['active_thumbnails_border_color'] = sanitize_text_field( $input['active_thumbnails_border_color'] );
			}

			if ( isset( $input['fullscreen_background_color'] ) ) {
				$sanitary_values['fullscreen_background_color'] = sanitize_text_field( $input['fullscreen_background_color'] );
			}

			return $sanitary_values;
		}

		public function section_info() {
			
		}

		public function enable_callback() {
			printf(
				'<input type="checkbox" name="woofz_options[enable]" id="enable" value="1" %s>',
				( isset( $this->options['enable'] ) && $this->options['enable'] === '1' ) ? 'checked' : ''
			);
		}

		public function slider_callback() {
			printf(
				'<input type="checkbox" name="woofz_options[slider]" id="slider" value="1" %s disabled><p class="description">in <a href="https://www.codester.com/items/3993/woocommerce-fullscreen-image-zoom" target="_blank">Pro</a> version</p>',
				( isset( $this->options['slider'] ) && $this->options['slider'] === '1' ) ? 'checked' : ''
			);
		}

		public function thumbnails_number_callback() {
			?>
			<select name="woofz_options[thumbnails_number]" id="thumbnails_number" disabled>
				<?php $selected = (isset( $this->options['thumbnails_number'] ) && $this->options['thumbnails_number'] === '1') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?>>1</option>
				<?php $selected = (isset( $this->options['thumbnails_number'] ) && $this->options['thumbnails_number'] === '2') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?>>2</option>
				<?php $selected = (isset( $this->options['thumbnails_number'] ) && $this->options['thumbnails_number'] === '3') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?>>3</option>
				<?php $selected = (isset( $this->options['thumbnails_number'] ) && $this->options['thumbnails_number'] === '4') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?>>4</option>
				<?php $selected = (isset( $this->options['thumbnails_number'] ) && $this->options['thumbnails_number'] === '5') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?>>5</option>
				<?php $selected = (isset( $this->options['thumbnails_number'] ) && $this->options['thumbnails_number'] === '6') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?>>6</option>
			</select>
			<p class="description">in <a href="https://www.codester.com/items/3993/woocommerce-fullscreen-image-zoom" target="_blank">Pro</a> version</p>
			<?php
		}

		public function thumbnails_position_callback() {
			?>
			<select name="woofz_options[thumbnails_position]" id="thumbnails_position">
				<?php $selected = (isset( $this->options['thumbnails_position'] ) && $this->options['thumbnails_position'] === '1') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="1"><?php echo __( 'Right', 'woo-fullzoom' ); ?></option>
				<?php $selected = (isset( $this->options['thumbnails_position'] ) && $this->options['thumbnails_position'] === '2') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="2"><?php echo __( 'Left', 'woo-fullzoom' ); ?></option>
			</select>
			<p class="description"><?php echo __( 'in fullscreen page', 'woo-fullzoom' ); ?></p>
			<?php
		}

		public function thumbnails_border_width_callback() {
			?>
			<select name="woofz_options[thumbnails_border_width]" id="thumbnails_border_width">
				<?php $selected = (isset( $this->options['thumbnails_border_width'] ) && $this->options['thumbnails_border_width'] === '0') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="0">No border</option>
				<?php $selected = (isset( $this->options['thumbnails_border_width'] ) && $this->options['thumbnails_border_width'] === '1') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="1">1px</option>
				<?php $selected = (isset( $this->options['thumbnails_border_width'] ) && $this->options['thumbnails_border_width'] === '2') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="2">2px</option>
				<?php $selected = (isset( $this->options['thumbnails_border_width'] ) && $this->options['thumbnails_border_width'] === '3') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="3">3px</option>
				<?php $selected = (isset( $this->options['thumbnails_border_width'] ) && $this->options['thumbnails_border_width'] === '4') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="4">4px</option>
				<?php $selected = (isset( $this->options['thumbnails_border_width'] ) && $this->options['thumbnails_border_width'] === '5') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="5">5px</option>
			</select>
			<?php
		}

		public function preloader_callback() {
			?>
			<select name="woofz_options[preloader]" id="preloader">
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '0') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="0">None</option>
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '1') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="1">Rotating plane</option>
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '2') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="2" disabled>Double bounce</option>
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '3') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="3" disabled>Wave</option>
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '4') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="4" disabled>Wandering cubes</option>
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '5') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="5" disabled>Pulse</option>
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '6') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="6" disabled>Chasing dots</option>
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '7') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="7" disabled>Three bounce</option>
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '8') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="8" disabled>Circle</option>
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '9') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="9" disabled>Cube grid</option>
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '10') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="10" disabled>Fading circle</option>
				<?php $selected = (isset( $this->options['preloader'] ) && $this->options['preloader'] === '11') ? 'selected' : '' ; ?>
				<option <?php echo $selected; ?> value="11" disabled>Folding cube</option>
			</select>
			<p class="description">more preloader in <a href="https://www.codester.com/items/3993/woocommerce-fullscreen-image-zoom" target="_blank">Pro</a> version</p>
			<?php
		}

		public function preloader_color_callback() {
			printf(
				'<input class="regular-text" type="text" name="woofz_options[preloader_color]" id="preloader_color" value="%s" data-default-color="#cccccc"><p class="description">work in <a href="https://www.codester.com/items/3993/woocommerce-fullscreen-image-zoom" target="_blank">Pro</a> version</p>',
				isset( $this->options['preloader_color'] ) ? esc_attr( $this->options['preloader_color']) : ''
			);
		}

		public function thumbnails_border_color_callback() {
			printf(
				'<input class="regular-text" type="text" name="woofz_options[thumbnails_border_color]" id="thumbnails_border_color" value="%s" data-default-color="#cccccc"><p class="description">work in <a href="https://www.codester.com/items/3993/woocommerce-fullscreen-image-zoom" target="_blank">Pro</a> version</p>',
				isset( $this->options['thumbnails_border_color'] ) ? esc_attr( $this->options['thumbnails_border_color']) : ''
			);
		}

		public function active_thumbnails_border_color_callback() {
			printf(
				'<input class="regular-text" type="text" name="woofz_options[active_thumbnails_border_color]" id="active_thumbnails_border_color" value="%s" data-default-color="#525252"><p class="description">work in <a href="https://www.codester.com/items/3993/woocommerce-fullscreen-image-zoom" target="_blank">Pro</a> version</p>',
				isset( $this->options['active_thumbnails_border_color'] ) ? esc_attr( $this->options['active_thumbnails_border_color']) : ''
			);
		}

		public function fullscreen_background_color_callback() {
			printf(
				'<input class="regular-text" type="text" name="woofz_options[fullscreen_background_color]" id="fullscreen_background_color" value="%s" data-default-color="#ffffff"><p class="description">work in <a href="https://www.codester.com/items/3993/woocommerce-fullscreen-image-zoom" target="_blank">Pro</a> version</p>',
				isset( $this->options['fullscreen_background_color'] ) ? esc_attr( $this->options['fullscreen_background_color']) : ''
			);
		}

		public function admin_enqueue_scripts() {
			if( isset( $_GET['page'] ) && $_GET['page'] == 'woofz' ) {
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_script( 'woofz-admin-script', WOOFULLZOOM_URL_PATH . 'assets/js/admin-script.js', array( 'wp-color-picker' ), false, true );
			}
		}

		public function enqueue_scripts() {
			wp_enqueue_style( 'woofz-style', WOOFULLZOOM_URL_PATH . 'assets/css/style.css' );
			wp_enqueue_style( 'woofz-preloader', WOOFULLZOOM_URL_PATH . 'assets/css/preloader.css' );
			wp_enqueue_script( 'woofz-script', WOOFULLZOOM_URL_PATH . 'assets/js/script.js', array( 'jquery' ) );

			wp_localize_script('woofz-script', 'woofz_script_vars', array(
				'preloader' => $this->options['preloader'],
			) );
		}

		public function custom_style() {
			$thumbnails_border_width = $this->options['thumbnails_border_width'];
			$thumbnails_position = $this->options['thumbnails_position'];
			?>
			<style>
			.woofz-product-gallery #woofz-thumbnails img, #woofz-fullscreen-thumbnails img {
				<?php if( $thumbnails_border_width > 0 ) : ?>
				border-width: <?php echo $thumbnails_border_width; ?>px !important;
				<?php endif; ?>
			}
			
			#woofz-fullscreen-thumbnails {
				<?php if( $thumbnails_position == '2' ) : ?>
				left: auto !important;
				right: 10px !important;
				<?php endif; ?>
			}
			</style>
			<?php
		}

		public function remove_product_thumbnail_gallery() {
			remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		}
		
		public function single_product_image() {
			global $post;
			
			$post_id = $post->ID;
			
			$options = $this->options;

			$product_id = $post->ID;
			$product = new WC_product( $product_id );
			
			$featured_id = get_post_thumbnail_id( $post_id );
			if( $featured_id ) {
				$thumbnail_small_url = wp_get_attachment_image_url( $featured_id, 'shop_thumbnail' );
				$thumbnail_medium_url = wp_get_attachment_image_url( $featured_id, 'shop_catalog' );
				$thumbnail_large_url = wp_get_attachment_image_url( $featured_id, 'full' );
			} else {
				$thumbnail_small_url = $thumbnail_medium_url = $thumbnail_large_url = wc_placeholder_img_src();
			}

			$attachment_ids = $product->get_gallery_attachment_ids();

			include( WOOFULLZOOM_DIR_PATH . 'templates/default.php' );
		}

		public function plugin_activated() {
			$options = WOOFULLZOOM::default_options();
			add_option( 'woofz_options', $options );
		}

		function plugin_settings_link( $links ) { 
			$settings_link = '<a href="options-general.php?page=woofz">Settings</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		public function is_woocommerce_activated() {
			if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				return true;
			} else {
				return false;
			}
		}
		
		public function load_textdomain() {
			load_plugin_textdomain( 'woo-fullzoom', false, WOOFULLZOOM_DIR_PATH . '/lang' ); 
		}
	}
}

?>