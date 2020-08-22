<?php
/**
 * Class parent.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt;

use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Post' ) ) {

	/**
	 * Class Post
	 *
	 * @package Muslimarkt
	 */
	abstract class Post {
		use Result;

		/**
		 * Post object variable.
		 *
		 * @var WP_Post
		 */
		public $post;

		/**
		 * Post constructor.
		 *
		 * @param Auth $auth authenticator object.
		 * @param bool $post_id id of the post.
		 * @param array $args args to create post.
		 */
		public function __construct( $auth, $post_id = false, $args = array() ) {

			// Check whether post id is defined or not.
			if ( $post_id ) {

				// Get post object.
				$this->post = get_post( $post_id );

				// Validate the post author.
				if ( $auth->user_id === $this->post->post_author ) {

					// Update the result.
					$this->is_error = false;
				} else {
					$this->message[] = __( 'Anda tidak memiliki hak untuk mengakses', 'muslimarkt' );
				}
			} else {

				// Create a new post.
				// Prepare default post title.
				$post_title = $this->get_random_post_name();

				// Prepare default args.
				$default_args = array(
					'post_type'   => 'post',
					'post_title'  => $post_title,
					'post_name'   => sanitize_title( $post_title ),
					'post_author' => $auth->user_id
				);

				// Merge args.
				$args = wp_parse_args( $args, $default_args );

				// Process create post.
				$new_post = wp_insert_post( $args );

				// Validate newly created post.
				if ( is_wp_error( $new_post ) ) {

					// Update result.
					$this->message[] = $new_post->get_error_message();
				} else {

					// Update result.
					$this->is_error = false;
					$this->post     = get_post( $new_post );
				}
			}
		}

		/**
		 * Delete post.
		 *
		 * @param bool $force_delete whether soft or hard delete.
		 *
		 * @return false|WP_Post|null
		 */
		public function delete( $force_delete = false ) {
			return wp_delete_post( $this->post->ID, $force_delete );
		}

		/**
		 * Get post meta.
		 *
		 * @param string $key name of the meta.
		 * @param bool $single whether single meta or not.
		 *
		 * @return mixed
		 */
		protected function get_meta( $key, $single = true ) {
			return Helper::get_post_meta( $key, $this->post->ID, $single );
		}

		/**
		 * Get multiple post meta.
		 *
		 * @param array $keys array of meta name.
		 *
		 * @return array
		 */
		protected function get_metas( $keys ) {
			return Helper::get_post_metas( $keys, $this->post->ID );
		}

		/**
		 * Save post meta.
		 *
		 * @param string $key name of the meta.
		 * @param mixed $value value of the meta.
		 */
		protected function save_meta( $key, $value ) {
			Helper::save_post_meta( $key, $value, $this->post->ID );
		}

		/**
		 * Save multiple post meta.
		 *
		 * @param array $args associate key=>value of meta.
		 */
		protected function save_metas( $args ) {
			Helper::save_post_metas( $args, $this->post->ID );
		}

		/**
		 * Get random post name.
		 *
		 * @return string
		 */
		private function get_random_post_name() {
			return uniqid( '', true );
		}
	}
}