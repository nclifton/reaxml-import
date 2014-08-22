<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColOhstarttime_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_found_gives_time() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><inspectionTimes><inspection>21-Dec-2010 11:00am to 1:00pm</inspection></inspectionTimes></residential>' );
		
		// Act
		$col = new ReaxmlEzrColOhstarttime ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalto ( '11:00:00' ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_givesnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential></residential>' );
		
		// Act
		$col = new ReaxmlEzrColOhstarttime ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
	/**
	 * @test
	 */
	public function getValue_empty_givesnull() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><inspectionTimes><inspection/></inspectionTimes></residential>' );
	
		// Act
		$col = new ReaxmlEzrColOhstarttime ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
}