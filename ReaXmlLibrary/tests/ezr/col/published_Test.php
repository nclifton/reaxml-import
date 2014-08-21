<?php
class ReaxmlEzrColPublished_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage market status was not found in the XML
	 */
	public function getValue_statusmissingfromxml_isNew() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
				
		// Act
		$col = new ReaxmlEzrColPublished ( $xml, $dbo );
		$col->getValue();
	}
	/**
	 * @test
	 */
	public function getValue_statusmissingfromxml_isNotNew() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
				
		// Act
		$col = new ReaxmlEzrColPublished ( $xml, $dbo );
		$value = $col->getValue ();
		// Assert
		$this->assertThat($value, $this->isNull());
	}
	/**
	 * @test
	 */
	public function getValue_statuswithdrawn() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential status="withdrawn"></residential>' );
		
		// Act
		$col = new ReaxmlEzrColPublished ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat($value, $this->isFalse());

	}
	public function test_getValue_statusnotwithdrawn() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential status="current"></residential>' );
		
		// Act
		$col = new ReaxmlEzrColPublished ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat($value, $this->isTrue());

	}
}
