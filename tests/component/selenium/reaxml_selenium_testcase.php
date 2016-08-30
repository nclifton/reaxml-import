<?php

abstract class reaxml_selenium_TestCase extends PHPUnit_Extensions_Selenium2TestCase{
    protected $siteHelper;
    private $dbHelper;
	protected $filepattern = 'screenshot_%s_%s.png';


	function __construct(){
		$this->dbHelper = new Reaxml_Tests_Selenium_DatabaseTestCase_Helper();
	}

    public function cleanDirectories()
    {
        foreach (['input', 'work', 'done', 'error', 'log'] as $name) {
            $filename = JPATH_ROOT . '/ftp/' . $name;
            if (file_exists($filename)) {
                $this->recursiveUnlink($filename.'/*');
            }
        }
    }

    /**
     *
     * @return JoomlaSiteHelper
     *
     * @since version
     */
    private function getJoomlaSiteHelper()
    {

        if ($this->siteHelper == null){
            $this->siteHelper = new JoomlaSiteHelper();
        }
        return $this->siteHelper;
    }

    protected function restoreJoomla(){

        echo $this->getJoomlaSiteHelper()->deleteSite('reaxml');
        echo $this->getJoomlaSiteHelper()->createSite('reaxml');
        echo $this->getJoomlaSiteHelper()->installExtension('reaxml',EZREALTY_INSTALL_FILE);

	}


    protected function installExtensionUnderTest()
    {
        echo $this->getJoomlaSiteHelper()->installExtension('reaxml', LIBRARY_INSTALL_FILE);
        echo $this->getJoomlaSiteHelper()->installExtension('reaxml', COMPONENT_INSTALL_FILE);

    }

    protected function linkExtensionUnderTest()
    {
        echo $this->getJoomlaSiteHelper()->extensionSymLink('reaxml', 'com_reaxmlimport');
        echo $this->getJoomlaSiteHelper()->extensionSymLink('reaxml', 'lib_reaxml');
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
        $releasesPath = realpath(__DIR__ . '/../../../releases');
        $libraryZip = $this->getLatestFile($releasesPath .'/lib_reaxml_*.zip');
        $componentZip = $this->getLatestFile($releasesPath . '/com_reaxmlimport_*.zip');

		$link = $this->byLinkText ( 'Extensions' );
		$link->click ();
		$link = $this->byLinkText ( 'Manage' );
		$link->click ();
		sleep(2);
		$pageTitle = $this->byCssSelector ( 'h1.page-title' );
		$this->assertRegExp ( '/Extensions: Install/', $pageTitle->text () );

		$this->byLinkText ( 'Upload Package File' )->click();

        $installPackage = $this->byId('install_package');
        $installPackage->value ($libraryZip);
		$button = $this->byId ( 'installbutton_package' );
		$button->click ();
        sleep(4);
        $message = $this->byCssSelector ( 'div.alert-success .alert-message' );
		$this->assertRegExp ( '/Installation of the library was successful./', $message->text () );

        $installPackage = $this->byId('install_package');
        $installPackage->value ($componentZip);
        $button = $this->byId ( 'installbutton_package' );
        $button->click ();
        sleep(4);
        $message = $this->byCssSelector ( 'div.alert-success .alert-message' );
        $this->assertRegExp ( '/Installation of the component was successful./', $message->text () );


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

    /**
     * @param $pattern
     * @return mixed
     */
    private function getLatestFile($pattern)
    {
        $fmtime = 0;
        $fileNameLatest = null;
        foreach (glob($pattern) as $filename) {
            if ($nfmtime = filemtime($filename) > $fmtime) {
                $fmtime = $nfmtime;
                $fileNameLatest = $filename;
            }
        }
        return $fileNameLatest;
    }

    protected function createDirectories()
    {
        if (!file_exists(JPATH_ROOT . '/ftp')) {
            mkdir(JPATH_ROOT . '/ftp');
        }
        foreach (['input', 'work', 'done', 'error', 'log'] as $name) {
            $filename = JPATH_ROOT . '/ftp/' . $name;
            if (!file_exists($filename)) {
                mkdir(JPATH_ROOT . '/ftp/' . $name);
            }
        }

    }

    protected function recursiveUnlink($pattern)
    {
        foreach (glob_recursive($pattern) as $file) {
            if (!is_dir($file)) {
                unlink($file);
            }
        }
        foreach (glob_recursive($pattern) as $file) {
            try {
                rmdir($file);
            } catch (Exception $e) {
            }
        }
    }


}
?>