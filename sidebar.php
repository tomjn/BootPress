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
