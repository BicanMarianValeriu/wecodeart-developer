<?php
/**
 * WeCodeArt Dev 
 *
 * @package		WeCodeArt Dev 
 * @subpackage	Scripts
 * @copyright	Copyright (c) 2022, WeCodeArt Dev
 * @link		https://www.wecodeart.com/
 * @since		2.1.2
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
		add_action( 'wp_enqueue_scripts',	[ $this, 'enqueue_assets' ] );
	}

    /**
	 * Skin Assets
	 */
	public function enqueue_assets() {
		// Enqueue Styles
		wp_enqueue_style( $this->make_handle(), self::get_file( 'css', 'frontend' ), [], wecodeart( 'version' ) );

		// Enqueue Scripts
		wp_enqueue_script( $this->make_handle(), self::get_file( 'js', 'frontend' ), [
	 		'wp-hooks'
		], wecodeart( 'version' ), true );
	}

 	/**
	 * Get File
	 */
	public static function get_file( $type, $name ) {
		$file_path = wecodeart_if( 'is_dev_mode' ) ? 'unminified' : 'minified';
		$file_path .= '/' . strtolower( $type ) . '/';
		$file_path .= wecodeart_if( 'is_dev_mode' ) ? $name . '.' . $type :  $name . '.min.' . $type;

		return esc_url( get_stylesheet_directory_uri() . '/assets/' . $file_path );
	}
}