<?php

/**
 * Flickr by Albums.
 *
 * @package   Flickr_by_Albums_Settings
 * @author    Mandi Wise <hello@mandiwise.com>
 * @license   GPL-2.0+
 * @link      http://mandiwise.com/
 * @copyright 2014 Mandi Wise
 */

if( !class_exists( 'Flickr_by_Albums_Settings' ) ) {

	class Flickr_by_Albums_Settings {

		// Array of sections for the plugin
		private $sections;
		private $checkboxes;
		private $settings;

		// The plugin text domain (unfortunately has to be redefined here...)
		protected $plugin_slug = 'flickr-by-albums';

		// Construct the plugin settings object
		public function __construct() {

			$this->checkboxes = array();
			$this->settings = array();
			$this->get_settings();

			// Create the settings sections
			$this->sections['flickr_api'] = __( 'Flickr API Settings', $this->plugin_slug );

			// Register the settings
			add_action( 'admin_init', array( &$this, 'admin_init' ) );

			if ( ! get_option( 'fba_options' ) ) {
            $this->initialize_settings();
         }

		}

		// Create the settings fields
		public function create_setting( $args = array() ) {

			$defaults = array(
				'id'      => 'default_field',
				'title'   => __( 'Default Field' ),
				'desc'    => '',
				'std'     => '',
				'type'    => 'text',
				'section' => 'flickr_api',
				'choices' => array(),
				'class'   => ''
			);

			extract( wp_parse_args( $args, $defaults ) );

			$field_args = array(
				'type'      => $type,
				'id'        => $id,
				'desc'      => $desc,
				'std'       => $std,
				'choices'   => $choices,
				'label_for' => $id,
				'class'     => $class
			);

			if ( $type == 'checkbox' ) {
            $this->checkboxes[] = $id;
         }

			add_settings_field( $id, $title, array( $this, 'display_setting' ), 'fba-options', $section, $field_args );
		}

		// Create the HTML output for each possible type of setting
		public function display_setting( $args = array() ) {

			extract( $args );
			$options = get_option( 'fba_options' );

			if ( ! isset( $options[$id] ) && $type != 'checkbox' ) {
            $options[$id] = $std;
         } elseif ( ! isset( $options[$id] ) ) {
            $options[$id] = 0;
         }

			$field_class = '';
			if ( $class != '' ) {
            $field_class = ' ' . $class;
         }

			switch ( $type ) {

				case 'checkbox':
					echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="fba_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
					break;

				case 'select':
					echo '<select class="select' . $field_class . '" name="fba_options[' . $id . ']">';
					foreach ( $choices as $value => $label ) {
                  echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
               }
					echo '</select>';

					if ( $desc != '' ) {
                  echo '<p class="description">' . $desc . '</p>';
               }
					break;

				case 'radio':
					$i = 0;
					foreach ( $choices as $value => $label ) {
						echo '<input class="radio' . $field_class . '" type="radio" name="fba_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
						if ( $i < count( $options ) - 1 ) {
							echo '<br />';
                  }
						$i++;
					}

					if ( $desc != '' ) {
                  echo '<p class="description">' . $desc . '</p>';
               }
					break;

				case 'textarea':
					echo '<textarea class="' . $field_class . '" id="' . $id . '" name="fba_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

					if ( $desc != '' ) {
                  echo '<p class="description">' . $desc . '</p>';
               }
					break;

				case 'password':
					echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="fba_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';

					if ( $desc != '' ) {
						echo '<p class="description">' . $desc . '</p>';
               }
					break;

				case 'text':
				default:
					echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="fba_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';

					if ( $desc != '' ) {
						echo '<p class="description">' . $desc . '</p>';
               }
					break;
			}

		}

		// Define all settings for this plugin and their defaults
		public function get_settings() {

			// The actual settings fields...

			$this->settings['flickr_api_key'] = array(
				'section' => 'flickr_api',
				'title'   => __( 'Flickr API Key', $this->plugin_slug ),
				'desc'    => __( 'Paste in your Flickr API Key.', $this->plugin_slug ),
				'type'    => 'text',
				'std'     => ''
			);
			$this->settings['thumb_link_class'] = array(
				'section' => 'flickr_api',
				'title'   => __( 'Custom CSS Class', $this->plugin_slug ),
				'desc'    => __( 'Optionally, add a class to the link that wraps your image thumbs (e.g. "fancybox").', $this->plugin_slug ),
				'type'    => 'text',
				'std'     => ''
			);

		}

		// Initialize the settings to their default values
		public function initialize_settings() {

			$default_settings = array();
			foreach ( $this->settings as $id => $setting ) {
				$default_settings[$id] = $setting['std'];
			}

			update_option( 'fba_options', $default_settings );

		}

		// Callback for the Flickr API section
		public function display_flickr_api_section() {
			echo '<p>' . __( 'To use this plugin, you\'ll need to get a ', $this->plugin_slug ) .'<a href="http://www.flickr.com/services/apps/create/apply/">'. __('Flickr API key', $this->plugin_slug ) . '</a>.</p>';
		}

		// Callback for future sections that don't have descriptions
		public function display_section() {
			// The default section echos nothing for the description
		}

		// Register settings by group and validate the them
		public function admin_init() {

			register_setting( 'fba_options', 'fba_options', array( &$this, 'validate_settings' ) );

			foreach ( $this->sections as $slug => $title ) {
				if ( $slug == 'flickr_api' ) {
               add_settings_section( $slug, $title, array( &$this, 'display_flickr_api_section' ), 'fba-options' );
            } else {
               add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'fba-options' );
            }
			}

			$this->get_settings();

			foreach ( $this->settings as $id => $setting ) {
				$setting['id'] = $id;
				$this->create_setting( $setting );
			}

		}

		// Validate the settings input before saving
		public function validate_settings( $input ) {
			// Create array for storing the validated options
			$output = array();

			// Loop through each of the incoming options
			foreach( $input as $key => $value ) {

				// Check to see if the current option has a value and then process it
				if( isset( $input[$key] ) ) {
					$output[$key] = strip_tags( stripslashes( $input[$key] ) );
				}

			}

			// - add settings error if the user doesn't save an API key -
			if ( $input[ 'flickr_api_key' ] == '' ) {
				add_settings_error( 'flickr_api_key', 'flickr_api_key_error', __( 'You\'ll need to enter an API key below for the shortcodes to work.', $this->plugin_slug ) );
			}

			return apply_filters( 'validate_settings', $output, $input );
		}

	} // end class Flickr_by_Albums_Settings
} // end 'if class exists'

/**
 * Get the plugin options (to be used in other plugin files)
 *
 * @since    1.0.0
 */

function fba_option( $option ) {
	$options = get_option( 'fba_options' );
	if ( isset( $options[$option] ) ) {
      return $options[$option];
   } else {
      return false;
   }
}
