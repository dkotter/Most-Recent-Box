<?php
namespace MRB\Core;

/**
 * Setup our needed hooks.
 *
 * @since 0.1.0
 *
 * @return void
 */
function setup() {
	add_action( 'init',               __NAMESPACE__ . '\\load_textdomain' );
	add_action( 'after_setup_theme',  __NAMESPACE__ . '\\add_image_size' );
	add_action( 'save_post',          __NAMESPACE__ . '\\regenerate_cache' );
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\load_styles' );

	add_filter( 'the_content',        __NAMESPACE__ . '\\add_most_recent_box' );

	// Allow additional functionality to be run after the plugin is loaded
	do_action( 'mrb_loaded' );
}

/**
 * Load our text domain.
 *
 * @since 0.1.0
 *
 * @return void
 */
function load_textdomain() {
	load_plugin_textdomain( 'mrb', false, basename( dirname( __FILE__ ) ) . '/languages' );
};

/**
 * Add our custom image size.
 *
 * @since 0.1.0
 *
 * @return void
 */
function add_image_size() {
	\add_image_size( 'mrb-thumb', 186, 150, true );
}

/**
 * Regenerate our cache.
 *
 * @since 0.1.0
 *
 * @param int $post_id ID of post being saved.
 * @return void
 */
function regenerate_cache( $post_id = 0 ) {
	get_recent_posts( $post_id, $force_refresh = true );
}

/**
 * Enqueue styles for front-end.
 *
 * @since 0.1.0
 *
 * @return void
 */
function load_styles() {
	// Only run this on single views of supported post types
	if ( ! is_front_page() && is_singular( (array) supported_post_types() ) ) {
		wp_enqueue_style(
			'mrb',
			MRB_URL . "/assets/css/most-recent-box.min.css",
			array(),
			MRB_VERSION
		);
	}
}

/**
 * Add the most recent box to the end of the content.
 *
 * @since 0.1.0
 *
 * @param string $content Current post content
 * @return string
 */
function add_most_recent_box( $content = '' ) {
	// Only run this on single views of supported post types
	if ( is_front_page() || ! is_singular( (array) supported_post_types() ) ) {
		return $content;
	}

	$return = '';

	// Get the most recent items
	$recent = get_recent_posts( get_the_ID() );

	// If we have something, let's output it
	if ( $recent && is_array( $recent ) ) {

		// Remove the current item from our list, if it's there
		$pos = array_search( get_the_ID(), $recent, true );
		if ( false !== $pos ) {
			unset( $recent[ $pos ] );
		}

		// Make sure we only return one item
		$recent = ! empty( $recent ) ? array_shift( $recent ) : false;

		// Setup our post object
		global $post;
		$post = get_post( $recent );
		setup_postdata( $post );

		// Need to append our output with $content, so start output buffering
		ob_start();

		// Load our template. Allow this to be overridden by themes
		\MRB\Utilities\get_template_part( 'box' );

		$return = ob_get_clean();

		wp_reset_postdata();
	}

	return $content . $return;
}

/**
 * Get a list of supported post types.
 *
 * Run this through a filter, so users can choose
 * which post types will get the most recent box.
 *
 * @since 0.1.0
 *
 * @return array
 */
function supported_post_types() {
	$post_types = get_post_types( array( 'public' => true ) );

	/**
	 * Filters the supported post types.
	 *
	 * @since 0.1.0
	 *
	 * @param array $post_types The public post types on the site
	 */
	return apply_filters( 'mrb_supported_post_types', $post_types );
}

/**
 * Grab the post_id of the most recent items we want to display.
 *
 * We only want to display one item, but because we don't want
 * to show the same post twice, we grab two items. Then our
 * display logic will only show one. This allows us to cache
 * this data easier but not have to worry about showing duplicates.
 *
 * @since 0.1.0
 *
 * @param int $post_id ID of post we are on.
 * @param bool $force_refresh Force a refresh or not. Default false.
 * @return array|bool
 */
function get_recent_posts( $post_id = 0, $force_refresh = false ) {
	$post_type = get_post_type( $post_id );

	// Grab our data from the cache
	$cache_key = "mrb_most_recent_{$post_type}";
	$return    = wp_cache_get( $cache_key );
	$current   = $post_id === $return;

	// If not in cache or we want to force a refresh, regenerate the data
	if ( true === $force_refresh || false === $return ) {
		// Run our query to get the most recent posts (or pages, CPTs, etc)
		$query = new \WP_Query( array(
			'posts_per_page'      => 2,
			'post_type'           => $post_type,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
		) );

		// If we have something, let's use it
		if ( $query->have_posts() ) {
			$return = $query->posts;

			// Cache our data
			if ( $return ) {
				// No expiration is set as we clear this on save
				wp_cache_set( $cache_key, $return );
			}
		}
	}

	/**
	 * Filter the post IDs we return.
	 *
	 * @since 0.1.0
	 *
	 * @param array $return The post IDs
	 * @param int $post_id ID of current post
	 */
	return apply_filters( 'mrb_recent_post', $return, $post_id );
}
