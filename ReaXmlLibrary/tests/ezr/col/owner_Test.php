<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColOwner_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage listingAgent/name was not found in the XML
	 */
	public function getValue_notfoundinxml_isNew() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColOwner( $xml, $dbo );
		$col->getValue ();
	}
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNotNew() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
	
		// Act
		$col = new ReaxmlEzrColOwner( $xml, $dbo );
		$value = $col->getValue ();
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
	
	/**
	 * @test
	 */
	public function getValue_notfoundindb_addsagent() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent><name>Bob Smith</name></listingAgent></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Bob Smith' ) )->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'insertEzrAgent' )->with ( $this->equalTo ( 'Bob Smith' ),$this->isNull(),$this->isNull() )->willReturn ( 770 );
		
		// Act
		$col = new ReaxmlEzrColOwner( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 770 ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfoundindb_addsagent_withspecifiedemailandtelephone() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent><name>Bob Smith</name><email>name@domain</email><telephone>1234567890</telephone></listingAgent></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Bob Smith' ) )->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'insertEzrAgent' )->with ( $this->equalTo ( 'Bob Smith' ),$this->equalTo('name@domain'),$this->equalTo('1234567890') )->willReturn ( 770 );
	
		// Act
		$col = new ReaxmlEzrColOwner( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 770 ) );
	}
	/**
	 * @test
	 */
	public function getValue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent><name>Bob Smith</name></listingAgent></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Bob Smith' ) )->willReturn ( 101 );
		
		// Act
		$col = new ReaxmlEzrColOwner( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 101 ) );
	}
}