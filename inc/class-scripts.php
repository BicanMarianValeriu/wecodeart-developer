<?php
/**
 * WeCodeArt Dev 
 *
 * @package		WeCodeArt Dev 
 * @subpackage	Scripts
 * @copyright	Copyright (c) 2019, WeCodeArt Dev
 * @link		https://www.wecodeart.com/
 * @since		1.2.1
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

		add_action( 'init',					[ $this, 'clean_head' ] );
		add_action( 'wp_head',				[ $this, 'google_font' ] ); 
		add_action( 'wp_enqueue_scripts',	[ $this, 'enqueue_scripts_styles' ] );
		add_action( 'wp_print_styles', 		[ $this, 'deregister_styles' ], 100 ); 
		add_filter( 'style_loader_src',		[ $this, 'remove_queries' ], 10, 2 );
		add_filter( 'script_loader_src',	[ $this, 'remove_queries' ], 10, 2 );	 
	}

    /**
	 * Skin Assets
	 */
	public function enqueue_scripts_styles() {
		$data = [
			'version' 		=> wecodeart( 'version' ),
			'dependencies'	=> [ 'jquery' ],
		];

		// CSS
		$deps = sprintf( '%s/assets/css/%s.php', get_stylesheet_directory(), 'frontend.asset' );
		if( is_readable( $deps ) ) {
			$file = require $deps;
			$data = wp_parse_args( $file, $data );
		}

		wp_register_style(
			$this->make_handle(), 
			get_stylesheet_directory_uri() . '/assets/css/frontend.css',
			[],
			$data['version']
		);

		wp_enqueue_style( $this->make_handle() );

		// JS
		$deps = sprintf( '%s/assets/js/%s.php', get_stylesheet_directory(), 'frontend.asset' );
		
		if( is_readable( $deps ) ) {
			$file = require $deps;
			$data = wp_parse_args( $file, $data );
		}

		wp_register_script( 
			$this->make_handle(),
			get_stylesheet_directory_uri() . '/assets/js/frontend.js',
			$data['dependencies'], 
			$data['version'], 
			true 
		);

		wp_enqueue_script( $this->make_handle() );
	}
    
    /**
	 * Deregister CSS
	 */
	public function deregister_styles() {
		wp_deregister_style( 'wecodeart-core' ); 
		wp_dequeue_style( 'wecodeart-core' );
		//wp_deregister_style( 'wp-block-library' );
		//wp_dequeue_style( 'wp-block-library' ); 
	}
	
	/**
	 * Clean Header
	 */
	public function clean_head() {
		// Clean Header of unnecesary code
		remove_action( 'wp_head',			'wlwmanifest_link'                  );
		remove_action( 'wp_head',			'rsd_link'                          );
		remove_action( 'wp_head',			'feed_links', 2                     );
		remove_action( 'wp_head',			'feed_links_extra', 3               );
		remove_action( 'wp_head',			'wp_generator'                      );
		remove_action( 'wp_head',			'wp_shortlink_wp_head'              );
		remove_action( 'wp_head',			'wp_oembed_add_discovery_links'     );
		remove_action( 'wp_head',			'rest_output_link_wp_head'          );
		remove_action( 'template_redirect',	'rest_output_link_header', 11, 0    );
		remove_action( 'wp_head',			'print_emoji_detection_script', 7   );
		remove_action( 'wp_print_styles',	'print_emoji_styles'                );	
	}  

	/**
	 * Google Fonts
	 */
	public function google_font() { 
		$font_url = 'https://fonts.googleapis.com/css?family=Montserrat:300,400,600|Open+Sans:300,400,700|Shadows+Into+Light';
	?>
		<link rel="stylesheet" href="<?php echo esc_url( $font_url ); ?>">
	<?php
	}

	/**
	 * Remove query var
	 */
	public function remove_queries( $src ) {
		if( strpos( $src, '?ver=' ) ) $src = remove_query_arg( 'ver', $src );
		return $src;
	}
}