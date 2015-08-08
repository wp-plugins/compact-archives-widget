<?php
/**
 * Plugin Name: Compact Archives Widget
 * Description: Create a widget for Compact Archives plugin
 * Plugin URI: http://dev.aldolat.it/projects/compact-archives-widget/
 * Author: Aldo Latino
 * Author URI: http://www.aldolat.it/
 * Version: 0.4.2
 * License: GPLv3 or later
 * Text Domain: caw-domain
 * Domain Path: /languages
 */

/*
 * Copyright (C) 2008, 2015  Aldo Latino  (email : aldolat@gmail.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Register the widget
 *
 * @since 0.1
 */
function caw_load_widget() {
	register_widget( 'CAW_Widget' );
}
add_action( 'widgets_init', 'caw_load_widget' );

/**
 * Create the widget
 *
 * @since 0.1
 */
class CAW_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'caw_widget',
			'description' => __( 'Create a widget for Compact Archives plugin', 'caw-domain' )
		);

		parent::__construct(
			'caw-widget',
			__( 'Compact Archives Widget', 'caw-domain' ),
			$widget_ops
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );

		switch ( $instance['text_style'] ) {
		case 'none' :
			$text_style = '';
			break;
		case 'uppercase' :
			$text_style = ' style="text-transform: uppercase;"';
			break;
		case 'capitalize':
			$text_style = ' style="text-transform: capitalize;"';
			break;
		}

		echo $before_widget;

		if ( $title ) echo $before_title . $title . $after_title; ?>

		<ul class="compact-archives"<?php echo $text_style; ?>>
			<?php if ( function_exists( 'compact_archive' ) ) {
				compact_archive( $style = $instance['style'] );
			} else { ?>
				<li>
					<?php printf( __( 'The %1$sCompact Archives%2$s plugin is not active. Please install it and activate it.', 'caw-domain' ),
				'<a href="http://wordpress.org/extend/plugins/compact-archives/">',
				'</a>' ); ?>
				</li>
			<?php } ?>
		</ul>

		<?php echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']      = strip_tags( $new_instance['title'] );
		$instance['style']      = $new_instance['style'];
		$instance['text_style'] = $new_instance['text_style'];
		return $instance;
	}

	public function form( $instance ) {
		$defaults = array(
			'title'      => __( 'Archives by Month', 'caw-domain' ),
			'style'      => 'initial',
			'text_style' => 'uppercase'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		if ( ! is_plugin_active( 'compact-archives/compact.php' ) ) { ?>
			<p style="background-color: #FFD5D5; padding: 10px;">
				<?php printf( __( '%3$sNotice.%4$s The main Compact Archive plugin is not active on your WordPress. Please, %1$sinstall from here%2$s: search for %3$sCompact Archives%4$s, click on Install, and activate it.', 'caw-domain' ),
					'<a href="' . admin_url( 'plugin-install.php' ) . '">',
					'</a>',
					'<strong>',
					'</strong>'
				); ?>
			</p>
		<?php } ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php _e( 'Title:', 'caw-domain' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'style' ); ?>">
				<?php _e( 'Select the style:', 'caw-domain' ); ?>
			</label><br />
			<select name="<?php echo $this->get_field_name( 'style' ); ?>" >
				<option <?php selected( 'initial', $instance['style'] ); ?> value="initial">
					<?php _e( 'Initials', 'caw-domain' ); ?>
				</option>
				<option <?php selected( 'block', $instance['style'] ); ?> value="block">
					<?php _e( 'Block', 'caw-domain' ); ?>
				</option>
				<option <?php selected( 'numeric', $instance['style'] ); ?> value="numeric">
					<?php _e( 'Numeric', 'caw-domain' ); ?>
				</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'text_style' ); ?>">
				<?php _e( 'Transform text:', 'caw-domain' ); ?>
			</label>
			<select name="<?php echo $this->get_field_name( 'text_style' ); ?>" >
				<option <?php selected( 'none', $instance['text_style'] ); ?> value="none">
					<?php _e( 'None transformation', 'caw-domain' ); ?>
				</option>
				<option <?php selected( 'uppercase', $instance['text_style'] ); ?> value="uppercase">
					<?php _e( 'UPPERCASE', 'caw-domain' ); ?>
				</option>
				<option <?php selected( 'capitalize', $instance['text_style'] ); ?> value="capitalize">
					<?php _e( 'Capitalize', 'caw-domain' ); ?>
				</option>
			</select>
		</p>
	<?php }
}

/**
 * Make plugin available for i18n
 * Translations must be archived in the /languages directory
 *
 * @since 0.1
 */

function caw_load_languages() {
	load_plugin_textdomain( 'caw-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'caw_load_languages' );

/***********************************************************************
 *                            CODE IS POETRY
 **********************************************************************/
