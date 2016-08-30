<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColType_business_Test extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function getValue_business_commercialauthorityauction_isauction() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><commercialAuthority value="auction"/></business>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_commercialAuthorityTender_isSaleByTender() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><commercialAuthority value="tender"/></business>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 6 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_commercialAuthorityForsale_isForSale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><commercialAuthority value="forsale"/></business>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_commercialAuthorityEoi_isForSale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><commercialAuthority value="eoi"/></business>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_commercialAuthorityeOffers_isForSale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><commercialAuthority value="offers"/></business>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_commercialAuthoritysale_isForSale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><commercialAuthority value="sale"/></business>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_commercialListingTypeSale_isForSale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><commercialListingType value="sale"/></business>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_commercialListingTypeLease_isForLease() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><commercialListingType value="lease"/></business>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 3 ) );
	}
	/**
	 * @test
	 */
	public function getValue_business_commercialListingTypeBoth_isForSale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><commercialListingType value="both"/></business>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
}