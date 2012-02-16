<?php

global $customoptions;
$left = $customoptions->get_option_value('sidebar_left');

$base = app_template_base();
get_header( $base );

if($left == 'yes'){
	get_sidebar($base);
}

include app_template_path();

if($left != 'yes'){
	get_sidebar( $base );
}
get_footer( $base );
