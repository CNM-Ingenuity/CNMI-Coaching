<?php


/**
 * Class Tribe__Events__Community__Tickets__Updater
 *
 * @since 4.5.4
 *
 */
class Tribe__Events__Community__Tickets__Updater extends Tribe__Events__Updater {

	protected $version_option = 'tribe-events-community-tickets-schema-version';

	/**
	 * Force upgrade script to run even without an existing version number
	 * The version was not previously stored for Community Tickets
	 *
	 * @since 4.5.4
	 *
	 * @return bool
	 */
	public function is_new_install() {
		return false;
	}
}
