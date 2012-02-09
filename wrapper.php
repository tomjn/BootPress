<?php $base = app_template_base(); ?>
 
<?php get_header( $base ); ?>
 
<section id="primary">
	<div id="content" role="main">
 
	<?php include app_template_path(); ?>
 
	</div><!-- #content -->
</section><!-- #primary -->
 
<?php get_sidebar( $base ); ?>
<?php get_footer( $base ); ?>