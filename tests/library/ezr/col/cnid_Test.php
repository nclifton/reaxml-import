<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColCnid_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage address/country was not found in the XML
	 */
	public function getValue_notfoundinxml_isNew() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColCnid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$col->getValue ();
	}
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNotNew() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColCnid ( new ReaxmlEzrRow ( $xml , $dbo) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull() );
		
	}
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage No match in the database for country Life
	 */
	public function getValue_notfoundindb() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><country>Life</country></address></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrCnidUsingCountry' )->with ( $this->equalTo ( 'Life' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColCnid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$col->getValue ();
	}
	/**
	 * @test
	 */
	public function getValue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><country>Life</country></address></residential>' );
				$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrCnidUsingCountry' )->with ( $this->equalTo ( 'Life' ) )->willReturn(1);
		
		// Act
		$col = new ReaxmlEzrColCnid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
}