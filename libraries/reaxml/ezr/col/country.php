<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: country.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColCountry extends ReaxmlEzrColumn {
    const XPATH = '//address/country';

    /*
     * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
     */
    public function getValue() {
        $found = $this->xml->xpath ( self::XPATH );
        if (count($found) == 0) {
            if ($this->isNew ()) {
                if (isSet($this->configuration->default_country))
                    return $this->configuration->default_country;
                else
                    throw new RuntimeException ( JText::_ ( 'LIB_REAXML_ERROR_MESSAGE_ADDRESS_COUNTRY_NOT_IN_XML' ) );
            } else {
                return null;
            }
        }
        return $found [0].'';

    }
}