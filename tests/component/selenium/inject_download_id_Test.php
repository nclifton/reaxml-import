<?php
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class inject_download_id_Test extends reaxml_selenium_TestCase {
	
	
	/**
	 * @after
	 */
	public static function after(){
	}
		
	/**
	 * @before
	 */
	public function before() {
        $this->restoreJoomla ();
        $this->createDirectories();
        $this->installExtensionUnderTest();
        $this->linkExtensionUnderTest();
        parent::setup();
	}

	/**
	 * @test
	 */
	public function can_enter_and_save_download_id() {
		$this->timeouts()->implicitWait(10000);
        $this->adminLogin ();
		$link = $this->byLinkText ( 'Components' );
		$link->click ();
        sleep(2);
		$link = $this->byLinkText ( 'REAXML Import' );
		$link->click ();
        sleep(2);
        $button = $this->byXpath ( '//button[contains(.,"Options")]' );
		$button->click ();
        sleep(2);
        $updateTab = $this->byLinkText ( 'Update' );
		$updateTab->click ();
        sleep(2);
        $this->assertTrue ( $this->byId ( 'update' )->displayed () );
		$this->assertTrue ( $this->byId ( 'jform_update_dlid-lbl' )->displayed () );
		$labelTitle = $this->byID ( 'jform_update_dlid-lbl' )->attribute ( 'data-original-title' );
        $labelContent = $this->byID ( 'jform_update_dlid-lbl' )->attribute ( 'data-content' );


        $this->assertEquals ( 'Download ID', $labelTitle );
        $this->assertEquals ( 'This is required to enable live updates of this extension. Please visit '
            . 'https://cliftonwebfoundry.com.au/profile to get your personal Download ID.', $labelContent );
		$this->assertRegExp ( '/Download ID/', $this->byId ( 'jform_update_dlid-lbl' )->text () );
		$this->assertTrue ( $this->byId ( 'jform_update_dlid' )->displayed () );
		$this->byId ( 'jform_update_dlid' )->value ( '9fea9accd48b745c0c5d71f2213e2c3d' );
		$this->byXpath ( '//button[contains(.,"Save & Close")]' )->click ();
		
		$dataSet = $this->getConnection ()->createDataSet ();
		$table = $this->filterdataset($dataSet)->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'update_sites' );
		$expectedDataset = $this->loadXMLDataSet ( __DIR__ . '/../files/expected-UpdateSites-afterUpdateWithDownloadId.xml' );
		$expectedTable = $this->filterdataset($expectedDataset)->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'update_sites' );
		$this->assertTablesEqual ( $expectedTable, $table );
		
	}
	private function filterdataset($dataset) {
		$filtereddataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ( $dataset );
		$filtereddataset->addIncludeTables ( array (
				$GLOBALS ['DB_TBLPREFIX'] . 'update_sites'
		) );
		$filtereddataset->setIncludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'update_sites', array (
				'extra_query'
		) );
		return $filtereddataset;
	}

}
?>