<?php
/**
 * Education post type class.
 *
 * @author Rendy
 * @package Muslimarkt
 */

namespace Muslimarkt\Type;

use Muslimarkt\Singleton;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Type\Education' ) ) {

	/**
	 * Class Education
	 *
	 * @package Muslimarkt\Type
	 */
	final class Education extends Type {
		use Singleton;

		/**
		 * Override slug variable.
		 *
		 * @var string
		 */
		protected $slug = 'education';

		/**
		 * Override post type args.
		 *
		 * @var array
		 */
		protected $args = array(
			'label'     => 'Educations',
			'menu_icon' => 'dashicons-welcome-learn-more',
		);
	}

	Education::init();
}