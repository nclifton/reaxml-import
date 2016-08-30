<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.3
 * @version 0.0.56: importer.php 2015-03-11T19:57:05.574
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *         
 */
class ReaxmlMaphelper {
	private static $showlog = false;
	private static $coordinates = array ();

	public function getLatitude($address) {
		return self::getGeo ( $address )->latitude;
	}
	public function getLongitude($address) {
		return self::getGeo ( $address )->longitude;
	}
	
	private function getGeo($address) {
		if (!array_key_exists ( $address, self::$coordinates )) {
			ReaxmlImporter::logAdd ( JText::sprintf ( 'LIB_REAXML_LOG_MAPHELPER_LOOKUP', $address ) );
			$encodedAddress = htmlentities ( urlencode ( $address ) );
			if (self::iscurlinstalled ()) {
				self::$coordinates [$address] = $this->getGeoUsingCurl ( $encodedAddress );
			} else {
				self::$coordinates [$address] = $this->getGeoUsingXmlLoad ( $encodedAddress );
			}
/* 			ReaxmlImporter::logAdd(JText::sprintf ( 'Latitude: %f Logitude: %f', self::$coordinates [$address]->latitude, self::$coordinates [$address]->longitude ), JLog::DEBUG );
		} else {
			ReaxmlImporter::logAdd  ( JText::sprintf ( 'maphelper already has geocode for address: ', $address ), JLog::DEBUG );	 */		
		}
		return self::$coordinates [$address];
	}
	private function getGeoUsingXmlLoad($address) {
		
		$geocode = new stdClass ();
		$geocode->latitude = '';
		$geocode->longitude = '';
		
		$url = "http://maps.googleapis.com/maps/api/geocode/xml?address=" . $address . "&sensor=false";
		
		$delay = 0;
		$geocode_pending = true;
		
		// load file from url
		while ( $geocode_pending ) {
			try {
				$xml = simplexml_load_file ( $url );
			} catch ( Exception $e ) {
				// return an empty array for a file request exception
				return array ();
			}
			
			// get response status
			$status = $xml->status;
			
			if (strcmp ( $status, 'OK' ) == 0) {
				$geocode_pending = false;
				
				// get coordinates from xml response
				
				$geocode->latitude = $xml->result->geometry->location->lat;
				$geocode->longitude = $xml->result->geometry->location->lng;
			} 			

			// handle timeout responses and delay re-execution of geocoding
			else if (strcmp ( $status, 620 ) == 0) {
				$delay += 100000;
			}
			
			usleep ( $delay );
		}
		if ($geocode->longitude ==''){
			echo 'failed to get the geocode';
			ReaxmlImporter::logAdd ( JText::sprintf ( 'LIB_REAXML_LOG_MAPHELPER_ERROR', 'unknown' ), JLog::ERROR );
		}
		
		return $geocode;
	}
	private function getGeoUsingCurl($address) {
		$geocode = new stdClass ();
		$geocode->latitude = '';
		$geocode->longitude = '';
		
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&sensor=false";
		
		if ($ch = curl_init ()) {
			
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_HEADER, 0 );
			curl_setopt ( $ch, CURLOPT_USERAGENT, $_SERVER ["HTTP_USER_AGENT"] );
			curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			
			$mdata = curl_exec ( $ch );
			curl_close ( $ch );
			$geo_json = json_decode ( $mdata, true );
			//print_r ( $geo_json );
			
			if ($geo_json ['status'] == 'OK') {
				
				if (isset ( $geo_json ['results'] [0] ['geometry'] ['location'] ['lat'] )) {
					$geocode->latitude = $geo_json ['results'] [0] ['geometry'] ['location'] ['lat'];
				}
				if (isset ( $geo_json ['results'] [0] ['geometry'] ['location'] ['lng'] )) {
					$geocode->longitude = $geo_json ['results'] [0] ['geometry'] ['location'] ['lng'];
				}
			} else {
				echo "Error in geocoding! Http error " . substr ( $mdata, 0, 3 );
				ReaxmlImporter::logAdd ( JText::sprintf ( 'LIB_REAXML_LOG_MAPHELPER_ERROR', substr ( $mdata, 0, 3 ) ), JLog::ERROR );
			}
		}
		return $geocode;
	}
	private function iscurlinstalled() {
		if (in_array ( 'curl', get_loaded_extensions () )) {
			return true;
		} else {
			return false;
		}
	}
}