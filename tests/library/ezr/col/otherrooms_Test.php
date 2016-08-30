<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColOtherrooms_Test extends PHPUnit_Framework_TestCase {


	/**
	 * @test
	 */
	public function getValue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><toilets>3</toilets><gym>yes</gym><rumpusRoom>no</rumpusRoom><study>yes</study><workshop>yes</workshop></features></residential>' );
		
		// Act
		$col = new ReaxmlEzrColOtherrooms ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Toilets: 3, Gym, Study, Workshop' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_notNew_allexisting_onlygymno() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><gym>no</gym></features></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrOtherrooms' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 'Toilets: 3, Gym, Rumpus Room, Study, Workshop' );
		
		// Act
		$col = new ReaxmlEzrColOtherrooms ( new ReaxmlEzrRow ( $xml , $dbo) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Toilets: 3, Rumpus Room, Study, Workshop' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_notfound_isNew() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColOtherrooms ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo('') );
		
	}
	
	/**
	 * @test
	 */
	public function getValue_notfound_isNotNew() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColOtherrooms(new ReaxmlEzrRow ( $xml, $dbo ));
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
}