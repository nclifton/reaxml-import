<?php
class ReaxmlEzrColRefrigeratoryn_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_isnew_isempty() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColRefrigeratoryn ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isFalse() );
	}
	/**
	 * @test
	 */
	public function getValue_isnotnew_isnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColRefrigeratoryn ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
}