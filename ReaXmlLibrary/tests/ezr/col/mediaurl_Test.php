<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColMediaurl_Test extends PHPUnit_Framework_TestCase {
	

	/**
	 * @test
	 */
	public function getValue_isnew_notfound_givesempty() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColMediaurl ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_isnotnew_notfound_givesnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
	
		// Act
		$col = new ReaxmlEzrColMediaurl ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull());
	}
	
	/**
	 * @test
	 */
	public function getValue_containshref_givesvalue() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><videoLink href="https://www.youtube.com/watch?v=k736vsLf1Js"/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColMediaurl ( $xml);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo('https://www.youtube.com/watch?v=k736vsLf1Js'));
	}
	
}