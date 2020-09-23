<?php
/**
 * Class media.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Model;

use Muslimarkt\Result;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Model\Media' ) ) {

	/**
	 * Class Media
	 *
	 * @package Muslimarkt
	 */
	final class Media {
		use Result;

		private $uploaded_file;

		/**
		 * Media constructor.
		 *
		 * @param array $file file that will be uploaded.
		 */
		public function __construct( $file ) {

			// Require core file.
			require_once ABSPATH . 'wp-admin/includes/admin.php';

			// Process upload.
			$upload = wp_handle_upload( $file );

			// Validate upload.
			if ( empty( $upload['error'] ) && empty( $upload['upload_error_handler'] ) ) {

			} else {

				// Save error message.
				$this->message[] = $upload['error'] ? $upload['error'] : '';
				$this->message[] = $upload['upload_error_handler'] ? $upload['upload_error_handler'] : '';
			}

		}

	}
}
