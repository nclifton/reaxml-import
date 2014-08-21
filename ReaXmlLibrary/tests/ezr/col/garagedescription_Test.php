<?php
class ReaxmlEzrColGaragedescription_Test extends PHPUnit_Framework_TestCase {


	/**
	 * @test
	 */
	public function getValue_yes() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><remoteGarage>Yes</remoteGarage></features></residential>' );
		
		// Act
		$col = new ReaxmlEzrColGaragedescription ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Remote control door' ) );
	}
	/**
	 * @test
	 */
	public function getValue_1() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><remoteGarage>1</remoteGarage></features></residential>' );
	
		// Act
		$col = new ReaxmlEzrColGaragedescription ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Remote control door' ) );
	}
	/**
	 * @test
	 */
	public function getValue_true() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><remoteGarage>true</remoteGarage></features></residential>' );
	
		// Act
		$col = new ReaxmlEzrColGaragedescription ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Remote control door' ) );
	}
	/**
	 * @test
	 */
	public function getValue_0() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><remoteGarage>0</remoteGarage></features></residential>' );
	
		// Act
		$col = new ReaxmlEzrColGaragedescription ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_no() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><remoteGarage>NO</remoteGarage></features></residential>' );
	
		// Act
		$col = new ReaxmlEzrColGaragedescription ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_false() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><remoteGarage>FaLse</remoteGarage></features></residential>' );
	
		// Act
		$col = new ReaxmlEzrColGaragedescription ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
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
		$col = new ReaxmlEzrColGaragedescription ( $xml, $dbo );
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
		$col = new ReaxmlEzrColGaragedescription ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
}