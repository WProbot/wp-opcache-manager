<?php
/**
 * Date handling
 *
 * Handles all date operations.
 *
 * @package System
 * @author  Pierre Lannoy <https://pierre.lannoy.fr/>.
 * @since   1.0.0
 */

namespace OPcacheManager\System;

/**
 * Define the date functionality.
 *
 * Handles all date operations.
 *
 * @package System
 * @author  Pierre Lannoy <https://pierre.lannoy.fr/>.
 * @since   1.0.0
 */
class Date {

	/**
	 * Converts an UTC date into the correct format.
	 *
	 * @param   string  $ts The UTC MySql datetime to be converted.
	 * @param   string  $tz Optional. The timezone.
	 * @param   string  $format Optional. The date format.
	 * @return  string   Formatted date relative to the given timezone.
	 * @since    1.0.0
	 */
	public static function get_date_from_mysql_utc($ts, $tz='', $format='-') {
		if ($format == '-') {
			$format = get_option('date_format');
		}
		if ($tz != '') {
			$datetime = new \DateTime($ts, new \DateTimeZone('UTC'));
			$datetime->setTimezone(new \DateTimeZone($tz));
			return date_i18n($format, strtotime($datetime->format('Y-m-d H:i:s')));
		}
		else {
			return date_i18n($format, strtotime(get_date_from_gmt($ts)));
		}
	}

	/**
	 * Get the difference between now and a date, in human readable style (like "8 minutes ago" or "currently").
	 *
	 * @param   string $from The UTC MySql datetime from which the difference must be computed (as today).
	 * @return  string  Human readable time difference.
	 * @since    1.0.0
	 */
	public static function get_positive_time_diff_from_mysql_utc($from) {
		if (strtotime($from) < time()) {
			return sprintf( esc_html__('%s ago', 'opcache-manager'), human_time_diff(strtotime($from)));
		}
		else {
			return esc_html__('currently', 'opcache-manager');
		}
	}

	/**
	 * Get the difference between now and a date, in human readable style (like "8 minutes ago" or "in 19 seconds").
	 *
	 * @param   string $from The UTC MySql datetime from which the difference must be computed (as today).
	 * @return  string  Human readable time difference.
	 * @since    1.0.0
	 */
	public static function get_time_diff_from_mysql_utc($from) {
		if (strtotime($from) < time()) {
			return sprintf( esc_html__('%s ago', 'opcache-manager'), human_time_diff(strtotime($from)));
		}
		else {
			return sprintf( esc_html__('in %s', 'opcache-manager'), human_time_diff(strtotime($from)));
		}
	}

	/**
	 * Converts a integer number of seconds into an array.
	 *
	 * @param integer $age The age in seconds.
	 * @param boolean $legend Optional. Add the legend.
	 * @param boolean $abbrev Optional. Legend is abbreviated.
	 * @return array Array of days, hours, minutes and seconds.
	 * @since 1.0.0
	 */
	public static function get_age_array_from_seconds( $age, $legend = false, $abbrev = false ) {
		if ( $abbrev ) {
			$intervals = [
				[ 60, _x( 'sec.', 'Abbreviation - Stands for "second".', 'opcache-manager' ), _x( 'sec.', 'Abbreviation - Stands for "second".', 'opcache-manager' ) ],
				[ 60, _x( 'min.', 'Abbreviation - Stands for "minute".', 'opcache-manager' ), _x( 'min.', 'Abbreviation - Stands for "minute".', 'opcache-manager' ) ],
				[ 100000, _x( 'hr.', 'Abbreviation - Stands for "hour".', 'opcache-manager' ), _x( 'hr.', 'Abbreviation - Stands for "hour".', 'opcache-manager' ) ],
			];
		} else {
			$intervals = [
				[ 60, _n( 'second', 'seconds', 1, 'opcache-manager' ), _n( 'second', 'seconds', 2, 'opcache-manager' ) ],
				[ 60, _n( 'minute', 'minutes', 1, 'opcache-manager' ), _n( 'minute', 'minutes', 2, 'opcache-manager' ) ],
				[ 100000, _n( 'hour', 'hours', 1, 'opcache-manager' ), _n( 'hour', 'hours', 2, 'opcache-manager' ) ],
			];
		}
		$value = [];
		foreach ( $intervals as $interval ) {
			$val = $age % $interval[0];
			$age = round( ( $age - $val ) / $interval[0], 0 );
			if ( ( $val > 0 && $legend ) || ( $val >= 0 && ! $legend ) ) {
				$value[] = $val . ( $legend ? ' ' . $interval[ ( 1 === $val ? 1 : 2 ) ] : '' );
			}
		}
		return array_reverse( $value );
	}

}
