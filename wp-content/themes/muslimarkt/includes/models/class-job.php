<?php
/**
 * Class job.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Model;

use Muslimarkt\Abstracts\Post;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Model\Job' ) ) {

	/**
	 * Class Job
	 *
	 * @package Muslimarkt
	 */
	final class Job extends Post {

		/**
		 * Job allowed meta.
		 *
		 * @var array
		 */
		protected $used_fields = array(
			'position',
			'industry',
			'type',
			'role',
			'education',
			'salary',
			'description',
			'requirement',
			'notes',
		);

		/**
		 * Override post type variable.
		 *
		 * @var string
		 */
		public $post_type = 'job';

		/**
		 * Job constructor.
		 *
		 * @param int                     $user_id id of the user.
		 * @param bool|string|int|WP_Post $post_tag object of WP_Post, id of the post or slug of the post.
		 * @param array                   $args args to create post.
		 */
		public function __construct( $user_id, $post_tag = false, $args = array() ) {
			parent::__construct( $user_id, $post_tag, $args );
		}
	}
}
