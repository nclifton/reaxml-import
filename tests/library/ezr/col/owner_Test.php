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
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColOwner( new ReaxmlEzrRow ( $xml, $dbo ) );
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
		$col = new ReaxmlEzrColOwner( new ReaxmlEzrRow ( $xml, $dbo ) );
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
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Bob Smith' ) )->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'insertEzrAgent' )->with ( $this->equalTo ( 'Bob Smith' ),$this->isNull(),$this->isNull() )->willReturn ( 770 );
		
		// Act
		$col = new ReaxmlEzrColOwner( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 770 ) );
	}
	/**
	 * @test
	 */
	public function getValue_altxml_notfoundindb_addsagent() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent id="1"><name>PETER PAZIOS</name>'.
				'<telephone type="mobile">0411 289 964</telephone><email>peter@pullman-williams.com.au</email>'.
				'</listingAgent><listingAgent id="2" /><listingAgent id="3" /></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )
			->method ( 'lookupEzrAgentUidUsingAgentName' )
			->with ( $this->logicalAnd($this->equalTo ( 'PETER PAZIOS' ),
				$this->isType ( 'string' )) )->willReturn ( false );
		$dbo->expects ( $this->once () )
			->method ( 'insertEzrAgent' )
			->with ( $this->logicalAnd( $this->equalTo ( 'PETER PAZIOS' ),
						$this->isType ( 'string' )),
					$this->logicalAnd( $this->equalTo('peter@pullman-williams.com.au'),
						$this->isType ( 'string' )),
					$this->logicalAnd($this->equalTo('0411 289 964') ,
						$this->isType ( 'string' )))
			->willReturn ( 770 );
	
		// Act
		$col = new ReaxmlEzrColOwner( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 770 ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfoundindb_addsagent_withspecifiedemailandtelephone() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><listingAgent><name>Bob Smith</name><email>name@domain</email>'.
				'<telephone>1234567890</telephone></listingAgent></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )
			->method ( 'lookupEzrAgentUidUsingAgentName' )
			->with ( $this->equalTo ( 'Bob Smith' ) )
			->willReturn ( false );
		$dbo->expects ( $this->once () )->method ( 'insertEzrAgent' )->with ( $this->equalTo ( 'Bob Smith' ),
				$this->equalTo('name@domain'),$this->equalTo('1234567890') )->willReturn ( 770 );
	
		// Act
		$col = new ReaxmlEzrColOwner( new ReaxmlEzrRow ( $xml, $dbo ) );
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
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrAgentUidUsingAgentName' )->with ( $this->equalTo ( 'Bob Smith' ) )->willReturn ( 101 );
		
		// Act
		$col = new ReaxmlEzrColOwner( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 101 ) );
	}
}