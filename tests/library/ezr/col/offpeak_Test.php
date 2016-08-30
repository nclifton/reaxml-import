<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColOffpeak_Test extends PHPUnit_Framework_TestCase {
	

	
	/**
	 * @test
	 */
	public function getValue_isNew_is0() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><uniqueID>foo</uniqueID></business>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColOffpeak ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();

		// Assert
		$this->assertThat ( $value, $this->equalTo(0) );
	}
	
	/**
	 * @test
	 */
	public function getValue_isNotNew_isnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><uniqueID>foo</uniqueID></business>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColOffpeak ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
}