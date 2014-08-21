<?php
class ReaxmlEzrColAssoc_agent_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_nosecondagent() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent><name>Jill Smith</name></listingAgent></residential>' );
		
		// Act
		$col = new ReaxmlEzrColAssoc_agent ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isFalse () );
	}
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage No match in the database for the second agent name Jill Smith
	 */
	public function getValue_secondagentnoid_nodbmatch() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent><name>Bob Smith</name></listingAgent><listingAgent><name>Jill Smith</name></listingAgent></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Jill Smith' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColAssoc_agent ( $xml, $dbo );
		$col->getValue ();
	}
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage No match in the database for the second agent name Bob Smith
	 */
	public function getValue_secondagentbyid_nodbmatch() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent id="2"><name>Bob Smith</name></listingAgent><listingAgent id="1"><name>Jill Smith</name></listingAgent></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Bob Smith' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColAssoc_agent ( $xml, $dbo );
		$col->getValue ();
	}
	/**
	 * @test
	 */
	public function getValue_secondagenthbyid() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent id="2"><name>Bob Smith</name></listingAgent><listingAgent id="1"><name>Jill Smith</name></listingAgent></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Bob Smith' ) )->willReturn ( 101 );
		
		// Act
		$col = new ReaxmlEzrColAssoc_agent ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo( 101 ) );
	}
	/**
	 * @test
	 */
	public function getValue_secondagenthbyposition() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent><name>Bob Smith</name></listingAgent><listingAgent><name>Jill Smith</name></listingAgent></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Jill Smith' ) )->willReturn ( 102 );
		
		// Act
		$col = new ReaxmlEzrColAssoc_agent ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 102 ) );
	}
}