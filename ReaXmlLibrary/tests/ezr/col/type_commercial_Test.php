<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColType_commercial_Test extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function getValue_commercial_commercialauthorityauction_isauction() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialAuthority value="auction"/></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_commercialAuthorityTender_isSaleByTender() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialAuthority value="tender"/></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 6 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_commercialAuthorityForsale_isForSale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialAuthority value="forsale"/></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_commercialAuthorityEoi_isForSale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialAuthority value="eoi"/></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_commercialAuthorityOffers_isForSale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialAuthority value="offers"/></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_commercialAuthoritySale_isForSale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialAuthority value="sale"/></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_commercialListingTypeSale_isForSale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialListingType value="sale"/></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_commercialListingTypeLease_isForLease() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialListingType value="lease"/></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 3 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercial_commercialListingTypeBoth_isForSale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialListingType value="both"/></commercial>' );
	
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}	
}