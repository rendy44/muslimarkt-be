<?php
/**
 * Company api class.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Rest;

use Muslimarkt\Abstracts\Rest;
use Muslimarkt\Auth;
use Muslimarkt\Model\Employer;
use Muslimarkt\Singleton;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Rest\Company' ) ) {

	/**
	 * Class Company
	 *
	 * @package Muslimarkt
	 */
	final class Company extends Rest {
		use Singleton;

		/**
		 * Override privilege variable.
		 *
		 * @var bool
		 */
		protected $is_require_key = false;

		/**
		 * Override endpoint url variable.
		 *
		 * @var string
		 */
		protected $endpoint = 'company';

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
		public function get_callback( $request ) {
			// TODO: Implement get_callback() method.
		}

		/**
		 * Callback for post method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		public function post_callback( $request ) {

			// Instance new auth.
			$auth = new Auth( $request );

			// Process create a user.
			$user = new Employer( false, $auth->get_args() );

			// Validate content.
			$auth->content_on_error( $user->message );
			$auth->content_on_success( __( 'Pendaftaran sukses, silahkan masuk dengan email dan kata sandi', 'muslimarkt' ) );
			$auth->parse_api( $user );
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

		/**
		 * Callback for getting detail method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		public function get_detail_callback( $request ) {
			// TODO: Implement get_detail_callback() method.
		}
	}

	Company::init();
}
