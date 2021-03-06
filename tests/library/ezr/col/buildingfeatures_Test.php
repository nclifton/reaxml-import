<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColBuildingfeatures_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_allyes_isall() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential modTime="" status="current">
 <uniqueID>foo</uniqueID>
  <features>
    <balcony>yes</balcony>
    <deck>yes</deck>
    <courtyard>yes</courtyard>
     <floorboards>yes</floorboards>
  </features>
</residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColBuildingfeatures ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Balcony;Deck;Courtyard;Floorboards' ) );
	}
	/**
	 * @test
	 */
	public function getValue_hasexisting_isall() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential modTime="" status="current">
 <uniqueID>foo</uniqueID>
  <features>
    <balcony>yes</balcony>
    <courtyard>no</courtyard>
     <floorboards>yes</floorboards>
  </features>
</residential>' );
		$dbo = $this->createMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrBuildingFeatures' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 'Balcony;Deck;Courtyard;Floorboards' );
		
		// Act
		$col = new ReaxmlEzrColBuildingfeatures ( new ReaxmlEzrRow ( $xml, $dbo ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Balcony;Deck;Floorboards' ) );
	}

}