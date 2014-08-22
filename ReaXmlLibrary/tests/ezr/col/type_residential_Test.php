<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColType_residential_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_residential_authorityauction_isauction() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="auction"/></residential>' );
		
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 4 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_residential_authorityexclusive_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="exclusive"/></residential>' );
		
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_residential_authoritymultilist_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="multilist"/></residential>' );
		
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_residential_authorityconjunctional_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="conjunctional"/></residential>' );
		
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_residential_authorityopen_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="open"/></residential>' );
		
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_residential_authoritysale_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="sale"/></residential>' );
		
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function getValue_residential_authoritysetsale_isforsale() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><authority value="setsale"/></residential>' );
		
		// Act
		$col = new ReaxmlEzrColType ( $xml);
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
}