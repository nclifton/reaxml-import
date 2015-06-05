<?php
/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/
if (! function_exists ( 'glob_recursive' )) {
	// Does not support flag GLOB_BRACE
	function glob_recursive($pattern, $flags = 0) {
		$files = glob ( $pattern, $flags );
		foreach ( glob ( dirname ( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir ) {
			$files = array_merge ( $files, glob_recursive ( $dir . '/' . basename ( $pattern ), $flags ) );
		}
		return $files;
	}
}
/**
 *
 * @author nclifton
 *        
 */
class ReaxmlImporter_db_Test extends Reaxml_Tests_DatabaseTestCase {
	
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
	private function recursiveUnlink($pattern) {
		foreach ( glob_recursive ( $pattern ) as $file ) {
			if (! is_dir ( $file )) {
				unlink ( $file );
			}
		}
		foreach ( glob_recursive ( $pattern ) as $file ) {
			try {
				rmdir ( $file );
			} catch ( Exception $e ) {
			}
		}
	}
	/**
	 * @before
	 */
	public function setUp() {
		parent::setUp ();
		$this->cleanDirectories ();
	}
	
	public function cleanDirectories() {
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . 'test_log' . DIRECTORY_SEPARATOR . '*' );
	}
	
	/**
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	public function getDataSet() {
		return $this->createMySQLXMLDataSet ( __DIR__ . '/files/ezrealty-seed.xml' );
	}
	
	/**
	 * @test
	 */
	public function insert_images() {
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'Sample_Floorplan.jpg', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'Sample_Floorplan.jpg' );
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_insert_sample.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'residential_insert_sample.xml' );
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'camden-camelot-house.jpg', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'camden-camelot-house.jpg' );
		
		$configuration = new ReaxmlConfiguration ();
		$configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
		$configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
		$configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
		$configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
		$configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
		
		// Act
		$importer = new ReaxmlImporter ();
		$importer->setConfiguration ( $configuration );
		$importer->import ();
		
		// Assert
		
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_images' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/files/expected_ezrealty_after_insert_test.xml' ) );
		$expectedTable1 = $expectedDataset->getTable (  $GLOBALS['DB_TBLPREFIX'].'ezrealty_images' );
		
		$this->assertThat ( $table1->getRowCount (), $this->equalTo ( $expectedTable1->getRowCount () ) );
		
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 3 ), 'done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'error' );
	}
	

	/**
	 * @test
	 */
	public function import_commerial_isnew(){

		// Arrange
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'commercial_sample.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'commercial_sample.xml' );
		$configuration = new ReaxmlConfiguration ();
		$configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
		$configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
		$configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
		$configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
		$configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
			
		// Act
		$importer = new ReaxmlImporter ();
		$importer->setConfiguration ( $configuration );
		$importer->import ();
		
		// Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$dataSet->setExcludeColumnsForTable( $GLOBALS['DB_TBLPREFIX'].'ezrealty',array('flpl1','flpl2'));
		$dataSet->setExcludeColumnsForTable( $GLOBALS['DB_TBLPREFIX'].'ezrealty_images',array('fname'));
		$table1 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty' );
		$table2 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_images' );
		
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/files/expected_ezrealty_after_commercial_insert_test.xml' ) );
		$expectedDataset->setExcludeColumnsForTable( $GLOBALS['DB_TBLPREFIX'].'ezrealty',array('flpl1','flpl2'));
		$expectedDataset->setExcludeColumnsForTable( $GLOBALS['DB_TBLPREFIX'].'ezrealty_images',array('fname'));
		$expectedTable1 = $expectedDataset->getTable (  $GLOBALS['DB_TBLPREFIX'].'ezrealty' );
		$expectedTable2 = $expectedDataset->getTable (  $GLOBALS['DB_TBLPREFIX'].'ezrealty_images' );
		
		$this->assertTablesEqual ( $expectedTable1, $table1 );
		$this->assertTablesEqual ( $expectedTable2, $table2 );
		
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 1 ), 'done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'error' );
		
		
	}
	
	
	/**
	 * @skip
	 */
	public function import_commercial_pullman(){
	
		// Arrange
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pullman_201410280550052876573.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'pullman_201410280550052876573.xml' );
		$configuration = new ReaxmlConfiguration ();
		$configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
		$configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
		$configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
		$configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
		$configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
			
		// Act
		$importer = new ReaxmlImporter ();
		$importer->setConfiguration ( $configuration );
		$importer->import ();
	
		// Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty' );
		$table2 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_images' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/files/expected_ezrealty_after_commercial_pullman_insert_test.xml' ) );
		
		$expectedTable1 = $expectedDataset->getTable (  $GLOBALS['DB_TBLPREFIX'].'ezrealty' );
		$expectedTable2 = $expectedDataset->getTable (  $GLOBALS['DB_TBLPREFIX'].'ezrealty_images' );
	
		$this->assertTablesEqual ( $expectedTable1, $table1 );
		$this->assertTablesEqual ( $expectedTable2, $table2 );
	
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 1 ), 'files in done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in error' );
	
	
	}
	
	/**
	 * @skip
	 */
	public function import_ethanproperty(){
	
		// Arrange
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . '14148_14148_20150331092716.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'pullman_201410280550052876573.xml' );
		$configuration = new ReaxmlConfiguration ();
		$configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
		$configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
		$configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
		$configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
		$configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
			
		// Act
		$importer = new ReaxmlImporter ();
		$importer->setConfiguration ( $configuration );
		$importer->import ();
	
		// Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty' );
		$table2 = $dataSet->getTable ( $GLOBALS['DB_TBLPREFIX'].'ezrealty_images' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/files/expected_ezrealty_after_ethanproperty_insert_test.xml' ) );
		$expectedTable1 = $expectedDataset->getTable (  $GLOBALS['DB_TBLPREFIX'].'ezrealty' );
		$expectedTable2 = $expectedDataset->getTable (  $GLOBALS['DB_TBLPREFIX'].'ezrealty_images' );
	
		$this->assertTablesEqual ( $expectedTable1, $table1 );
		$this->assertTablesEqual ( $expectedTable2, $table2 );
	
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 1 ), 'files in done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in error' );
	
	
	}
	
	
	
	private function filterDataset($dataSet) {
		$filterDataSet = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ( $dataSet );
 		$filterDataSet->setIncludeColumnsForTable($GLOBALS['DB_TBLPREFIX'].'ezrealty',array('id','propdesc'));
 		
/*  		$filterDataSet->setExcludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty', array (
				'hits',
				'flpl1' ,'flpl2'
		) ); */
		$filterDataSet->setIncludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions', array (
				'params' 
		) );

		$filterDataSet->setExcludeColumnsForTable( $GLOBALS['DB_TBLPREFIX'].'ezrealty_images',array('fname')); 
		
		return $filterDataSet;
	}
}