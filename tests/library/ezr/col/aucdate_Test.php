<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColAucdate_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_found_gives_date() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><auction date="2010-12-23T11:00:00"/></residential>' );
		
		// Act
		$col = new ReaxmlEzrColAucdate ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalto ('2010-12-23') );
	}
	/**
	 * @test
	 */
	public function getValue_found_altdateformat_gives_date() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><auction date="2010-12-23-11:00:00"/></residential>' );
	
		// Act
		$col = new ReaxmlEzrColAucdate ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalto ('2010-12-23') );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_givesnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential></residential>' );
		
		// Act
		$col = new ReaxmlEzrColAucdate ( new ReaxmlEzrRow ( $xml ) );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
}