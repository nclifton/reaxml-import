<?php
class ReaxmlEzrColUnit_num_Test extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function getValue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><subNumber>18b</subNumber></address></residential>' );
		
		// Act
		$col = new ReaxmlEzrColUnit_num ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '18b' ) );
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
		$col = new ReaxmlEzrColUnit_num ( $xml , $dbo);
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
		$col = new ReaxmlEzrColUnit_num ( $xml , $dbo);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
}