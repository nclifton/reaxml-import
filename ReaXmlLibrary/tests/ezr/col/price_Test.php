<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColPrice_Test extends PHPUnit_Framework_TestCase {


	/**
	 * @test
	 */
	public function getValue_residential() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><price>350000000</price></residential>' );
		
		// Act
		$col = new ReaxmlEzrColPrice ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 350000000 ) );
	}
	/**
	 * @test
	 */
	public function getValue_rural() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rural><price>350000000</price></rural>' );
	
		// Act
		$col = new ReaxmlEzrColPrice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 350000000 ) );
	}
	/**
	 * @test
	 */
	public function getValue_land() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land><price>350000000</price></land>' );
	
		// Act
		$col = new ReaxmlEzrColPrice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 350000000 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><price>350000000</price></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColPrice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 350000000 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercialRent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialRent tax="exclusive" period="annual">36000.00</commercialRent></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColPrice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 36000 ) );
	}
	

	/**
	 * @test
	 */
	public function getValue_business() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business><price>350000000</price></business>' );
	
		// Act
		$col = new ReaxmlEzrColPrice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 350000000 ) );
	}
	/**
	 * @test
	 */
	public function getValue_rental_weekly() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><rent period="weekly">350000000</rent></rental>' );
	
		// Act
		$col = new ReaxmlEzrColPrice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 350000000 ) );
	}
	/**
	 * @test
	 */
	public function getValue_rental_week() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><rent period="week">350000000</rent></rental>' );
	
		// Act
		$col = new ReaxmlEzrColPrice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 350000000 ) );
	}
	/**
	 * @test
	 */
	public function getValue_holidayRental_weekly() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental><rent period="weekly">350000000</rent></holidayRental>' );
	
		// Act
		$col = new ReaxmlEzrColPrice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 350000000 ) );
	}
	/**
	 * @test
	 */
	public function getValue_holidayRental_week() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental><rent period="week">350000000</rent></holidayRental>' );
	
		// Act
		$col = new ReaxmlEzrColPrice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 350000000 ) );
	}
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Price or Rent was not found in the XML. Price or Rent is required for new property listings.
	 */
	public function getValue_residential_notfound_isNew_isexception() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$col->getValue ();
		
	}
	
	/**
	 * @test
	 */
	public function getValue_residential_notfound_isNotNew_isnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Price or Rent was not found in the XML. Price or Rent is required for new property listings.
	 */
	public function getValue_rural_notfound_isNew_isexception() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rural><uniqueID>foo</uniqueID></rural>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$col->getValue ();
	
	}
	
	/**
	 * @test
	 */
	public function getValue_rural_notfound_isNotNew_isnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rural><uniqueID>foo</uniqueID></rural>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Price or Rent was not found in the XML. Price or Rent is required for new property listings.
	 */
	public function getValue_land_notfound_isNew_isexception() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land><uniqueID>foo</uniqueID></land>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$col->getValue ();
	
	}
	
	/**
	 * @test
	 */
	public function getValue_land_notfound_isNotNew_isnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land><uniqueID>foo</uniqueID></land>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Price or Rent was not found in the XML. Price or Rent is required for new property listings.
	 */
	public function getValue_commercial_notfound_isNew_isexception() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><uniqueID>foo</uniqueID></commercial>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$col->getValue ();
	
	}
	
	/**
	 * @test
	 */
	public function getValue_commercial_notfound_isNotNew_isnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><uniqueID>foo</uniqueID></commercial>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Price or Rent was not found in the XML. Price or Rent is required for new property listings.
	 */
	public function getValue_commercialLand_notfound_isNew_isexception() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><uniqueID>foo</uniqueID></commercialLand>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$col->getValue ();
	
	}
	
	/**
	 * @test
	 */
	public function getValue_commercialLand_notfound_isNotNew_isnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><uniqueID>foo</uniqueID></commercialLand>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Price or Rent was not found in the XML. Price or Rent is required for new property listings.
	 */
	public function getValue_business_notfound_isNew_isexception() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business><uniqueID>foo</uniqueID></business>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$col->getValue ();
	
	}
	
	/**
	 * @test
	 */
	public function getValue_business_notfound_isNotNew_isnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business><uniqueID>foo</uniqueID></business>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Price or Rent was not found in the XML. Price or Rent is required for new property listings.
	 */
	public function getValue_rental_notfound_isNew_isexception() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><uniqueID>foo</uniqueID></rental>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$col->getValue ();
	
	}
	
	/**
	 * @test
	 */
	public function getValue_rental_notfound_isNotNew_isnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><uniqueID>foo</uniqueID></rental>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Price or Rent was not found in the XML. Price or Rent is required for new property listings.
	 */
	public function getValue_holidayRental_notfound_isNew_isexception() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental><uniqueID>foo</uniqueID></holidayRental>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$col->getValue ();
	
	}
	
	/**
	 * @test
	 */
	public function getValue_holidayRental_notfound_isNotNew_isnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental><uniqueID>foo</uniqueID></holidayRental>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColPrice ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
}