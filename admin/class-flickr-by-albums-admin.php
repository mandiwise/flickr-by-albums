<?php
/**
 * Flickr by Albums.
 *
 * @package   Flickr_by_Albums_Admin
 * @author    Mandi Wise <hello@mandiwise.com>
 * @license   GPL-2.0+
 * @link      http://mandiwise.com/
 * @copyright 2014 Mandi Wise
 */

class Flickr_by_Albums_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Call $plugin_slug from public plugin class.
		$plugin = Flickr_by_Albums::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add the options page.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Define custom functionality.
		add_action( 'fba_after_settings_form', array( $this, 'instructions_after_settings' ) );

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
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		// Add a settings page for this plugin to the Settings menu.
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Flickr by Albums', $this->plugin_slug ),
			__( 'Flickr by Albums', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(__( 'Sorry! You don\'t have sufficient permissions to access this page.', $this->plugin_slug ));
		}

		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * Admin-side functions.
	 *
	 * @since    1.0.0
	 */

	// Echo shortcode instructions after setting fields
	public function instructions_after_settings() {
	?>
		<h3><?php _e( 'Shortcode Usage Instructions:', $this->plugin_slug ); ?></h3>
		<p><?php _e( 'Once you\'ve saved your API key above, you can use the following shortcode throughout your site:', $this->plugin_slug ); ?></p>
		<pre><?php _e( 'e.g.', $this->plugin_slug ); ?> <code>[flickr-album id="1234567890" size="large"]</code></pre>
		<p><strong><?php _e( 'Please note:', $this->plugin_slug ); ?></strong></p>
		<ul>
			<li><code>id=""</code><?php _e( ' is required and the ID can be found in your browser\'s address bar when viewing the album in Flickr.', $this->plugin_slug ); ?></li>
			<li><code>size=""</code><?php _e( ' is optional and the thumbnail size may be "large" or "small" (defaults to "large" if not specified).', $this->plugin_slug ); ?></li>
		</ul>
	<?php
	}

} // end class Flickr_by_Albums_Admin
