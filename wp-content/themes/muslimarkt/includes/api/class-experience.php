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
			$auth->success_callback(
				function () use ( $auth ) {
					// Get user detail.
					$user = new \Muslimarkt\Model\User( $auth->user_id );
					$user->get_experiences();
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
			$auth->success_callback(
				function () use ( $auth ) {

					// Create a new experience.
					$exp = new \Muslimarkt\Model\Experience( $auth );
					$exp->save_details( array(
						'position'      => 'WordPress Developer',
						'company'       => 'PT. SoftwareSeni',
						'month_start'   => 'Feb',
						'year_start'    => '2019',
						'month_end'     => '',
						'year_end'      => '',
						'still_working' => '1',
						'role'          => 'Staff',
						'industry'      => 'Konsultan IT',
						'country'       => 'Indonesia',
						'province'      => 'DI Yogyakarta',
						'notes'         => 'Kosong',
					) );

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