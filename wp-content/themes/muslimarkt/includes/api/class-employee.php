<?php
/**
 * Employee class.
 *
 * @author Rendy
 * @package Muslimarkt
 */

namespace Muslimarkt\Rest;

use Muslimarkt\Abstracts\Rest;
use Muslimarkt\Auth;
use Muslimarkt\Singleton;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Rest\Employee' ) ) {

	/**
	 * Class Employee
	 *
	 * @package Muslimarkt\Rest
	 */
	final class Employee extends Rest {
		use Singleton;

		/**
		 * Override endpoint variable.
		 *
		 * @var string
		 */
		protected $endpoint = 'employee';

		/**
		 * Override use get variable.
		 *
		 * @var bool
		 */
		protected $use_get = true;

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

			// Instance a new auth.
			$auth = new Auth( $request, true );

			// Create callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Instance a new employee.
					$employee = new \Muslimarkt\Model\Employee( $auth->user_id );

					// Re-validate.
					$auth->content_on_success( $employee->items );
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

			// Create callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Instance a new employee.
					$employee = new \Muslimarkt\Model\Employee( $auth->user_id );

					// Update employee.
					$employee->update( $auth->get_args() );

					// Re-validate.
					$auth->content_on_error( $employee->message );
					$auth->content_on_success( __( 'Berhasil diperbarui', 'muslimark' ) );
					$auth->update_checked_error( $employee->is_error );
					$auth->parse_api( $employee, false );
				},
				false
			);
			$auth->validate();
		}
	}

	Employee::init();
}
