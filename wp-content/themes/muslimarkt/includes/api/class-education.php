<?php
/**
 * Education api class.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Rest;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Muslimarkt\Abstracts\Rest;
use Muslimarkt\Auth;
use Muslimarkt\Model\Employee;
use Muslimarkt\Singleton;
use WP_REST_Request;

if ( ! class_exists( 'Muslimarkt\Rest\Education' ) ) {

	/**
	 * Class Education
	 *
	 * @package Muslimarkt\Rest
	 */
	final class Education extends Rest {
		use Singleton;

		/**
		 * Override endpoint url variable.
		 *
		 * @var string
		 */
		protected $endpoint = 'education';

		/**
		 * Override use get variable.
		 *
		 * @var bool
		 */
		protected $use_get = true;

		/**
		 * Override get with detail variable.
		 *
		 * @var bool
		 */
		protected $use_detail = true;

		/**
		 * Override use post variable.
		 *
		 * @var bool
		 */
		protected $use_post = true;

		/**
		 * Override use delete variable.
		 *
		 * @var bool
		 */
		protected $use_delete = true;

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

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Get user detail.
					$user = new Employee( $auth->user_id );

					// Get user's educations.
					$user->get_educations();

					// Re-validate.
					$auth->content_on_success( $user->items );
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

			// Instance a new auth.
			$auth = new Auth( $request, true );

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Get education detail.
					$exp = new \Muslimarkt\Model\Education( $auth->user_id, $auth->get_detail_slug() );

					// Get education details.
					$exp->get_details();

					// Re-validate.
					$auth->content_on_error( $exp->message );
					$auth->content_on_success( $exp->items );
					$auth->update_checked_error( $exp->is_error );
					$auth->parse_api( $exp, false );
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

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Create a new education.
					$exp = new \Muslimarkt\Model\Education( $auth->user_id );

					// Save education's details.
					$exp->save_details( $auth->get_args() );

					// Re-validate.
					$auth->content_on_error( $exp->message );
					$auth->content_on_success( $exp->post->post_name );
					$auth->update_checked_error( $exp->is_error );
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

			// Instance a new auth.
			$auth = new Auth( $request, true );

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Get education detail.
					$exp = new \Muslimarkt\Model\Education( $auth->user_id, $auth->get_detail_slug() );

					// Delete education.
					$exp->delete();

					// Re-validate.
					$auth->content_on_error( $exp->message );
					$auth->content_on_success( __( 'Berhasil dihapus', 'muslimarkt' ) );
					$auth->update_checked_error( $exp->is_error );
					$auth->parse_api( $exp, false );
				},
				false
			);
			$auth->validate();
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

					// Get existing education.
					$exp = new \Muslimarkt\Model\Education( $auth->user_id, $auth->get_detail_slug() );

					// Save education's details.
					$exp->save_details( $auth->get_args() );

					// Re-validate.
					$auth->content_on_error( $exp->message );
					$auth->content_on_success( __( 'Berhasil diperbarui', 'muslimarkt' ) );
					$auth->update_checked_error( $exp->is_error );
					$auth->parse_api( $exp, false );
				},
				false
			);

			// Validate the request.
			$auth->validate();
		}
	}

	Education::init();
}