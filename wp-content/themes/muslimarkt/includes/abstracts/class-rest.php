<?php
/**
 * RestAPi abstract class.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Rest;

use WP_REST_Request;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Rest\Rest' ) ) {

	/**
	 * Class Rest
	 *
	 * @package Muslimarkt
	 */
	abstract class Rest {

		/**
		 * Namespace variable.
		 *
		 * @var string
		 */
		private $namespace = 'muslimarkt';

		/**
		 * Endpoint variable.
		 *
		 * @var string
		 */
		protected $endpoint = '';

		/**
		 * Variable use post.
		 *
		 * @var bool
		 */
		protected $use_post = false;

		/**
		 * Variable use get.
		 *
		 * @var bool
		 */
		protected $use_get = false;

		/**
		 * Variable use put.
		 *
		 * @var bool
		 */
		protected $use_put = false;

		/**
		 * Variable use delete.
		 *
		 * @var bool
		 */
		protected $use_delete = false;

		/**
		 * Rest constructor.
		 */
		public function __construct() {

			// Register settings.
			add_action( 'rest_api_init', array( $this, 'register_api' ) );
		}

		/**
		 * Callback for get method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		abstract function get_callback( $request );

		/**
		 * Callback for post method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		abstract function post_callback( $request );

		/**
		 * Callback for delete method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		abstract function delete_callback( $request );

		/**
		 * Callback for put method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		abstract function put_callback( $request );

		/**
		 * Get list of method callbacks based on method configuration.
		 *
		 * @return array
		 */
		private function get_methods_collection() {
			$methods = array();

			// Check delete method.
			if ( $this->use_delete ) {
				$methods[] = array(
					'methods'  => WP_REST_Server::DELETABLE,
					'callback' => array( $this, 'delete_callback' ),
				);
			}

			// Check post method.
			if ( $this->use_post ) {
				$methods[] = array(
					'methods'  => WP_REST_Server::CREATABLE,
					'callback' => array( $this, 'post_callback' ),
				);
			}

			// Check update method.
			if ( $this->use_put ) {
				$methods[] = array(
					'methods'  => WP_REST_Server::EDITABLE,
					'callback' => array( $this, 'put_callback' ),
				);
			}

			// Check get method.
			if ( $this->use_get ) {
				$methods[] = array(
					'methods'  => WP_REST_Server::READABLE,
					'callback' => array( $this, 'get_callback' ),
				);
			}

			return $methods;
		}

		/**
		 * Do register API.
		 */
		public function register_api() {
			register_rest_route( $this->namespace, '/' . $this->endpoint . '/(?P<key>[\S]+)', $this->get_methods_collection() );
		}
	}
}