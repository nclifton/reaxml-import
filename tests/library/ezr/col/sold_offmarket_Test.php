<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColSold_offmarket_Test extends PHPUnit_Framework_TestCase {
	

	/**
	 * @test
	 */
	public function getValue_residential_offmarket_isoffmarket() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential status="offmarket"></residential>' );
		
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(10));
	
	}
	/**
	 * @test
	 */
	public function getValue_rental_offmarket_isoffmarket() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rental status="offmarket"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(10));
	
	}
	/**
	 * @test
	 */
	public function getValue_holidayRental_offmarket_isoffmarket() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental status="offmarket"/>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(10));
	
	}	/**
	 * @test
	 */
	public function getValue_rural_offmarket_isoffmarket() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rural status="offmarket"></rural>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(10));
	
	}
	/**
	 * @test
	 */
	public function getValue_land_offmarket_isoffmarket() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land status="offmarket"></land>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(10));
	
	}

	/**
	 * @test
	 */
	public function getValue_commercial_offmarket_isoffmarket() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial status="offmarket"></commercial>' );
			
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(10));
	
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_offmarket_isoffmarket() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand status="offmarket"></commercialLand>' );
			
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(10));
	
	}
	/**
	 * @test
	 */
	public function getValue_business_offmarket_isoffmarket() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business status="offmarket"></business>' );
			
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(10));
	
	}

}