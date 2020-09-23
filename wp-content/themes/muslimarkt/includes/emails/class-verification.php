<?php
/**
 * Email verification class.
 *
 * @author Rendy
 * @package Muslimarkt
 */

namespace Muslimarkt\Emails;

use Muslimarkt\Abstracts\Email;
use Muslimarkt\Helper;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Emails\Verification' ) ) {

	/**
	 * Class Verification
	 *
	 * @package Muslimarkt\Emails
	 */
	final class Verification extends Email {

		/**
		 * Override subject variable.
		 *
		 * @var string
		 */
		protected $subject = 'Selamat Datang';

		/**
		 * Verification constructor.
		 *
		 * @param string $recipient email recipients.
		 * @param string $user_key user key.
		 */
		public function __construct( $recipient, $user_key ) {
			parent::__construct( $recipient, $user_key );
		}

		/**
		 * Get plain body.
		 */
		protected function plain_body() {
			Helper::get_template( 'email/plain/verification', array( 'key' => $this->args ) );
		}
	}
}
