<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
class ReaxmlEzrColFreq_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNew_isNotApplicable() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><uniqueID>foo</uniqueID></rental>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNotNew_isnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<something><uniqueID>foo</uniqueID></something>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	
	/**
	 * @test
	 */
	public function getValue_rental_week_perweek() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><rent period="week">3000</rent></rental>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 2 ) );
	}
	/**
	 * @test
	 */
	public function getValue_rental_weekly_perweek() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><rent period="weekly">3000</rent></rental>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 2 ) );
	}
	/**
	 * @test
	 */
	public function getValue_rental_month_permonth() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><rent period="month">3000</rent></rental>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	/**
	 * @test
	 */
	public function getValue_rental_monthly_permonth() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><rent period="monthly">3000</rent></rental>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_holidayRental_week_perweek() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental><rent period="week">3000</rent></holidayRental>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 2 ) );
	}
	/**
	 * @test
	 */
	public function getValue_holidayRental_weekly_perweek() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental><rent period="weekly">3000</rent></holidayRental>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 2 ) );
	}
	/**
	 * @test
	 */
	public function getValue_holidayRental_month_permonth() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental><rent period="month">3000</rent></holidayRental>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	/**
	 * @test
	 */
	public function getValue_holidayRental_monthly_permonth() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental><rent period="monthly">3000</rent></holidayRental>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	/**
	 * @test
	 */
	public function getValue_residential_notApplicable() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential/>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_rural_notApplicable() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rural/>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_land_notApplicable() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<land/>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_nocommercialRental_notApplicable() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><uniqueID>foo</uniqueID></commercial>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_commercialRent_annual() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialRent tax="exclusive" period="annual">36000.00</commercialRent></commercial>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 7 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_commercialRent_defaultannual() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialRent tax="exclusive">36000.00</commercialRent></commercial>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 7 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_commercialRent_rentPerSquareMetre() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialRent tax="exclusive"><rentPerSquareMetre><range><min>88</min><max>99</max></range></rentPerSquareMetre></commercialRent></commercial>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 6 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_notApplicable() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand/>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_notApplicable() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business/>' );
		
		// Act
		$col = new ReaxmlEzrColFreq ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
}