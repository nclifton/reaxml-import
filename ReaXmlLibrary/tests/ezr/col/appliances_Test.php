<?php
class ReaxmlEzrColAppliances_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_dishwasher3_isDishwasher() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><dishwasher>3</dishwasher></features></residential>' );
		
		// Act
		$col = new ReaxmlEzrColAppliances ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Dishwasher' ) );
	}
	/**
	 * @test
	 */
	public function getValue_dishwasheryes_istrue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><dishwasher>yes</dishwasher></features></residential>' );
		
		// Act
		$col = new ReaxmlEzrColAppliances ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Dishwasher' ) );
	}
	/**
	 * @test
	 */
	public function getValue_dishwasher0_isfalse() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><dishwasher>no</dishwasher></features></residential>' );
		
		// Act
		$col = new ReaxmlEzrColAppliances ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_dishwasherempty_isfalse() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><dishwasher></dishwasher></features></residential>' );
		
		// Act
		$col = new ReaxmlEzrColAppliances ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_dishwashernotfound_isnew_isEmpty() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColAppliances ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_dishwashernotfound_isnotnew_isNull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColAppliances ( $xml, $dbo );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
}