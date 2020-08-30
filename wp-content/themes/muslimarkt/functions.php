<?php
/**
 * Main file of the theme.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants.
defined( 'TEMP_DIR' ) || define( 'TEMP_DIR', get_template_directory() );
defined( 'TEMP_URI' ) || define( 'TEMP_URI', get_template_directory_uri() );
defined( 'TEMP_PATH' ) || define( 'TEMP_PATH', get_theme_file_path() );
defined( 'TEMP_PREFIX' ) || define( 'TEMP_PREFIX', 'mslmrkt_' );

// Require the dependency classes.
require_once TEMP_DIR . '/includes/traits/class-result.php';
require_once TEMP_DIR . '/includes/traits/class-singleton.php';
require_once TEMP_DIR . '/includes/class-helper.php';
require_once TEMP_DIR . '/includes/class-auth.php';
require_once TEMP_DIR . '/includes/class-setting.php';
require_once TEMP_DIR . '/includes/abstracts/class-post.php';
require_once TEMP_DIR . '/includes/abstracts/class-type.php';
require_once TEMP_DIR . '/includes/abstracts/class-rest.php';
require_once TEMP_DIR . '/includes/abstracts/class-user.php';
require_once TEMP_DIR . '/includes/types/class-experience.php';
require_once TEMP_DIR . '/includes/types/class-education.php';
require_once TEMP_DIR . '/includes/api/class-register.php';
require_once TEMP_DIR . '/includes/api/class-login.php';
require_once TEMP_DIR . '/includes/api/class-experience.php';
require_once TEMP_DIR . '/includes/api/class-education.php';
require_once TEMP_DIR . '/includes/api/class-user.php';
require_once TEMP_DIR . '/includes/models/class-query.php';
require_once TEMP_DIR . '/includes/models/class-employee.php';
require_once TEMP_DIR . '/includes/models/class-employer.php';
require_once TEMP_DIR . '/includes/models/class-experience.php';
require_once TEMP_DIR . '/includes/models/class-education.php';
