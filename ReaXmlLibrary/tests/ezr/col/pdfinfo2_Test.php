<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
jimport ( 'joomla.filesystem.file' );
class ReaxmlEzrColPdfinfo2_Test extends PHPUnit_Framework_TestCase {
	private $file;
	
	/**
	 * @beforeclass
	 */
	public function setup() {
		$dt = new DateTime ();
		$logfile = 'REAXMLImport-' . $dt->format ( 'YmdHis' ) . '.log';
		JLog::addLogger ( array (
				'text_file' => $logfile,
				'text_file_path' => __DIR__ . '/../../test_log',
				'text_file_no_php' => true,
				'text_entry_format' => '{DATE} {TIME} {PRIORITY} {MESSAGE}' 
		), JLog::ALL, array (
				REAXML_LOG_CATEGORY 
		) );
	}
	
	/**
	 * @test
	 */
	public function getValue_isnew_notfound_givesempty() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColPdfinfo2 ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_isnotnew_notfound_givesnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColPdfinfo2 ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	/**
	 * @test
	 */
	public function getValue_file_copiesfile_givesfilename() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><document file="hello.pdf" format="pdf" id="2" /></objects></residential>' );
		
		$configuration = new ReaxmlConfiguration ();
		$configuration->work_dir = __DIR__ . '/../../files';
		
		// Act
		$col = new ReaxmlEzrColPdfinfo2 ( $xml, null, $configuration );
		$value = $col->getValue ();
		
		// Assert
		
		$file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR . $value;
		
		$this->assertThat ( file_exists ( $file ), $this->isTrue () );
		
		$this->file = $file;
	}
	/**
	 * @test
	 */
	public function getValue_url_copiesfile_givesfilename() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><document url="http://www.newforests.net/wp-content/uploads/2011/01/sample_pdf.pdf" format="pdf" id="2" /></objects></residential>' );
		
		// Act
		$col = new ReaxmlEzrColPdfinfo2 ( $xml );
		$value = $col->getValue ();
		
		// Assert
		
		$file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR . $value;
		$found = file_exists ( $file );
		
		$this->assertThat ( $found, $this->isTrue () );
		
		$this->file = $file;
	}
	/**
	 * @test
	 */
	public function getValue_idonly_isnew_givesempty() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><document id="2" /></objects></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColPdfinfo2 ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_idonly_isnotnew_looksupfilenameanddeletesit_returnsempty() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><document id="2" /></objects></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrPdfinfo2' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 'hello.pdf' );
		$file = JPATH_BASE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezrealty' . DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR . 'hello.pdf';
		copy ( __DIR__ . '/../../files/hello.pdf', $file );
		$this->file = $file;
		
		// Act
		$col = new ReaxmlEzrColPdfinfo2 ( $xml, $dbo );
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
	}
}