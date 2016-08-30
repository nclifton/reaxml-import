<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColOpenhouse_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_found_gives_true() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><inspectionTimes><inspection>21-Dec-2010 11:00am to 1:00pm</inspection></inspectionTimes></residential>' );
		
		// Act
		$col = new ReaxmlEzrColOpenhouse ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isTrue () );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_isnew_givesfalse() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColOpenhouse ( new ReaxmlEzrRow ( $xml ,$dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_isnotnew_givesnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColOpenhouse ( new ReaxmlEzrRow ( $xml , $dbo) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
	/**
	 * @test
	 */
	public function getValue_empty_givesfalse() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><inspectionTimes><inspection/></inspectionTimes></residential>' );
	
		// Act
		$col = new ReaxmlEzrColOpenhouse ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse());
	}
	/**
	 * @test
	 */
	public function getValue_2empty_givesfalse() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><inspectionTimes><inspection/><inspection/></inspectionTimes></residential>' );
	
		// Act
		$col = new ReaxmlEzrColOpenhouse ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isFalse() );
	}
	
}