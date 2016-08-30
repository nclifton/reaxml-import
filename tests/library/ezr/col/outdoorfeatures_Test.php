<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColOutdoorfeatures_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_allyes_isall() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential>
	<uniqueID>foo</uniqueID>
	<features>
		<poolInGround>yes</poolInGround>
		<poolAboveGround>yes</poolAboveGround>
		<outsideSpa>yes</outsideSpa>
		<tennisCourt>yes</tennisCourt>
		<shed>yes</shed>
		<outdoorEnt>yes</outdoorEnt>			
	</features>
</residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColOutdoorfeatures ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'In Ground Pool;Above Ground Pool;Spa;Tennis Court;Shed;Outdoor Entertainment' ) );
	}
	/**
	 * @test
	 */
	public function getValue_pooltypeaboveground_isabovegroundpool() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><pool type="aboveground">yes</pool></features></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColOutdoorfeatures ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Above Ground Pool' ) );
	}
	/**
	 * @test
	 */
	public function getValue_pooltypeinground_isingroundpool() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><pool type="inground">yes</pool></features></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
	
		// Act
		$col = new ReaxmlEzrColOutdoorfeatures ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'In Ground Pool' ) );
	}
	/**
	 * @test
	 */
	public function getValue_spatypeaboveground_isabovegroundspa() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><spa type="aboveground">yes</spa></features></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
	
		// Act
		$col = new ReaxmlEzrColOutdoorfeatures ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Above Ground Spa' ) );
	}
	/**
	 * @test
	 */
	public function getValue_spatypeinground_isingroundspa() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><spa type="inground">yes</spa></features></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
	
		// Act
		$col = new ReaxmlEzrColOutdoorfeatures ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'In Ground Spa' ) );
	}


}