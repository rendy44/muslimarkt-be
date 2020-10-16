<?php
/**
 * Job api class.
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

if ( ! class_exists( 'Muslimarkt\Rest\Job' ) ) {

	/**
	 * Class Job
	 *
	 * @package Muslimarkt\Rest
	 */
	final class Job extends Rest {
		use Singleton;

		/**
		 * Override endpoint url variable.
		 *
		 * @var string
		 */
		protected $endpoint = 'job';

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
		public function get_callback( $request ) {

			// Instance a new auth.
			$auth = new Auth( $request, true );

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Get user detail.
					$user = new Employee( $auth->user_id );

					// Get user's jobs.
					$user->get_jobs();

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
		public function get_detail_callback( $request ) {

			// Instance a new auth.
			$auth = new Auth( $request, true );

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Get job detail.
					$job = new \Muslimarkt\Model\Job( $auth->user_id, $auth->get_detail_slug() );

					// Get job details.
					$job->get_details();

					// Re-validate.
					$auth->content_on_error( $job->message );
					$auth->content_on_success( $job->items );
					$auth->update_checked_error( $job->is_error );
					$auth->parse_api( $job, false );
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
		public function post_callback( $request ) {

			// Instance a new auth.
			$auth = new Auth( $request );

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Create a new job.
					$job = new \Muslimarkt\Model\Job( $auth->user_id );

					// Save job's details.
					$job->save_details( $auth->get_args() );

					// Re-validate.
					$auth->content_on_error( $job->message );
					$auth->content_on_success( $job->post->post_name );
					$auth->update_checked_error( $job->is_error );
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
		public function delete_callback( $request ) {

			// Instance a new auth.
			$auth = new Auth( $request, true );

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Get job detail.
					$job = new \Muslimarkt\Model\Job( $auth->user_id, $auth->get_detail_slug() );

					// Delete job.
					$job->delete();

					// Re-validate.
					$auth->content_on_error( $job->message );
					$auth->content_on_success( __( 'Berhasil dihapus', 'muslimarkt' ) );
					$auth->update_checked_error( $job->is_error );
					$auth->parse_api( $job, false );
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
		public function put_callback( $request ) {

			// Instance a new auth.
			$auth = new Auth( $request );

			// Create a callback.
			$auth->success_callback(
				function () use ( $auth ) {

					// Get existing job.
					$job = new \Muslimarkt\Model\Job( $auth->user_id, $auth->get_detail_slug() );

					// Save job's details.
					$job->save_details( $auth->get_args() );

					// Re-validate.
					$auth->content_on_error( $job->message );
					$auth->content_on_success( __( 'Berhasil diperbarui', 'muslimarkt' ) );
					$auth->update_checked_error( $job->is_error );
					$auth->parse_api( $job, false );
				},
				false
			);

			// Validate the request.
			$auth->validate();
		}
	}

	Job::init();
}
