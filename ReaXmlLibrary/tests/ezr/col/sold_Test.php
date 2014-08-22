<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColSold_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Valid listing status not found in the XML for a new property listing.
	 */
	public function getValue_notfoundinxml_isNew_isException() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><uniqueID>foo</uniqueID></rental>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColSold ( $xml, $dbo );
		$col->getValue();
	}
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNotNew_isNull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><uniqueID>foo</uniqueID></rental>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
	
		// Act
		$col = new ReaxmlEzrColSold ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert 
		$this->assertThat($value, $this->isNull());
		
	}	

}