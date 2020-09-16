<?php
/**
 * Email abstract class.
 *
 * @author Rendy
 * @package Muslimarkt
 */

namespace Muslimarkt\Abstracts;

use Muslimarkt\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Abstracts\Email' ) ) {

	/**
	 * Class Email
	 *
	 * @package Muslimarkt\Abstracts
	 */
	abstract class Email {

		/**
		 * Email recipients variable.
		 *
		 * @var array
		 */
		private $recipients;

		/**
		 * Email subject variable.
		 *
		 * @var string
		 */
		protected $subject = '';

		/**
		 * Email headers variable.
		 *
		 * @var array
		 */
		protected $headers = array();

		/**
		 * Additional args variable.
		 *
		 * @var string|array|object|int|mixed
		 */
		protected $args;

		/**
		 * Email constructor.
		 *
		 * @param string|array $recipients recipients of the email.
		 * @param string|array|object|int|mixed $args additional passed data.
		 */
		public function __construct( $recipients = array(), $args = false ) {

			// Save recipients.
			$this->recipients = $recipients;

			// Save property.
			$this->args = $args;

			// Send email as html.
			add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
		}

		/**
		 * Callback for setting email content type.
		 *
		 * @return string
		 */
		public function set_html_content_type() {
			return 'text/html';
		}

		/**
		 * Get plain body.
		 */
		protected abstract function plain_body();

		/**
		 * Get html content header.
		 */
		protected function header() {
			Helper::get_template( 'email/header' );
		}

		/**
		 * Get html content footer.
		 */
		protected function footer() {
			Helper::get_template( 'email/footer' );
		}

		/**
		 * Get formatted email body.
		 *
		 * @return false|string
		 */
		private function get_formatted_body() {
			ob_start();

			$this->header();

			/**
			 * Muslimarkt before plain email body action hook.
			 */
			do_action( 'muslimarkt_before_plain_email_body' );

			$this->plain_body();

			/**
			 * Muslimarkt after plain email body action hook.
			 */
			do_action( 'muslimarkt_after_plain_email_body' );

			$this->footer();

			return ob_get_clean();
		}

		/**
		 * Get formatted email subject.
		 *
		 * @return mixed|void
		 */
		private function get_formatted_subject() {
			$subject = sprintf( '%s - %s', get_bloginfo( 'name' ), $this->subject );

			/**
			 * Muslimarkt email subject filter hook.
			 *
			 * @param string $subject current email subject.
			 * @param Email $this object of the current mailer.
			 */
			return apply_filters( 'muslimarkt_email_subject', $subject, $this );
		}

		/**
		 * Process send the email.
		 */
		public function send() {
			wp_mail( $this->recipients, $this->get_formatted_subject(), $this->get_formatted_body(), $this->headers );

			// Remove filter.
			remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
		}
	}
}
