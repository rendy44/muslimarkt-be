<?php
/**
 * Login api class.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Rest;

use Muslimarkt\Abstracts\Rest;
use Muslimarkt\Auth;
use Muslimarkt\Model\Employee;
use Muslimarkt\Singleton;
use Muslimarkt\Model\User;
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
		 * Override privilege variable.
		 *
		 * @var bool
		 */
		protected $is_require_key = false;

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

			// Create callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Get user detail.
					$user = new Employee( $auth->user_id );

					// Re-validate.
					$auth->content_on_success( $user->items );
				},
				false
			);
			$auth->validate();
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

		/**
		 * Callback for getting detail method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		function get_detail_callback( $request ) {
			// TODO: Implement get_detail_callback() method.
		}
	}

	Login::init();
}