<?php
global $customoptions;
$left = $customoptions->get_option_value('sidebar_left');
if($left != 'yes'){
?>
	</div><!-- #content -->
</section><!-- #primary -->
<?php
}
?>
<div id="sidebar">

<?php
if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar') ) {
	?>
	<div class="well widget widget_pages">
		<h2 class="title">Pages:</h2>
		<ul>
			<?php wp_list_pages('title_li=');?>
		</ul>
	</div>

	<?php
}?>
</div>
<?php
if($left == 'yes'){
?>
<section id="primary">
	<div id="content" role="main">
<?php
}