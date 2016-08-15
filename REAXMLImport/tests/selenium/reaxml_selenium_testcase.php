<?php

abstract class reaxml_selenium_TestCase extends PHPUnit_Extensions_Selenium2TestCase{
	private $dbHelper;
	protected $filepattern = 'screenshot_%s_%s.png';


	function __construct(){
		$this->dbHelper = new Reaxml_Tests_Selenium_DatabaseTestCase_Helper();
	}

	protected static function restoreJoomla(){
	    $output = [];
        $exitcode = 0;
        $workDir = realpath(__DIR__ . '/../unite');
        exec('cd ' . $workDir .'&& php unite.php',$output,$exitcode);
        foreach ($output as $line){
            echo $line."\n";
            if ($line == 'Total definitions failed to run        : 1')
                exit(1);
        }

		sleep(2); // wait for clean up to complete
	}

	public function setUp(){
		$this->dbHelper->setUp();
		$this->setBrowser ( 'chrome' );
		$this->setBrowserUrl ( 'http://'.$GLOBALS ['SERVER_NAME'] );
		$this->setHost('localhost');
		$this->setPort(4444);
		$this->setSeleniumServerRequestsTimeout(60);
		$this->setDesiredCapabilities([]);
	}
	
	public function adminLogin() {
		$this->url('http://'.$GLOBALS ['SERVER_NAME'].'/administrator');
		$usernameInput = $this->byId('mod-login-username');
		$usernameInput->value('admin');
		$this->assertEquals('admin', $usernameInput->value());
		$passwordInput = $this->byId('mod-login-password');
		$passwordInput->value("admin");
        $this->byCssSelector('button[tabindex="3"]')->click();
		$pageTitle = $this->byCssSelector('h1.page-title');
		$this->assertRegExp('/Control Panel/', $pageTitle->text());
	}
	public function loadExtension() {
		$this->adminLogin ();
		$link = $this->byLinkText ( 'Extensions' );
		$link->click ();
		$link = $this->byLinkText ( 'Manage' );
		$link->click ();
		sleep(2);
		$pageTitle = $this->byCssSelector ( 'h1.page-title' );
		$this->assertRegExp ( '/Extensions: Install/', $pageTitle->text () );
		$installPackage = $this->byId ( 'install_package' );
        $filespec = realpath(__DIR__ . '/../../../REAXMLPackage/packed/pkg_reaxml-latest.zip');
        $installPackage->value ($filespec);
		$button = $this->byId ( 'installbutton_package' );
		$button->click ();
        sleep(4);
        $message = $this->byCssSelector ( 'div.alert-success .alert-message' );
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
	protected function saveScreenshot($suffix=''){
		sleep(2);
		$filedata = $this->currentScreenshot();
		$file= sprintf($this->filepattern,$this->getName().$suffix,time());

		file_put_contents($file, $filedata);

		print("Screen shot saved to ".$file."\n");
	}
}
?>