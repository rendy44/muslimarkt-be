<?php
/**
 * Class for basic authentication.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt;

use WP_REST_Request;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Auth' ) ) {

	/**
	 * Class Auth
	 *
	 * @package Muslimarkt
	 */
	final class Auth {
		use Result;

		/**
		 * Id of the user.
		 *
		 * @var int
		 */
		public $user_id = 0;

		/**
		 * Array args variable.
		 *
		 * @var array|mixed
		 */
		private $array_args = array();

		/**
		 * Detail slug variable.
		 *
		 * @var string
		 */
		private $detail_slug = '';

		/**
		 * User key variable.
		 *
		 * @var string
		 */
		private $user_key;

		/**
		 * User email variable.
		 *
		 * @var string
		 */
		private $user_email;

		/**
		 * User password variable.
		 *
		 * @var string
		 */
		private $user_password;

		/**
		 * Checked object variable
		 *
		 * @var null|Result
		 */
		private $checked_obj;

		/**
		 * Success message variable.
		 *
		 * @var mixed
		 */
		private $success_message;

		/**
		 * Error message variable.
		 *
		 * @var mixed
		 */
		private $error_message;

		/**
		 * Success callback variable.
		 *
		 * @var bool|callback
		 */
		private $callback = false;

		/**
		 * Force callback variable.
		 *
		 * @var bool
		 */
		private $force_callback = true;

		/**
		 * Auth constructor.
		 *
		 * @param WP_REST_Request $request request object.
		 * @param bool $use_param whether use params or body to be parsed.
		 */
		public function __construct( $request, $use_param = false ) {

			// Get decoded params.
			$this->array_args = $use_param ? $request->get_params() : json_decode( $request->get_body(), true );

			// Extract args.
			$this->extract_args();
		}

		/**
		 * Get args from the request.
		 *
		 * @return array|mixed
		 */
		public function get_args() {
			return $this->array_args;
		}

		/**
		 * Get detail slug arg from the request.
		 *
		 * @return string
		 */
		public function get_detail_slug() {
			return $this->detail_slug;
		}

		/**
		 * Validate request.
		 */
		public function validate() {

			// Check whether key is provided or not.
			// If yes, get user id based on that kay.
			if ( $this->user_key ) {

				// Find user by key.
				$user = $this->get_user_by_key();

				// Validate the user.
				if ( ! $user ) {
					$this->message[] = __( 'Anda tidak diperbolehkan melakukan aksi ini.', 'muslimarkt' );
				} else {

					// Everything is looking good.
					$this->is_error = false;
					$this->user_id  = $user->ID;
				}
			} else {

				// Validate the inputs.
				if ( ! $this->user_email || ! filter_var( $this->user_email, FILTER_VALIDATE_EMAIL ) ) {
					$this->message[] = __( 'Mohon masukkan email yang valid', 'muslimarkt' );
				}
				if ( ! $this->user_password ) {
					$this->message[] = __( 'Mohon masukkan kata sandi', 'muslimarkt' );
				}

				// Make sure no errors occur.
				if ( empty( $this->message ) ) {

					// Find the user.
					$user = $this->get_user_by_email( $this->user_email );

					// Validate the user.
					if ( ! $user ) {
						$this->message[] = __( 'Email yang Anda gunakan salah', 'muslimarkt' );
					} else {

						// Prepare the args for user to login.
						$credentials = array(
							'user_login'    => $this->user_email,
							'user_password' => $this->user_password,
							'remember'      => true
						);

						// Do login;
						$login = wp_signon( $credentials );

						// Validate login.
						if ( is_wp_error( $login ) ) {
							$this->message[] = 'incorrect_password' === $login->get_error_code() ? __( 'Kata sandi yang Anda masukkan salah' ) : $login->get_error_message();
						} else {

							// Everything is looking good.
							$this->is_error = false;
							$this->user_id  = $login->ID;
						}
					}
				}
			}

			$this->parse_api();
		}

		/**
		 * Update obj that will be validated.
		 *
		 * @param bool|Result $obj checked obj.
		 */
		public function update_checked_obj( $obj = false ) {

			// Override object if empty.
			$this->checked_obj = false !== $obj ? $obj : $this;
		}

		/**
		 * Parse api result.
		 *
		 * @param bool|Result|mixed $obj object that will be validated.
		 */
		public function parse_api( $obj = false ) {

			// Update checked obj.
			$this->update_checked_obj( $obj );

			// Validate api to decide api response.
			if ( $this->checked_obj->is_error ) {

				// Send error json.
				wp_send_json_error( $this->maybe_get_api_content() );
			} else {

				// Check maybe it has callback.
				if ( false !== $this->callback ) {
					$cb_func = $this->callback;

					// Trigger callback.
					$cb_func();

					// Check maybe callback is not forced.
					if ( ! $this->force_callback ) {

						// Send success json.
						wp_send_json_success( $this->maybe_get_api_content() );
					}
				} else {

					// No callback, send success json.
					wp_send_json_success( $this->maybe_get_api_content() );
				}
			}
		}

		/**
		 * Save callback;
		 *
		 * @param callback $callback callback function.
		 * @param bool $force_stop whether callback completely override success or not.
		 */
		public function success_callback( $callback, $force_stop = true ) {
			$this->callback       = $callback;
			$this->force_callback = $force_stop;
		}

		/**
		 * Get api content on success.
		 *
		 * @param mixed $content content on success.
		 */
		public function content_on_success( $content ) {
			$this->success_message = $content;
		}

		/**
		 * Get api content on error.
		 *
		 * @param mixed $content content on error.
		 */
		public function content_on_error( $content ) {
			$this->error_message = $content;
		}

		/**
		 * Maybe get api content.
		 *
		 * @return bool|mixed|string
		 */
		private function maybe_get_api_content() {
			$default_content = $this->checked_obj->is_error ? $this->error_message : $this->success_message;

			return $default_content ? $default_content : $this->message;
		}

		/**
		 * Extract authentication args.
		 */
		private function extract_args() {

			// Prepare default args.
			$default_args = array(
				'key'      => false,
				'email'    => false,
				'password' => false
			);

			// Parse args.
			$this->array_args = wp_parse_args( $this->array_args, $default_args );

			// Save individual args.
			$this->detail_slug   = $this->array_args['slug'];
			$this->user_key      = $this->array_args['key'];
			$this->user_email    = $this->array_args['email'];
			$this->user_password = $this->array_args['password'];
		}

		/**
		 * Get user by email address.
		 *
		 * @param string $email email address.
		 *
		 * @return bool|WP_User
		 */
		private function get_user_by_email( $email ) {
			return get_user_by_email( $email );
		}

		/**
		 * Get user by key.
		 *
		 * @return bool|WP_User
		 */
		private function get_user_by_key() {

			// Convert user key into email.
			$maybe_email = helper::encrypt( $this->user_key, true );

			// Find user by its email.
			return $this->get_user_by_email( $maybe_email );
		}
	}
}