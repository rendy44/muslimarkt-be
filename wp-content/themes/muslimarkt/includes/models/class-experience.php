<?php
/**
 * Class experience.
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

if ( ! class_exists( 'Muslimarkt\Model\Experience' ) ) {

	/**
	 * Class Experience
	 *
	 * @package Muslimarkt
	 */
	final class Experience extends Post {

		/**
		 * Experience allowed meta.
		 *
		 * @var array
		 */
		protected $used_fields = array(
			'position',
			'company',
			'date_start_month',
			'date_start_year',
			'date_end_month',
			'date_end_year',
			'date_end_cb',
			'role',
			'industry',
			'notes',
		);

		/**
		 * Override post type variable.
		 *
		 * @var string
		 */
		public $post_type = 'experience';

		/**
		 * Experience constructor.
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
