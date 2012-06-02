<?php
/*
	Plugin Name: Compact Archives Widget
	Description: Create a widget for Compact Archives plugin
	Plugin URI: http://www.aldolat.it/wordpress/wordpress-plugins/compact-archives-widget/
	Author: Aldo Latino
	Author URI: http://www.aldolat.it/
	Domain Path: /languages
	Text Domain: caw-domain
	Version: 0.3
*/

/*
	Copyright (C) 2010  Aldo Latino  (email : aldolat@gmail.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Check if the main plugin Compact Archives is active
 * otherwise display a notice
 *
 * @since 0.1
 */

add_action( 'admin_init', 'caw_init' );

function caw_init() {
	if ( ! is_plugin_active( 'compact-archives/compact.php' ) ) {
		add_action( 'admin_notices', create_function( '', "echo '<div class=\"error\"><p>".sprintf( __( '"Compact Archive" plugin is not active on your WordPress. Please, <a href="%s">install from here</a>: search for "Compact Archives", click on Install, and activate it.', 'caw-domain' ), admin_url( 'plugin-install.php' ) )."</p></div>';" ) );
	}
}

add_action( 'widgets_init', 'caw_load_widget' );

/**
 * Register the widget
 *
 * @since 0.1
 */

function caw_load_widget() {
	register_widget( 'CAW_Widget' );
}

/**
 * Create the widget
 *
 * @since 0.1
 */

class CAW_Widget extends WP_Widget {
	function CAW_Widget() {
		$widget_ops = array(
			'classname'   => 'caw_widget',
			'description' => __( 'Create a widget for Compact Archives plugin', 'caw-domain' )
		);
		$this->WP_Widget( 'caw-widget', __( 'Compact Archives Widget', 'caw-domain' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$widget_style = $instance['style'];
		if( $instance['text_style'] == 'uppercase' ) {
			$text_style = ' style="text-transform: uppercase;"';
		} elseif( $instance['text_style'] == 'capitalize' ) {
			$text_style = ' style="text-transform: capitalize;"';
		} else {
			$text_style = '';
		}

		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		echo '<ul class="compact-archives"' . $text_style . '>';
			if ( function_exists( 'compact_archive' ) ) {
				compact_archive( $style = $widget_style );
			}
		echo '</ul>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['style'] = $new_instance['style'];
		$instance['text_style'] = $new_instance['text_style'];
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => __( 'Archives by Month', 'caw-domain' ),
			'style' => 'initial'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$style = $instance['style'];
		$text_style = $instance['text_style'];
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">
					<?php _e( 'Title:', 'caw-domain' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'style' ); ?>">
					<?php _e( 'Select the style:', 'caw-domain' ); ?>
				</label><br />
				<select name="<?php echo $this->get_field_name( 'style' ); ?>" >
					<option <?php selected( 'initial', $style ); ?> value="initial">
						<?php _e( 'Initials', 'caw-domain' ); ?>
					</option>
					<option <?php selected( 'block', $style ); ?> value="block">
						<?php _e( 'Block', 'caw-domain' ); ?>
					</option>
					<option <?php selected( 'numeric', $style ); ?> value="numeric">
						<?php _e( 'Numeric', 'caw-domain' ); ?>
					</option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'text_style' ); ?>">
					<?php _e( 'Transform text:', 'caw-domain' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name( 'text_style' ); ?>" >
					<option <?php selected( 'None', $text_style ); ?> value="none">
						<?php _e( 'None transformation', 'caw-domain' ); ?>
					</option>
					<option <?php selected( 'uppercase', $text_style ); ?> value="uppercase">
						<?php _e( 'UPPERCASE', 'caw-domain' ); ?>
					</option>
					<option <?php selected( 'capitalize', $text_style ); ?> value="capitalize">
						<?php _e( 'Capitalize', 'caw-domain' ); ?>
					</option>
				</select>
			</p>
		<?php
	}
}

/**
 * Make plugin available for i18n
 * Translations must be archived in the /languages directory
 *
 * @since 0.1
 */

function caw_load_languages() {
	load_plugin_textdomain( 'caw-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
}

add_action( 'init', 'caw_load_languages' );

/***********************************************************************
 *                            CODE IS POETRY
 **********************************************************************/