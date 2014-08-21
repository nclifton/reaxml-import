<?php
class ReaxmlEzrColMls_id_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage uniqueID was not found in the XML
	 */
	public function getValue_notfoundinxml() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential></residential>' );
		
		// Act
		$col = new ReaxmlEzrColMls_id ( $xml );
		$col->getValue ();
	}
	/**
	 * @test
	 */
	public function getValue() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>ABCD1234</uniqueID></residential>' );
		
		// Act
		$col = new ReaxmlEzrColMls_id ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalTo ( 'ABCD1234' ) );
	}
}