<?php
/**
 * Class User
 * Class to basic user data..
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Model;

use Muslimarkt\Result;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Model\User' ) ) {

	/**
	 * Class User
	 *
	 * @package Muslimarkt
	 */
	final class User extends \Muslimarkt\Abstracts\User {
		use Result;

		/**
		 * User detail fields.
		 *
		 * @var array
		 */
		protected $user_fields = array(
			'user_key',
			'is_profile_complete',
			'active',
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
		}

		/**
		 * Activate user account.
		 */
		public function activate_account() {
			$this->save_meta( 'active', true );
		}

		/**
		 * Set user as an employee.
		 */
		public function set_as_employee() {
			$this->save_meta( 'recruiter', false );
		}

		/**
		 * Set user as an employer.
		 */
		public function set_as_employer() {
			$this->save_meta( 'recruiter', true );
		}
	}
}