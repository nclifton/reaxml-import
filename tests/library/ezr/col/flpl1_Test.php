<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
jimport ( 'joomla.filesystem.file' );
class ReaxmlEzrColFlpl1_Test extends PHPUnit_Framework_TestCase {
	private $file;
	private $logfile;
	
	/**
	 * @before
	 */
	public function setup() {
		$dt = new DateTime ();
		$this->logfile = 'REAXMLImport-' . $dt->format ( 'YmdHis' ) . '.log';
		JLog::addLogger ( array (
				'text_file' => $this->logfile,
				'text_file_path' => __DIR__ . '/../../test_log',
				'text_file_no_php' => true,
				'text_entry_format' => '{DATE} {TIME} {PRIORITY} {MESSAGE}' 
		), JLog::ALL, array (
				REAXML_LOG_CATEGORY 
		) );
		
		$this->logfile = __DIR__ . '/../../test_log' . DIRECTORY_SEPARATOR . $this->logfile;
	}
	
	/**
	 * @test
	 */
	public function getValue_isnew_notfound_givesempty() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColFlpl1 ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_file_copiesfile_givesfilename() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><floorplan file="Sample_Floorplan.jpg" format="jpg" id="1" /></objects></residential>' );
		$dbo = null;
		$configuration = new ReaxmlConfiguration ();
		$configuration->work_dir = __DIR__ . '/../../files';
		
		// Act
		$col = new ReaxmlEzrColFlpl1 ( new ReaxmlEzrRow ( $xml, $dbo, $configuration ) );
		$value = $col->getValue ();
		
		// Assert
		
		$file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'floorplans' . DIRECTORY_SEPARATOR . $value;

		$this->assertFileExists ( $file );
		
		$this->file = $file;
	}
    /**
     * @test
     */
    public function getValue_file_copiesfile_givesfilename() {
        // Arrange
        $xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><floorplan file="Sample_Floorplan.jpg" format="jpg" id="1" /></objects></residential>' );
        $dbo = null;
        $configuration = new ReaxmlConfiguration ();
        $configuration->work_dir = __DIR__ . '/../../files';

        // Act
        $col = new ReaxmlEzrColFlpl1 ( new ReaxmlEzrRow ( $xml, $dbo, $configuration ) );
        $value = $col->getValue ();

        // Assert

        $file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'floorplans' . DIRECTORY_SEPARATOR . $value;

        $this->assertFileExists ( $file );

        $this->file = $file;
    }
	/**
	 * @test
	 */
	public function getValue_url_copiesfile_givesfilename() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><floorplan url="http://www.ezblueprint.com/p7lsm_img_2/fullsize/floorplan1_fs.jpg" format="jpg" id="1" /></objects></residential>' );
		$dbo = null;
		$configuration = new ReaxmlConfiguration ();
		$configuration->work_dir = __DIR__ . '/../../files';
		
		// Act
		$col = new ReaxmlEzrColFlpl1 ( new ReaxmlEzrRow ( $xml, $dbo, $configuration ) );
		$value = $col->getValue ();
		
		// Assert
		
		$file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'floorplans' . DIRECTORY_SEPARATOR . $value;
		$found = file_exists ( $file );
		
		$this->assertThat ( $found, $this->isTrue () );
		
		$this->file = $file;
	}
	/**
	 * @test
	 */
	public function getValue_urlnotfound_logserror() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><floorplan url="http://www.realestate.com.au/tmp/floorplan1.gif" format="jpg" id="1" /></objects></residential>' );
		$dbo = null;
		$configuration = new ReaxmlConfiguration ();
		$configuration->work_dir = __DIR__ . '/../../files';
		
		// Act
		$col = new ReaxmlEzrColFlpl1 ( new ReaxmlEzrRow ( $xml, $dbo, $configuration ) );
		$col->getValue ();
		
		sleep(3);
		
		// Assert
		
		$this->assertThat ( file_exists ( $this->logfile ), $this->isTrue () );
		$log = file ( $this->logfile );
		$matches = array();
		$found = preg_match('/ (ERROR .*)(\b\s$)/', $log [count ( $log ) - 2],$matches);
		
		$this->assertThat($found , $this->greaterThan(0));
		$this->assertThat ( $matches[1], $this->equalTo ( 'ERROR copy(http://www.realestate.com.au/tmp/floorplan1.gif): failed to open stream: HTTP request failed! HTTP/1.0 404 Not Found' ) );
	}
	/**
	 * @test
	 */
	public function getValue_idonly_isnew_givesempty() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><floorplan id="1" /></objects></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColFlpl1 ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_idonly_isnotnew_looksupfilenameanddeletesit_returnsempty() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><floorplan id="1" /></objects></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrFlpl1' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 'Sample_Floorplan.jpg' );
		$file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'floorplans' . DIRECTORY_SEPARATOR . 'Sample_Floorplan.jpg';
		copy ( __DIR__ . '/../../files/Sample_Floorplan.jpg', $file );
		$this->file = $file;
		
		// Act
		$col = new ReaxmlEzrColFlpl1 ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ), 'return value' );
		$this->assertThat ( count ( glob ( $file ) ), $this->equalTo ( 0 ), 'file has been deleted' );
	}
	/**
	 * @after
	 */
	public function cleanup() {
		if (JFile::exists ( $this->file )) {
			JFile::delete ( $this->file );
		}
		if (JFile::exists ( $this->logfile )) {
			JFile::delete ( $this->logfile );
		}
	}
}