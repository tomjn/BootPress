<?php

// Include the class
require_once( 'wp-less/wp-less.php' );

// enqueue a .less style sheet
if ( ! is_admin() )
    wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/lib/bootstrap/less/bootstrap.less' );

	
	function app_template_path() {
	return APP_Wrapping::$main_template;
}

function app_template_base() {
	return APP_Wrapping::$base;
}


class APP_Wrapping {

	/**
	 * Stores the full path to the main template file
	 */
	static $main_template;

	/**
	 * Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	 */
	static $base;

	static function wrap( $template ) {
		self::$main_template = $template;

		self::$base = substr( basename( self::$main_template ), 0, -4 );

		if ( 'index' == self::$base )
			self::$base = false;

		$templates = array( 'wrapper.php' );

		if ( self::$base )
			array_unshift( $templates, sprintf( 'wrapper-%s.php', self::$base ) );

		return locate_template( $templates );
	}
}

add_filter( 'template_include', array( 'APP_Wrapping', 'wrap' ), 99 );