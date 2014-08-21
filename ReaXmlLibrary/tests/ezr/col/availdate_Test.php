<?php
class ReaxmlEzrColAvaildate_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_found_gives_date() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<rental><dateAvailable>2010-12-23T11:00:00</dateAvailable></rental>' );
		
		// Act
		$col = new ReaxmlEzrColAvaildate ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalto ('2010-12-23') );
	}
	/**
	 * @test
	 */
	public function getValue_notfound_givesnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential></residential>' );
		
		// Act
		$col = new ReaxmlEzrColAvaildate ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
}