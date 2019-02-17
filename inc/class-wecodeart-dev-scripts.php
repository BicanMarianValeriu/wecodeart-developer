<?php namespace WeCodeArt\Dev; 
/**
 * WeCodeArt Dev 
 *
 * @package 		WeCodeArt Dev 
 * @subpackage 	Scripts
 * @copyright   Copyright (c) 2019, WeCodeArt Dev
 * @link    		https://www.wecodeart.com/
 * @since				1.0.0 
 * 
 */
 
class Scripts {  
	/**
	 * Instance
	 *
	 * @access 	private
	 * @var 	object
	 */
	private static $instance;

	/**
	 * Initiator
	 */
	public static function get_instance() {
		if( ! isset( self::$instance ) ) self::$instance = new self;
		return self::$instance;
	}
	
    /**
	 * Construtor
	 */
	public function __construct() {
		$this->is_local = ( isset( $_SERVER['SERVER_ADDR'] ) && $_SERVER['SERVER_ADDR'] === '127.0.0.1' ) ? true : false;
		$this->is_build = ( $this->is_local === false ) ? 'build/' : ''; 

		add_action( 'init',					[ $this, 'clean_head' ] );
		add_action( 'wp_head', 				[ $this, 'google_font' ] ); 
		add_action( 'wp_enqueue_scripts',	[ $this, 'enqueue_scripts_styles' ] );
		add_action( 'wp_print_styles', 		[ $this, 'deregister_styles' ], 100 ); 
		add_filter( 'style_loader_src',     [ $this, 'remove_queries' ], 10, 2 );
		add_filter( 'script_loader_src',    [ $this, 'remove_queries' ], 10, 2 );	 
    }

    /**
	 * Skin Assets
	 */
	public function enqueue_scripts_styles() {
		$bundle_deps = [ 'jquery' ]; 

		wp_enqueue_script( 'babel-polyfill', '//cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.2.5/polyfill.min.js', [], null, true  );
		wp_script_add_data( 'babel-polyfill', 'conditional', 'IE' ); 

		foreach( [ 'css', 'js' ] as $dir ) {

			$buildDir = new \DirectoryIterator( STYLESHEETPATH . '/assets/' . $this->is_build . $dir ); 

			foreach( $buildDir as $file ) { 

				$fullName = basename( $file );
				$name = substr( basename( $fullName ), 0, strpos( basename( $fullName ), '.' ) );

				if ( pathinfo( $file, PATHINFO_EXTENSION ) === 'css' ) { 
					wp_register_style( $name, $this->get_asset_uri( $file, 'css' ) , [], null );
					wp_enqueue_style( $name ); 
				}
	
				if ( pathinfo( $file, PATHINFO_EXTENSION ) === 'js' ) {  
					wp_register_script( $name, $this->get_asset_uri( $file, 'js' ), $bundle_deps, null, true );
					wp_enqueue_script( $name ); 
				}
			} 
		}
	}

    /**
     * Get Asset File URL
     * 
     * @param   string  $file
     * @param   string  $directory
     * 
     */
	public function get_asset_uri( $file, $directory ) { 
		if( ! $file && ! $type ) return;  
		return esc_url( get_theme_file_uri( '/assets/' . $this->is_build . $directory . '/' . basename( $file ) ) ); 
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
		remove_action( 'wp_head',               'wlwmanifest_link'                  );
		remove_action( 'wp_head',               'rsd_link'                          );
		remove_action( 'wp_head',               'feed_links', 2                     );
		remove_action( 'wp_head',               'feed_links_extra', 3               );
		remove_action( 'wp_head',               'wp_generator'                      );
		remove_action( 'wp_head',               'wp_shortlink_wp_head'              );
		remove_action( 'wp_head',               'wp_oembed_add_discovery_links'     );
		remove_action( 'wp_head',               'rest_output_link_wp_head'          );
		remove_action( 'template_redirect',     'rest_output_link_header', 11, 0    );
		remove_action( 'wp_head',               'print_emoji_detection_script', 7   );
		remove_action( 'wp_print_styles',       'print_emoji_styles'                );	
	}  

	/**
	 * Google Fonts
	 */
	public function google_font() { 
	?>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600|Open+Sans:300,400,700|Shadows+Into+Light">
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