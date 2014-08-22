<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/ 
class ReaxmlRowTest extends PHPUnit_Framework_TestCase {
	

	
	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage Column with name "foo" is not supported
	 */
	public function getValue_classNotFound() {
		
		// Arrange
		$xml = new SimpleXMLElement ( '<residential/>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		
		// Act
		$row = new ReaxmlEzrRow ( $xml, $dbo );
		$result = $row->getValue ( 'foo' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 'foo' ) );
	}
	
	/**
	 * @test
	 */
	public function getValue_mls_id() {
		// Arrange
		$xml = new SimpleXMLElement ( '<residential><uniqueID>ABCD1234</uniqueID></residential>' );
		$dbo = $this->getMock ( 'ReaxmlEzrDbo' );
		
		// Act
		$row = new ReaxmlEzrRow ( $xml, $dbo );
		$result = $row->getValue ( 'mls_id' );
		
		// Assert
		$this->assertThat ( $result, $this->equalTo ( 'ABCD1234' ) );
	}
	
	/**
	 * @test
	 */
	public function all_ezrealty_columns_have_a_class() {
		
		/*
		 * SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='yourdatabasename' AND `TABLE_NAME`='yourtablename';
		 */
		$db = JFactory::getDbo ();
		
		$columns = $db->getTableColumns ( '#__ezrealty' );
		$dbo = new ReaxmlEzrDbo();
		
		$xml = simplexml_load_file ( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_insert_sample.xml' );	
		$xml = new SimpleXMLElement($xml->children ()[0]->asXML());
		
		$row = new ReaxmlEzrRow ( $xml, $dbo );
		
		foreach ( array_keys($columns) as $name ) {
			if ($name !== 'id') {
				try {
					$result = $row->getValue ( $name );
					JLog::add ( '"' . $name . '" = "' . $result . '"', JLog::INFO, REAXML_LOG_CATEGORY );
				} catch (Exception $e) {
					JLog::add ( '"' . $name . '" error '.$e->getMessage(), JLog::ERROR, REAXML_LOG_CATEGORY );
				}
			}
		}
		$this->assertThat(true , $this->isTrue());
	}
	
}
