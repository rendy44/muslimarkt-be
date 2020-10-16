<?php
/**
 * Job post type class.
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

if ( ! class_exists( 'Muslimarkt\Type\Job' ) ) {

	/**
	 * Class Job
	 *
	 * @package Muslimarkt\Type
	 */
	final class Job extends Type {
		use Singleton;

		/**
		 * Override slug variable.
		 *
		 * @var string
		 */
		protected $slug = 'job';

		/**
		 * Override post type args.
		 *
		 * @var array
		 */
		protected $args = array(
			'label'              => 'Jobs',
			'menu_icon'          => 'dashicons-clipboard',
			'publicly_queryable' => false,
		);
	}

	Job::init();
}
