<?php
/**
 * All NewRelic plugin tests
 */
class AllNewRelicTest extends CakeTestCase {

/**
 * Suite define the tests for this plugin
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All NewRelic test');

		$path = CakePlugin::path('NewRelic') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}

}
