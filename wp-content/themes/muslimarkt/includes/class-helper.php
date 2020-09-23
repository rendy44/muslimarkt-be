<?php
/**
 * Class Helper
 * Class to collect useful functions.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt;

use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Helper' ) ) {

	/**
	 * Class helper
	 *
	 * @package Muslimarkt
	 */
	final class Helper {

		/**
		 * Perform encrypt and decrypt
		 *
		 * @param string $string plain string.
		 * @param bool   $is_decrypt whether decrypt or encrypt.
		 *
		 * @return bool|false|string
		 */
		public static function encrypt( $string, $is_decrypt = false ) {
			$encrypt_method = 'AES-256-CBC';
			$secret_key     = TEMP_PREFIX;
			$secret_iv      = 'muslimarkt_secret_key';

			// Hash.
			$key = hash( 'sha256', $secret_key );

			// iv - encrypt method AES-256-CBC expects 16 bytes
			$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
			if ( ! $is_decrypt ) {
				$output = openssl_encrypt( $string, $encrypt_method, $key, 0, $iv );
				$output = base64_encode( $output );
			} else {
				$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
			}

			return $output;
		}

		/**
		 * Get post single meta.
		 *
		 * @param string $key name of the meta.
		 * @param int    $post_id id of the post.
		 * @param bool   $single whether display single value or not.
		 * @param bool   $use_prefix whether use prefix or not.
		 *
		 * @return mixed
		 */
		public static function get_post_meta( $key, $post_id, $single = true, $use_prefix = true ) {
			$key = $use_prefix ? TEMP_PREFIX . $key : $key;

			return get_post_meta( $post_id, $key, $single );
		}

		/**
		 * Get post metas.
		 *
		 * @param array $keys collection of the post metas.
		 * @param int   $post_id id of the post.
		 * @param bool  $use_prefix whether use prefix or not.
		 *
		 * @return array
		 */
		public static function get_post_metas( $keys, $post_id, $use_prefix = true ) {
			$result = array();
			if ( ! empty( $keys ) ) {
				foreach ( $keys as $key ) {
					$result[ $key ] = self::get_post_meta( $key, $post_id, true, $use_prefix );
				}
			}

			return $result;
		}

		/**
		 * Get user metas.
		 *
		 * @param array $keys collection of the user metas.
		 * @param int   $user_id id of the user.
		 * @param bool  $use_prefix whether use prefix or not.
		 *
		 * @return array
		 */
		public static function get_user_metas( $keys, $user_id, $use_prefix = true ) {
			$result = array();
			if ( ! empty( $keys ) ) {
				foreach ( $keys as $key ) {
					$result[ $key ] = self::get_user_meta( $key, $user_id, true, $use_prefix );
				}
			}

			return $result;
		}

		/**
		 * Get user single meta.
		 *
		 * @param string $key name of the meta.
		 * @param int    $user_id id of the user.
		 * @param bool   $single whether display single value or not.
		 * @param bool   $use_prefix whether use prefix or not.
		 *
		 * @return mixed
		 */
		public static function get_user_meta( $key, $user_id, $single = true, $use_prefix = true ) {
			$key = $use_prefix ? TEMP_PREFIX . $key : $key;

			return get_user_meta( $user_id, $key, $single );
		}

		/**
		 * Save post meta.
		 *
		 * @param string $key name of the meta key.
		 * @param mixed  $value value of the meta.
		 * @param int    $post_id id of the post.
		 * @param bool   $use_prefix whether save with prefix or not.
		 */
		public static function save_post_meta( $key, $value, $post_id, $use_prefix = true ) {
			$key = $use_prefix ? TEMP_PREFIX . $key : $key;

			update_post_meta( $post_id, $key, $value );
		}

		/**
		 * Save post metas.
		 *
		 * @param array $values collection key=>value of the meta.
		 * @param int   $post_id id of the post.
		 * @param bool  $use_prefix whether use prefix or not.
		 */
		public static function save_post_metas( $values, $post_id, $use_prefix = true ) {
			if ( ! empty( $values ) ) {
				foreach ( $values as $key => $value ) {
					self::save_post_meta( $key, $value, $post_id, $use_prefix );
				}
			}
		}

		/**
		 * Save user meta.
		 *
		 * @param string $key name of the meta key.
		 * @param mixed  $value value of the meta.
		 * @param int    $user_id id of the user.
		 * @param bool   $use_prefix whether save with prefix or not.
		 */
		public static function save_user_meta( $key, $value, $user_id, $use_prefix = true ) {
			$key = $use_prefix ? TEMP_PREFIX . $key : $key;

			update_user_meta( $user_id, $key, $value );
		}

		/**
		 * Save user metas.
		 *
		 * @param array $values collection key=>value of the meta.
		 * @param int   $user_id id of the user.
		 * @param bool  $use_prefix whether use prefix or not.
		 */
		public static function save_user_metas( $values, $user_id, $use_prefix = true ) {
			if ( ! empty( $values ) ) {
				foreach ( $values as $key => $value ) {
					self::save_user_meta( $key, $value, $user_id, $use_prefix );
				}
			}
		}

		/**
		 * Validate user key.
		 *
		 * @param string $user_key user key.
		 *
		 * @return int|WP_Error
		 */
		public static function validate_user_key( $user_key ) {
			// Convert user key into email.
			$maybe_email = self::encrypt( $user_key, true );

			// Find user by its email.
			$found_user = get_user_by_email( $maybe_email );

			// Validate the user.
			if ( ! $found_user ) {
				return new WP_Error( 'err_auth', __( 'Anda tidak diizinkan untuk melakukan aksi ini', 'muslimarkt' ) );
			}

			// Save the user.
			wp_set_current_user( $found_user->ID );

			return $found_user->ID;
		}

		/**
		 * Get template.
		 *
		 * @param string $file_name name of the template.
		 * @param array  $args additional args.
		 */
		public static function get_template( $file_name, $args = array() ) {

			// Set default path folder.
			$template_path = TEMP_PATH . '/templates';

			// Load the template.
			load_template( "{$template_path}/{$file_name}.php", false, $args );
		}
	}
}
