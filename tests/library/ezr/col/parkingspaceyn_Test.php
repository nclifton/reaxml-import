<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColParkingspaceyn_Test extends PHPUnit_Framework_TestCase {


	/**
	 * @test
	 */
	public function getValue_openspaces3_istrue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><openSpaces>3</openSpaces></features></residential>' );
		
		// Act
		$col = new ReaxmlEzrColParkingspaceyn ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isTrue() );
	}
	/**
	 * @test
	 */
	public function getValue_openspacesyes_istrue() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><openSpaces>yes</openSpaces></features></residential>' );
	
		// Act
		$col = new ReaxmlEzrColParkingspaceyn ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isTrue() );
	}
	/**
	 * @test
	 */
	public function getValue_openspaces0_isfalse() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><openSpaces>no</openSpaces></features></residential>' );
	
		// Act
		$col = new ReaxmlEzrColParkingspaceyn ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse() );
	}
	/**
	 * @test
	 */
	public function getValue_openspacesempty_isfalse() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><openSpaces></openSpaces></features></residential>' );
	
		// Act
		$col = new ReaxmlEzrColParkingspaceyn ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse() );
	}
}