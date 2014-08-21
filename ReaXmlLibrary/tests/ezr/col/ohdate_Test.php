<?php
class ReaxmlEzrColOhdate_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function getValue_found_gives_date() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><inspectionTimes><inspection>21-Dec-2010 11:00am to 1:00pm</inspection></inspectionTimes></residential>' );
		
		// Act
		$col = new ReaxmlEzrColOhdate ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->equalto ('2010-12-21') );
	}
	/**
	 * @test
	 */
	public function getValue_empty_givesnull() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><inspectionTimes><inspection/></inspectionTimes></residential>' );
		
		// Act
		$col = new ReaxmlEzrColOhdate ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );	
	}
	/**
	 * @test
	 */
	public function getValue_2empty_givesnull() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><inspectionTimes><inspection/><inspection/></inspectionTimes></residential>' );
		
		// Act
		$col = new ReaxmlEzrColOhdate ( $xml );
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
		$col = new ReaxmlEzrColOhdate ( $xml );
		$value = $col->getValue ();
		
		// Assert
		$this->assertThat ( $value, $this->isNull () );
	}
}