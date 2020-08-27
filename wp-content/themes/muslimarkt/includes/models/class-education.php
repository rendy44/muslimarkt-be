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
			'country',
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
		 * @param bool|int $post_id id of the post.
		 */
		public function __construct( $user_id, $post_id = false ) {
			parent::__construct( $user_id, $post_id );
		}

		public function get_details() {

			// Get education base detail.
			$this->get_base_details();

			// Get additional details.
			$details = $this->get_metas( $this->used_fields );

			// Merge details.
			$this->items = array_merge( $this->items, $details );
		}

		private function get_base_details() {
			$this->items['id']   = $this->post->ID;
			$this->items['slug'] = $this->post->post_name;
		}

		/**
		 * Save education detail.
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