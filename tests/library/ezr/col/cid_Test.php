<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
class ReaxmlEzrColCid_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage category was not found in the XML
	 */
	public function getValue_notfoundinxml_isNew_residential() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$col->getValue ();
	}
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage category was not found in the XML
	 */
	public function getValue_notfoundinxml_isNew_commercial() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><uniqueID>foo</uniqueID></commercial>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$col->getValue ();
	}
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage category was not found in the XML
	 */
	public function getValue_notfoundinxml_isNew_rental() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><uniqueID>foo</uniqueID></rental>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$col->getValue ();
	}
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNotNew_residential() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNotNew_commercial() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><uniqueID>foo</uniqueID></commercial>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNotNew_rental() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><uniqueID>foo</uniqueID></rental>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	/**
	 * @test
	 */
	public function getValue_notfoundindb_addscategory_residential() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><category name="junk"/></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrCategoryIdUsingCategoryName' )->with ( $this->equalTo ( 'junk' ) )->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'insertEzrCategory' )->with ( $this->equalTo ( 'junk' ) )->willReturn ( 5 );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 5 ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfoundindb_addscategory_rental() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><category name="arcology"/></rental>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrCategoryIdUsingCategoryName' )->with ( $this->equalTo ( 'arcology' ) )->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'insertEzrCategory' )->with ( $this->equalTo ( 'arcology' ) )->willReturn ( 6 );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 6 ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfoundindb_commercial() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialCategory name="shack"/></commercial>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrCategoryIdUsingCategoryName' )->with ( $this->equalTo ( 'shack' ) )->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'insertEzrCategory' )->with ( $this->equalTo ( 'shack' ) )->willReturn ( 7 );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 7 ) );
	}
	/**
	 * @test
	 */
	public function getValue_residential() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><category name="arcology"/></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrCategoryIdUsingCategoryName' )->with ( $this->equalTo ( 'arcology' ) )->willReturn ( 42 );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 42 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_commercial() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<commercial><commercialCategory name="arcology"/></commercial>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrCategoryIdUsingCategoryName' )->with ( $this->equalTo ( 'arcology' ) )->willReturn ( 42 );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 42 ) );
	}
	/**
	 * @test
	 */
	public function getValue_rental() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><category name="arcology"/></rental>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrCategoryIdUsingCategoryName' )->with ( $this->equalTo ( 'arcology' ) )->willReturn ( 42 );
		
		// Act
		$col = new ReaxmlEzrColCid ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 42 ) );
	}
}