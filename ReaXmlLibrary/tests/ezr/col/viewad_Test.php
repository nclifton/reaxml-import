<?php
class ReaxmlEzrColViewad_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_yesspecified() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address display="yes"></address></residential>' );
		
		// Act
		$col = new ReaxmlEzrColViewad ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isTrue() );
	}
	/**
	 * @test
	 */
	public function getValue_nospecified() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><address display="no"></address></residential>' );
		
		// Act
		$col = new ReaxmlEzrColViewad ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isFalse() );
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
		$col = new ReaxmlEzrColViewad ( $xml , $dbo);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isTrue() );
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
		$col = new ReaxmlEzrColViewad ( $xml , $dbo);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
}