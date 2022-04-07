<?php
/**
 * WeCodeArt Dev 
 *
 * @package		WeCodeArt Dev 
 * @subpackage	Scripts
 * @copyright	Copyright (c) 2022, WeCodeArt Dev
 * @link		https://www.wecodeart.com/
 * @since		2.1.1
 */

namespace WeCodeArt\Dev;

use WeCodeArt\Singleton;
use WeCodeArt\Config\Traits\Asset;

/**
 * Scripts
 */
class Scripts {

	use Singleton;
	use Asset;

	/**
	 * Send Construtor
	 */
	public function init() {
		add_filter( 'wecodeart/filter/head/clean', '__return_true' );
		add_action( 'wp_enqueue_scripts',	[ $this, 'enqueue_scripts_styles' ] );
	}

    /**
	 * Skin Assets
	 */
	public function enqueue_scripts_styles() {
		// Enqueue Styles
		wp_enqueue_style( $this->make_handle(), $this->get_asset( 'css', 'frontend' ), [], wecodeart( 'version' ) );

		// Enqueue Scripts
		wp_enqueue_script( $this->make_handle(), $this->get_asset( 'js', 'frontend' ), [
	 		'wp-hooks'
		], wecodeart( 'version' ), true );
	}
}