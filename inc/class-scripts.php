<?php
/**
 * WeCodeArt Dev 
 *
 * @package		WeCodeArt Dev 
 * @subpackage	Scripts
 * @copyright	Copyright (c) 2023, WeCodeArt Dev
 * @link		https://www.wecodeart.com/
 * @since		2.1.3
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
		wecodeart( 'assets' )->add_script( $this->make_handle(), [
			'path' => self::get_file( 'js', 'frontend' ),
			'deps' => [ 'wecodeart-support-assets' ]
		] );
		
		wecodeart( 'assets' )->add_style( $this->make_handle(), [
			'path' => self::get_file( 'css', 'frontend' )
		] );
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