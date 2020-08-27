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
		 * Variable to determine whether api route should use api or not.
		 *
		 * @var bool
		 */
		protected $is_require_key = true;

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
		 * get with detail variable.
		 *
		 * @var bool
		 */
		protected $get_with_detail = false;

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
		 * Callback for getting detail method.
		 *
		 * @param WP_REST_Request $request request object.
		 */
		abstract function get_detail_callback( $request );

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

			// Register route.
			register_rest_route( $this->namespace, '/' . $this->get_base_endpoint(), $this->get_methods_collection() );

			// Check maybe add custom endpoint for getting detail.
			if ( $this->use_get && $this->get_with_detail ) {

				// Register custom endpoint.
				$this->custom_get_with_detail_api();
			}
		}

		/**
		 * Get base route endpoint, include whether use key or not.
		 *
		 * @return string
		 */
		private function get_base_endpoint() {
			return $this->is_require_key ? $this->endpoint . '/(?P<key>[\S]+)' : $this->endpoint;
		}

		/**
		 * Create custom endpoint route for getting detail.
		 */
		private function custom_get_with_detail_api() {

			// Register custom route for getting detail.
			register_rest_route( $this->namespace, '/' . $this->get_base_endpoint() . '/(?P<slug>[\S]+)', array(
				array(
					'methods'  => WP_REST_Server::READABLE,
					'callback' => array( $this, 'get_detail_callback' ),
				)
			) );
		}
	}
}