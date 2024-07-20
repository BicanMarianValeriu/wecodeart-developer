<?php
/**
 * WeCodeArt Dev 
 *
 * @package     WeCodeArt Dev 
 * @subpackage 	Functions
 * @copyright   Copyright (c) 2024, WeCodeArt Dev
 * @link        https://www.wecodeart.com/
 * @since       1.0.0 
 * @version     2.1.5
 * 
 */
namespace WeCodeArt\Dev;

// Child theme (do not remove)
define( 'CHILD_THEME_NAME', 	'WeCodeArt Developer' );
define( 'CHILD_THEME_URL', 		'https://www.wecodeart.com/' );
define( 'CHILD_THEME_VERSION', 	'2.1.5' ); 
define( 'CHILD_THEME_NS',       'WeCodeArt\Dev' );
define( 'CHILD_THEME_INC', 	    __DIR__ . '/inc' );

// Start the engine
require_once( get_parent_theme_file_path( '/inc/init.php' ) ); 
new \WeCodeArt\Autoloader( CHILD_THEME_NS, CHILD_THEME_INC ); 

/**
 * Skin Assets
 */
\add_action( 'init', __NAMESPACE__ . '\\assets', 20, 1 );
function assets() {
    \wecodeart( 'assets' )->add_script( 'wecodeart-developer', [
        'path' => get_file( 'js', 'frontend' ),
        'deps' => [ 'wecodeart-support-assets' ]
    ] );
    
    \wecodeart( 'assets' )->add_style( 'wecodeart-developer', [
        'path' => get_file( 'css', 'frontend' )
    ] );

    \add_editor_style( get_file( 'css', 'frontend' ) );
}

/**
 * Get File
 */
function get_file( $type, $name ) {
    $file_path = wecodeart_if( 'is_dev_mode' ) ? 'unminified' : 'minified';
    $file_path .= '/' . strtolower( $type ) . '/';
    $file_path .= wecodeart_if( 'is_dev_mode' ) ? $name . '.' . $type :  $name . '.min.' . $type;

    return esc_url( get_stylesheet_directory_uri() . '/assets/' . $file_path );
}