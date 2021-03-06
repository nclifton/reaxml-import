<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: porchpatio.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColPorchpatio extends \ReaxmlEzrColumn {
	const XPATH_FEATURES_DECK = '//features/deck';
	const XPATH_FEATURES_BALCONY = '//features/balcony';
	public function getValue() {
		$features = array ();
		$nodeck = false;
		$nobalcony = false;
		$found = $this->xml->xpath ( self::XPATH_FEATURES_DECK );
		if (count($found) >0) {
			if ($this->featureBoolean ( $found [0] )) {
				$features ['deck'] = JText::_ ( 'LIB_REAXML_EZR_PORCH_PATIO_DECK' );
			}
		} else {
			$nodeck = true;
		}
		
		$found = $this->xml->xpath ( self::XPATH_FEATURES_BALCONY );
		if (count($found) >0) {
			if ($this->featureBoolean ( $found [0] )) {
				$features ['balcony'] = JText::_ ( 'LIB_REAXML_EZR_PORCH_PATIO_BALCONY' );
			}
		} else {
			$nobalcony = true;
		}
		
		if ($nodeck || $nobalcony) {
			if ($this->isNew ()) {
				if ($nodeck) {
					unset ( $features ['deck'] );
				}
				if ($nobalcony) {
					unset ( $features ['balcony'] );
				}
			} else {
				$string = $this->dbo->lookupEzrPorchpatio ( $this->getId () );
				
				$deckInDb = (strpos ( $string, "Deck" ) !== false);
				$balconyInDb = (strpos ( $string, "Balcony" ) !== false);
				
				if ($nodeck && $deckInDb) {
					$features ['deck'] = JText::_ ( 'LIB_REAXML_EZR_PORCH_PATIO_DECK' );
				}
				if ($nobalcony && $balconyInDb) {
					$features ['balcony'] = JText::_ ( 'LIB_REAXML_EZR_PORCH_PATIO_BALCONY' );
				}
			}
		}
		$strings = array_values ( $features );
		if (count ( $strings ) > 0) {
			return join ( ', ', $strings );
		} else if ($this->isNew ()) {
			return '';
		} else {
			return null;
		}
	}
}