<?php
/**
 * Class Employer
 * Class to manager employer data.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Model;

use Muslimarkt\Abstracts\User;
use Muslimarkt\Result;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Model\Employer' ) ) {

	/**
	 * Class User
	 *
	 * @package Muslimarkt
	 */
	final class Employer extends User {
		use Result;

		/**
		 * User detail fields.
		 *
		 * @var array
		 */
		protected $user_fields = array(
			'company',
			'user_key',
			'recruiter',
			'is_profile_complete',
		);

		/**
		 * User constructor.
		 *
		 * @param int|bool $user_id id of the user.
		 * @param array $args args which contains information to create a new user.
		 * @param bool $no_fetch whether skip to get user details value.
		 */
		public function __construct( $user_id = false, $args = array(), $no_fetch = false ) {
			parent::__construct( $user_id, $args, $no_fetch );

			// If the class is called upon creating a new user.
			if ( ! $user_id ) {

				// Add filter to modify user details.
				add_filter( 'muslimarkt_new_user_generic_details', array( $this, 'manage_generic_details' ), 10, 2 );
			}
		}

		/**
		 * Callback for modifying user details.
		 *
		 * @param array $user_details default user details.
		 * @param int $new_user id od newly created user.
		 *
		 * @return array
		 */
		public function manage_generic_details( $user_details, $new_user ) {

			// Add a new user field.
			$user_details['recruiter'] = true;

			return $user_details;
		}
	}
}