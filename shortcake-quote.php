<?php
/**
 * Plugin Name: Shortcake - Quote
 * Version: 1.0
 * Description: Adds [shortcake_quote] shortcode to WordPress to use with Shortcode UI (Shortcake)
 * Author: Cross Media Cloud
 * Author URI: http://www.cross-media-cloud.de
 * Text Domain: shortcake_quote
 * License: GPL v3 or later
 *
 * This plugin is based on the "Shortcode UI Example" by Fusion Engineering and community
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

/*
 * Load the translation
 */
load_plugin_textdomain(
	'shortcake_quote',
	false,
	'shortcake-quote/languages'
);

/*
 * The function it self
 */
add_action( 'init', 'dr_shortcake_quote_function' );
function  dr_shortcake_quote_function() {

	/*
	 * Check if Shortcode UI plugin is active
	 */
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		add_action( 'admin_notices', function () {
			if ( current_user_can( 'activate_plugins' ) ) {
				echo '<div class="error message"><p>' . __( 'Shortcode UI plugin must be active for Shortcode UI Example plugin to function.', 'shortcake_quote' ) . '</p></div>';
			}
		} );

		return;
	}

	/**
	 * Register your shortcode as you would normally.
	 * This is a simple example for a pullquote with a citation.
	 */
	add_shortcode( 'shortcake_quote', function( $attr, $content = '' ) {
		$attr = wp_parse_args( $attr, array(
			'source'     => '',
			'attachment' => 0
		) );
		ob_start();
		?>

		<blockquote>
			<?php
			echo wpautop( wp_kses_post( $content ) );
			if ( isset( $attr['source'] ) AND 0 < strlen( $attr['source'] ) ) { ?>
				<footer>
					<cite><?php echo esc_html( $attr['source'] ); ?></cite>
				</footer>
			<?php } ?>
		</blockquote>

		<?php
		return ob_get_clean();
	} );


	/**
	 * Register a UI for the Shortcode.
	 * Pass the shortcode tag (string)
	 * and an array or args.
	 */
	shortcode_ui_register_for_shortcode(
		'shortcake_quote',
		array(
			// Display label. String. Required.
			'label'         => __( 'Shortcake Quote', 'shortcake_quote' ),
			// Icon/attachment for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
			'listItemImage' => 'dashicons-editor-quote',
			'inner_content' => array(
				'label' => __( 'Quote', 'shortcake_quote' ),
			),
			'post_type'     => array( 'post', ),
			// Available shortcode attributes and default values. Required. Array.
			// Attribute model expects 'attr', 'type' and 'label'
			// Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
			'attrs'         => array(
				array(
					'label' => __( 'Cite', 'shortcake_quote' ),
					'attr'  => 'source',
					'type'  => 'text',
					'meta'  => array(
						'placeholder' => __( 'Who said that?', 'shortcake_quote' ),
						'data-test'   => 1,
					),
				),
			),
		)
	);
}