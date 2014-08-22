<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColSold_leased_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_rental_leased_isleased() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental status="leased"/>' );
		
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 9 ) );
	}
	/**
	 * @test
	 */
	public function getValue_holidayRental_leased_isleased() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental status="leased"/>' );
		
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 9 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_leased_isleased() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial status="leased"/>' );
		
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 9 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_leased_isleased() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand status="leased"/>' );
		
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 9 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_leased_isleased() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business status="leased"/>' );
		
		// Act
		$col = new ReaxmlEzrColSold ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 9 ) );
	}
}