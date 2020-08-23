<?php
/**
 * User api class.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Rest;

use Muslimarkt\Auth;
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
		 * Override use post variable.
		 *
		 * @var bool
		 */
		protected $use_post = true;

		/**
		 * Override use put variable.
		 *
		 * @var bool
		 */
		protected $use_put = true;

		/**
		 * Override use get variable.
		 *
		 * @var bool
		 */
		protected $use_get = true;

		/**
		 * Callback for get method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		function get_callback( $request ) {

			// Instance a new auth.
			$auth = new Auth( $request, true );
			$auth->success_callback(
				function () use ( $auth ) {
					// Get user detail.
					$user = new \Muslimarkt\Model\User( $auth->user_id );
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

			// Instance new auth.
			$auth = new Auth( $request );

			// Process create a user.
			$user = new \Muslimarkt\Model\User( false, $auth->get_args() );
			$auth->content_on_error( $user->message );
			$auth->content_on_success( __( 'Pendaftaran sukses, silahkan masuk dengan email dan kata sandi', 'muslimarkt' ) );
			$auth->parse_api( $user );
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
			$auth->success_callback( function () use ( $auth ) {

				// Instance a new user.
				$user = new \Muslimarkt\Model\User( $auth->user_id, array(), true );

				// Update the user.
				$user->update( $auth->get_args() );

				// Validate update result.
				if ( $user->is_error ) {

					// Display error request.
					wp_send_json_error( $user->message );
				} else {

					// Display success request.
					wp_send_json_success( $user->items );
				}
			} );
			$auth->validate();
//
//			// Validate the auth.
//			if ( $auth->is_error ) {
//
//				// Display error request.
//				wp_send_json_error( $auth->message );
//			} else {
//
//				// Instance a new user.
//				$user = new \Muslimarkt\Model\User( $auth->user_id, array(), true );
//
//				// Update the user.
//				$user->update( $auth->get_args() );
//
//				// Validate update result.
//				if ( $user->is_error ) {
//
//					// Display error request.
//					wp_send_json_error( $user->message );
//				} else {
//
//					// Display success request.
//					wp_send_json_success( $user->items );
//				}
//			}
		}
	}

	User::init();
}