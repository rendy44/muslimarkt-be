<?php
/**
 * Class experience.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Model;

use Muslimarkt\Auth;
use Muslimarkt\Post;

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
			'country',
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
		 * @param Auth $auth
		 * @param bool $post_id
		 */
		public function __construct( $auth, $post_id = false ) {
			parent::__construct( $auth, $post_id );
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