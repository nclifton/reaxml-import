<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColBond_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_isNew_is0() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><uniqueID>foo</uniqueID></rental>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColBond ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_isNotNew_isnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><uniqueID>foo</uniqueID></rental>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColBond ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	/**
	 * @test
	 */
	public function getValue() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><uniqueID>foo</uniqueID><bond>1234</bond></rental>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
	
		// Act
		$col = new ReaxmlEzrColBond ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo(1234.00) );
	}
}