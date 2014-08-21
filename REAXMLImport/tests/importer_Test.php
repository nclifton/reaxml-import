<?php
jimport ( 'joomla.filesystem.file' );
class ReaxmlImporter_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @beforeclass
	 */
	public function setuplog() {
		$dt = new DateTime ();
		$logfile = 'REAXMLImport-' . $dt->format ( 'YmdHis' ) . '.log';
		JLog::addLogger ( array (
				'text_file' => $logfile,
				'text_file_path' => sys_get_temp_dir (),
				'text_file_no_php' => true,
				'text_entry_format' => '{DATE} {TIME} {PRIORITY} {MESSAGE}' 
		), JLog::ALL, array (
				REAXML_LOG_CATEGORY 
		) );
	}
	
	/**
	 * @test
	 * @expectedException Exception
	 * @expectedExceptionMessage Log directory junk does not exist
	 */
	public function throws_exception_when_log_dir_doesnt_exist() {
		
		// Arrange
		$configuration = new ReaxmlConfiguration ();
		$configuration->log_dir = 'junk';
		
		// Act
		$importer = ReaxmlImporter::getInstance ( $configuration );
		
		// Assert
	}
	
	/**
	 * @test
	 */
	public function finds_files_to_import() {
		
		// Arrange
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'Sample_Floorplan.jpg', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'Sample_Floorplan.jpg' );
		copy ( __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_sample.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'residential_sample.xml' );
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
		
		$this->assertThat ( count ( glob ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 1 ), 'input' );
		$this->assertThat ( count ( glob ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 5 ), 'work' );
		$this->assertThat ( count ( glob ( __DIR__ . DIRECTORY_SEPARATOR . 'test_log' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 1 ), 'log' );
		$this->assertThat ( count ( glob ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'done' );
		$this->assertThat ( count ( glob ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'error' );
	}
	
	/**
	 * @after
	 */
	public function teardown() {
		$this->cleanDirectories ();
	}
	/**
	 * @before
	 */
	public function setup() {
		$this->cleanDirectories ();
	}
	public function cleanDirectories() {
		foreach ( glob ( __DIR__ . DIRECTORY_SEPARATOR . 'test_log' . DIRECTORY_SEPARATOR . '*' ) as $file ) {
			unlink ( $file );
		}
		
		foreach ( glob ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) as $file ) {
			unlink ( $file );
		}
		
		foreach ( glob ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) as $file ) {
			unlink ( $file );
		}
		
		foreach ( glob ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) as $file ) {
			unlink ( $file );
		}
	}
}