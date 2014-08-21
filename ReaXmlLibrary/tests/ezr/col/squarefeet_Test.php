<?php
class ReaxmlEzrColSquarefeet_Test extends PHPUnit_Framework_TestCase {


	/**
	 * @test
	 */
	public function getValue_squaremeters() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><buildingDetails><area unit="squareMeter">30</area></buildingDetails></residential>' );
		
		// Act
		$col = new ReaxmlEzrColSquarefeet ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '30' ) );
	}
	/**
	 * @test
	 */
	public function getValue_square() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><buildingDetails><area unit="square">3</area></buildingDetails></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSquarefeet ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '28' ) );
	}
	/**
	 * @test
	 */
	public function getValue_hectare() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><buildingDetails><area unit="hectare">2</area></buildingDetails></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSquarefeet ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '20,000' ) );
	}
	/**
	 * @test
	 */
	public function getValue_acre() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><buildingDetails><area unit="acre">2</area></buildingDetails></residential>' );
	
		// Act
		$col = new ReaxmlEzrColSquarefeet ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '8,094' ) );
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
		$col = new ReaxmlEzrColSquarefeet ( $xml, $dbo );
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
		$col = new ReaxmlEzrColSquarefeet ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
}