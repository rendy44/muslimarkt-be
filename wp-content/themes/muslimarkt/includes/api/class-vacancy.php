<?php
/**
 * Vacancy api class.
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
use Muslimarkt\Singleton;
use WP_REST_Request;

if ( ! class_exists( 'Muslimarkt\Rest\Vacancy' ) ) {

	/**
	 * Class Vacancy
	 *
	 * @package Muslimarkt\Rest
	 */
	final class Vacancy extends Rest {
		use Singleton;

		/**
		 * Override endpoint url variable.
		 *
		 * @var string
		 */
		protected $endpoint = 'vacancy';

		/**
		 * Override require key variable.
		 *
		 * @var bool
		 */
		protected $is_require_key = false;

		/**
		 * Override get variable.
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
		 * Callback for getting detail method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		public function get_detail_callback( $request ) {

			// Instance a new auth.
			$auth = new Auth( $request, true );

			// Instance a new job.
			$job = new \Muslimarkt\Model\Job( false, $auth->get_detail_slug() );
			$job->get_details();
			$job->get_user_details();

			// Send to output.
			$auth->content_on_error( $job->message );
			$auth->content_on_success( $job->items );
			$auth->parse_api( $job );
		}

		/**
		 * Callback for get method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		public function get_callback( $request ) {

			// Instance a new auth.
			$auth = new Auth( $request );

			// Instance posts query.
			$vacancies = mm_get_vacancies();

			// Send to output.
			$auth->content_on_error( $vacancies->get_error_message );
			$auth->content_on_success( $vacancies );
			$auth->parse_api( $vacancies );
		}

		/**
		 * Callback for post method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		public function post_callback( $request ) {
			// TODO: Implement post_callback() method.
		}

		/**
		 * Callback for delete method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		public function delete_callback( $request ) {
			// TODO: Implement delete_callback() method.
		}

		/**
		 * Callback for put method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		public function put_callback( $request ) {
			// TODO: Implement put_callback() method.
		}
	}

	Vacancy::init();
}
