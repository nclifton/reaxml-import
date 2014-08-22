<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColSold_current_Test extends PHPUnit_Framework_TestCase {
	

	/**
	 * @test
	 */
	public function getValue_residential_current_iscurrent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residentual status="current"/>' );
		
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	
	}
	/**
	 * @test
	 */
	public function getValue_rural_current_iscurrent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rural status="current"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	
	}
	/**
	 * @test
	 */
	public function getValue_land_current_iscurrent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land status="current"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	
	}
	/**
	 * @test
	 */
	public function getValue_rental_current_iscurrent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rental status="current"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	
	}
	/**
	 * @test
	 */
	public function getValue_holidayRental_current_iscurrent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental status="current"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	
	}
	/**
	 * @test
	 */
	public function getValue_commercial_current_iscurrent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial status="current"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_current_iscurrent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand status="current"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	
	}
	/**
	 * @test
	 */
	public function getValue_business_current_iscurrent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business status="current"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	
	}

}