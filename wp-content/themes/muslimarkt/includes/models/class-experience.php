<?php
/**
 * Class experience.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Experience' ) ) {

	/**
	 * Class Experience
	 *
	 * @package Muslimarkt
	 */
	final class Experience extends Post {

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
		 * Experience constructor.
		 *
		 * @param Auth $auth
		 * @param bool $post_id
		 * @param array $args
		 */
		public function __construct( $auth, $post_id = false, $args = array() ) {
			parent::__construct( $auth, $post_id, $args );
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