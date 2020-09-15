<?php
/**
 * After registration email template.
 *
 * @author Rendy
 * @package Muslimarkt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo sprintf( '<p>Hi %s, Terima kasih sudah mendaftar di %s, silahkan konfirmasi email Anda untuk menyelesaikan pendaftaran</p>', $args['name'], get_bloginfo( 'name' ) );