<?php
/**
 * Account api class.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Rest;

use Muslimarkt\Abstracts\Rest;
use Muslimarkt\Auth;
use Muslimarkt\Helper;
use Muslimarkt\Model\Employee;
use Muslimarkt\Model\Employer;
use Muslimarkt\Singleton;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Rest\Account' ) ) {

	/**
	 * Class Account
	 *
	 * @package Muslimarkt\Rest
	 */
	final class Account extends Rest {
		use Singleton;

		/**
		 * Override endpoint url variable.
		 *
		 * @var string
		 */
		protected $endpoint = 'account';

		/**
		 * Override use get variable.
		 *
		 * @var bool
		 */
		protected $use_get = true;

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

			// Instance a new auth.
			$auth = new Auth( $request, true );

			// Create callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Instance a new user.
					$user = new \Muslimarkt\Model\User( $auth->user_id );

					// Re-validate.
					$auth->content_on_success( $user->items );
				},
				false
			);
			$auth->validate();
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

					// Instance a new user.
					$user = new \Muslimarkt\Model\User( $auth->user_id );

					// Get account type.
					$type = $auth->get_args( 'type' );

					// Check maybe set as employer.
					if ( 'employer' === $type ) {
						$user->set_as_employer();
					} else {
						$user->set_as_employee();
					}
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

	Account::init();
}