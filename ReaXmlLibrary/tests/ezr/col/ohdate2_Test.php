<?php
class ReaxmlEzrColOhdate2_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_found_gives_time() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><inspectionTimes><inspection>21-Dec-2010 11:00am to 1:00pm</inspection><inspection>22-Dec-2010 10:30am to 12:30pm</inspection></inspectionTimes></residential>' );
		
		// Act
		$col = new ReaxmlEzrColOhdate2 ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalto ( '2010-12-22' ) );
	}
	/**
	 * @test
	 */
	public function getValue_empty_givesnull() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><inspectionTimes><inspection/><inspection/></inspectionTimes></residential>' );
		
		// Act
		$col = new ReaxmlEzrColOhdate2 ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );	
	}
	/**
	 * @test
	 */
	public function getValue_notfound_givesnull() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential></residential>' );
		
		// Act
		$col = new ReaxmlEzrColOhdate2 ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
}