<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
class ReaxmlEzrColAssoc_agent_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_nosecondagent() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent><name>Jill Smith</name></listingAgent></residential>' );
		
		// Act
		$col = new ReaxmlEzrColAssoc_agent ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	/**
	 * @test
	 */
	public function getValue_secondagentnoid_nodbmatch_addsagent() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent><name>Bob Smith</name></listingAgent><listingAgent><name>Jill Smith</name></listingAgent></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Jill Smith' ) )->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'insertEzrAgent' )->with ( $this->equalTo ( 'Jill Smith' ), $this->isNull (), $this->isNull () )->willReturn ( 651 );
		
		// Act
		$col = new ReaxmlEzrColAssoc_agent ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// assert
		
		$this->assertThat ( $value, $this->equalTo ( 651 ) );
	}
	/**
	 * @test
	 */
	public function getValue_secondagentbyid_nodbmatch_addsagent() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent id="2"><name>Bob Smith</name></listingAgent><listingAgent id="1"><name>Jill Smith</name></listingAgent></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Bob Smith' ) )->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'insertEzrAgent' )->with ( $this->equalTo ( 'Bob Smith' ), $this->isNull (), $this->isNull () )->willReturn ( 651 );
		
		// Act
		$col = new ReaxmlEzrColAssoc_agent ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// assert
		
		$this->assertThat ( $value, $this->equalTo ( 651 ) );
	}
	/**
	 * @test
	 */
	public function getValue_secondagenthbyid() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent id="2"><name>Bob Smith</name></listingAgent><listingAgent id="1"><name>Jill Smith</name></listingAgent></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Bob Smith' ) )->willReturn ( 101 );
		
		// Act
		$col = new ReaxmlEzrColAssoc_agent ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 101 ) );
	}
	/**
	 * @test
	 */
	public function getValue_secondagenthbyposition() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent><name>Bob Smith</name></listingAgent><listingAgent><name>Jill Smith</name></listingAgent></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Jill Smith' ) )->willReturn ( 102 );
		
		// Act
		$col = new ReaxmlEzrColAssoc_agent ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 102 ) );
	}
}