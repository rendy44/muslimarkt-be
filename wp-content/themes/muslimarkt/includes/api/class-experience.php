<?php
/**
 * Experience api class.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Rest;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Muslimarkt\Auth;
use Muslimarkt\Singleton;
use WP_REST_Request;

if ( ! class_exists( 'Muslimarkt\Rest\Experience' ) ) {

	/**
	 * Class Experience
	 *
	 * @package Muslimarkt\Rest
	 */
	final class Experience extends Rest {
		use Singleton;

		/**
		 * Override endpoint url variable.
		 *
		 * @var string
		 */
		protected $endpoint = 'experience';

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
		protected $get_with_detail = true;

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

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Get user detail.
					$user = new \Muslimarkt\Model\User( $auth->user_id );

					// Get user's experiences.
					$user->get_experiences();

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

					// Get experience detail.
					$experience = new \Muslimarkt\Model\Experience( $auth->user_id, $auth->get_detail_slug() );

					// Get experience details.
					$experience->get_details();

					// Re-validate.
					$auth->content_on_success( $experience->items );
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

					// Create a new experience.
					$exp = new \Muslimarkt\Model\Experience( $auth );

					// Save experience's details.
					$exp->save_details( $auth->get_args() );

					// Re-validate.
					$auth->content_on_error( $exp->message );
					$auth->content_on_success( $exp->post->post_name );
					$auth->update_checked_obj( $exp );
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
	}

	Experience::init();
}