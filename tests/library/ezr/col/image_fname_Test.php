<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
jimport ( 'joomla.filesystem.file' );
class ReaxmlEzrColImage_fname_Test extends PHPUnit_Framework_TestCase {
	private $file;
	private $thumb;
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
	public function getValue0_isnew_notfound_givesempty() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		$row = new ReaxmlEzrRow ( $xml, $dbo );
		
		// Act
		$col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValueAt ( 0 );
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue0_isnotnew_notfound_givesnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		$row = new ReaxmlEzrRow ( $xml, $dbo );
		
		// Act
		$col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValueAt ( 0 );
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue1_isnew_notfound_givesempty() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="m" modTime="2009-01-01-12:30:00" file="Sample_Floorplan.jpg" format="jpg" /></objects></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo, null ) );
		$value = $col->getValueAt ( 1 );
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue1_isnotnew_notfound_givesnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="m" modTime="2009-01-01-12:30:00" file="Sample_Floorplan.jpg" format="jpg" /></objects></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo, null, new ReaxmlEzrRow ( $xml, $dbo ) ) );
		$value = $col->getValueAt ( 1 );
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue0_filefound_hasformat_copiesfile_givesfilename_generatesthumb() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><objects><img id="m" modTime="2009-01-01-12:30:00" file="Sample_Floorplan.jpg" format="jpg" /></objects></residential>' );
		$dbo = null;
		$configuration = $this->createMock(ReaxmlConfiguration::class);
		$configuration->work_dir = __DIR__ . '/../../files';
        $configuration->expects($this->exactly(1))->method('get')->with($this->equalTo('newthumbwidth'),$this->equalTo(200))->willReturn(200);
		
		// Act
		$col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo, $configuration) );
		$value = $col->getValueAt ( 0 );
		
		// Assert
		
		$file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . $value;
		$found = file_exists ( $file );
		
		$this->assertThat ( $found, $this->isTrue (), 'main' );
		
		$this->file = $file;
		
		$thumb = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . 'th' . DIRECTORY_SEPARATOR . $value;
		$found = file_exists ( $thumb );
		
		$this->assertThat ( $found, $this->isTrue (), 'thumb' );
		
		$this->thumb = $thumb;
	}

    /**
     * @test
     */
    public function getValue0_filefound_noformat_copiesfile_givesfilename_generatesthumb() {
        // Arrange
        $xml = new SimpleXMLElement ( '<residential><objects><img id="m" modTime="2009-01-01-12:30:00" file="Sample_Floorplan.JPG" /></objects></residential>' );
        $dbo = null;
        $configuration = $this->createMock(ReaxmlConfiguration::class);
        $configuration->work_dir = __DIR__ . '/../../files';
        $configuration->expects($this->exactly(1))->method('get')->with($this->equalTo('newthumbwidth'),$this->equalTo(200))->willReturn(200);

        // Act
        $col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo, $configuration) );
        $value = $col->getValueAt ( 0 );

        // Assert

        $file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . $value;
        $found = file_exists ( $file );

        $this->assertThat ( $found, $this->isTrue (), 'main' );

        $this->file = $file;

        $thumb = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . 'th' . DIRECTORY_SEPARATOR . $value;
        $found = file_exists ( $thumb );

        $this->assertThat ( $found, $this->isTrue (), 'thumb' );

        $this->thumb = $thumb;
    }

	/**
	 * @test
	 */
	public function getValue1_file_copiesfile_givesfilename_generatesthumb() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><objects><img id="a" modTime="2009-01-01-12:30:00" file="Sample_Floorplan.jpg" format="jpg" /></objects></residential>' );
		$dbo = null;
        $configuration = $this->createMock(ReaxmlConfiguration::class);
		$configuration->work_dir = __DIR__ . '/../../files';
        $configuration->expects($this->exactly(1))->method('get')->with($this->equalTo('newthumbwidth'),$this->equalTo(200))->willReturn(200);
		
		// Act
		$col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo, $configuration) );
		$value = $col->getValueAt ( 1 );
		
		// Assert
		
		$file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . $value;
		$found = file_exists ( $file );
		
		$this->assertThat ( $found, $this->isTrue (), 'main' );
		
		$this->file = $file;
		
		$thumb = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . 'th' . DIRECTORY_SEPARATOR . $value;
		$found = file_exists ( $thumb );
		
		$this->assertThat ( $found, $this->isTrue (), 'thumb' );
		
		$this->thumb = $thumb;
	}
	
	/**
	 * @test
	 */
	public function getValue0_url_hasformat_copiesfile_givesfilename_generatesthumb() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><objects><img url="http://www.ezblueprint.com/p7lsm_img_2/fullsize/floorplan1_fs.jpg" format="jpg" id="m" /></objects></residential>' );
		$dbo = null;
        $configuration = $this->createMock(ReaxmlConfiguration::class);
		$configuration->work_dir = __DIR__ . '/../../files';
        $configuration->expects($this->exactly(1))->method('get')->with($this->equalTo('newthumbwidth'),$this->equalTo(200))->willReturn(200);

		// Act
		$col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo, $configuration) );
		$value = $col->getValueAt ( 0 );
		
		// Assert
		
		$file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . $value;
		$found = file_exists ( $file );
		
		$this->assertThat ( $found, $this->isTrue (), 'main' );
		
		$this->file = $file;
		
		$thumb = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . 'th' . DIRECTORY_SEPARATOR . $value;
		$found = file_exists ( $thumb );
		
		$this->assertThat ( $found, $this->isTrue (), 'thumb' );
		
		$this->thumb = $thumb;
	}

    /**
     * @test
     */
    public function getValue0_url_hasnoformat_copiesfile_givesfilename_generatesthumb() {
        // Arrange
        $xml = new SimpleXMLElement ( '<residential><objects><img url="http://www.ezblueprint.com/p7lsm_img_2/fullsize/floorplan1_fs.jpg" id="m" /></objects></residential>' );
        $dbo = null;
        $configuration = $this->createMock(ReaxmlConfiguration::class);
        $configuration->work_dir = __DIR__ . '/../../files';
        $configuration->expects($this->exactly(1))->method('get')->with($this->equalTo('newthumbwidth'),$this->equalTo(200))->willReturn(200);

        // Act
        $col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo, $configuration) );
        $value = $col->getValueAt ( 0 );

        // Assert

        $file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . $value;
        $found = file_exists ( $file );

        $this->assertThat ( $found, $this->isTrue (), 'main' );

        $this->file = $file;

        $thumb = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . 'th' . DIRECTORY_SEPARATOR . $value;
        $found = file_exists ( $thumb );

        $this->assertThat ( $found, $this->isTrue (), 'thumb' );

        $this->thumb = $thumb;
    }

	/**
	 * @test
	 */
	public function getValue0_urlnotfound_logserror() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img url="http://www.realestate.com.au/tmp/floorplan1.gif" format="gif" id="m" /></objects></residential>' );
		$dbo = null;
		$configuration = new ReaxmlConfiguration ();
		$configuration->work_dir = __DIR__ . '/../../files';
		
		// Act
		$col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo, $configuration ) );
		$value = $col->getValueAt ( 0 );
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
		
		$this->assertThat ( file_exists ( $this->logfile ), $this->isTrue () );
		$log = file ( $this->logfile );
		
		$found = 0;
		$matches = array ();
		foreach ($log as $value) {
			$found = preg_match ( '/ (ERROR .*)(\b\s$)/', $value, $matches );
			if ($found > 0){
				break;
			}
		}
		$this->assertThat ( $found, $this->greaterThan ( 0 ) );
		$this->assertThat ( $matches [1], $this->equalTo ( 'ERROR copy(http://www.realestate.com.au/tmp/floorplan1.gif): failed to open stream: HTTP request failed! HTTP/1.0 404 Not Found' ) );
	}
	/**
	 * @test
	 */
	public function getValue0_filenotfound_logserror() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img file="someimage.jpg" format="jpg" id="m" /></objects></residential>' );
		$dbo = null;
		$configuration = new ReaxmlConfiguration ();
		$configuration->work_dir = __DIR__ . '/../../files';
		
		// Act
		$col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo, $configuration ) );
		$value = $col->getValueAt ( 0 );
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
		
		sleep ( 2 );
		
		$this->assertThat ( file_exists ( $this->logfile ), $this->isTrue () );
		$log = file ( $this->logfile );
		
		$found = 0;
		$matches = array ();
		foreach ($log as $value) {
			$found = preg_match ( '/ (ERROR .*$)/', $value, $matches );
			if ($found > 0){
				break;
			}
		}
				
		$this->assertThat ( $found, $this->greaterThan ( 0 ) );
		$this->assertThat ( $matches [1], $this->equalTo ( 'ERROR The file someimage.jpg referenced in the XML could not be found. Possibly ommitted from the uploaded package.' ) );
	}
	/**
	 * @test
	 */
	public function getValue0_idonly_isnew_givesempty() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="m" /></objects></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo, null ) );
		$value = $col->getValueAt ( 0 );
		
		// Assert
		
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue0_idmOnly_isNotNew_looksupFilenameAndDeletesMainAndThumb_returnsEmpty() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="m" /></objects></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrImageFnameUsingMls_idAndOrdering' )->with ( $this->equalTo ( 'foo' ), $this->equalTo ( 1 ) )->willReturn ( 'Sample_Floorplan.jpg' );
		$file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . 'Sample_Floorplan.jpg';
		$thumb = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'properties' . DIRECTORY_SEPARATOR . 'th' . DIRECTORY_SEPARATOR . 'Sample_Floorplan.jpg';
		copy ( __DIR__ . '/../../files/Sample_Floorplan.jpg', $file );
		copy ( __DIR__ . '/../../files/Sample_Floorplan.jpg', $thumb );
		$this->file = $file;
		
		// Act
		$col = new ReaxmlEzrColImage_fname ( new ReaxmlEzrRow ( $xml, $dbo, null ) );
		$value = $col->getValueAt ( 0 );
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ), 'return value' );
		$this->assertThat ( count ( glob ( $file ) ), $this->equalTo ( 0 ), 'file ' . $file . ' has been deleted' );
		$this->assertThat ( count ( glob ( $thumb ) ), $this->equalTo ( 0 ), 'thumb ' . $thumb . ' has been deleted' );
	}
	/**
	 * @after
	 */
	public function cleanup() {
		sleep ( 2 );
		if (JFile::exists ( $this->file )) {
			JFile::delete ( $this->file );
		}
		if (JFile::exists ( $this->logfile )) {
			JFile::delete ( $this->logfile );
		}
		if (JFile::exists ( $this->thumb )) {
			JFile::delete ( $this->thumb );
		}
	}
}