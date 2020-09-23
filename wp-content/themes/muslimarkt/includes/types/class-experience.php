<?php
/**
 * Experience post type class.
 *
 * @author Rendy
 * @package Muslimarkt
 */

namespace Muslimarkt\Type;

use Muslimarkt\Abstracts\Type;
use Muslimarkt\Singleton;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Type\Experience' ) ) {

	/**
	 * Class Experience
	 *
	 * @package Muslimarkt\Type
	 */
	final class Experience extends Type {
		use Singleton;

		/**
		 * Override slug variable.
		 *
		 * @var string
		 */
		protected $slug = 'experience';

		/**
		 * Override post type args.
		 *
		 * @var array
		 */
		protected $args = array(
			'label'              => 'Experiences',
			'menu_icon'          => 'dashicons-nametag',
			'publicly_queryable' => false,
		);
	}

	Experience::init();
}
