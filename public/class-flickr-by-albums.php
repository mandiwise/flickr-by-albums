<?php
/**
 * Flickr by Albums.
 *
 * @package   Flickr_by_Albums
 * @author    Mandi Wise <hello@mandiwise.com>
 * @license   GPL-2.0+
 * @link      http://mandiwise.com/
 * @copyright 2014 Mandi Wise
 */

class Flickr_by_Albums {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * The text domain for internationalizing strings of text.
	 *
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_slug = 'flickr-by-albums';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

      // Initiate the plugin settings class so we can use what's saved in those options.
      require_once( FBA_DIR . '/admin/views/settings.php' );
      $Flickr_by_Albums_Settings = new Flickr_by_Albums_Settings();

		// Define custom functionality.
		add_shortcode( 'flickr-album', array( $this, 'flickr_album_display' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 * @param    boolean    $network_wide
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_activate();
				}
				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 * @param    boolean    $network_wide
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_deactivate();
				}
				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */

	/*
	public function enqueue_scripts() {
		@TODO...
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}
	*/

	/**
	 * Public-facing functions.
	 *
	 * @since    1.0.0
	 */

	public function flickr_album_display( $atts ) {

		// Shortcode attributes
		extract( shortcode_atts(
			array(
				'id' => '',
				'size' => 'large',
			), $atts )
		);

		// Get the plugin and shortcode options
		$api_key = fba_option( 'flickr_api_key' );
		$custom_class = fba_option( 'thumb_link_class' );
		$album_id = esc_attr( $id );
      $has_class = !empty( $custom_class ) ? 'class="' . $custom_class . '"' : '';
      $thumb_size = $size == 'small' ? 'square' : 'square_150';

		// Check for the API key and set ID, and display the set photos if everything's OK
		if ( ! $api_key ) {

			$error = '<p style="color: red;"><em>'. __( 'Danger Will Robinson! You haven\'t saved your Flickr API key on the Flickr by Albums settings page.', $this->plugin_slug ) .'</em></p>';
			return $error;

		} elseif ( ! $album_id ) {

			$error = '<p style="color: red;"><em>'. __( 'Danger Will Robinson! Your shortcode is missing its Flickr album ID.', $this->plugin_slug ) .'</em></p>';
			return $error;

		} else {

			include_once( FBA_DIR . '/public/includes/phpFlickr/phpFlickr.php' );
			$f = new phpFlickr( $api_key );

			$photos = $f->photosets_getPhotos( $album_id );

			$content = '<ul class="flickr-photos">';
			foreach ( (array)$photos['photoset']['photo'] as $photo ) {
				$content .= '<li>';
				$content .= "<a " . $has_class . " href=" . $f->buildPhotoURL($photo, "large") . ">";
				$content .= "<img border='0' alt='$photo[title]' " . "src=" . $f->buildPhotoURL( $photo, "$thumb_size" ) . ">";
				$content .= '</a></li>';
			}
			$content .= '</ul><div class="clear"></div>';

			return $content;

		}

	}

} // end class Flickr_by_Albums
