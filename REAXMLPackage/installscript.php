<?php

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */
define ( 'REAXML_LIBRARIES', JPATH_ROOT . '/libraries' );

JLoader::registerPrefix ( 'Reaxml', REAXML_LIBRARIES . '/reaxml' );
JLoader::import('joomla.installer.installer');
JLoader::import('reaxml.ezr.dbo');


class com_reaxmlImportInstallerScript {
	
	/**
	 * method to install the library
	 *
	 * @return void
	 */
	function install($parent) {
		// $parent is the class calling this method
		echo '<p>' . JText::_ ( 'COM_REAXMLIMPORT_INSTALL_TEXT' ) . '</p>';
	}
	
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) {
		// $parent is the class calling this method
		echo '<p>' . JText::_ ( 'COM_REAXMLIMPORT_UNINSTALL_TEXT' ) . '</p>';
	}
	
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) {
		// $parent is the class calling this method
		echo '<p>' . JText::sprintf ( 'COM_REAXMLIMPORT_UPDATE_TEXT', $parent->get ( 'manifest' )->version ) . '</p>';
	}
	
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) {
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '<p>' . JText::_ ( strtoupper ( 'COM_REAXMLIMPORT_PREFLIGHT_' . $type . '_TEXT' ) ) . '</p>';
	}
	
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) {
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '<p>' . JText::_ ( strtoupper ( 'COM_REAXMLIMPORT_POSTFLIGHT_' . $type . '_TEXT' ) ) . '</p>';
		$this->updateCom_ezrealtyParams ();
		$this->update_ezrealty_table ();
	}
	private function updateCom_ezrealtyParams() {
		echo '<p>' . JText::_ ( 'COM_REAXMLIMPORT_ADJUSTING_EZREALTY' ) . '</p>';
		
		// load the library language file
		$lang = JFactory::getLanguage ();
		$extension = 'lib_reaxml';
		$base_dir = JPATH_SITE;
		$language_tag = 'en-GB';
		$reload = true;
		$lang->load ( $extension, $base_dir, $language_tag, $reload );
		
		JLoader::registerPrefix ( 'Reaxml', REAXML_LIBRARIES . '/reaxml' );
		jimport ( 'reaxml.ezr.dbo' );
		
		$dbo = new ReaxmlEzrDbo ();
		$dbo->updateCom_ezrealtyParamsFeatures ( 'appliancefeats', array (
				JText::_ ( 'LIB_REAXML_APPLIANCE_FEATURE_DISHWASHER' ) 
		) );
		$dbo->updateCom_ezrealtyParamsFeatures ( 'indoorfeats', array (
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_AIR_CONDITIONING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_VACUUM_SYSTEM' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_OPEN_FIRE_PLACE' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_GAS_HEATING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_ELECTRIC_HEATING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_GDH_HEATING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_SOLID_FUEL_HEATING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_HEATING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_GAS_HOT_WATER' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_ELECTRIC_HOT_WATER' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_SOLAR_HOT_WATER' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_BUILTIN_ROBES' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_DUCTED_COOLING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_DUCTED_HEATING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_EVAPORATIVE_COOLING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_HYDRONIC_HEATING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_REVERSE_CYCLE_AIR_CONDITIONING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_SPLIT_SYSTEM_AIR_CONDITIONING' ),
				JText::_ ( 'LIB_REAXML_INDOOR_FEATURE_SPLIT_SYSTEM_HEATING' ),
				JText::_ ( 'LIB_REAXML_FEATURE_SPA' ) 
		) );
		$dbo->updateCom_ezrealtyParamsFeatures ( 'outdoorfeats', array (
				JText::_ ( 'LIB_REAXML_FEATURE_SPA' ),
				JText::_ ( 'LIB_REAXML_OUTDOOR_FEATURE_IN_GROUND_POOL' ),
				JText::_ ( 'LIB_REAXML_OUTDOOR_FEATURE_ABOVE_GROUND_POOL' ),
				JText::_ ( 'LIB_REAXML_OUTDOOR_FEATURE_ABOVE_GROUND_SPA' ),
				JText::_ ( 'LIB_REAXML_OUTDOOR_FEATURE_IN_GROUND_SPA' ),
				JText::_ ( 'LIB_REAXML_OUTDOOR_FEATURE_TENNIS_COURT' ),
				JText::_ ( 'LIB_REAXML_OUTDOOR_FEATURE_SHED' ),
				JText::_ ( 'LIB_REAXML_OUTDOOR_FEATURE_OUTDOOR_ENTERTAINMENT' ) 
		) );
		$dbo->updateCom_ezrealtyParamsFeatures ( 'buildingfeats', array (
				JText::_ ( 'LIB_REAXML_BUILDING_FEATURE_BALCONY' ),
				JText::_ ( 'LIB_REAXML_BUILDING_FEATURE_DECK' ),
				JText::_ ( 'LIB_REAXML_BUILDING_FEATURE_COURTYARD' ),
				JText::_ ( 'LIB_REAXML_BUILDING_FEATURE_FLOORBOARDS' ) 
		) );
		$dbo->updateCom_ezrealtyParamsFeatures ( 'otherfeats', array (
				JText::_ ( 'LIB_REAXML_OTHER_FEATURE_INTERCOM' ),
				JText::_ ( 'LIB_REAXML_OTHER_FEATURE_REMOTE_CONTROL_GARAGE_DOOR' ),
				JText::_ ( 'LIB_REAXML_OTHER_FEATURE_SECURE_PARKING' ),
				JText::_ ( 'LIB_REAXML_OTHER_FEATURE_ALARM_SYSTEM' ),
				JText::_ ( 'LIB_REAXML_OTHER_FEATURE_FULLY_FENCED' ),
				JText::_ ( 'LIB_REAXML_OTHER_FEATURE_BROADBAND' ),
				JText::_ ( 'LIB_REAXML_OTHER_FEATURE_PAY_TV' ),
				JText::_ ( 'LIB_REAXML_OTHER_FEATURE_SOLAR_PANELS' ),
				JText::_ ( 'LIB_REAXML_OTHER_FEATURE_WATER_TANK' ),
				JText::_ ( 'LIB_REAXML_OTHER_FEATURE_GREY_WATER_SYSTEM' ) 
		) );
	}
	private function update_ezrealty_table() {
		echo '<p>' . JText::_ ( 'COM_REAXMLIMPORT_CHECKING_EZREALTY_MLS_ID' ) . '</p>';
		
		$db = JFactory::getDbo ();
		$sql = "SELECT substring_index(column_type,'(',1) as type, substring_index(substring_index(column_type,')',1),'(',-1) as size FROM information_schema.columns where table_name = '" . $db->getPrefix () . "ezrealty' and column_name = 'mls_id'";
		$db->setQuery ( $sql );
		$row = $db->loadAssoc ();
		if (($row ['type'] == 'varchar') && ($row ['size'] < 36)) {
			echo '<p>' . JText::_ ( 'COM_REAXMLIMPORT_ADJUSTING_EZREALTY_MLS_ID' ) . '</p>';
			$db->setQuery ( "alter table #__ezrealty modify column mls_id varchar(36) NOT NULL DEFAULT ''" );
			$db->execute ();
		}
	}
}