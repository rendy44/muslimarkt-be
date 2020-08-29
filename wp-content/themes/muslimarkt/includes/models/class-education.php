<?php
/**
 * Class education.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Model;

use Muslimarkt\Post;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Model\Education' ) ) {

	/**
	 * Class Education
	 *
	 * @package Muslimarkt
	 */
	final class Education extends Post {

		/**
		 * Education allowed meta.
		 *
		 * @var array
		 */
		protected $used_fields = array(
			'position',
			'company',
			'month_start',
			'year_start',
			'month_end',
			'year_end',
			'still_working',
			'role',
			'industry',
			'overseas',
			'province',
			'notes',
		);

		/**
		 * Override post type variable.
		 *
		 * @var string
		 */
		public $post_type = 'education';

		/**
		 * Education constructor.
		 *
		 * @param int $user_id id of the user.
		 * @param bool|string|int|WP_Post $post_tag object of WP_Post, id of the post or slug of the post.
		 * @param array $args args to create post.
		 */
		public function __construct( $user_id, $post_tag = false, $args = array() ) {
			parent::__construct( $user_id, $post_tag, $args );
		}
	}
}