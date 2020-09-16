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

echo sprintf( '<p>Hi,<br/>Terima kasih sudah mendaftar di %s, silahkan konfirmasi email Anda untuk menyelesaikan pendaftaran</p>', get_bloginfo( 'name' ) );
echo sprintf( '<p>Klik <a href="%s">di sini</a> untuk melakukan verifikasi.', CLIENT_URL . '/validasi?key=' . $args['key'] );