<?php
defined('_JEXEC') or die ('Restricted access');

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: locid.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/
class ReaxmlEzrColLocid extends ReaxmlEzrColumn
{
    const SUBURB_XPATH = '//address/suburb';
    const POSTCODE_XPATH = '//address/postcode';
    const STATE_XPATH = '//address/state';
    const COUNTRY_XPATH = '//address/country';


    /*
     * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
     */
    public function getValue()
    {
        $found = $this->xml->xpath(self::SUBURB_XPATH);
        if (count($found) == 0) {
            if ($this->isNew()) {
                throw new RuntimeException (JText::_('LIB_REAXML_ERROR_MESSAGE_ADDRESS_SUBURB_NOT_IN_XML'));
            } else {
                return null;
            }
        }
        $suburb = $found [0] . '';
        $state = $this->row->getValue('state');
        $postcode = $this->row->getValue('postcode');
        $country = $this->row->getValue('country');
        if ($country == null) return null;

        $locid = $this->dbo->lookupEzrLocidUsingLocalityDetails($suburb, $postcode, $state, $country);
        if ($locid == false) {
            $stateid = $this->dbo->lookupEzrStidUsingState($state);
            if ($stateid == false) {
                $countryid = $this->dbo->lookupEzrCnidUsingCountry($country);
                if ($countryid == false) {
                    $countryid = $this->dbo->insertEzrCountry($country);
                }
                $stateid = $this->dbo->insertEzrState($state, $countryid);
            }
            return $this->dbo->insertEzrLocality($suburb, $stateid, $postcode);
        }
        return $locid;
    }
}