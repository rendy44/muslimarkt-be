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
use WP_REST_Request;

final class Experience extends Rest {

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
	 * Callback for get method.
	 *
	 * @param WP_REST_Request $request request object.
	 */
	function get_callback( $request ) {
		// Instance a new auth.
		$auth = new Auth( $request );
		$auth->validate();

		// Validate the auth.
		// TODO: Implement get_callback() method.
	}

	/**
	 * Callback for post method.
	 *
	 * @param WP_REST_Request $request request object.
	 */
	function post_callback( $request ) {
		// TODO: Implement post_callback() method.
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