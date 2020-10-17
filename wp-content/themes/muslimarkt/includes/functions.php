<?php

use Muslimarkt\Helper;
use Muslimarkt\Model;

/**
 * Collection of useful functions.
 *
 * @author Rendy
 * @package Muslimakrt
 */

if ( ! function_exists( 'mm_get_vacancies' ) ) {

	/**
	 * Get all available vacancies.
	 *
	 * @return array
	 */
	function mm_get_vacancies() {

		$result = array();

		// Instance posts.
		$posts     = new Model\Posts( 'job' );
		$vacancies = $posts->get_all_posts();

		// Validate vacancies.
		if ( $vacancies->have_posts() ) {

			// Loop vacancies.
			while ( $vacancies->have_posts() ) {
				$vacancies->the_post();

				// Instance a new job.
				$job = new Model\Job( false, get_the_ID() );

				// Get job details.
				$job->get_details();

				// Add into result variable.
				$result[] = $job->items;
			}
			wp_reset_postdata();
		}

		return $result;
	}
}

if ( ! function_exists( 'mm_is_employer' ) ) {

	/**
	 * Check whether user is employer or employee.
	 *
	 * @param int $user_id id of the user.
	 *
	 * @return bool
	 */
	function mm_is_employer( $user_id ) {
		return Helper::get_user_meta( 'recruiter', $user_id );
	}
}