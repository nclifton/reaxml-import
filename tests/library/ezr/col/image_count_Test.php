<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColImage_count_Test extends PHPUnit_Framework_TestCase {
	
	
	/**
	 * @test
	 */
	public function getValue_isnew_notfound_gives0() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColImage_count ( new ReaxmlEzrRow ( $xml, $dbo) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_isnotnew_notfound_givescountfromdb() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'countEzrImagesUsingMls_id' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 2 );
		
		// Act
		$col = new ReaxmlEzrColImage_count ( new ReaxmlEzrRow ( $xml , $dbo) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 2 ) );
		
	}
	/**
	 * @test
	 */
	public function getValue_isnew_finds2images_gives2() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="m" modTime="2009-01-01-12:30:00" file="f1.jpg" format="jpg" /><img id="a" modTime="2009-01-01-12:30:00" file="f2.jpg" format="jpg" /></objects></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColImage_count ( new ReaxmlEzrRow ( $xml , $dbo) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 2 ) );
	
	}
	/**
	 * @test
	 */
	public function getValue_isNotNew_finds2images_3inDb_gives3() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="m" modTime="2009-01-01-12:30:00" file="f1.jpg" format="jpg" /><img id="a" modTime="2009-01-01-12:30:00" file="f2.jpg" format="jpg" /></objects></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'countEzrImagesUsingMls_id' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 3 );
		
		// Act
		$col = new ReaxmlEzrColImage_count ( new ReaxmlEzrRow ( $xml , $dbo) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 3 ) );
	
	}
	/**
	 * @test
	 */
	public function getValue_isNotNew_finds2images_1inDb_gives2() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="m" modTime="2009-01-01-12:30:00" file="f1.jpg" format="jpg" /><img id="a" modTime="2009-01-01-12:30:00" file="f2.jpg" format="jpg" /></objects></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'countEzrImagesUsingMls_id' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 1 );
	
		// Act
		$col = new ReaxmlEzrColImage_count ( new ReaxmlEzrRow ( $xml , $dbo) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 2 ) );
	
	}
}