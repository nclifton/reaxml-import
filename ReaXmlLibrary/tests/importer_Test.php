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
class ReaxmlImporter_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @beforeClass
	 */
	public static function unlinklog() {
		foreach ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_log' . DIRECTORY_SEPARATOR . '*' ) as $file ) {
			unlink ( $file );
		}
	}
	
	/**
	 * @before
	 */
	public function setup() {
		$this->cleanDirectories ();
	}
	
	/**
	 * @skip
	 * @expectedException Exception
	 * @expectedExceptionMessage Log directory junk does not exist
	 */
	public function throws_exception_when_log_dir_doesnt_exist() {
		
		// Arrange
		$configuration = new ReaxmlConfiguration ();
		$configuration->log_dir = 'junk';
		
		// Act
		ReaxmlImporter::getInstance ( $configuration );
		
		// Assert
	}
	
	/**
	 * @skip
	 */
	public function finds_files_to_import() {
		
		// Arrange
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'Sample_Floorplan.jpg', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'Sample_Floorplan.jpg' );
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_insert_sample.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'residential_insert_sample.xml' );
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'junk.txt', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'junk.txt' );
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'hello.pdf', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'hello.pdf' );
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'hello.doc', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'hello.doc' );
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'hello.xls', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'hello.xls' );
		
		$configuration = new ReaxmlConfiguration ();
		$configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
		$configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
		$configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
		$configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
		$configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
		
		// Act
		$importer = ReaxmlImporter::getInstance ( $configuration );
		$importer->moveInputToWork ();
		
		// Assert
		
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 1 ), 'input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 5 ), 'work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'error' );
	}
	
	/**
	 * @skip
	 */
	public function opens_and_moves_zip_files() {
		// Arrange
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'Sample.zip', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'Sample.zip' );
		
		$configuration = new ReaxmlConfiguration ();
		$configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
		$configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
		$configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
		$configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
		$configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
		
		// Act
		$importer = ReaxmlImporter::getInstance ( $configuration );
		$importer->moveInputToWork ();
		
		// Assert
		
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 6 ), 'work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'error' );
	}
	/**
	 * @test
	 */
	public function import_fromazipfile() {
		// Arrange
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'Sample.zip', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'Sample.zip' );
		
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
		
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 6 ), 'done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'error' );
	}
	/**
	 * @skip
	 */
	public function import_updateprice() {
		// Arrange
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_updateprice_sample.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'residential_updateprice_sample.xml' );
		
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
		
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 1 ), 'done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'error' );
	}
	
	/**
	 * @skip
	 */
	public function import_updatesold() {
		// Arrange
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_updatelistingsold_sample.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'residential_updatelistingsold_sample.xml' );
		
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
		
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 1 ), 'done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'error' );
	}
	/**
	 * @skip
	 */
	public function import_updatewithdrawn() {
		// Arrange
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_updatelistingwithdrawn_sample.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'residential_updatelistingwithdrawn_sample.xml' );
		
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
		
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 1 ), 'done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'error' );
	}
	
	/**
	 * @after
	 */
	public function teardown() {
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
	public function cleanDirectories() {
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . 'test_log' . DIRECTORY_SEPARATOR . '*' );
	}
}