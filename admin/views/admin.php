<?php
/**
 * Represents the view for the administration dashboard.
 *
 * @package   Flickr_by_Albums
 * @author    Mandi Wise <hello@mandiwise.com>
 * @license   GPL-2.0+
 * @link      http://mandiwise.com/
 * @copyright 2014 Mandi Wise
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="post" action="options.php">
		<?php settings_fields( 'fba_options' ); ?>
		<?php do_settings_sections( 'fba-options' ); ?>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
		</p>
	</form>
	<?php do_action ( 'fba_after_settings_form' ); ?>

</div>
