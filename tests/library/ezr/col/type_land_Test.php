<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColType_land_Test extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function getValue_land_authorityauction_isauction() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land><authority value="auction"/></land>' );
	
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(4));
	}	/**
	 * @test
	 */
	public function getValue_land_authorityexclusive_isforsale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land><authority value="exclusive"/></land>' );
	
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	}/**
	 * @test
	 */
	public function getValue_land_authoritymultilist_isforsale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land><authority value="multilist"/></land>' );
	
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	}/**
	 * @test
	 */
	public function getValue_land_authorityconjunctional_isforsale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land><authority value="conjunctional"/></land>' );
	
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	}/**
	 * @test
	 */
	public function getValue_land_authorityopen_isforsale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land><authority value="open"/></land>' );
	
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	}/**
	 * @test
	 */
	public function getValue_land_authoritysale_isforsale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land><authority value="sale"/></land>' );
	
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	}/**
	 * @test
	 */
	public function getValue_land_authoritysetsale_isforsale() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<land><authority value="setsale"/></land>' );
	
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat($value, $this->equalTo(1));
	}
}