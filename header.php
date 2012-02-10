<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	
	<!-- Use the .htaccess and remove these lines to avoid edge case issues.
		 More info: h5bp.com/i/378 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title><?php the_title();?></title>
	<meta name="description" content="">
	
	<!-- Mobile viewport optimized: h5bp.com/viewport -->
	<meta name="viewport" content="width=device-width">
	
	<!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
<?php
	wp_head();/*
	<link rel="stylesheet" href="css/style.css">
	
	<!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->
	
	<!-- All JavaScript at the bottom, except this Modernizr build.
	Modernizr enables HTML5 elements & feature detects for optimal performance.
	Create your own custom Modernizr build: www.modernizr.com/download/ -->
	<script src="js/libs/modernizr-2.5.2.min.js"></script> */ ?>
</head>
<body <?php body_class(); ?>>
	<header>
		<div class="navbar ">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
					<div class="nav-collapse">
						<?php
						if(has_nav_menu('header-menu')){
							$args = array(
								'theme_location'	=>	'header-menu',
								'depth'				=>	2,
								'walker'			=>	new Bootstrap_Walker_Nav_Menu(),
								'menu_class'      	=> 'menu nav'
							);
							wp_nav_menu( $args );
						}
						?>
					</div><!--/.nav-collapse -->
					
				</div>
			</div>
		</div>
	</header>
	<div class="container">
		<div role="main" id="main" class="row">
		