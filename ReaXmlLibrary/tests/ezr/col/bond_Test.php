<?php
class ReaxmlEzrColBond_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_isNew_is0() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><uniqueID>foo</uniqueID></business>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColBond ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_isNotNew_isnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<business><uniqueID>foo</uniqueID></business>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColBond ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
}