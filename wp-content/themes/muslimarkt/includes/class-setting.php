<?php
/**
 * Setting class.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt;

use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Setting' ) ) {

	/**
	 * Class Setting
	 *
	 * @package Muslimarkt
	 */
	final class Setting {
		use Singleton;

		/**
		 * Front-end origin.
		 *
		 * @var string
		 */
		private $allowed_origin = 'http://localhost:3000';

		private function __construct() {
			// Handle preflight.
			add_action( 'init', array( $this, 'handle_preflight' ) );

			add_filter( 'rest_authentication_errors', array( $this, 'rest_filter_incoming_connections' ) );
		}

		/**
		 * Callback for modifying cors.
		 */
		public function handle_preflight() {
			$origin = get_http_origin();
			if ( $origin === $this->allowed_origin ) {
				header( "Access-Control-Allow-Origin: {$this->allowed_origin}" );
				header( "Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE" );
				header( "Access-Control-Allow-Credentials: true" );
				header( 'Access-Control-Allow-Headers: Origin, X-Requested-With, X-WP-Nonce, Content-Type, Accept, Authorization' );
				if ( 'OPTIONS' === $_SERVER['REQUEST_METHOD'] ) {
					status_header( 200 );
					exit();
				}
			}
		}

		/**
		 * Callback for checking origin request.
		 *
		 * @param WP_Error|null $errors current error message.
		 *
		 * @return WP_Error
		 */
		public function rest_filter_incoming_connections( $errors ) {
			$origin = get_http_origin();
			if ( $origin && $origin !== $this->allowed_origin ) {
				return new WP_Error( 'forbidden_access', $origin, array(
					'status' => 403
				) );
			}

			return $errors;
		}
	}

	Setting::init();
}
