<?php
class ReaxmlEzrImages_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function isCountable() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="m" modTime="2009-01-01-12:30:00" file="f1.jpg" format="jpg" /><img id="a" modTime="2009-01-01-12:30:00" file="f2.jpg" format="jpg" /></objects></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->once () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$images = new ReaxmlEzrImages ( $xml, $dbo, new ReaxmlConfiguration() , new ReaxmlEzrRow($xml,$dbo) );
		$count = count ( $images );
		
		// Assert
		$this->assertThat ( $count, $this->equalTo ( 2 ) );
	}
	
	/**
	 * @test
	 */
	public function isTraversable() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="m" modTime="2009-01-01-12:30:00" file="f1.jpg" format="jpg" /><img id="a" modTime="2009-01-01-12:30:00" file="f2.jpg" format="jpg" /></objects></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->atLeastOnce () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$images = new ReaxmlEzrImages ( $xml, $dbo , new ReaxmlConfiguration() , new ReaxmlEzrRow($xml,$dbo));
		
		// Assert
		$idx = 0;
		foreach ( $images as $key => $value ) {
			$this->assertThat ( $key, $this->equalTo ( $idx + 1 ), 'key@'.$idx.'=' . $idx+1 );
			$this->assertThat ( $value->ordering, $this->equalTo($idx + 1), 'ordering=' . $idx + 1 );
			++ $idx;
		}
	}
	/**
	 * @test
	 */
	public function isTraversable_withgaps() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>foo</uniqueID><objects><img id="m" modTime="2009-01-01-12:30:00" file="f1.jpg" format="jpg" /><img id="j" modTime="2009-01-01-12:30:00" file="f2.jpg" format="jpg" /></objects></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		$dbo->expects ( $this->atLeastOnce () )->method ( 'exists' )->with ( $this->equalTo ( 'foo' ) )->willReturn ( false );
		
		// Act
		$images = new ReaxmlEzrImages ( $xml, $dbo , new ReaxmlConfiguration() , new ReaxmlEzrRow($xml,$dbo));
		
		// Assert
		$idx = 0;
		foreach ( $images as $key => $value ) {
			$this->assertThat ( $key, $this->equalTo ( $idx+1 ), 'key@'.$idx.'=' . $idx+1 );
			$this->assertThat ( $value->ordering, $this->equalTo ( $idx + 1 ), 'ordering@' . $idx . '=' . $idx + 1 );
			if ($idx > 0 && $idx < 10) {
				$this->assertThat ( $value->fname, $this->equalTo ( '' ), 'fname@' . $idx . '=""' );
			}
			++ $idx;
		}
	}
}

