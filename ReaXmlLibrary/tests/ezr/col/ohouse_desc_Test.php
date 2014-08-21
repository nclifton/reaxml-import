<?php
class ReaxmlEzrColOhouse_desc_Test extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function getValue_isnotnew_isNull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColOhouse_desc( $xml , $dbo);
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
	/**
	 * @test
	 */
	public function getValue_isnew_isempty() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
	
		// Act
		$col = new ReaxmlEzrColOhouse_desc( $xml , $dbo);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo('') );
	}
}