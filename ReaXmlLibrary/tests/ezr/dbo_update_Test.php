<?php

/**
 * @author nclifton
 *
 */
class ReaxmlDboUpdate_Test extends Reaxml_Tests_DatabaseTestCase {

	/**
	 * @beforeclass
	 */
	public function classsetup() {
		$dt = new DateTime ();
		$logfile = 'REAXMLImport-' . $dt->format ( 'YmdHis' ) . '.log';
		JLog::addLogger ( array (
				'text_file' => $logfile,
				'text_file_path' => __DIR__ . '/../test_log',
				'text_file_no_php' => true,
				'text_entry_format' => '{DATE} {TIME} {PRIORITY} {MESSAGE}' 
		), JLog::ALL, array (
				REAXML_LOG_CATEGORY 
		) );
	}
	
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
		return $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_ezrealty_afterinsertusingrealrowxml.xml' );
	}
	

	/**
	 * @test
	 */
	public function update_usingrealxml() {
		// Arrange
		$xml = simplexml_load_file ( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_updateprice_sample.xml' );
		$elements = $xml->xpath ( 'business | commercial | commercialLand | land | rental | holidayRental | residential | rural' );
		$configuration = new ReaxmlConfiguration ();
		$configuration->work_dir = __DIR__ . '/../../files';
		
		// Act
		$dbo = new ReaxmlEzrDbo ();
		$row = new ReaxmlEzrRow ( $elements [0], $dbo, $configuration );
		$dbo->update ( $row );
	
		// Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( 'ezrea_ezrealty' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_ezrealty_after_updateusingrealrowxml.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( 'ezrea_ezrealty' );
		$this->assertTablesEqual ( $expectedTable1, $table1 );
	}
	private function filterDataset($dataSet) {
		$filterDataSet = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ( $dataSet );
		$filterDataSet->setExcludeColumnsForTable ( 'ezrea_ezrealty', array (
				'hits'
		) );
		$filterDataSet->setIncludeColumnsForTable ( 'ezrea_extensions', array (
				'params'
		) );
	
		return $filterDataSet;
	}
}