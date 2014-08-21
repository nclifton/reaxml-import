<?php

/**
 * @author nclifton
 *
 */
class ReaxmlDbo_Test extends Reaxml_Tests_DatabaseTestCase {
	
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
		return $this->createMySQLXMLDataSet ( __DIR__ . '/../files/ezrealty-seed.xml' );
	}
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage No match in the database for agent name Mr John Doe
	 */
	public function insert_usingrealrowxml_unregisteredagent_throwsexception() {
		// Arrange
		$xml = simplexml_load_file ( __DIR__ . '/../files/residential_unregagent_sample.xml' );
		$configuration = new ReaxmlConfiguration ();
		$configuration->work_dir = __DIR__ . '/../../files';
		
		// Act
		$dbo = new ReaxmlEzrDbo ();
		$row = new ReaxmlEzrRow ( $xml, $dbo, $configuration );
		$dbo->insert ( $row );
		
		// Assert
	}
	
	/**
	 * @test
	 */
	public function insert_usingrealrowxml() {
		// Arrange
		$xml = simplexml_load_file ( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_insert_sample.xml' );
		$elements = $xml->xpath ( 'business | commercial | commercialLand | land | rental | holidayRental | residential | rural' );
		
		$configuration = new ReaxmlConfiguration ();
		$configuration->work_dir = __DIR__ . '/../../files';
		
		// Act
		$dbo = new ReaxmlEzrDbo ();
		$row = new ReaxmlEzrRow ( $elements [0], $dbo, $configuration );
		$dbo->insert ( $row );
		
		// Assert
		
		// Assert database comtains the new record
		$this->assertEquals ( 2, $this->getConnection ()->getRowCount ( 'ezrea_ezrealty' ), "Inserting failed" );
		
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( 'ezrea_ezrealty' );
		$table2 = $dataSet->getTable ( 'ezrea_ezrealty_incats' );
		$table3 = $dataSet->getTable ( 'ezrea_extensions' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_ezrealty_afterinsertusingrealrowxml.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( 'ezrea_ezrealty' );
		$expectedTable2 = $expectedDataset->getTable ( 'ezrea_ezrealty_incats' );
		$expectedTable3 = $expectedDataset->getTable ( 'ezrea_extensions' );
		$this->assertTablesEqual ( $expectedTable1, $table1 );
		$this->assertTablesEqual ( $expectedTable2, $table2 );
		$this->assertTablesEqual ( $expectedTable3, $table3 );
	}
	private function filterDataset($dataSet) {
		$filterDataSet = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ( $dataSet );
		$filterDataSet->setExcludeColumnsForTable ( 'ezrea_ezrealty', array (
				'hits',
				'flpl1' 
		) );
		$filterDataSet->setIncludeColumnsForTable ( 'ezrea_extensions', array (
				'params' 
		) );
		
		return $filterDataSet;
	}
	
	/**
	 * @test
	 */
	public function updateprice() {
		// Arrange
		$xml = new SimpleXMLElement ( '
	<residential modTime="2009-01-01-12:30:00" status="current">
		<uniqueID>foo</uniqueID>
		<price display="yes">6000000</price>
	</residential>' );
		
		// Act
		$dbo = new ReaxmlEzrDbo ();
		$row = new ReaxmlEzrRow ( $xml, $dbo );
		$dbo->update ( $row );
		
		// Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( 'ezrea_ezrealty' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_ezrealty_after_updateprice.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( 'ezrea_ezrealty' );
		$this->assertTablesEqual ( $expectedTable1, $table1 );
	}
	/**
	 * @test
	 */
	public function insert_images() {
		// Arrange
		$xml = simplexml_load_file ( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_insert_sample.xml' );
		
		$xml = new SimpleXMLElement ( $xml->residential->asXML () );
		
		$dbo = new ReaxmlEzrDbo ();
		$configuration = new ReaxmlConfiguration ();
		$configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'files';
		$row = new ReaxmlEzrRow ( $xml, $dbo, $configuration );
		$images = new ReaxmlEzrImages ( $xml, $dbo, $configuration, $row );
		
		// Act
		$dbo->insert ( $row, $images );
		
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( 'ezrea_ezrealty_images' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_ezrealty_after_insert_test.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( 'ezrea_ezrealty_images' );
		
		$this->assertThat( $table1->getRowCount() , $this->equalTo($expectedTable1->getRowCount()));

	}
}