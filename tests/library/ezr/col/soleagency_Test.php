<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColSoleagency_Test extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function getValue_exclusivityOpen() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><exclusivity value="open"/></business>' );
		
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function getValue_exclusivityExclusive() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<business><exclusivity value="exclusive"/></business>' );
	
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isTrue () );
	}
	/**
	 * @test
	 */
	public function getValue_authorityExclusive() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="exclusive"/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isTrue () );
	}
	/**
	 * @test
	 */
	public function getValue_authorityMultilist() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="multilist"/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse());
	}
	/**
	 * @test
	 */
	public function getValue_authorityConjunctional() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="conjunctional"/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse());
	}
	/**
	 * @test
	 */
	public function getValue_authorityOpen() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="open"/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse());
	}
	/**
	 * @test
	 */
	public function getValue_authorityAuction_oneListingAgent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="auction"/><listingAgent/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isTrue());
	}
	/**
	 * @test
	 */
	public function getValue_authorityAuction_twoListingAgents() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="auction"/><listingAgent id="1"/><listingAgent id="2"/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse());
	}
	/**
	 * @test
	 */
	public function getValue_authoritySale_oneListingAgent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="sale"/><listingAgent/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isTrue());
	}
	/**
	 * @test
	 */
	public function getValue_authoritySale_twoListingAgents() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="sale"/><listingAgent id="1"/><listingAgent id="2"/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse());
	}
	/**
	 * @test
	 */
	public function getValue_authoritySetsale_oneListingAgent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="setsale"/><listingAgent/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isTrue());
	}
	/**
	 * @test
	 */
	public function getValue_authoritySetsale_twoListingAgents() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="setsale"/><listingAgent id="1"/><listingAgent id="2"/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSoleagency ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse());
	}	

	
}
