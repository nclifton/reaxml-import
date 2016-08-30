<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColSold_underoffer_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_residential_underoffer_isunderoffer() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential status="current"><underOffer value="yes"/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(2));
	
	}
	/**
	 * @test
	 */
	public function getValue_residential_underofferno_iscurrent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential status="current"><underOffer value="no"/></residential>' );
		
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	
	}
	/**
	 * @test
	 */
	public function getValue_rural_underoffer_isunderoffer() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rural status="current"><underOffer value="yes"/></rural>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(2));
	
	}
	/**
	 * @test
	 */
	public function getValue_land_underoffer_isunderoffer() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land status="current"><underOffer value="yes"/></land>' );
	
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(2));
	
	}

	/**
	 * @test
	 */
	public function getValue_commercial_underoffer_isunderoffer() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial status="current"><underOffer value="yes"/></commercial>' );
			
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(2));
	
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_underoffer_isunderoffer() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand status="current"><underOffer value="yes"/></commercialLand>' );
			
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(2));
	
	}
	/**
	 * @test
	 */
	public function getValue_business_underoffer_isunderoffer() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business status="current"><underOffer value="yes"/></business>' );
			
		// Act
		$col = new ReaxmlEzrColSold ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(2));
	
	}

}