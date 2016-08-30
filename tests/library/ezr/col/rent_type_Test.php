<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColRent_type_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNew() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<something><uniqueID>foo</uniqueID></something>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNotNew() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<something><uniqueID>foo</uniqueID></something>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	
	/**
	 * @test
	 */
	public function getValue_rental_isLongTerm() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental/>' );
		
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_holidayrental_isShortTerm() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental/>' );
		
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 2 ) );
	}
	/**
	 * @test
	 */
	public function getValue_residential_isNotApplicable() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential/>' );
	
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_rural_isNotApplicable() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rural/>' );
	
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_land_isNotApplicable() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land/>' );
	
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}	
	/**
	 * @test
	 */
	public function getValue_commercial_commercialListingTypeLease_isCommercial() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialListingType value="lease"/></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_isNew_isNotApplicable() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><uniqueID>foo</uniqueID></commercial>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml, $dbo) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_isNotNew_isNull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><uniqueID>foo</uniqueID></commercial>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
	
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml, $dbo) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_commercialListingTypeLease_isCommercial() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><commercialListingType value="lease"/></commercialLand>' );
	
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_isNew_isNotApplicable() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><uniqueID>foo</uniqueID></commercialLand>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
	
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml, $dbo) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_isNotNew_isNull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><uniqueID>foo</uniqueID></commercialLand>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
	
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml, $dbo) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
	/**
	 * @test
	 */
	public function getValue_business_commercialListingTypeLease_isCommercial() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business><commercialListingType value="lease"/></business>' );
	
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_isNew_isNotApplicable() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business><uniqueID>foo</uniqueID></business>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
	
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml, $dbo) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_isNotNew_isNull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business><uniqueID>foo</uniqueID></business>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
	
		// Act
		$col = new ReaxmlEzrColRent_type ( new ReaxmlEzrRow ( $xml, $dbo) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
}