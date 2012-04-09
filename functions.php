<?php


if ( file_exists(dirname( __FILE__ ).'/includes/globaloptions.php') ) {
	include_once(dirname( __FILE__ ).'/includes/globaloptions.php');
}
// enqueue a .less style sheet
if ( ! is_admin() ){
	// Include the class
	require_once( 'wp-less/wp-less.php' );
	add_filter( 'less_vars', 'default_bootstrap_less_vars', 10, 2 );
	function default_bootstrap_less_vars( $vars, $handle ) {
		// $handle is a reference to the handle used with wp_enqueue_style()
		$vars[ 'textColor' ] = '@grayDark';
		
		//$vars[ '' ] = '';
		
		// Navbar
		$vars[ 'navbarHeight' ] = '40px';
		$vars[ 'navbarBackground' ] = '@grayDarker';
		$vars[ 'navbarBackgroundHighlight' ] = '@grayDark';
		
		$vars[ 'navbarText' ] = '@grayLight';
		$vars[ 'navbarLinkColor' ] = '@grayLight';
		$vars[ 'navbarLinkColorHover' ] = '@white';

		return $vars;
	}
	wp_enqueue_style( 'animatecss', get_template_directory_uri() . '/lib/animate/animate.css' );
	
    wp_enqueue_style( 'lessstyle', get_template_directory_uri() . '/lessstyle.less' );
	if(is_child_theme()){
		wp_enqueue_style( 'childlessstyle', get_stylesheet_directory_uri() . '/lessstyle.less' );
	}
	
	
}

register_sidebar(array(
	'name' => 'Sidebar',
	'id' => 'sidebar',
	'description' => 'Sidebar.',
	'before_title' => '<h2 class="widget-title">',
	'after_title' => '</h2>',
	'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
	'after_widget'  => '</div>'
));

function register_my_menus() {
	register_nav_menus(
		array('header-menu' => __( 'Header Menu' ) )
	);
}
add_action( 'init', 'register_my_menus' );

function register_bootstrap_js() {
	wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
    wp_enqueue_script( 'jquery' );
	wp_register_script( 'bootstrap-dropdown', get_template_directory_uri().'/lib/bootstrap/js/bootstrap-dropdown.js', array('jquery'),'2.0');
	wp_enqueue_script( 'bootstrap-dropdown' );
	
	wp_register_script( 'bootpress-script', get_template_directory_uri().'/script.js', array('jquery'),'1.0');
	wp_enqueue_script( 'bootpress-script' );
}
if(!is_admin()){
	add_action( 'init', 'register_bootstrap_js' );
}

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


add_action( 'after_setup_theme', 'bootstrap_setup' );

if ( ! function_exists( 'bootstrap_setup' ) ){

	function bootstrap_setup(){
		
		
		add_filter('nav_menu_css_class',array('Bootstrap_Walker_Nav_Menu','add_classes'),1000,3);

		class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {
			
			function start_lvl( &$output, $depth ) {
				$indent = str_repeat( "\t", $depth );
				$output	   .= "\n$indent<ul class=\"sub-menu dropdown-menu\">\n";
			}
			
			public static function add_classes($classes , $item, $args){
				if($item->is_bootstrap == true){
					if ($item->is_dropdown){
						$classes[] 		= 'dropdown';
					}
					$classes[] = ($item->current || $item->current_item_parent) ? 'active' : '';
					if(in_array('current-product-ancestor',$classes)){
						$classes[] = 'active';
					}
				}
				return $classes;
			}
			
			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				if( 1 == $args->depth ) {
					parent::start_el( &$output, $item, $depth, $args );
					return;
				}
		
				$args_copy = (object) get_object_vars( $args );
		
				if ( $item->is_dropdown ) {
					$item->url = '#';
					$args_copy->link_after .= '<b class="caret"></b>';
				}
		
				if ( $depth > 1 ){
					$args_copy->link_before = str_repeat( '&nbsp;', $depth - 1 ) . ' ' . $args_copy->link_before;
				}
			
				$item_html = '';
				parent::start_el( &$item_html, $item, $depth, $args_copy );
				
				if ( $item->is_dropdown )
					$item_html = str_replace( '<a', '<a class="dropdown-toggle" data-toggle="dropdown"', $item_html );
				
				$output .= $item_html;
			}
			
			function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
				$element->is_bootstrap = true;
				$element->is_dropdown  = ( 0 == $depth ) && ! empty( $children_elements[$element->ID] );
				parent::display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output );
			}

		}
		
		class Bootstrap_Second_Level_Walker_Nav_Menu extends Walker_Nav_Menu {

			public $active_element = null;
			public $active_parent = null;

			function start_lvl( &$output, $depth ) {

				$indent = str_repeat( "\t", $depth );
				$output	   .= "\n$indent<ul class=\"menu nav\">\n";

			}

			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				
				if($item->current){

					$this->active_element = $item->ID;
					if($item->menu_item_parent == 0){
						$this->active_parent = $item->ID;
					}
					if(($depth == 0)&&($args->has_children == false)){
						return;
					}
				}
				
				if($item->current_item_parent == 1){
					$this->active_parent = $item->ID;
				}
				
				if($depth == 0){
					$dontdo = true;
				} else if((!$item->current)&&($item->menu_item_parent != $this->active_parent)){
					$dontdo = true;
				}
				
				if($dontdo){
					$item_output = $args->before;

					$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
					return;
				}
				
				if( 1 == $args->depth ) {
					parent::start_el( &$output, $item, $depth, $args );
					return;
				}
		
				$args_copy = (object) get_object_vars( $args );
		
				if ( $item->is_dropdown ) {
					$item->url = '#';
					$args_copy->link_after .= '<b class="caret"></b>';
				}
		
				if ( $depth > 1 ){
					$args_copy->link_before = str_repeat( '&nbsp;', $depth - 1 ) . ' ' . $args_copy->link_before;
				}
			
				$item_html = '';
				parent::start_el( &$item_html, $item, $depth, $args_copy );
				
				if ( $item->is_dropdown )
					$item_html = str_replace( '<a', '<a class="dropdown-toggle" data-toggle="dropdown"', $item_html );
				
				$output .= $item_html;
			}
			
			function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
				$element->is_bootstrap = true;
				$element->is_dropdown  = ( 0 == $depth ) && ! empty( $children_elements[$element->ID] );
				parent::display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output );
			}
		}
	}

}

function add_theme_settings(){
	global $customoptions;

	// create our options object
	$customoptions = new Global_Options('bootpress-options');

	// add/register our option fields
	// options appear in the order they're registered

	$customoptions->register_option(array(
		'name'			=>	'separated_navbar',
		'human_name'	=>	'Show navbar on its own (Dont use fill width)',
		'type'			=>	'checkbox',
		'placeholder'	=>	''
	));
	
	$customoptions->register_option(array(
		'name'			=>	'twostage_navbar',
		'human_name'	=>	'Show navbar on two levels',
		'type'			=>	'checkbox',
		'placeholder'	=>	''
	));
	
	$customoptions->register_option(array(
		'name'			=>	'sidebar_left',
		'human_name'	=>	'Show the sidebar on the left',
		'type'			=>	'checkbox',
		'placeholder'	=>	''
	));

}
add_action('init','add_theme_settings');