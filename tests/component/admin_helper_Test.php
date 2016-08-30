<?php

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */

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
		return $this->createMySQLXMLDataSet ( __DIR__ . '/files/admin-helper-seed.xml' );
	}
	
	/**
	 * @test
	 */
	public function updatesUpdateSitesExtraQuery(){
		//Arrange
		
		//Act
		ReaXmlImportHelpersAdmin::updateUpdateSiteWithDownloadId('com_reaxmlimport');		
				
		//Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'update_sites' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/files/expected-UpdateSites-afterUpdateWithDownloadId.xml' ) );	
		$expectedTable = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'update_sites' );

        for($ri=0;$ri<$expectedTable->getRowCount();$ri++){
            $expectedRow = $expectedTable->getRow($ri);
            $row = $table->getRow($ri);
            foreach ($expectedTable->getTableMetaData()->getColumns() as $column){
                $expectedValue = $expectedRow[$column];
                $value = $row[$column];
                $this->assertThat($value,$this->equalTo($expectedValue),"row:$ri column:$column");
            }
        }
		
	}
	
	private function filterDataset($dataSet) {
		$filterDataSet = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ( $dataSet );

	
		return $filterDataSet;
	}
}

?>