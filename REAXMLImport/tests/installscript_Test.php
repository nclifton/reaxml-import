<?php

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
include_once __DIR__ . '/../installscript.php';
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
		
		$installer = new com_reaxmlImportInstallerScript ();
		$installer->install ( $parent );
		
		// Assert
		
		$dataSet = $this->getConnection ()->createDataSet ();
		$table = $this->filterdataset ( $dataSet )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );
		$expectedDataset = $this->createMySQLXMLDataSet ( __DIR__ . '/files/expected_extensions_afterinstall.xml' );
		$expectedTable = $this->filterdataset ( $expectedDataset )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );
		$this->assertTablesEqual ( $expectedTable, $table );
		
		assertThat(file_exists($filename))
	}
	private function filterdataset($dataset) {
		$filtereddataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ( $dataset );
		$filtereddataset->addIncludeTables ( array (
				$GLOBALS ['DB_TBLPREFIX'] . 'extensions' 
		) );
		$filtereddataset->setIncludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions', array (
				'params' 
		) );
		return $filtereddataset;
	}
}