<?php

abstract class reaxml_selenium_TestCase extends PHPUnit_Extensions_Selenium2TestCase{
	private $dbHelper;
	
	function __construct(){
		$this->dbHelper = new Reaxml_Tests_Selenium_DatabaseTestCase_Helper();
	}

	protected static function restoreJoomla(){
		shell_exec('php '.__DIR__.'/../unite/unite.php');
		sleep(2); // wait for clean up to complete
	}

	public function setUp(){
		$this->dbHelper->setUp();
		$this->setBrowser ( 'firefox' );
		$this->setBrowserUrl ( 'http://reaxml.dev' );
		$this->setHost('localhost');
		$this->setPort(4444);
		$this->setSeleniumServerRequestsTimeout(60);
		$this->setDesiredCapabilities([]);
	}
	
	public function adminLogin() {
		$this->url('http://reaxml.dev/administrator');
		$usernameInput = $this->byId('mod-login-username');
		$usernameInput->value('admin');
		$this->assertEquals('admin', $usernameInput->value());
		$passwordInput = $this->byId('mod-login-password');
		$passwordInput->value("admin\r");
		$pageTitle = $this->byCssSelector('h1.page-title');
		$this->assertRegExp('/Control Panel/', $pageTitle->text());
	}
	public function loadExtension() {
		$this->adminLogin ();
		$link = $this->byLinkText ( 'Extensions' );
		$link->click ();
		$link = $this->byLinkText ( 'Extension Manager' );
		$link->click ();
		sleep(1);
		$pageTitle = $this->byCssSelector ( 'h1.page-title' );
		$this->assertRegExp ( '/Extension Manager: Install/', $pageTitle->text () );
		$installPackage = $this->byId ( 'install_package' );
		$installPackage->value ( __DIR__ . '/../../../REAXMLPackage/packed/pkg_reaxml-latest.zip' );
		$button = $this->byCssSelector ( 'input[value="Upload & Install"]' );
		$button->click ();
		$message = $this->byCssSelector ( 'div.alert-success p' );
		$this->assertRegExp ( '/Installation of the package was successful./', $message->text () );
	}
	public function getConnection(){
		return $this->dbHelper->getConnection();
	}
	protected function loadXMLDataSet($file){
		return $this->dbHelper->loadXMLDataSet($file);
	}
	protected function assertTablesEqual ( $expectedTable, $table ){
		$this->dbHelper->assertTablesEqual ( $expectedTable, $table );
	}

}
?>