<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColSold_withdrawn_Test extends PHPUnit_Framework_TestCase {
	

	/**
	 * @test
	 */
	public function getValue_residential_withdrawn_iswithdrawn() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residentual status="withdrawn"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(8));
	
	}
	/**
	 * @test
	 */
	public function getValue_rural_withdrawn_iswithdrawn() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rural status="withdrawn"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(8));
	
	}
	/**
	 * @test
	 */
	public function getValue_land_withdrawn_iswithdrawn() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land status="withdrawn"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(8));
	
	}
	/**
	 * @test
	 */
	public function getValue_rental_withdrawn_iswithdrawn() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rental status="withdrawn"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(8));
	
	}
	/**
	 * @test
	 */
	public function getValue_holidayRental_withdrawn_iswithdrawn() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental status="withdrawn"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(8));
	
	}
	/**
	 * @test
	 */
	public function getValue_commercial_withdrawn_iswithdrawn() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial status="withdrawn"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(8));
	
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_withdrawn_iswithdrawn() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand status="withdrawn"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(8));
	
	}
	/**
	 * @test
	 */
	public function getValue_business_withdrawn_iswithdrawn() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business status="withdrawn"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(8));
	
	}
}