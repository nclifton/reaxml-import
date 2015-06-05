<?php

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/
class ReaxmlMaphelper_Test extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function valid_address_obtains_latitude(){
		// Arrange
		$helper = new ReaxmlMaphelper();
		
		// Act
		$latitude = $helper->getLatitude('12 Wyndham Avenue, Leumeah, NSW 2560, Australia');
		
		// Assert
		$this->assertThat($latitude, $this->equalTo(-34.0438697, 
				                                      0.0000001));
	}

	/**
	 * @test
	 */
	public function valid_address_obtains_longitude(){
		// Arrange
		$helper = new ReaxmlMaphelper();
	
		// Act
		$longitude = $helper->getLongitude('12 Wyndham Avenue, Leumeah, NSW 2560, Australia');
	
		// Assert
		$this->assertThat($longitude, $this->equalTo(150.8434243,
				                                       0.0000001));
	}
	
}