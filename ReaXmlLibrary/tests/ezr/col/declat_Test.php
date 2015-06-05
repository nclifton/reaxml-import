<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
class ReaxmlEzrColDeclat_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><latitude>-37.6759953</latitude></address></residential>' );
		
		// Act
		$col = new ReaxmlEzrColDeclat ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '-37.6759953', 0.0000001 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_notfound_isNotNew_usemap_gets_null() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$mockMapHelper = $this->getMock ( 'ReaxmlMaphelper' );
		$mockMapHelper->expects ( $this->never () )->method ( 'getLatitude' )->with ( $this->equalTo ( '12 Wyndham Avenue, Leumeah, NSW, 2560, Australia' ) )->willReturn ( - 34.0438697 );
		$config = new ReaxmlConfiguration ();
		$config->usemap = 1;
		
		// Act
		$col = new ReaxmlEzrColDeclat ( $xml, $dbo, $config );
		$col->setMaphelper ( $mockMapHelper );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_isNotNew_no_usemap_gets_null() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$mockMapHelper = $this->getMock ( 'ReaxmlMaphelper' );
		$mockMapHelper->expects ( $this->never () )->method ( 'getLatitude' )->with ( $this->equalTo ( '12 Wyndham Avenue, Leumeah, NSW, 2560, Australia' ) )->willReturn ( - 34.0438697 );
		$config = new ReaxmlConfiguration ();
		$config->usemap = 0;
		
		// Act
		$col = new ReaxmlEzrColDeclat ( $xml, $dbo, $config );
		$col->setMaphelper ( $mockMapHelper );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_isNotNew_always_usemap_gets_from_google() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><address><streetNumber>12</streetNumber><street>Wyndham Avenue</street><suburb>Leumeah</suburb><state>NSW</state><postcode>2560</postcode><country>Australia</country></address></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$mockMapHelper = $this->getMock ( 'ReaxmlMaphelper' );
		$mockMapHelper->expects ( $this->once () )->method ( 'getLatitude' )->with ( $this->equalTo ( '12 Wyndham Avenue, Leumeah, NSW 2560, Australia' ) )->willReturn ( - 34.0438697 );
		$config = new ReaxmlConfiguration ();
		$config->usemap = 2;
		
		// Act
		$col = new ReaxmlEzrColDeclat ( $xml, $dbo, $config );
		$col->setMaphelper ( $mockMapHelper );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( - 34.0438697, 0.0000001 ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_isNew_usemap_gets_from_google() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><address><streetNumber>12</streetNumber><street>Wyndham Avenue</street><suburb>Leumeah</suburb><state>NSW</state><postcode>2560</postcode><country>Australia</country></address></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		$mockMapHelper = $this->getMock ( 'ReaxmlMaphelper' );
		$mockMapHelper->expects ( $this->once () )->method ( 'getLatitude' )->with ( $this->equalTo ( '12 Wyndham Avenue, Leumeah, NSW 2560, Australia' ) )->willReturn ( - 34.0438697 );
		$config = new ReaxmlConfiguration ();
		$config->usemap = 1;
		
		// Act
		$col = new ReaxmlEzrColDeclat ( $xml, $dbo, $config );
		$col->setMaphelper ( $mockMapHelper );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( - 34.0438697, 0.0000001 ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_isNew_no_usemap_gets_empty() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><address><streetNumber>12</streetNumber><street>Wyndham Avenue</street><suburb>Leumeah</suburb><state>NSW</state><postcode>2560</postcode><country>Australia</country></address></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		$mockMapHelper = $this->getMock ( 'ReaxmlMaphelper' );
		$mockMapHelper->expects ( $this->never () )->method ( 'getLatitude' )->with ( $this->equalTo ( '12 Wyndham Avenue, Leumeah, NSW, 2560, Australia' ) )->willReturn ( - 34.0438697 );
		$config = new ReaxmlConfiguration ();
		$config->usemap = 0;
		
		// Act
		$col = new ReaxmlEzrColDeclat ( $xml, $dbo, $config );
		$col->setMaphelper ( $mockMapHelper );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_isNew_always_usemap_gets_from_google() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><address><streetNumber>12</streetNumber><street>Wyndham Avenue</street><suburb>Leumeah</suburb><state>NSW</state><postcode>2560</postcode><country>Australia</country></address></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		$mockMapHelper = $this->getMock ( 'ReaxmlMaphelper' );
		$mockMapHelper->expects ( $this->once () )->method ( 'getLatitude' )->with ( $this->equalTo ( '12 Wyndham Avenue, Leumeah, NSW 2560, Australia' ) )->willReturn ( - 34.0438697 );
		$config = new ReaxmlConfiguration ();
		$config->usemap = 2;
		
		// Act
		$col = new ReaxmlEzrColDeclat ( $xml, $dbo, $config );
		$col->setMaphelper ( $mockMapHelper );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( - 34.0438697, 0.0000001 ) );
	}
}