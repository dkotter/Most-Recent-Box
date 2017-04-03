<?php
/**
 * Plugin Name: Most Recent Box
 * Plugin URI:  https://darinkotter.com
 * Description: Show the most recent story on each post
 * Version:     0.1.0
 * Author:      Darin Kotter
 * Author URI:  https://darinkotter.com
 * Text Domain: mrb
 * Domain Path: /languages
 * 
 */

/**
 * Built using yo wp-make:plugin
 * Copyright (c) 2017 10up, LLC
 * https://github.com/10up/generator-wp-make
 */

// Useful global constants
define( 'MRB_VERSION', '0.1.0' );
define( 'MRB_URL',     plugin_dir_url( __FILE__ ) );
define( 'MRB_PATH',    dirname( __FILE__ ) . '/' );
define( 'MRB_INC',     MRB_PATH . 'includes/' );

// Include files
require_once MRB_INC . 'functions/core.php';
require_once MRB_INC . 'functions/utilities.php';

// Bootstrap
MRB\Core\setup();
