<?php
/**
 * Abstract class user.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Abstracts;

use Muslimarkt\Helper;
use Muslimarkt\Result;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Abstracts\User' ) ) {

	/**
	 * Class User
	 *
	 * @package Muslimarkt
	 */
	abstract class User {
		use Result;

		/**
		 * User object variable.
		 *
		 * @var WP_User
		 */
		public $user;

		/**
		 * User detail fields.
		 *
		 * @var array
		 */
		protected $user_fields = array();

		/**
		 * User constructor.
		 *
		 * @param int|bool $user_id id of the user.
		 * @param array $args args which contains information to create a new user.
		 * @param bool $no_fetch whether skip to get user details value.
		 */
		public function __construct( $user_id = false, $args = array(), $no_fetch = false ) {

			// Check whether user_id is provided or not, if not, create a new one.
			// If yes, get the existing user data.
			if ( $user_id ) {

				// Save user object.
				$user_data = get_userdata( $user_id );

				// Validate the user.
				if ( ! $user_data ) {
					$this->message[] = __( 'Akun tidak valid', 'muslimarkt' );
				} else {

					// Save user data object.
					$this->user = $user_data;

					// Maybe get user details.
					if ( ! $no_fetch ) {

						// Generate user details.
						$this->generate_user_details();
					}

					// Get more user details.
					$this->items['first_name']   = $this->user->first_name;
					$this->items['last_name']    = $this->user->last_name;
					$this->items['display_name'] = $this->user->display_name;
					$this->items['email']        = $this->user->user_email;
					$this->items['avatar_url']   = get_avatar_url( $user_id );

					// Update result.
					$this->is_error = false;
				}

			} else {

				// we are going to create a new user.
				// Prepare default args.
				$default_args = array(
					'email'     => false,
					'password'  => false,
					'password2' => false,
				);

				// Merge the args.
				$args = wp_parse_args( $args, $default_args );

				// Validate the args.
				if ( ! $args['email'] || ! filter_var( $args['email'], FILTER_VALIDATE_EMAIL ) ) {
					$this->message[] = __( 'Mohon masukkan alamat email yang valid', 'muslimarkt' );
				}
				if ( ! $args['password'] ) {
					$this->message[] = __( 'Kata sandi harus diisi', 'muslimarkt' );
				}
				if ( ! $args['password2'] || $args['password'] !== $args['password2'] ) {
					$this->message[] = __( 'Konfirmasi kata sandi harus sama', 'muslimarkt' );
				}

				// Check that no errors occur.
				if ( empty( $this->message ) ) {

					// Create a new user.
					$new_user = wp_create_user( $this->generate_username(), $args['password'], $args['email'] );

					// Validate created user.
					if ( is_wp_error( $new_user ) ) {

						// Save error message.
						$this->message[] = $new_user->get_error_message();
					} else {

						// Update the result.
						$this->user     = get_userdata( $new_user );
						$this->is_error = false;

						// Save user details.
						$user_details = array(
							'user_key'            => $this->generate_user_key_by_email( $args['email'] ),
							'is_profile_complete' => false,
							'active'              => false,
						);

						/**
						 * Muslimarkt new user generic details filter hook.
						 *
						 * @param array $user_details current user details.
						 * @param int $new_user id of newly created user.
						 */
						$user_details = apply_filters( 'muslimarkt_new_user_generic_details', $user_details, $new_user );

						// Save user details.
						$this->save_metas( $user_details );

						// Maybe get extra args.
						$extra_args = $this->get_extra_registration_metas( $args );

						// Validate extra args.
						if ( ! empty( $extra_args ) ) {

							// Save extra args.
							$this->save_metas( $extra_args );
						}
					}
				}
			}
		}

		/**
		 * Maybe add extra metas.
		 *
		 * @param array $args args from request.
		 *
		 * @return array
		 */
		private function get_extra_registration_metas( $args ) {

			$used_args = array();

			// Validate and loop args.
			if ( ! empty( $args ) ) {


				// Loop all used fields.
				foreach ( $this->user_fields as $field ) {

					// If args is defined.
					if ( ! empty( $args[ $field ] ) ) {
						$used_args[ $field ] = $args[ $field ];
					}
				}
			}

			return $used_args;
		}

		/**
		 * Generate random username.
		 *
		 * @return string
		 */
		private function generate_username() {
			return uniqid();
		}

		/**
		 * Get user meta.
		 *
		 * @param string $key name of the meta.
		 *
		 * @return mixed
		 */
		protected function get_meta( $key ) {
			return Helper::get_user_meta( $key, $this->user->ID );
		}

		/**
		 * Get array of user meta.
		 *
		 * @param array $keys list of meta name.
		 *
		 * @return array
		 */
		protected function get_metas( $keys ) {
			return Helper::get_user_metas( $keys, $this->user->ID );
		}

		/**
		 * Save user meta.
		 *
		 * @param string $key name of the meta.
		 * @param mixed $value value of the meta.
		 */
		protected function save_meta( $key, $value ) {
			Helper::save_user_meta( $key, $value, $this->user->ID );
		}

		/**
		 * Save multiple user meta.
		 *
		 * @param array $args associate key => value of the meta.
		 */
		protected function save_metas( $args ) {
			foreach ( $args as $arg_key => $arg_value ) {

				// Always make sure that key is allowed.
				if ( in_array( $arg_key, $this->user_fields, true ) ) {

					// Do a save.
					$this->save_meta( $arg_key, $arg_value );
				}
			}
		}

		/**
		 * Generate user key by email address.
		 *
		 * @param string $email email address.
		 *
		 * @return bool|false|string
		 */
		private function generate_user_key_by_email( $email ) {
			return Helper::encrypt( $email );
		}

		/**
		 * Update user details.
		 *
		 * @param array $args array of values.
		 */
		public function update( $args ) {

			// Update some fundamental fields.
			$update = wp_update_user( array(
				'ID'           => $this->user->ID,
				'first_name'   => $args['first_name'],
				'last_name'    => $args['last_name'],
				'display_name' => $args['first_name'] . ' ' . $args['last_name']
			) );

			// Validate the update.
			if ( is_wp_error( $update ) ) {

				// Save error message.
				$this->message[] = $update->get_error_message();
			} else {

				// First, remove key from being updated.
				unset( $args['key'] );

				// Save user details.
				$this->save_metas( $args );

				// Generate user details.
				$this->generate_user_details();

				// Everything seems ok.
				$this->is_error = false;
			}
		}

		/**
		 * Generate user details.
		 *
		 * @param array $args predefined values, or leave it empty to get value from the db.
		 */
		protected function generate_user_details( $args = array() ) {

			// Get user details.
			$details = ! empty( $args ) ? $args : $this->get_metas( $this->user_fields );

			// Maybe merge the data.
			if ( ! empty( $this->items ) ) {
				$this->items = array_merge( $this->items, $details );
			} else {

				// Override details.
				$this->items = $details;
			}
		}
	}
}