<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * @var bool
	 */
	protected $useDatabase = true;

	/**
	 * @var array
	 */
	protected $user = [
		'username' => 'Admin',
		'password' => '123456',
	];

	/**
	 * @var array
	 */
	protected $admin = [
		'username' => 'User',
		'password' => '123456',
	];

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

	/**
	 * Set up function for all tests
	 */
	public function setUp()
	{
		parent::setUp();

		if ($this->useDatabase) {
			$this->setUpDb();
		}
		// To test auth, we must re-enable filters on the routes
		// By default, filters are disabled in testing
		Route::enableFilters();
	}

	/**
	 * Tear down function for all tests
	 */
	public function tearDown()
	{
		parent::tearDown();
		if ($this->useDatabase) {
			$this->teardownDb();
		}
		Mockery::close();
		Auth::logout();
		Session::flush();
	}

	/**
	 * Set up the database for tests
	 */
	public function setUpDb()
	{
		Artisan::call('migrate');
		Artisan::call('db:seed');
	}

	/**
	 * Tear down the database for tests
	 */
	public function teardownDb()
	{
		Artisan::call('migrate:reset');
	}

	/**
	 * Impersonate a guest
	 */
	public function beGuest() 
	{
		Auth::logout();
		Session::flush();
	}

	/**
	 * Impersonate a user
	 */
	public function beUser() 
	{
		Auth::attempt($this->user);
	}

	/**
	 * Impersonate an admin
	 */
	public function beAdmin() 
	{
		Auth::attempt($this->admin);
	}

}