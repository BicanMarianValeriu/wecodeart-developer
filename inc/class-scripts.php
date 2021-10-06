<?php
/**
 * WeCodeArt Dev 
 *
 * @package		WeCodeArt Dev 
 * @subpackage	Scripts
 * @copyright	Copyright (c) 2019, WeCodeArt Dev
 * @link		https://www.wecodeart.com/
 * @since		1.3.0
 */

namespace WeCodeArt\Dev;

/**
 * Scripts
 */
class Scripts {

	use \WeCodeArt\Singleton;
	use \WeCodeArt\Core\Scripts\Base;

	/**
	 * Send Construtor
	 */
	public function init() {
		add_filter( 'wecodeart/filter/head/clean', '__return_true' );
		add_action( 'wp_enqueue_scripts',	[ $this, 'enqueue_scripts_styles' ] );
		add_action( 'wp_print_styles', 		[ $this, 'deregister_styles' ], 100 ); 
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
    
    /**
	 * Deregister CSS
	 */
	public function deregister_styles() {
		wp_deregister_style( 'wecodeart-core-scripts' ); 
		wp_dequeue_style( 'wecodeart-core-scripts' );
		//wp_deregister_style( 'wp-block-library' );
		//wp_dequeue_style( 'wp-block-library' ); 
	}
}