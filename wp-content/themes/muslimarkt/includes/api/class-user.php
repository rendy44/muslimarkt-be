<?php
/**
 * User api class.
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
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Rest\User' ) ) {

	/**
	 * Class User
	 *
	 * @package Muslimarkt\Rest
	 */
	final class User extends Rest {
		use Singleton;

		/**
		 * Override endpoint url variable.
		 *
		 * @var string
		 */
		protected $endpoint = 'user';

		/**
		 * Override use put variable.
		 *
		 * @var bool
		 */
		protected $use_put = true;

		/**
		 * Callback for get method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		function get_callback( $request ) {
			// TODO: Implement get_callback method.
		}

		/**
		 * Callback for post method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		function post_callback( $request ) {
			// TODO: Implement post_callback() method.
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

			// Instance a new auth.
			$auth = new Auth( $request );

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Instance a new user.
					$user = new Employee( $auth->user_id, array(), true );

					// Update the user.
					$user->update( $auth->get_args() );

					// Re-validate.
					$auth->content_on_success( $user->items );
					$auth->content_on_error( $user->message );
					$auth->update_checked_error( $user->is_error );
				},
				false
			);
			$auth->validate();
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

	User::init();
}