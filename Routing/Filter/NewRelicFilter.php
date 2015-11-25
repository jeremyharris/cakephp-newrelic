<?php

App::uses('DispatcherFilter', 'Routing');

class NewRelicFilter extends DispatcherFilter {

/**
 * Handles current transaction
 *
 * @param CakeEvent $event Dispatch event
 * @return true
 */
	public function beforeDispatch(CakeEvent $event) {
		$request = $event->data['request'];
		$response = $event->data['response'];

		if (!$this->hasNewRelic()) {
			return true;
		}

		// Set NewRelic appName
		$appName = Configure::read('NewRelic.appName');
		if( ! empty($appName)) {
			$this->setAppName($appName);
		}

		$ignored = Configure::read('NewRelic.ignoreRoutes');
		$url = '/' . $event->data['request']->url;
		if (!empty($ignored)) {
			foreach ($ignored as $ignoreTest) {
				$cakeRoute = new CakeRoute($ignoreTest);
				if ($cakeRoute->parse($url) !== false) {
					$this->ignoreTransaction();
					continue;
				}
			}

		}

		$this->nameTransaction($request->controller . '/' . $request->action);

		return true;
	}

/**
 * Ignores the current transaction
 *
 * @return bool
 */
	public function ignoreTransaction() {
		return newrelic_ignore_transaction();
	}

/**
 * Renames the transaction
 *
 * @param string $name Transaction name
 * @return bool
 */
	public function nameTransaction($name) {
		return newrelic_name_transaction($name);
	}

/**
 * Checks for new relic extension
 *
 * @return bool
 */
	public function hasNewRelic() {
		return extension_loaded('newrelic');
	}
/**
 * Set NewRelic AppName
 *
 * @param string $name Application name
 * @return boolean
 */
    public function setAppName($name) {
        return newrelic_set_appname($name);
    }

}
