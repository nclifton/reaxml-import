<?php


class Reaxml_Tests_Selenium_DatabaseTestCase_Helper extends Reaxml_Tests_DatabaseTestCase{
	
	/**
	 * @before
	 */
	public function setUp()
	{
		parent::setUp();
	}
	
	/**
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
    public function getDataSet() {
        return $this->createMySQLXMLDataSet ( __DIR__ . '/../files/ezrealty-minimal-seed.xml' );
    }

	public function loadXMLDataset($file){
		return $this->createMySQLXMLDataSet($file);
	}
	
}