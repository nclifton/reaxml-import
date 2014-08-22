<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColShowprice_Test extends PHPUnit_Framework_TestCase {


	/**
	 * @test
	 */
	public function getValue_price_displayYes() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><price display="yes">350000000</price></residential>' );
		
		// Act
		$col = new ReaxmlEzrColShowprice ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isTrue() );
	}
	/**
	 * @test
	 */
	public function getValue_price_displayNo() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><price display="no">350000000</price></residential>' );
	
		// Act
		$col = new ReaxmlEzrColShowprice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse() );
	}
	/**
	 * @test
	 */
	public function getValue_rent_displayYes() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><rent display="yes">350000000</rent></rental>' );
	
		// Act
		$col = new ReaxmlEzrColShowprice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isTrue() );
	}
	/**
	 * @test
	 */
	public function getValue_rent_displayNo() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><rent display="no">350000000</rent></rental>' );
	
		// Act
		$col = new ReaxmlEzrColShowprice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse() );
	}
	/**
	 * @test
	 */
	public function getValue_displayNotfound_isnew_istrue() {
	
		$xml = new SimpleXMLElement ( '<business><uniqueID>foo</uniqueID></business>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColShowprice ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isTrue() );
	}
	/**
	 * @test
	 */
	public function getValue_displayNotfound_isnotnew_isnull() {
	
		$xml = new SimpleXMLElement ( '<business><uniqueID>foo</uniqueID></business>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColShowprice ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
}