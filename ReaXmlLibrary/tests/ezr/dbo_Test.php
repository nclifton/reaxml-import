<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
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
	 */
	public function insert_usingrealrowxml() {
		// Arrange
		$xml = simplexml_load_file ( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_insert_sample.xml' );
		$elements = $xml->xpath ( 'business | commercial | commercialLand | land | rental | holidayRental | residential | rural' );
		
		$configuration = new ReaxmlConfiguration ();
		$configuration->work_dir = __DIR__ . '/../../files';
		$configuration = new ReaxmlConfiguration();
		$configuration->usemap=1;
		
		// Act
		$dbo = new ReaxmlEzrDbo ();
		$row = new ReaxmlEzrRow ( $elements [0], $dbo, $configuration );
		$dbo->insert ( $row );
		
		// Assert
		
		// Assert database comtains the new record
		$this->assertEquals ( 4, $this->getConnection ()->getRowCount ( $GLOBALS['DB_TBLPREFIX'].'ezrealty' ), "Inserting failed" );
		
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$dataSet->setExcludeColumnsForTable($GLOBALS['DB_TBLPREFIX'].'ezrealty', array('propdesc', 'smalldesc'));
		$table1 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty' );
		$table2 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_incats' );
		$table3 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'extensions' );
		
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_ezrealty_afterinsertusingrealrowxml.xml' ) );
		$expectedDataset->setExcludeColumnsForTable($GLOBALS['DB_TBLPREFIX'].'ezrealty', array('propdesc', 'smalldesc'));
		$expectedTable1 = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty' );
		$expectedTable2 = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_incats' );
		$expectedTable3 = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'extensions' );
		
		$this->assertTablesEqual ( $expectedTable1, $table1 );
		$this->assertTablesEqual ( $expectedTable2, $table2 );
		$this->assertTablesEqual ( $expectedTable3, $table3 );
	}
	private function filterDataset($dataSet) {
		$filterDataSet = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ( $dataSet );
		$filterDataSet->setExcludeColumnsForTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty', array (
				'hits',
				'flpl1' 
		) );
		$filterDataSet->setIncludeColumnsForTable ( $GLOBALS['DB_TBLPREFIX'].'extensions', array (
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
		$table1 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_ezrealty_after_updateprice.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty' );
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
		$configuration = new ReaxmlConfiguration();
		$configuration->usemap=0;
		
		// Act
		$dbo->insert ( $row, $images );
		
		// Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_images' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_ezrealty_after_insert_test.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_images' );
		
		$this->assertThat( $table1->getRowCount() , $this->equalTo($expectedTable1->getRowCount()));

	}
	
	/**
	 * @test
	 */
	public function inserts_new_locality (){
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		
		// Act
		$dbo->insertEzrLocality("Back of Bourke",1,'2999');
		
		// Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_locality' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_locality_after_insert_locality_test.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_locality' );
		
		$this->assertTablesEqual ( $expectedTable1, $table1 );
						
		
	}
	/**
	 * @test
	 */
	public function inserts_new_state (){
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
	
	
		// Act
		$dbo->insertEzrState("ZIM",1);
	
		// Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_state' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_state_after_insert_state_test.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_state' );
	
		$this->assertTablesEqual ( $expectedTable1, $table1 );
	
	
	}
	/**
	 * @test
	 */
	public function inserts_new_country (){
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
	
	
		// Act
		$dbo->insertEzrCountry("Lilyput");
	
		// Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_country' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( realpath(__DIR__ . '/../files/expected_country_after_insert_Country_test.xml') ) );
		$expectedTable1 = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_country' );
	
		$this->assertTablesEqual ( $expectedTable1, $table1 );
	
	
	}
	/**
	 * @test
	 */
	public function inserts_new_category() {
		//arrange
		$dbo = new ReaxmlEzrDbo();
		
		// act
		$id = $dbo->insertEzrCategory('Arcology');
		
		// assert
		$this->assertThat($id, $this->equalTo(2),'has assigned an id');
		
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_catg' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_catg_after_insert_category_test.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_catg' );
		
		$this->assertTablesEqual ( $expectedTable1, $table1 );
		
		
		
	}
	/**
	 * @test
	 */
	public function inserts_new_agent_nameonly() {
		//arrange
		$dbo = new ReaxmlEzrDbo();
		
		//act
		$uid = $dbo->insertEzrAgent('Bob Smith');
		
		// assert
		$this->assertThat($uid, $this->equalTo(651),'hass assigned a user id');

		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$ezportalTable = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezportal' );
		$usersTable = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'users' );
		
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_after_insert_new_agent_test.xml' ) );
		$expectedEzportalTable = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezportal' );
		$expectedUsersTable = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'users' );
		
		$this->assertTablesEqual ( $expectedEzportalTable, $ezportalTable );
		$this->assertTablesEqual ( $expectedUsersTable, $usersTable );
		
		
	}
	/**
	 * @test
	 */
	public function inserts_new_agent_nameemailtelephone() {
		//arrange
		$dbo = new ReaxmlEzrDbo();
	
		//act
		$uid = $dbo->insertEzrAgent('Bob Smith','name@domain','1234567890');
	
		// assert
		$this->assertThat($uid, $this->equalTo(651),'has assigned a user id');
	
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$ezportalTable = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezportal' );
		$usersTable = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'users' );
	
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/../files/expected_after_insert_new_agent_specifiedemailtelephone_test.xml' ) );
		$expectedEzportalTable = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezportal' );
		$expectedUsersTable = $expectedDataset->getTable ( $GLOBALS['DB_TBLPREFIX'].'users' );
	
		$this->assertTablesEqual ( $expectedEzportalTable, $ezportalTable );
		$this->assertTablesEqual ( $expectedUsersTable, $usersTable );
	
	
	}
	
}