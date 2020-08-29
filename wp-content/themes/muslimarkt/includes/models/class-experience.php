<?php
/**
 * Class experience.
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
		private $used_fields = array(
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
		public $post_type = 'experience';

		/**
		 * Experience constructor.
		 *
		 * @param int $user_id id of the user.
		 * @param bool|string|int|WP_Post $post_tag object of WP_Post, id of the post or slug of the post.
		 * @param array $args args to create post.
		 */
		public function __construct( $user_id, $post_tag = false, $args = array() ) {
			parent::__construct( $user_id, $post_tag, $args );
		}

		/**
		 * Get experience details.
		 */
		public function get_details() {

			// Get experience base detail.
			$this->get_base_details();

			// Get additional details.
			$details = $this->get_metas( $this->used_fields );

			// Merge details.
			$this->items = array_merge( $this->items, $details );
		}

		/**
		 * Get post base details.
		 */
		private function get_base_details() {
			$this->items['id']   = $this->post->ID;
			$this->items['slug'] = $this->post->post_name;
		}

		/**
		 * Save experience detail.
		 *
		 * @param array $args associate key=>value detail.
		 */
		public function save_details( $args ) {

			// Loop all possible fields.
			foreach ( $args as $arg_key => $arg_value ) {

				// Make sure, field is allowed.
				if ( in_array( $arg_key, $this->used_fields, true ) ) {

					// Update the field.
					$this->save_meta( $arg_key, $arg_value );
				}
			}
		}
	}
}