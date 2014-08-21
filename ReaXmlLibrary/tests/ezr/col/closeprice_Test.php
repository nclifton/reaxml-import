<?php
class ReaxmlEzrColCloseprice_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_displayyes_issoldprice() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><soldDetails><soldPrice display="yes">350000000</soldPrice></soldDetails></residential>' );
		
		// Act
		$col = new ReaxmlEzrColCloseprice ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( '350000000' ) );
	}
	/**
	 * @test
	 */
	public function getValue_displayno_is0() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><soldDetails><soldPrice display="no">350000000</soldPrice></soldDetails></residential>' );
	
		// Act
		$col = new ReaxmlEzrColCloseprice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_displayrange_is0() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><soldDetails><soldPrice display="range">350000000-370000000</soldPrice></soldDetails></residential>' );
	
		// Act
		$col = new ReaxmlEzrColCloseprice ( $xml );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}	
	/**
	 * @test
	 */
	public function getValue_notfound_isnew_is0() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$col = new ReaxmlEzrColCloseprice ( $xml,$dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 0 ) );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_isnotnew_isnull() {
	
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( true );
		
		// Act
		$col = new ReaxmlEzrColCloseprice ( $xml, $dbo );
		$value = $col->getValue ();
	
		// Assert
		$this->assertThat ( $value, $this->isNull() );
	}
}