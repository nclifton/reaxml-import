<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColSold_sold_Test extends PHPUnit_Framework_TestCase {
	

	/**
	 * @test
	 */
	public function getValue_residential_sold_issold() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential status="sold"></residential>' );
		
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(5));
	
	}
	/**
	 * @test
	 */
	public function getValue_rural_sold_issold() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rural status="sold"></rural>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(5));
	
	}
	/**
	 * @test
	 */
	public function getValue_land_sold_issold() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land status="sold"></land>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(5));
	
	}

	/**
	 * @test
	 */
	public function getValue_commercial_sold_issold() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial status="sold"></commercial>' );
			
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(5));
	
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_sold_issold() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand status="sold"></commercialLand>' );
			
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(5));
	
	}
	/**
	 * @test
	 */
	public function getValue_business_sold_issold() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business status="sold"></business>' );
			
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(5));
	
	}

}