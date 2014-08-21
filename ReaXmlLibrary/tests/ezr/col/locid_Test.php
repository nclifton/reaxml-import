<?php
class ReaxmlEzrColLocidTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage address/suburb was not found in the XML
	 */
	public function getValue_notfoundinxml_isNew() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColLocid ( $xml, $dbo );
		$col->getValue ();
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
		$col = new ReaxmlEzrColLocid ( $xml , $dbo);
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull() );
		
	}
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage No match in the database for location Back of Bourke
	 */
	public function getValue_notfoundindb() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><suburb>Back of Bourke</suburb></address></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrLocidUsingSuburb' )->with ( $this->equalTo ( 'Back of Bourke' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColLocid ( $xml, $dbo );
		$col->getValue ();
	}
	/**
	 * @test
	 */
	public function getValue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address><suburb>Back of Bourke</suburb></address></residential>' );
				$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrLocidUsingSuburb' )->with ( $this->equalTo ( 'Back of Bourke' ) )->willReturn(88);
		
		// Act
		$col = new ReaxmlEzrColLocid ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 88 ) );
	}
}