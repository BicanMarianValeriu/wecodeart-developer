<?php
/**
 * WeCodeArt Dev Starter.
 *
 * This file adds functions to the WeCodeArt Framework.
 *
 * @package WeCodeArt Developer Starter Kit
 * @author  Bican Marian Valeriu
 * @license GPL-2.0+
 * @link    https://www.wecodeart.com/
 */

// Child theme (do not remove)
define( 'CHILD_THEME_NAME', 	'WeCodeArt Dev Starter' );
define( 'CHILD_THEME_URL', 		'https://www.wecodeart.com/' );
define( 'CHILD_THEME_VERSION', 	'1.0.0' );

// Start the engine
include_once( get_template_directory() . '/inc/init.php' );

// Enqueue Scripts and Styles
add_action( 'wp_enqueue_scripts', 'enqueue_scripts_styles' );
function enqueue_scripts_styles() {
	$is_local = ( $_SERVER['SERVER_ADDR'] == '127.0.0.2' ) ? true : false;
	$is_build = ( $is_local === false ) ? 'build/' : '';

	$bundle_deps = [ 'jquery' ]; 

	wp_enqueue_script( 'babel-polyfill', '//cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.2.5/polyfill.min.js', [], null, true  );
	wp_script_add_data( 'babel-polyfill', 'conditional', 'IE' );  

	foreach( [ 'css', 'js' ] as $dir ) {

		$buildDir = new \DirectoryIterator( STYLESHEETPATH . '/assets/' . $is_build . $dir ); 

		foreach( $buildDir as $file ) {

			$fullName = basename( $file );
			$name = substr( basename( $fullName ), 0, strpos( basename( $fullName ), '.' ) );

			if ( pathinfo( $file, PATHINFO_EXTENSION ) === 'css' ) { 
				wp_register_style( $name, wecodeart_get_asset_uri( $file, 'css' ) , [], null );
				wp_enqueue_style( $name ); 
			}

			if ( pathinfo( $file, PATHINFO_EXTENSION ) === 'js' ) {  
				wp_register_script( $name, wecodeart_get_asset_uri( $file, 'js' ), $bundle_deps, null, true );
				wp_enqueue_script( $name ); 
			}
		} 
	}
}

add_action( 'init', 'wecodeart_clean_head' );
function wecodeart_clean_head() {
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

add_filter( 'style_loader_src',     'wecodeart_remove_queries', 10, 2 );
add_filter( 'script_loader_src',    'wecodeart_remove_queries', 10, 2 );	
function wecodeart_remove_queries( $src ) {
    if( strpos( $src, '?ver=' ) ) $src = remove_query_arg( 'ver', $src );
    return $src;
}

/**
 * Return either local dev asset file or the one from build
 */
function wecodeart_get_asset_uri( $file, $directory ) { 
	$is_local = ( $_SERVER['SERVER_ADDR'] == '127.0.0.2' ) ? true : false;
	$is_build = ( $is_local === false ) ? 'build/' : '';

	if( ! $file && ! $type ) return;  
	return esc_url( get_theme_file_uri( '/assets/' . $is_build . $directory . '/' . basename( $file ) ) ); 
}