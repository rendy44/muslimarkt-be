<?php
/**
 * Validate api class.
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

if ( ! class_exists( 'Muslimarkt\Rest\Validate' ) ) {

	/**
	 * Class Validate
	 *
	 * @package Muslimarkt\Rest
	 */
	final class Validate extends Rest {
		use Singleton;

		/**
		 * Override endpoint url variable.
		 *
		 * @var string
		 */
		protected $endpoint = 'validate';

		/**
		 * Override use get variable.
		 *
		 * @var bool
		 */
		protected $use_post = true;

		/**
		 * Callback for post method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		function post_callback( $request ) {

			// Instance a new auth.
			$auth = new Auth( $request );

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Instance a new user.
					$user = new \Muslimarkt\Model\User( $auth->user_id );

					// Activate user account.
					$user->activate_account();
				},
				false
			);

			// Validate the request.
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

		/**
		 * Callback for get method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		function get_callback( $request ) {
			// TODO: Implement get_callback() method.
		}
	}

	Validate::init();
}
