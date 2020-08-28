<?php
/**
 * Traits for result.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! trait_exists( 'Muslimarkt\Result' ) ) {

	/**
	 * Trait Result
	 *
	 * @package Muslimarkt
	 */
	trait Result {

		/**
		 * Is error variable.
		 *
		 * @var bool
		 */
		public $is_error = true;

		/**
		 * Error messages variable.
		 *
		 * @var string
		 */
		public $message = array();

		/**
		 * Items variable.
		 *
		 * @var array
		 */
		public $items = array();
	}
}