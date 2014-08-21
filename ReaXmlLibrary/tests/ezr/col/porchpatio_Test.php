<?php
class ReaxmlEzrColPorchpatio_Test extends PHPUnit_Framework_TestCase {


	/**
	 * @test
	 */
	public function getValue_deckyes_balcony1() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><deck>yes</deck><balcony>1</balcony></features></residential>' );
		
		// Act
		$col = new ReaxmlEzrColPorchpatio ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Deck, Balcony' ) );
	}
	/**
	 * @test
	 */
	public function getValue_deckno_balconyyes() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><features><deck>no</deck><balcony>yes</balcony></features></residential>' );
	
		// Act
		$col = new ReaxmlEzrColPorchpatio ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Balcony' ) );
	}
	/**
	 * @test
	 */
	public function getValue_deckno_balconyno() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><deck>no</deck><balcony>no</balcony></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColPorchpatio ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function getValue_deckno_nobalcony_existingDeckBalcony() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><features><deck>no</deck></features></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'lookupEzrPorchpatio' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( 'Deck, Balcony' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColPorchpatio ( $xml , $dbo);
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'Balcony' ) );
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
		$col = new ReaxmlEzrColPorchpatio ( $xml, $dbo );
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
		$col = new ReaxmlEzrColPorchpatio ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	
	}
}