<?php

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/
jimport ( 'joomla.filesystem.file' );

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
class ReaxmlImporterCli_Test extends Reaxml_Tests_DatabaseTestCase {
	
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
	public function import() {
		$configuration = $this->getConfiguration ();
		$importer = new ReaxmlImporter ();
		$importer->setConfiguration ( $configuration );
		return $importer->import ();
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
	public function import_commercial_pullman() {
		// Arrange
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pullman_201410280550052876573.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'pullman_201410280550052876573.xml' );
		
		// Act
		include __DIR__ . '/../admin/cli/reaxml-importer.php';
		
		// Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty' );
		$table2 = $dataSet->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty_images' );
		
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/files/expected_ezrealty_after_commercial_pullman_insert_test.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty' );
		$expectedTable2 = $expectedDataset->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty_images' );
		
		$this->assertTablesEqual ( $expectedTable1, $table1 );
		$this->assertTablesEqual ( $expectedTable2, $table2 );
		
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 1 ), 'files in done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in error' );
	}
	private function filterDataset($dataSet) {
		$filterDataSet = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ( $dataSet );
		$filterDataSet->setExcludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty', array (
				'hits',
				'flpl1',
				'flpl2' 
		) );
		$filterDataSet->setIncludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions', array (
				'params' 
		) );
		
		$filterDataSet->setExcludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty_images', array (
				'fname' 
		) );
		
		return $filterDataSet;
	}
}