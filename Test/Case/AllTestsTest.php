<?php

class AllTestsTest extends PHPUnit_Framework_TestSuite {
	public static function suite() {
		$suite = new CakeTestSuite('All Tests');
		$pluginPath = App::pluginPath('NewRelic');
		$suite->addTestDirectoryRecursive($pluginPath . 'Test' . DS . 'Case' . DS);
		return $suite;
	}
}