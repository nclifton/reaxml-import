<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColType_rural_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_rural_authorityauction_isauction() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rural><authority value="auction"></rural>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_rural_authorityexclusive_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rural><authority value="exclusive"></rural>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_rural_authoritymultilist_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rural><authority value="multilist"></rural>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_rural_authorityconjunctional_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rural><authority value="conjunctional"></rural>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_rural_authorityopen_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rural><authority value="open"></rural>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_rural_authoritysale_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rural><authority value="sale"></rural>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_rural_authoritysetsale_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rural><authority value="setsale"></rural>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
}