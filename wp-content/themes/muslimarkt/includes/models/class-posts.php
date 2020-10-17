<?php
/**
 * Post class.
 *
 * @author Rendy
 * @package Muslimarkt
 */

namespace Muslimarkt\Model;

use Muslimarkt\Result;
use WP_Query;

if ( ! class_exists( 'Muslimarkt\Model\Posts' ) ) {

	/**
	 * Class Posts
	 *
	 * @package Muslimarkt\Model
	 */
	class Posts {
		use Result;

		/**
		 * Post type variable.
		 *
		 * @var string
		 */
		private $post_type;

		/**
		 * Query args.
		 *
		 * @var array
		 */
		private $args;

		/**
		 * Posts constructor.
		 *
		 * @param string $post_type name of the post type.
		 * @param array  $args wp_query args.
		 */
		public function __construct( $post_type = 'post', $args = array() ) {

			// Save properties.
			$this->post_type = $post_type;
			$this->args      = $args;
		}

		/**
		 * Get all posts.
		 *
		 * @return bool|mixed|WP_Query|null
		 */
		public function get_all_posts() {

			// Readjust args.
			$args                   = $this->args;
			$args['post_type']      = $this->post_type;
			$args['posts_per_page'] = - 1;

			// Instance the query.
			$query = new Query( $args, 'get_all_' . $this->post_type );
			return $query->get_query();
		}
	}
}
