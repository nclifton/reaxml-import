<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla! 3.4
 * @version 1.3.3: column.php 2015-04-21T14:32:48.880
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *         
 *         
 */
abstract class ReaxmlEzrMapcolumn extends ReaxmlEzrColumn {
	const XPATH_ADDRESS_STREETNUMBER = '//address/streetNumber';
	const XPATH_ADDRESS_STREET = '//address/street';
	const XPATH_SUBURB = '//address/suburb';
	const XPATH_POSTCODE = '//address/postcode';
	const XPATH_STATE = '//address/state';
	const XPATH_COUNTRY = '//address/country';
	const ADDRESS_FORMAT = '%s %s, %s, %s %s, %s';
	const DEFAULT_COUNTRY = 'Australia';
	private $maphelper;
	public function __construct($xml, $dbo = null, $config = null) {
		Parent::__construct($xml,$dbo,$config);
		$this->maphelper = new ReaxmlMaphelper ();
	}
	protected function getLatitude() {
		//ReaxmlImporter::logAdd ( 'will get latitude using map helper', JLog::DEBUG );
		return $this->getMaphelper ()->getLatitude ( $this->getAddress () );
	}
	protected function getLongitude() {
		//ReaxmlImporter::logAdd ( 'will get longitude using map helper', JLog::DEBUG );
		return $this->getMaphelper ()->getLongitude ( $this->getAddress () );
	}
	public function getMaphelper() {
		return $this->maphelper;
	}
	public function setMaphelper($maphelper) {
		$this->maphelper = $maphelper;
		return $this;
	}
	private function getAddress() {
		return sprintf ( self::ADDRESS_FORMAT, $this->getStreetNumber (), $this->getStreetName (), $this->getSuburb (), $this->getState (), $this->getPostcode (), $this->getCountry () );
	}
	private function getStreetNumber() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_STREETNUMBER );
		return (count ( $found ) > 0) ? $found [0] . '' : '';
	}
	private function getStreetName() {
		$found = $this->xml->xpath ( self::XPATH_ADDRESS_STREET );
		return (count ( $found ) > 0) ? $found [0] . '' : '';
	}
	private function getSuburb() {
		$found = $this->xml->xpath ( self::XPATH_SUBURB );
		return (count ( $found ) > 0) ? $found [0] . '' : '';
	}
	private function getPostcode() {
		$found = $this->xml->xpath ( self::XPATH_POSTCODE );
		return (count ( $found ) > 0) ? $found [0] . '' : '';
	}
	private function getState() {
		$found = $this->xml->xpath ( self::XPATH_STATE );
		return (count ( $found ) > 0) ? $found [0] . '' : '';
	}
	private function getCountry() {
		$found = $this->xml->xpath ( self::XPATH_COUNTRY );
		return (count ( $found ) > 0) ? $found [0] . '' : self::DEFAULT_COUNTRY;
	}
}