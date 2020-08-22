<?php
/**
 * Login api class.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Rest;

use Muslimarkt\Auth;
use Muslimarkt\Singleton;
use Muslimarkt\User;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Rest\Login' ) ) {

	/**
	 * Class Login
	 *
	 * @package Muslimarkt
	 */
	final class Login extends Rest {
		use Singleton;

		/**
		 * Override endpoint url variable.
		 *
		 * @var string
		 */
		protected $endpoint = 'login';

		/**
		 * Override use post variable.
		 *
		 * @var bool
		 */
		protected $use_post = true;

		/**
		 * Callback for get method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		function get_callback( $request ) {
			// TODO: Implement get_callback() method.
		}

		/**
		 * Callback for post method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		function post_callback( $request ) {

			// Instance a new auth.
			$auth = new Auth( $request );
			$auth->validate();

			// Validate the process.
			if ( $auth->is_error ) {

				// Display error json.
				wp_send_json_error( $auth->message );
			} else {

				// Get user detail.
				$user = new User( $auth->user_id );

				// Display success json.
				wp_send_json_success( $user->items );
			}
		}

		/**
		 * Callback for delete method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		function delete_callback( $request ) {
			// TODO: Implement delete_callback() method.
		}

		/**
		 * Callback for put method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		function put_callback( $request ) {
			// TODO: Implement put_callback() method.
		}
	}

	Login::init();
}