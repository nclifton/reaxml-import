<?php
class ReaxmlEzrColOtherfeatures_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_allyes_isall() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '
<residential modTime="" status="current">
  <uniqueID>foo</uniqueID>
  <features>
    <remoteGarage>yes</remoteGarage>
    <secureParking>yes</secureParking>
    <alarmSystem>yes</alarmSystem>
    <intercom>yes</intercom>
    <fullyFenced>yes</fullyFenced>
    <broadband>yes</broadband>
    <payTV>yes</payTV>
    <otherFeatures>views, quiet, close to beach</otherFeatures>			
  </features>
  <ecoFriendly>
    <solarPanels>yes</solarPanels>
    <waterTank>yes</waterTank>
    <greyWaterSystem>yes</greyWaterSystem>
  </ecoFriendly>				
</residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColOtherfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Remote Control Garage Door;Secure Parking;Alarm System;Intercom;Fully Fenced;Broadband;Pay TV;Solar Panels;Water Tank;Grey Water System;Views;Quiet;Close to beach' ) );
	}
	/**
	 * @test
	 */
	public function getValue_hasexistingotherotherfeatures_isall() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '
<residential modTime="" status="current">
  <uniqueID>foo</uniqueID>
  <features>
    <remoteGarage>yes</remoteGarage>
    <otherFeatures>National Park</otherFeatures>
  </features>
</residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrOtherFeatures' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 'Views;Quiet;Close to Beach' );
		
		// Act
		$col = new ReaxmlEzrColOtherfeatures ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Remote Control Garage Door;National Park' ) );
	}
}