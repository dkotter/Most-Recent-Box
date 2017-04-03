<?php
/**
 * Useful Utility Functions
 */

namespace MRB\Utilities;

/**
 * Retrieves a template part.
 *
 * @since 0.1.0
 *
 * @param string $slug Slug of template to load.
 * @param string $name Name of template to load. Optional. Default null.
 * @param bool $load If true the template file will be loaded if it is found. Optional. Default true.
 * @return string
 */
function get_template_part( $slug = '', $name = null, $load = true ) {
	/**
	 * Allow additional functionality to be run when the template is being loaded.
	 *
	 * @since 0.1.0
	 *
	 * @param string $slug The template slug.
	 * @param string $name The template name. Default null.
	 */
	do_action( 'get_template_part_' . $slug, $slug, $name );

	// Setup possible parts
	$templates = array();
	if ( isset( $name ) ) {
		$templates[] = $slug . '-' . $name . '.php';
	}
	$templates[] = $slug . '.php';

	/**
	 * Allow templates to be filtered.
	 *
	 * @since 0.1.0
	 *
	 * @param array $templates The templates we want to load.
	 * @param string $slug The template slug.
	 * @param string $name The template name. Default null.
	 */
	$templates = apply_filters( 'mrb_get_template_part', $templates, $slug, $name );

	// Return the part that is found
	return locate_template( $templates, $load, false );
}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
 * inherit from a parent theme can just overload one file. If the template is
 * not found in either of those, it looks in the plugin last.
 *
 * @since 0.1.0
 *
 * @param array $template_names Template file(s) to search for, in order.
 * @param bool $load If true the template file will be loaded if it is found. Default false.
 * @param bool $require_once Whether to require_once or require. Default true.
 * @return string
 */
function locate_template( $template_names = array(), $load = false, $require_once = true ) {
	// No file found yet
	$located = false;

	// Try to find a template file
	foreach ( (array) $template_names as $template_name ) {

		// Continue if template is empty
		if ( empty( $template_name ) ) {
			continue;
		}

		// Trim off any slashes from the template name
		$template_name = ltrim( $template_name, '/' );

		// Check child theme first
		if ( file_exists( trailingslashit( get_stylesheet_directory() ) . 'mrb/' . $template_name ) ) {
			$located = trailingslashit( get_stylesheet_directory() ) . 'mrb/' . $template_name;
			break;

		// Check parent theme next
		} elseif ( file_exists( trailingslashit( get_template_directory() ) . 'mrb/' . $template_name ) ) {
			$located = trailingslashit( get_template_directory() ) . 'mrb/' . $template_name;
			break;

		// Check plugin last
		} elseif ( file_exists( MRB_PATH . 'templates/' . $template_name ) ) {
			$located = MRB_PATH . 'templates/' . $template_name;
			break;
		}
	}

	if ( ( true === $load ) && ! empty( $located ) ) {
		load_template( $located, $require_once );
	}

	return $located;
}

/**
 * Output our byline area.
 *
 * @return void
 */
function posted_on() {
	// Get the author name; wrap it in a link.
	$byline = sprintf(
		/* translators: %s: post author */
		__( 'By: %s', 'mrb' ),
		'<span class="author vcard"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	// Finally, let's write all of this to the page.
	echo '<span class="author"> ' . $byline . '</span> <span class="time">' . time_stamp() . '</span>';
}

/**
 * Output our time stamp.
 *
 * @return string
 */
function time_stamp() {
	return sprintf(
		/* translators: %s: human readable date diff */
		esc_html__( '%1$s ago', 'mrb' ),
		esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) )
	);
}
