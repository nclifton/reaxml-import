<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColType_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNew() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<something><uniqueID>foo</uniqueID></something>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNotNew() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<something><uniqueID>foo</uniqueID></something>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}

	/**
	 * @test
	 */
	public function getValue_rental_isforrent() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental/>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 2 ) );
	}
	/**
	 * @test
	 */
	public function getValue_holidayrental_isforrent() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<holidayRental/>' );
		
		// Act
		$col = new ReaxmlEzrColType ( new ReaxmlEzrRow ( $xml) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 2 ) );
	}

}