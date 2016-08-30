<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 * @author nclifton
 *        
 */
class ReaxmlDboLookup_Test extends Reaxml_Tests_DatabaseTestCase {
	
	/**
	 * @beforeclass
	 */
	public function classsetup() {
		$dt = new DateTime ();
		$logfile = 'REAXMLImport-' . $dt->format ( 'YmdHis' ) . '.log';
		JLog::addLogger ( array (
				'text_file' => $logfile,
				'text_file_path' => __DIR__ . '/../test_log',
				'text_file_no_php' => true,
				'text_entry_format' => '{DATE} {TIME} {PRIORITY} {MESSAGE}' 
		), JLog::ALL, array (
				REAXML_LOG_CATEGORY 
		) );
	}
	
	/**
	 * @before
	 */
	public function setUp() {
		parent::setUp ();
	}
	/**
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	public function getDataSet() {
		return $this->createMySQLXMLDataSet ( __DIR__ . '/../files/ezrealty-seed.xml' );
	}
	
	/**
	 * @test
	 */
	public function exists_true() {
		
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		$xml = new SimpleXMLElement ( '<root/>' );
		$row = $this->createMock ( 'ReaxmlEzrRow');
		$row->expects ( $this->once () )->method ( '__get' )->with ( $this->equalTo ( 'mls_id' ) )->willReturn ( 'foo' );
		
		// Act
		$result = $dbo->exists ( $row );
		
		// Assert
		$this->assertThat ( $result, $this->isTrue () );
	}
	/**
	 * @test
	 */
	public function exists_false() {
		
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		$xml = new SimpleXMLElement ( '<root/>' );
		$row = $this->createMock ( 'ReaxmlEzrRow', array (
				'getValue' 
		), array (
				$xml,
				$dbo 
		) );
		$row->expects ( $this->once () )->method ( '__get' )->with ( $this->equalTo ( 'mls_id' ) )->willReturn ( 'bar' );
		
		// Act
		$result = $dbo->exists ( $row );
		
		// Assert
		$this->assertThat ( $result, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function exists_idonly_true() {
		
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->exists ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->isTrue () );
	}
	/**
	 * @test
	 */
	public function exists_idonly_false() {
		
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->exists ( 'bar' );
		
		// Assert
		$this->assertThat ( $result, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function lookupEzrAgentUidUsingAgentName_nomatch() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrAgentUidUsingAgentName ( 'Bob Smith' );
		
		// Assert
		$this->assertThat ( $result, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function lookupEzrAgentUidUsingAgentName() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrAgentUidUsingAgentName ( 'Brendan Roberts' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 650 ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrLocidUsingLocalityDetails_nomatch() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrLocidUsingLocalityDetails ( 'Back of Bourke', '2999', 'NSW', 'AUSTRALIA' );
		
		// Assert
		$this->assertThat ( $result, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function lookupEzrLocidUsingLocalityDetails_nopostcodematch() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrLocidUsingLocalityDetails ( 'Camden', '2577', 'NSW', 'AUSTRALIA' );
		
		// Assert
		$this->assertThat ( $result, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function lookupEzrLocidUsingLocalityDetails_nostatematch() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrLocidUsingLocalityDetails ( 'Camden', '2570', 'VIC', 'AUSTRALIA' );
		
		// Assert
		$this->assertThat ( $result, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function lookupEzrLocidUsingLocalityDetails_nocountrymatch() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
	
		// Act
		$result = $dbo->lookupEzrLocidUsingLocalityDetails ( 'Camden', '2570', 'NSW', 'LILYPUT' );
	
		// Assert
		$this->assertThat ( $result, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function lookupEzrLocidUsingLocalityDetails() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrLocidUsingLocalityDetails ( 'Camden', '2570', 'NSW', 'AUSTRALIA' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 4 ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrStidUsingState_nomatch() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrStidUsingState ( 'Solid' );
		
		// Assert
		$this->assertThat ( $result, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function lookupEzrStidUsingState() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrStidUsingState ( 'NSW' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrCnidUsingCountry_nomatch() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrCnidUsingCountry ( 'Life' );
		
		// Assert
		$this->assertThat ( $result, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function lookupEzrCnidUsingCountry_AU() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrCnidUsingCountry ( 'AU' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrCnidUsingCountry_AUS() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrCnidUsingCountry ( 'AUS' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrCnidUsingCountry_AUStralia() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrCnidUsingCountry ( 'AUStralia' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 1 ) );
	}
	
	/**
	 * @test
	 */
	public function lookupEzrPorchpatio() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrPorchpatio ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrOtherrooms() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrOtherrooms ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrCategoryIdUsingCategoryName() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrCategoryIdUsingCategoryName ( 'House' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 1 ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrCategoryIdUsingCategoryName_notfound() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrCategoryIdUsingCategoryName ( 'Arcology' );
		
		// Assert
		$this->assertThat ( $result, $this->isFalse () );
	}
	/**
	 * @test
	 */
	public function lookupEzrIndoorFeatures() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrIndoorFeatures ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 'Carpets;Alarm System' ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrOutdoorFeatures() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrOutdoorFeatures ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 'In Ground Pool' ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrBuildingFeatures() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrBuildingFeatures ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 'Brick' ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrOtherFeatures() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrOtherFeatures ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 'Views' ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrPdfinfo1() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrPdfinfo1 ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 'd10f2c2687c3551532ba07541036c853.pdf' ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrPdfinfo2() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrPdfinfo2 ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( '' ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrflpl1() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrFlpl1 ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 'c73ca5f56adc95bb6d64cc65143ce8a6.jpg' ) );
	}
	/**
	 * @test
	 */
	public function lookupEzrflpl2() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrFlpl2 ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( '' ) );
	}
	
	/**
	 * @test
	 */
	public function lookupEzrImageFnameUsingMls_id() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->lookupEzrImageFnameUsingMls_idAndOrdering ( 'foo', 1 );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( '3bbf4ddaa372548469385fb069d90d3d.jpg' ) );
	}
	/**
	 * @test
	 */
	public function countEzrImagesUsingMls_id() {
		// Arrange
		$dbo = new ReaxmlEzrDbo ();
		
		// Act
		$result = $dbo->countEzrImagesUsingMls_id ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 1 ) );
	}
}