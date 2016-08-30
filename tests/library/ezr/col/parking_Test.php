<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColParking_Test extends PHPUnit_Framework_TestCase {


	/**
	 * @test
	 */
	public function getValue_secureparkingyes() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><secureParking>Yes</secureParking></features><parkingComments>parking is ok</parkingComments></residential>' );
		
		// Act
		$col = new ReaxmlEzrColParking ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Secure parking. parking is ok' ) );
	}
	/**
	 * @test
	 */
	public function getValue_secureparkingno() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><secureParking>no</secureParking></features><parkingComments>parking is ok</parkingComments></residential>' );
	
		// Act
		$col = new ReaxmlEzrColParking ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'parking is ok' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_notfound_isNew() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColParking ( new ReaxmlEzrRow ( $xml, $dbo ) );
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
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColParking ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
}