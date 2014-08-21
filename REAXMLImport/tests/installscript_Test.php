<?php
class ReaxmlInstaller_Test extends Reaxml_Tests_DatabaseTestCase {
	
	/**
	 * @before
	 */
	public function setUp() {
		parent::setUp ();
	}
	/**
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	public function getDataSet() {
		return $this->createMySQLXMLDataSet ( __DIR__ . '/files/extensions-seed.xml' );
	}
	/**
	 * @test
	 */
	public function install() {
		// Arrange
		$parent = null;
		
		// Act
		$installer = new lib_reaxmlInstallerScript ();
		$installer->install ( $parent );
		
		// Assert
		
		$dataSet = $this->getConnection ()->createDataSet ();
		$table = $this->filterdataset($dataSet)->getTable ( 'ezrea_extensions' );
		$expectedDataset = $this->createMySQLXMLDataSet ( __DIR__ . '/files/expected_extensions_afterinstall.xml' );
		$expectedTable = $this->filterdataset($expectedDataset)->getTable ( 'ezrea_extensions' );
		$this->assertTablesEqual ( $expectedTable, $table );
	}
	
	private function filterdataset($dataset){
		
		$filtereddataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter($dataset);
		$filtereddataset->addIncludeTables(array('ezrea_extensions'));
		$filtereddataset->setIncludeColumnsForTable('ezrea_extensions', array('params'));
		return $filtereddataset;
	}
}