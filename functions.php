<?php
/**
 * WeCodeArt Dev 
 *
 * @package     WeCodeArt Dev 
 * @subpackage 	Functions
 * @copyright   Copyright (c) 2023, WeCodeArt Dev
 * @link        https://www.wecodeart.com/
 * @since       1.0.0 
 * @version     2.1.3
 * 
 */

// Child theme (do not remove)
define( 'CHILD_THEME_NAME', 	'WeCodeArt Developer' );
define( 'CHILD_THEME_URL', 		'https://www.wecodeart.com/' );
define( 'CHILD_THEME_VERSION', 	'2.1.3' ); 
define( 'CHILD_THEME_NS',       'WeCodeArt\Dev' );
define( 'CHILD_THEME_INC', 	    __DIR__ . '/inc' );

// Start the engine
require_once( get_parent_theme_file_path( '/inc/init.php' ) ); 
new WeCodeArt\Autoloader( CHILD_THEME_NS, CHILD_THEME_INC ); 

// Load Skin DEPS 
WeCodeArt\Dev\Scripts::get_instance(); 