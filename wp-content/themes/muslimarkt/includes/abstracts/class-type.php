<?php
/**
 * Post type abstract class.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Abstracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Abstracts\Type' ) ) {

	/**
	 * Class Type
	 *
	 * @package Muslimarkt\Type
	 */
	abstract class Type {

		/**
		 * Slug variable.
		 *
		 * @var string
		 */
		protected $slug = 'custom';

		/**
		 * Args variable.
		 *
		 * @var array
		 */
		protected $args = array();

		/**
		 * Type constructor.
		 */
		protected function __construct() {

			// Add action to register custom post type.
			add_action( 'init', array( $this, 'register_post_type' ) );

			// Prepare default args.
			$default_args = array(
				'label'  => ucfirst( $this->slug . 's' ),
				'public' => true,
			);

			// Merge args.
			$this->args = wp_parse_args( $this->args, $default_args );
		}

		/**
		 * Register post type.
		 */
		public function register_post_type() {
			register_post_type( $this->slug, $this->args );
		}
	}
}
