<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: state.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrColState extends ReaxmlEzrColumn {
    const STATE_XPATH = '//address/state';

    /*
     * (non-PHPdoc) @see ReaxmlDbColumn::getValue()
     */
    public function getValue()
    {
        $found = $this->xml->xpath(self::STATE_XPATH);
        if (count($found) == 0) {
            if ($this->isNew()) {
                throw new RuntimeException (JText::_('LIB_REAXML_ERROR_MESSAGE_ADDRESS_STATE_NOT_IN_XML'));
            } else {
                return null;
            }
        }
        return $found[0] . '';
    }
}