<?php
/**
 * Class parent.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Abstracts;

use Muslimarkt\Helper;
use Muslimarkt\Result;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Abstracts\Post' ) ) {

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
		 * Used fields variable.
		 *
		 * @var array
		 */
		protected $used_fields = array();

		/**
		 * Post type variable.
		 *
		 * @var string
		 */
		public $post_type = 'post';

		/**
		 * Post constructor.
		 *
		 * @param int $user_id id of the user.
		 * @param bool|string|int|WP_Post $post_tag object of WP_Post, id of the post or slug of the post.
		 * @param array $args args to create post.
		 */
		protected function __construct( $user_id, $post_tag = false, $args = array() ) {

			// Check whether post is defined or not.
			if ( $post_tag ) {

				// Maybe it's already post object.
				if ( $post_tag instanceof WP_Post ) {

					// Directly save tag as post object.
					$this->post = $post_tag;

				} elseif ( is_int( $post_tag ) ) {

					// Get post based on id.
					$find_post = get_post( $post_tag );

					// Validate found post.
					if ( $find_post ) {

						// Save found post.
						$this->post     = $find_post;
						$this->is_error = false;
					} else {

						// Add message.
						$this->message[] = __( 'Pos id tidak ditemukan', 'muslimarkt' );
					}

				} elseif ( is_string( $post_tag ) ) {

					// Get post based on slug.
					$find_post = get_page_by_path( $post_tag, OBJECT, $this->post_type );

					// Validate found post.
					if ( $find_post ) {

						// Save found post.
						$this->post     = $find_post;
						$this->is_error = false;
					} else {

						// Add message.
						$this->message[] = __( 'Pos alias tidak ditemukan', 'muslimarkt' );
					}
				} else {
					$this->message[] = __( 'Terjadi kesalahan', 'muslimarkt' );
				}

				// Make sure, we haven't encountered any errors yet.
				if ( ! empty( $this->message ) ) {

					// Validate the post author.
					if ( $user_id === $this->post->post_author ) {

						// Update the result.
						$this->is_error = false;
					} else {
						$this->message[] = __( 'Anda tidak memiliki hak untuk mengakses pos ini', 'muslimarkt' );
					}
				}
			} else {

				// Create a new post.
				// Prepare default post title.
				$post_title = $this->get_random_post_name();

				// Prepare default args.
				$default_args = array(
					'post_type'   => $this->post_type,
					'post_title'  => $post_title,
					'post_name'   => sanitize_title( $post_title ),
					'post_author' => $user_id,
					'post_status' => 'publish',
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
		 * Get experience details.
		 */
		public function get_details() {

			// Get experience base detail.
			$this->get_base_details();

			// Get additional details.
			$details = $this->get_metas( $this->used_fields );

			// Merge details.
			$this->items = array_merge( $this->items, $details );
		}

		/**
		 * Get post base details.
		 */
		private function get_base_details() {
			$this->items['id']   = $this->post->ID;
			$this->items['slug'] = $this->post->post_name;
		}

		/**
		 * Save experience detail.
		 *
		 * @param array $args associate key=>value detail.
		 */
		public function save_details( $args ) {

			// Loop all possible fields.
			foreach ( $args as $arg_key => $arg_value ) {

				// Make sure, field is allowed.
				if ( in_array( $arg_key, $this->used_fields, true ) ) {

					// Update the field.
					$this->save_meta( $arg_key, $arg_value );
				}
			}
		}
	}
}