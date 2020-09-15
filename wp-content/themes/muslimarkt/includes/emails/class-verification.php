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
		 * @param string|array $recipients email recipients.
		 */
		public function __construct( $recipients = array() ) {
			parent::__construct( $recipients );
		}

		/**
		 * Get plain body.
		 */
		protected function plain_body() {
			Helper::get_template( 'emails/plain/verification' );
		}
	}
}