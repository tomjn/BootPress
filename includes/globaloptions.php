<?php

if ( !class_exists( 'Global_Options' ) ) {
	class Global_Options {
		var $options = array();
		var $namespace = '';

		function Global_Options($namespace){
			$this->namespace = $namespace;
			add_action('admin_init', array(&$this,'register_settings'));
			add_action('admin_menu', array(&$this, 'add_theme_options_menu'));
			//$this->retrieve_form_options();
		}

		function register_settings(){
			register_setting($this->namespace, $this->namespace, array(&$this,'sanitize_settings') );
		}

		function sanitize_settings( $in_settings ){
			return $in_settings;
		}

		function add_theme_options_menu() {
			add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', $this->namespace, array(&$this,'settings_page') );
		}

		function settings_page(){
			?>
			<div class="wrap">
			<?php
			screen_icon();
			?>
			<h2><?php echo get_current_theme() . ' '; _e('Theme Options'); ?></h2>
			<form method="post" action="options.php" enctype="multipart/form-data">
			<?php settings_fields($this->namespace); // important! ?>

				<table class="form-table">
					<?php
					echo $this->get_option_form_fields();
					?>
				</table>
				<p class="submit">
					<input type="submit" class="button-primary" value="Save Changes" />
				</p>
			</div>
			<?php
		}

		function set_namespace($namespace){
			$this->namespace = $namespace;
		}

		function register_option($args){
			if(!isset($args['name'])){
				$args['name'] = sanitize_title_with_dashes($args['human_name']);
			}
			$name = $args['name'];
			$args['value'] = $this->get_option_value($name);
			$this->options[$name] = $args;
			return $args;
		}

		function get_option_value($name){
			$settings = get_option($this->namespace);
			$value = $settings[$name];
			$this->options[$name]['value'] = $value;
			return $value;
		}

		function set_option($name,$value){
			$this->options[$name]['value'] = $value;
			update_option($this->option_form_name($name),$value);
		}

		function option_form_name($name){
			return $this->namespace.'['.$name.']';
		}

		function get_option_form_fields(){
			$output = '';
			if(!empty($this->options)){
				foreach($this->options as $key => $option){
					$output .= $this->get_option_form_field($option);
				}
			}
			return $output;
		}

		function get_option_form_field($option){
			$value = $option['value'];//$this->get_option_value($name);
			$name = $option['name'];
			$option_name = $this->option_form_name($name);
			$output = '<tr valign="top"><th scope="row">';
			$output .= '<label for="'.$this->namespace.'_'.$option['name'].'">'.$option['human_name'].'</label></th><td>';

			//
			$tagtype = 'input';
			if($option['type'] == 'textarea'){
				$tagtype = 'textarea rows="8"';
			}

			$output .= '<'.$tagtype.' ';
			$output .= 'id="'.$this->namespace.'_'.$option['name'].'" ';
			$output .= 'type="'.$option['type'].'" ';
			$output .= 'name="'.$option_name.'" ';
			if($option['type'] == 'checkbox'){
				$output .= 'value="yes" ';
				$output .= checked('yes',$value,false);
			} else if($option['type'] != 'textarea'){
				$output .= 'value="'.$value.'" ';
			}
			$output .= 'placeholder="'.$option['placeholder'].'" ';
			$class = '';
			if($option['type'] == 'text'){
				$class= 'widefat';
			} else if($option['type'] == 'textarea'){
				$class = 'large-text code';
			}
			$output .= 'class="'.$class.'"';
			$output .= '>';
			if($option['type'] == 'textarea'){
				$output .= $value;
				$output .= '</textarea>';
			}

			if(!empty($option['description'])){
				$output .= '<p><span class="description">'.$option['description'].'</span></p>';
			}
			$output .= '</td></tr>';
			return $output;
		}


		function extra_editor($content, $id) { ?>
			<script type="text/javascript">
				/* <![CDATA[ */
				jQuery(document).ready( function () {
					jQuery("#<?php echo $id; ?>").addClass("mceEditor");
						 if ( typeof( tinyMCE ) == "object" && typeof( tinyMCE.execCommand ) == "function" ) {
							 tinyMCE.settings.theme_advanced_buttons1 += ",|,add_image,add_video,add_audio,add_media,|,code";
							 tinyMCE.execCommand("mceAddControl", false, "<?php echo $id; ?>");
					}
				});
				/* ]]> */
			</script>
			<div class="wysiwyg_field">
				<textarea class="<?php echo $id; ?>" name="<?php $this->item_attrib( $id, 'value', true); ?>" id="<?php echo $id; ?>"><?php echo apply_filters('the_content', $content); ?>
				</textarea>
			</div><?php
		}

	};
}