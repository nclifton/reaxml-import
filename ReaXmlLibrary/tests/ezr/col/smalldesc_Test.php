<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlEzrColSmalldesc_Test extends PHPUnit_Framework_TestCase {
	
	
	/**
	 * @test
	 */
	public function getValue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><description>Don&#39;t pass up an opportunity like this! First to inspect will buy! Close to local amenities and schools. Features lavishly appointed bathrooms, modern kitchen, rustic outhouse. Don&#39;t pass up an opportunity like this! First to inspect will buy! Close to local amenities and schools. Features lavishly appointed bathrooms, modern kitchen, rustic outhouse.</description></residential>' );
		
		// Act
		$col = new ReaxmlEzrColSmalldesc ( $xml  );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Don\'t pass up an opportunity like this! First to inspect will buy! Close to local amenities and schools. Features lavishly appointed bathrooms, modern kitchen, rustic outhouse. Don\'t pass up an opportunity like this! First to inspect will buy! Close to lo' ) );
	}

	/**
	 * @test
	 */
	public function getValueWithTagsInDescription() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><description>&lt;div&gt;Don&#39;t pass up an opportunity like this! First to inspect will buy! Close to local amenities and schools. &lt;br/&gt;Features lavishly appointed bathrooms, modern kitchen, rustic outhouse. Don&#39;t pass up an opportunity like this! First to inspect will buy! Close to local amenities and schools. Features lavishly appointed bathrooms, modern kitchen, rustic outhouse.&lt;/div&gt;</description></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSmalldesc ( $xml  );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Don\'t pass up an opportunity like this! First to inspect will buy! Close to local amenities and schools. Features lavishly appointed bathrooms, modern kitchen, rustic outhouse. Don\'t pass up an opportunity like this! First to inspect will buy! Close to lo' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_notfound_isnew_isempty() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColSmalldesc ( $xml,$dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_isnotnew_isnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColSmalldesc ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
}