<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColAcrestotal_Test extends PHPUnit_Framework_TestCase {


	/**
	 * @test
	 */
	public function getValue_squaremeters() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><landDetails><area unit="squareMeter">30000</area></landDetails></residential>' );
		
		// Act
		$col = new ReaxmlEzrColAcrestotal ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '3.00' ) );
	}
	/**
	 * @test
	 */
	public function getValue_square() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><landDetails><area unit="square">3000</area></landDetails></residential>' );
			
		// Act
		$col = new ReaxmlEzrColAcrestotal ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '2.79' ) );
	}
	/**
	 * @test
	 */
	public function getValue_hectare() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><landDetails><area unit="hectare">2</area></landDetails></residential>' );
			
		// Act
		$col = new ReaxmlEzrColAcrestotal ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '2' ) );
	}
	/**
	 * @test
	 */
	public function getValue_acre() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><landDetails><area unit="acre">3</area></landDetails></residential>' );
			
		// Act
		$col = new ReaxmlEzrColAcrestotal ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '1.21' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_notfound_isNew() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColAcrestotal ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo('') );
		
	}
	/**
	 * @test
	 */
	public function getValue_notfound_isNotNew() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColAcrestotal ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
}