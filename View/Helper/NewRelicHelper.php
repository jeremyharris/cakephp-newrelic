<?php

App::uses('AppHelper', 'View/Helper');

class NewRelicHelper extends AppHelper {

/**
 * Starts user data timer
 *
 * @return string NR JS
 */
	public function start() {
		if ($this->hasNewRelic()) {
			return newrelic_get_browser_timing_header();
		}
		return null;
	}

/**
 * Ends user data timer
 *
 * @return string
 */
	public function end() {
		if ($this->hasNewRelic()) {
			return newrelic_get_browser_timing_footer();
		}
		return null;
	}

/**
 * Checks for new relic extension
 *
 * @return bool
 */
	public function hasNewRelic() {
		return extension_loaded('newrelic');
	}

}
