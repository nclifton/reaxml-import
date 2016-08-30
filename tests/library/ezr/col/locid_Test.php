<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColLocidTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage address/suburb was not found in the XML
	 */
	public function getValue_notfoundinxml_isNew() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColLocid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$col->getValue ();
	}
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNotNew() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColLocid ( new ReaxmlEzrRow ( $xml , $dbo) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull() );
		
	}
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage address/state was not found in the XML
	 */
	public function getValue_statenotfoundinxml() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><address><suburb>Back of Bourke</suburb></address></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		//$dbo->expects ( $this->once () )->method ( 'lookupEzrLocidUsingLocalityDetails' )->with ( $this->equalTo ( 'Back of Bourke' ) )->willReturn ( false );
	
		// Act
		$col = new ReaxmlEzrColLocid ( new ReaxmlEzrRow ( $xml , $dbo) );
		$col->getValue ();

	
	}
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage address/postcode was not found in the XML
	 */
	public function getValue_postcodenotfoundinxml() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><address><suburb>Back of Bourke</suburb><state>NSW</state></address></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		//$dbo->expects ( $this->once () )->method ( 'lookupEzrLocidUsingLocalityDetails' )->with ( $this->equalTo ( 'Back of Bourke' ) )->willReturn ( false );
	
		// Act
		$col = new ReaxmlEzrColLocid ( new ReaxmlEzrRow ( $xml , $dbo) );
		$col->getValue ();

	
	}
    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage address/country was not found in the XML
     */
    public function getValue_countrynotfoundinxml_isNew_noDefault() {

        // Arrange
        $xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><address><suburb>Back of Bourke</suburb><state>NSW</state><postcode>2999</postcode></address></residential>' );
        $dbo = $this->createMock ( 'ReaxmlEzrDbo' );
        $dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );

        // Act
        $col = new ReaxmlEzrColLocid ( new ReaxmlEzrRow ( $xml , $dbo) );
        $value = $col->getValue ();
        $this->assertThat ( $value, $this->isNull());
    }

    /**
     * @test
     */
    public function getValue_countrynotfoundinxml_isNotNew_noDefault() {

        // Arrange
        $xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><address><suburb>Back of Bourke</suburb><state>NSW</state><postcode>2999</postcode></address></residential>' );
        $dbo = $this->createMock ( 'ReaxmlEzrDbo' );
        $dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );

        // Act
        $col = new ReaxmlEzrColLocid ( new ReaxmlEzrRow ( $xml , $dbo) );
        $value = $col->getValue ();
        $this->assertThat ( $value, $this->isNull());
    }
	/**
	 * @test
	 */
	public function getValue_notfoundindb_addstodb_stateindb() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><suburb>Back of Bourke</suburb><state>NSW</state><postcode>2999</postcode><country>AUSTRALIA</country></address></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrLocidUsingLocalityDetails' )->with ( $this->equalTo ( 'Back of Bourke' ) , $this->equalTo ( '2999' ),  $this->equalTo ( 'NSW' ),  $this->equalTo ( 'AUSTRALIA' ))->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrStidUsingState' )->with ( $this->equalTo ( 'NSW' ) )->willReturn ( 1 );
		$dbo->expects ( $this->once () )->method ( 'insertEzrLocality' )->with ( $this->equalTo ( 'Back of Bourke' ) , $this->equalTo(1), $this->equalTo('2999') )->willReturn ( 77 );
		
		// Act
		$col = new ReaxmlEzrColLocid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();

		// Assert
		$this->assertThat ( $value, $this->equalTo ( 77 ) );
	}

	/**
	 * @test
	 */
	public function getValue_notfoundindb_addlocalitytodb_addstatetodb_countryindb() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><suburb>Back of Bourke</suburb><state>NSW</state><postcode>2999</postcode><country>AUSTRALIA</country></address></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrLocidUsingLocalityDetails' )->with ( $this->equalTo ( 'Back of Bourke' ) , $this->equalTo ( '2999' ),  $this->equalTo ( 'NSW' ),  $this->equalTo ( 'AUSTRALIA' ))->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrStidUsingState' )->with ( $this->equalTo ( 'NSW' ) )->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrCnidUsingCountry' )->with ( $this->equalTo ( 'AUSTRALIA' ) )->willReturn ( 1 );
		$dbo->expects ( $this->once () )->method ( 'insertEzrState' )->with ( $this->equalTo ( 'NSW' ) , $this->equalTo(1) )->willReturn ( 1 );
		$dbo->expects ( $this->once () )->method ( 'insertEzrLocality' )->with ( $this->equalTo ( 'Back of Bourke' ) , $this->equalTo(1), $this->equalTo('2999') )->willReturn ( 77 );
	
		// Act
		$col = new ReaxmlEzrColLocid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 77 ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfoundindb_addlocalitytodb_addstatetodb_addcountrytodb() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><suburb>Back of Bourke</suburb><state>NSW</state><postcode>2999</postcode><country>AUSTRALIA</country></address></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrLocidUsingLocalityDetails' )->with ( $this->equalTo ( 'Back of Bourke' ) , $this->equalTo ( '2999' ),  $this->equalTo ( 'NSW' ),  $this->equalTo ( 'AUSTRALIA' ))->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrStidUsingState' )->with ( $this->equalTo ( 'NSW' ) )->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrCnidUsingCountry' )->with ( $this->equalTo ( 'AUSTRALIA' ) )->willReturn (false );
		$dbo->expects ( $this->once () )->method ( 'insertEzrCountry' )->with ( $this->equalTo ( 'AUSTRALIA' ) )->willReturn ( 1 );
		$dbo->expects ( $this->once () )->method ( 'insertEzrState' )->with ( $this->equalTo ( 'NSW' ) , $this->equalTo(1) )->willReturn ( 1 );
		$dbo->expects ( $this->once () )->method ( 'insertEzrLocality' )->with ( $this->equalTo ( 'Back of Bourke' ) , $this->equalTo(1), $this->equalTo('2999') )->willReturn ( 77 );
	
		// Act
		$col = new ReaxmlEzrColLocid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 77 ) );
	}
	/**
	 * @test
	 */
	public function getValue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><suburb>Back of Bourke</suburb><state>NSW</state><postcode>2999</postcode><country>AUSTRALIA</country></address></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrLocidUsingLocalityDetails' )->with ( $this->equalTo ( 'Back of Bourke' ) , $this->equalTo ( '2999' ),  $this->equalTo ( 'NSW' ),  $this->equalTo ( 'AUSTRALIA' ))->willReturn ( 88 );
						
		// Act
		$col = new ReaxmlEzrColLocid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 88 ) );
	}
}