<?php
/**
 * Class Employee
 * Class to manager employee data.
 *
 * @author Rendy
 * @package Muslimarkt
 * @version 0.0.1
 */

namespace Muslimarkt\Model;

use Muslimarkt\Abstracts\User;
use Muslimarkt\Result;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Muslimarkt\Model\Employee' ) ) {

	/**
	 * Class User
	 *
	 * @package Muslimarkt
	 */
	final class Employee extends User {
		use Result;

		/**
		 * User detail fields.
		 *
		 * @var array
		 */
		protected $user_fields = array(
			'name',
			'birthday_day',
			'birthday_month',
			'birthday_year',
			'address',
			'phone',
			'identity_type',
			'identity_value',
			'gender',
			'notes',
		);

		/**
		 * User constructor.
		 *
		 * @param int|bool $user_id id of the user.
		 * @param array    $args args which contains information to create a new user.
		 * @param bool     $no_fetch whether skip to get user details value.
		 */
		public function __construct( $user_id = false, $args = array(), $no_fetch = false ) {
			parent::__construct( $user_id, $args, $no_fetch );
		}

		/**
		 * Get user experiences.
		 */
		public function get_experiences() {
			$experiences = new Query(
				array(
					'post_type' => 'experience',
					'author'    => $this->user->ID,
					'order'     => 'ASC',
				),
				false
			);

			// Build the query.
			$query = $experiences->get_query();

			// Reset object.
			$this->items = array();

			// Loop the query.
			while ( $query->have_posts() ) {
				$query->the_post();

				// Instance a new experience.
				$experience = new Experience( $this->user->ID, get_the_ID() );

				// Load experience details.
				$experience->get_details();

				// Store experience details.
				$this->items[] = $experience->items;
			}
			wp_reset_postdata();
		}

		/**
		 * Get user educations.
		 */
		public function get_educations() {
			$educations = new Query(
				array(
					'post_type' => 'education',
					'author'    => $this->user->ID,
					'order'     => 'ASC',
				),
				false
			);

			// Build the query.
			$query = $educations->get_query();

			// Reset object.
			$this->items = array();

			// Loop the query.
			while ( $query->have_posts() ) {
				$query->the_post();

				// Instance a new education.
				$education = new Education( $this->user->ID, get_the_ID() );

				// Load education details.
				$education->get_details();

				// Store education details.
				$this->items[] = $education->items;
			}
			wp_reset_postdata();
		}
	}
}
