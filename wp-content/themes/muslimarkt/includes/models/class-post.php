<?php
/**
 * Post query class.
 *
 * @author Rendy
 * @package Muslimarkt.
 */

namespace Muslimarkt\Model;

use Muslimarkt\Result;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Model\Post' ) ) {

	/**
	 * Class Post
	 *
	 * @package Muslimarkt\Model
	 */
	class Post {
		use Result;

		/**
		 * Query variable
		 *
		 * @var array
		 */
		private $args = array();

		/**
		 * Query variable.
		 *
		 * @var null|WP_Query
		 */
		private $query = null;

		/**
		 * Transient key variable.
		 *
		 * @var string
		 */
		private $transient_key = '';

		/**
		 * Post constructor.
		 *
		 * @param array $args query args.
		 * @param string|bool $transient_key optional transient key.
		 */
		public function __construct( $args = array(), $transient_key = '' ) {

			// Save properties.
			$this->args          = $args;
			$this->transient_key = $transient_key;
		}

		/**
		 * Get query object.
		 *
		 * @return bool|mixed|WP_Query|null
		 */
		public function get_query() {

			// First, check from transient.
			$result = $this->maybe_get_transient();

			// If transient not exist, create a new query.
			if ( ! $result ) {

				// Create a new query.
				$this->new_query();

				// Save a new transient.
				$this->save_transient();

				// Finally, return them.
				return $this->query;
			}

			return $result;
		}

		/**
		 * Generate transient key, based on property or args.
		 *
		 * @return false|string
		 */
		private function get_transient_key() {
			return $this->transient_key ? $this->transient_key : wp_json_encode( $this->args );
		}

		/**
		 * Maybe get content from transient.
		 *
		 * @return bool|mixed
		 */
		private function maybe_get_transient() {
			return false !== $this->transient_key ? get_transient( $this->get_transient_key() ) : false;
		}

		/**
		 * Generate a new query.
		 */
		private function new_query() {
			$this->query = new WP_Query( $this->args );
		}

		/**
		 * Save transient.
		 */
		private function save_transient() {
			set_transient( $this->get_transient_key(), $this->query, HOUR_IN_SECONDS );
		}
	}
}