<?php
class ReaxmlEzrColStid_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage address/state was not found in the XML
	 */
	public function getValue_notfoundinxml_isNew() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColStid ( $xml, $dbo );
		$col->getValue();
	}
	/**
	 * @test
	 */
	public function getValue_notfoundinxml_isNotNew() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColStid ( $xml , $dbo);
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull() );
		
	}
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage No match in the database for state Solid
	 */
	public function getValue_notfoundindb() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><state>Solid</state></address></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrStidUsingState' )->with ( $this->equalTo ( 'Solid' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColStid ( $xml, $dbo );
		$value = $col->getValue ();
	}
	/**
	 * @test
	 */
	public function getValue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><state>Solid</state></address></residential>' );
				$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrStidUsingState' )->with ( $this->equalTo ( 'Solid' ) )->willReturn(1);
		
		// Act
		$col = new ReaxmlEzrColStid ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 1 ) );
	}
}