<?php
class ReaxmlEzrColStreet_num_Test extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function getValue() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><streetNumber>12</streetNumber></address></residential>' );
	
		// Act
		$col = new ReaxmlEzrColStreet_num ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '12' ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_isNew() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
			
		// Act
		$col = new ReaxmlEzrColStreet_num ( $xml, $dbo );
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
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
			
		// Act
		$col = new ReaxmlEzrColStreet_num ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}	
}