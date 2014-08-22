<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColImage_ordering_Test extends PHPUnit_Framework_TestCase {
	
	
	/**
	 * @test
	 */
	public function getValue0_isnew_notfound_gives1() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColImage_ordering ( $xml, $dbo, null, new ReaxmlEzrRow($xml, $dbo) );
		$value = $col->getValueAt (0);
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue0_isnotnew_notfound_givesnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColImage_ordering ( $xml, $dbo, null, new ReaxmlEzrRow($xml, $dbo)  );
		$value = $col->getValueAt (0);
		
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
	/**
	 * @test
	 */
	public function getValue0_idm_gives1() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><objects><img id="m" modTime="2009-01-01-12:30:00" file="Sample_Floorplan.jpg" format="jpg" /></objects></residential>' );
		
		// Act
		$col = new ReaxmlEzrColImage_ordering ( $xml , null, null, new ReaxmlEzrRow($xml, null) );
		$value = $col->getValueAt (0);
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
		
	}
	/**
	 * @test
	 */
	public function getValue0_isnew_noidm_gives0() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="j" modTime="2009-01-01-12:30:00" file="Sample_Floorplan.jpg" format="jpg" /></objects></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColImage_ordering ( $xml, $dbo , null, new ReaxmlEzrRow($xml, $dbo) );
		$value = $col->getValueAt (0);
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	
	}
	/**
	 * @test
	 */
	public function getValue0_isnotnew_noidm_givesnull() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="j" modTime="2009-01-01-12:30:00" file="Sample_Floorplan.jpg" format="jpg" /></objects></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColImage_ordering ( $xml, $dbo , null, new ReaxmlEzrRow($xml, $dbo) );
		$value = $col->getValueAt (0);
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
	
	/**
	 * @test
	 */
	public function getValue10_idj_isnew_gives1() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><objects><img id="j" modTime="2009-01-01-12:30:00" file="Sample_Floorplan.jpg" format="jpg" /></objects></residential>' );
		$dbo = null;
	
		// Act
		$col = new ReaxmlEzrColImage_ordering ( $xml, $dbo , null, new ReaxmlEzrRow($xml, $dbo) );
		$value = $col->getValueAt (10);
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 11 ) );
	
	}
}