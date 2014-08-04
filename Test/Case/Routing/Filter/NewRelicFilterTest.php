<?php

App::uses('Controller', 'Controller');
App::uses('NewRelicFilter', 'NewRelic.Routing/Filter');

class NewRelicTestController extends Controller {
	public function admin_index() {}
	public function edit($id = null) {}
}

class NewRelicUserTestController extends Controller {
	public function edit($id = null) {}
	public function view($id = null) {}
}

class NewRelicFilterTest extends CakeTestCase {

	protected $prefixes = null;

	protected function _getNewRelicMock() {
		return $this->getMock('NewRelicFilter', array('hasNewRelic', 'nameTransaction', 'ignoreTransaction'));
	}

	public function setUp() {
		$this->prefixes = Configure::read('Routing.prefixes');
		Configure::write('Routing.prefixes', array('admin'));
		parent::setUp();
	}

	public function tearDown() {
		Configure::write('Routing.prefixes', $this->prefixes);
		parent::tearDown();
	}

	public function testNameTransaction() {
		$filter = $this->_getNewRelicMock();
		$filter
			->expects($this->once())
			->method('hasNewRelic')
			->will($this->returnValue(true));
		$filter
			->expects($this->never())
			->method('ignoreTransaction');
		$filter
			->expects($this->once())
			->method('nameTransaction')
			->with('new_relic_test/index');

		$url = '/new_relic_test/index';
		$response = $this->getMock('CakeResponse', array('_sendHeader'));
		$request = new CakeRequest($url);
		$request->addParams(Router::parse($url));
		$event = new CakeEvent('Dispatcher.beforeRequest', $this, compact('request', 'response'));

		$this->assertTrue($filter->beforeDispatch($event));
		$this->assertFalse($event->isStopped());
	}

	public function testMissingNewRelic() {
		$filter = $this->_getNewRelicMock();
		$filter
			->expects($this->once())
			->method('hasNewRelic')
			->will($this->returnValue(false));
		$filter
			->expects($this->never())
			->method('nameTransaction');

		$url = '/new_relic_test/index';
		$response = $this->getMock('CakeResponse', array('_sendHeader'));
		$request = new CakeRequest($url);
		$request->addParams(Router::parse($url));
		$event = new CakeEvent('Dispatcher.beforeRequest', $this, compact('request', 'response'));

		$this->assertTrue($filter->beforeDispatch($event));
		$this->assertFalse($event->isStopped());
	}

	public function testIgnoreRequests() {
		$ignored = Configure::read('NewRelic.ignoreRoutes');
		Configure::write('NewRelic.ignoreRoutes', array(
			'/admin/:controller/:action/*',
			'/:controller/edit/*',
			'/new_relic_user_test/:action/5',
		));

		$testSucceed = array(
			'/new_relic_test/index',
			'/new_relic_user_test/view/3'
		);

		foreach ($testSucceed as $testUrl) {
			$filter = $this->_getNewRelicMock();
			$filter
				->expects($this->once())
				->method('hasNewRelic')
				->will($this->returnValue(true));
			$filter
				->expects($this->never())
				->method('ignoreTransaction');

			$response = $this->getMock('CakeResponse', array('_sendHeader'));
			$request = new CakeRequest($testUrl);
			$request->addParams(Router::parse($testUrl));
			$event = new CakeEvent('Dispatcher.beforeRequest', $this, compact('request', 'response'));

			$this->assertTrue($filter->beforeDispatch($event));
			$this->assertFalse($event->isStopped());
		}

		$testFail = array(
			'/admin/new_relic_test/index',
			'/new_relic_user_test/edit',
			'/new_relic_user_test/edit/3',
			'/new_relic_user_test/view/5'
		);

		foreach ($testFail as $testUrl) {
			$filter = $this->_getNewRelicMock();
			$filter
				->expects($this->once())
				->method('hasNewRelic')
				->will($this->returnValue(true));
			$filter
				->expects($this->once())
				->method('ignoreTransaction');

			$response = $this->getMock('CakeResponse', array('_sendHeader'));
			$request = new CakeRequest($testUrl);
			$request->addParams(Router::parse($testUrl));
			$event = new CakeEvent('Dispatcher.beforeRequest', $this, compact('request', 'response'));

			$this->assertTrue($filter->beforeDispatch($event));
			$this->assertFalse($event->isStopped());
		}

		Configure::write('NewRelic.ignoreRoutes', $ignored);
	}

}