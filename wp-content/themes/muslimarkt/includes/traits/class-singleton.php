<?php
/**
 * Singleton traits.
 *
 * @author Rendy
 * @package Muslimarkt.
 */

namespace Muslimarkt;

use stdClass;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! trait_exists( 'Muslimarkt\Singleton' ) ) {

	/**
	 * Trait Singleton
	 *
	 * @package Muslimarkt
	 */
	trait Singleton {

		/**
		 * Instance variable.
		 *
		 * @var array
		 */
		private static $instance = array();

		/**
		 * Singleton instance.
		 *
		 * @return array|StdClass
		 */
		public static function init() {

			// Get called class.
			$called_class = get_called_class();

			if ( empty( self::$instance[ $called_class ] ) ) {
				self::$instance[ $called_class ] = new $called_class();
			}

			return self::$instance[ $called_class ];
		}
	}
}