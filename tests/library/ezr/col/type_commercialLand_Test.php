<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColType_commercialLand_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_commercialLand_authorityauction_isauction() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><authority value="auction"/></commercialLand>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_commercialLand_authorityexclusive_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><authority value="exclusive"/></commercialLand>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_commercialLand_authoritymultilist_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><authority value="multilist"/></commercialLand>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_commercialLand_authorityconjunctional_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><authority value="conjunctional"/></commercialLand>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_authorityopen_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><authority value="open"/></commercialLand>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_authoritysale_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><authority value="sale"/></commercialLand>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_commercialLand_authoritysetsale_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercialLand><authority value="setsale"/></commercialLand>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
}