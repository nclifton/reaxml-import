<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColIndoorfeatures_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_allyes_isall() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID>
  <features>
     <airConditioning>yes</airConditioning>
    <vacuumSystem>yes</vacuumSystem>
     <openFirePlace>yes</openFirePlace>
    <heating type="gas">yes</heating>
    <hotWaterService type="gas">yes</hotWaterService>
    <insideSpa>yes</insideSpa>
    <builtInRobes>yes</builtInRobes>
    <ductedCooling>yes</ductedCooling>
    <ductedHeating>yes</ductedHeating>
    <evaporativeCooling>yes</evaporativeCooling>
    <hydronicHeating>yes</hydronicHeating>
    <reverseCycleAirCon>yes</reverseCycleAirCon>
    <splitSystemAirCon>yes</splitSystemAirCon>
    <splitSystemHeating>yes</splitSystemHeating>
  </features>
  </residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Air Conditioning;Vacuum System;Open Fire Place;Gas Heating;Gas Hot Water Service;Spa;Built-in Robes;Ducted Cooling;Ducted Heating;Evaporative Cooling;Hydronic Heating;Reverse Cycle Air Conditioning;Split System Air Conditioning;Split System Heating' ) );
	}
	/**
	 *     <solarHotWater>solarHotWater</solarHotWater>

	 * @test
	 */
	public function getValue_hotwaterservicetypesolar_issolarHotWater() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><hotWaterService type="solar">yes</hotWaterService></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Solar Hot Water Service' ) );
	}

	/**
	 * @test
	 */
	public function getValue_solarhotwateryes_issolarHotWater() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><ecoFriendly><solarHotWater>yes</solarHotWater></ecoFriendly></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
	
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Solar Hot Water Service' ) );
	}
	
	
	/**
	 * @test
	 */
	public function getValue_hotwaterservicetypeelectric_iselectricHotWater() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><hotWaterService type="electric">yes</hotWaterService></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Electric Hot Water Service' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_hotwaterservicetypegas_isGasHotWater() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><hotWaterService type="gas">yes</hotWaterService></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Gas Hot Water Service' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_heatingtypeother_isHeating() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><heating type="other">yes</heating></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Heating' ) );
	}
	/**
	 * @test
	 */
	public function getValue_heatingtypesolid_isSolidFuelHeating() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><heating type="solid">yes</heating></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Solid Fuel Heating' ) );
	}
	/**
	 * @test
	 */
	public function getValue_heatingtypeGDH_isGasDuctedHeating() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><heating type="GDH">yes</heating></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Gas Ducted Heating' ) );
	}
	/**
	 * @test
	 */
	public function getValue_heatingtypeelectric_iselectricheating() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><heating type="electric">yes</heating></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Electric Heating' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_airconditioningno_isnew_isempty() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><airConditioning>no</airConditioning></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_airconditioningno_isnotnew_isempty() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><airConditioning>no</airConditioning></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrIndoorFeatures' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( '' );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_noindoorfeatures_isnew_isempty() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_isnotnew_noindoorfeatures_hasnoexistingindoorfeatures_isnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrIndoorFeatures' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( '' );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	/**
	 * @test
	 */
	public function getValue_isnotnew_noindoorfeatures_hasexistingindoorfeaturesCarpets_isnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrIndoorFeatures' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 'Carpets' );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	/**
	 * @test
	 */
	public function getValue_isnotnew_vacuumsystemyes_hasexistingindoorfeaturescarpets_isnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><vacuumSystem>yes</vacuumSystem></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrIndoorFeatures' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 'Carpets' );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Carpets;Vacuum System' ) );
	}
	/**
	 * @test
	 */
	public function getValue_isnotnew_vacuumsystemno_hasexistingindoorfeaturescarpetsvacuumsystem_iscarpetsonly() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><vacuumSystem>no</vacuumSystem></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrIndoorFeatures' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 'Carpets;Vacuum System' );
		
		// Act
		$col = new ReaxmlEzrColIndoorfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Carpets' ) );
	}
}