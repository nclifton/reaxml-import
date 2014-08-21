<?php
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
		$importer = ReaxmlImporter::getInstance ( $configuration );
		$importer->start ();
	
		// Assert
	
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( 'ezrea_ezrealty_images' );
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/files/expected_ezrealty_after_insert_test.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( 'ezrea_ezrealty_images' );
		
		$this->assertThat( $table1->getRowCount() , $this->equalTo($expectedTable1->getRowCount()));
		
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 3 ), 'done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'error' );
		
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
	
	
}