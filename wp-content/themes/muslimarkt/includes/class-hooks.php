<?php
/**
 * Hooks class.
 *
 * @author Rendy
 * @package Muslimarkt
 */

namespace Muslimarkt;

use Muslimarkt\Emails\Verification;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Hooks' ) ) {

	/**
	 * Class Hooks
	 *
	 * @package Muslimarkt
	 */
	final class Hooks {
		use Singleton;

		/**
		 * Hooks constructor.
		 */
		private function __construct() {

			// Send verification email.
			add_action( 'muslimarkt_after_creating_user', array( $this, 'send_verification_email' ), 10, 2 );
		}

		/**
		 * Callback for sending verification email to newly registered user.
		 *
		 * @param WP_User $new_user object of newly created user.
		 * @param array $user_details array of additional user details.
		 */
		public function send_verification_email( WP_User $new_user, array $user_details ) {

			// Instance a new mailer.
			$mailer = new Verification( $new_user->user_email, $user_details['user_key'] );

			// Send the email.
			$mailer->send();
		}
	}

	Hooks::init();
}