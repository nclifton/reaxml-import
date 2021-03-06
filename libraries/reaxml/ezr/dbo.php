<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Library REAXML Library for Joomla!
 * @version 1.5.10: dbo.php 2016-08-15T00:04:57.830
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since 3.6
 *
 **/ 
class ReaxmlEzrDbo {
	private $db;
	private $ids = array ();
	private $images = array ();
	private $imageIds = array ();
	public function exists($row_id) {
		return ($this->getId ( $row_id ) > 0);
	}
	public function getId($row_id) {
		if (is_a ( $row_id, 'ReaxmlEzrRow' )) {
			$key = $row_id->mls_id;
		} else {
			$key = $row_id;
		}
		if (! isset ( $this->ids [$key] )) {
			$this->lookupPropertyId ( $key );
		}
		return $this->ids [$key];
	}
	private function lookupPropertyId($key) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( $db->quoteName ( 'id' ) );
		$query->from ( $db->quoteName ( '#__ezrealty' ) );
		$query->where ( $db->quoteName ( 'mls_id' ) . ' = ' . $db->quote ( $key ) );
		$db->setQuery ( $query );
		$id = $db->loadResult ();
		$this->ids [$key] = $id; // should be false if not found ...
	}
	private function getDb() {
		if (! isset ( $this->db )) {
			$this->db = JFactory::getDbo ();
		}
		return $this->db;
	}
	public function lookupEzrAgentUidUsingAgentName($agentName) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( 'uid' );
		$query->from ( $db->quoteName ( '#__ezportal' ) );
		$query->where ( $db->quoteName ( 'seller_name' ) . ' = ' . $db->quote ( $agentName ) );
		$db->setQuery ( $query );
		$uid = $db->loadResult ();
		return ($uid == null) ? false : $uid;
	}
	public function lookupEzrLocidUsingLocalityDetails($suburb, $postcode, $state, $country) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( $db->qn('l.id') );
		$query->from ( $db->quoteName ( '#__ezrealty_locality', 'l' ) );
		$query->join ( 'INNER', $db->qn ( '#__ezrealty_state', 's' ) . ' ON (' . $db->qn ( 'l.stateid' ) . ' = ' . $db->qn ( 's.id' ) . ')' );
		$query->join ( 'INNER', $db->qn ( '#__ezrealty_country', 'c' ) . ' ON (' . $db->qn ( 's.countid' ) . ' = ' . $db->qn ( 'c.id' ) . ')' );
		$query->where ( 'LOWER(' . $db->qn ( 'l.ezcity' ) . ') = ' . $db->q ( strtolower ( $suburb ) ) );
		$query->where ( $db->qn ( 'l.postcode' ) . ' = ' . $db->q ( $postcode ), 'AND' );
		$query->where ( 'LOWER(' . $db->qn ( 's.name' ) . ') = ' . $db->q ( $state ), 'AND' );
		$query->where ( '(LOWER(' . $db->qn ( 'c.name' ) . ') = ' . $db->q ( strtolower ( $country ) ) . ' OR LOWER(' . $db->qn ( 'c.alias' ) . ') = ' . $db->q ( strtolower ( $country ) ). ' OR LOWER(' . $db->qn ( 'c.name' ) . ') like ' . $db->q ( strtolower ( $country . '%' ) ) . ')', 'AND' );
		$db->setQuery ( $query );
		$id = $db->loadResult ();
		return ($id == null) ? false : $id;
	}
	public function lookupEzrStidUsingState($state) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( 'id' );
		$query->from ( $db->quoteName ( '#__ezrealty_state' ) );
		$query->where ( 'LOWER(' . $db->quoteName ( 'name' ) . ') = ' . $db->quote ( strtolower ( $state ) ) );
		$db->setQuery ( $query );
		$id = $db->loadResult ();
		return ($id == null) ? false : $id;
	}
	public function lookupEzrCnidUsingCountry($country) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( 'id' );
		$query->from ( $db->quoteName ( '#__ezrealty_country' ) );
		$query->where ( 'LOWER(' . $db->quoteName ( 'name' ) . ') = ' . $db->quote ( strtolower ( $country ) ), 'OR' );
		$query->where ( 'LOWER(' . $db->quoteName ( 'alias' ) . ') = ' . $db->quote ( strtolower ( $country ) ), 'OR' );
		$query->where ( 'LOWER(' . $db->quoteName ( 'name' ) . ') like ' . $db->quote ( strtolower ( $country . '%' ) ), 'OR' );
		$db->setQuery ( $query );
		$id = $db->loadResult ();
		return ($id == null) ? false : $id;
	}
	public function lookupEzrPorchpatio($mls_id) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( 'porchPatio' );
		$query->from ( $db->quoteName ( '#__ezrealty' ) );
		$query->where ( $db->quoteName ( 'mls_id' ) . ' = ' . $db->quote ( $mls_id ) );
		$db->setQuery ( $query );
		$porchpatio = $db->loadResult ();
		return ($porchpatio == null) ? '' : $porchpatio;
	}
	public function lookupEzrOtherrooms($mls_id) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( 'otherrooms' );
		$query->from ( $db->quoteName ( '#__ezrealty' ) );
		$query->where ( $db->quoteName ( 'mls_id' ) . ' = ' . $db->quote ( $mls_id ) );
		$db->setQuery ( $query );
		$otherrooms = $db->loadResult ();
		return ($otherrooms == null) ? '' : $otherrooms;
	}
	public function lookupEzrCategoryIdUsingCategoryName($categoryName) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( 'id' );
		$query->from ( $db->quoteName ( '#__ezrealty_catg' ) );
		$query->where ( $db->quoteName ( 'name' ) . ' = ' . $db->quote ( $categoryName ) );
		$db->setQuery ( $query );
		$cid = $db->loadResult ();
		return ($cid == null) ? false : $cid;
	}
	private function lookupSingleEzrealtyColumn($mls_id, $columnName) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( $columnName );
		$query->from ( $db->quoteName ( '#__ezrealty' ) );
		$query->where ( $db->quoteName ( 'mls_id' ) . ' = ' . $db->quote ( $mls_id ) );
		$db->setQuery ( $query );
		$value = $db->loadResult ();
		return ($value == null) ? '' : $value;
	}
	public function lookupEzrIndoorFeatures($mls_id) {
		return $this->lookupSingleEzrealtyColumn ( $mls_id, 'indoorFeatures' );
	}
	public function lookupEzrOutdoorFeatures($mls_id) {
		return $this->lookupSingleEzrealtyColumn ( $mls_id, 'outdoorFeatures' );
	}
	public function lookupEzrBuildingFeatures($mls_id) {
		return $this->lookupSingleEzrealtyColumn ( $mls_id, 'buildingFeatures' );
	}
	public function lookupEzrOtherFeatures($mls_id) {
		return $this->lookupSingleEzrealtyColumn ( $mls_id, 'otherFeatures' );
	}
	public function lookupEzrPdfinfo1($mls_id) {
		return $this->lookupSingleEzrealtyColumn ( $mls_id, 'pdfinfo1' );
	}
	public function lookupEzrPdfinfo2($mls_id) {
		return $this->lookupSingleEzrealtyColumn ( $mls_id, 'pdfinfo2' );
	}
	public function lookupEzrFlpl1($mls_id) {
		return $this->lookupSingleEzrealtyColumn ( $mls_id, 'flpl1' );
	}
	public function lookupEzrFlpl2($mls_id) {
		return $this->lookupSingleEzrealtyColumn ( $mls_id, 'flpl2' );
	}
	public function insert(ReaxmlEzrRow $row, ReaxmlEzrImages $images = NULL) {
		$object = $this->getRowPropertyValues ( $row );
		if ($this->getDb ()->insertObject ( '#__ezrealty', $object, 'id' )) {
			$this->updateIncats ( $object );
			$this->updateCom_ezrealtyParamsOtherFeatures ( $object );
			if (isset ( $images )) {
				$this->updateImages ( $object, $images );
			}
		}
	}
	private function updateImages($row, ReaxmlEzrImages $images) {
		foreach ( $images as $ordering => $image ) {
			$currentImage = $this->lookupImageUsingMls_idAndOrdering ( $row->mls_id, $ordering );
			$object = $this->getImagePropertyValues ( $image, $currentImage );
			if (isset ( $currentImage )) {
				if (isset ( $object->fname )) {
					if (empty ( ($object->fname) . '' )) {
						$db = $this->getDb ();
						$query = $db->getQuery ( true );
						$query->delete ( $db->qn ( '#__ezrealty_images' ) )->where ( $db->qn ( 'id' ) . ' = ' . $currentImage->id );
						$db->setQuery ( $query );
						$db->execute ();
					} else {
						$this->getDb ()->updateObject ( '#__ezrealty_images', $object, array (
								'id' 
						), false );
					}
				}
			} else {
				if (! empty ( $object->fname . '' )) {
					$object->propid = $row->id;
					$this->getDb ()->insertObject ( '#__ezrealty_images', $object, 'id' );
				}
			}
		}
	}
	private function lookupImageUsingMls_idAndOrdering($mls_id, $ordering) {
		if (! $this->imageCached ( $mls_id, $ordering )) {
			$db = $this->getDb ();
			$query = $db->getQuery ( true );
			$query->select ( 'a.' . $db->qn ( 'id', 'id' ) )->select ( 'a.' . $db->qn ( 'fname', 'fname' ) )->select ( 'a.' . $db->qn ( 'ordering', 'ordering' ) );
			$query->from ( $db->qn ( '#__ezrealty_images', 'a' ) )->join ( 'INNER', '#__ezrealty as b ON b.id = a.propid' )->where ( 'b.' . $db->qn ( 'mls_id' ) . ' = ' . $db->q ( $mls_id ), 'AND' )->where ( 'a.' . $db->qn ( 'ordering' ) . ' = ' . $db->q ( $ordering ) );
			$db->setQuery ( $query );
			$currentImage = $db->loadObject ();
			$this->cacheImage ( $mls_id, $ordering, $currentImage );
		}
		return $this->images [$mls_id] [$ordering];
	}
	private function cacheImage($mls_id, $ordering, $image) {
		if (! isset ( $this->imageIds [$mls_id] )) {
			$this->images [$mls_id] = array ();
			$this->imageIds [$mls_id] = array ();
		}
		if (! isset ( $this->imageIds [$mls_id] [$ordering] )) {
			$this->images [$mls_id] [$ordering] = $image;
			$this->imageIds [$mls_id] [$ordering] = (isset ( $image )) ? $image->id : 0;
		}
	}
	private function imageCached($mls_id, $ordering) {
		if (! isset ( $this->imageIds [$mls_id] )) {
			return false;
		} else {
			if (! isset ( $this->imageIds [$mls_id] [$ordering] )) {
				return false;
			} else {
				return true;
			}
		}
	}
	public function lookupImageId($mls_id, $ordering) {
		if (! isset ( $this->ids [$mls_id] )) {
			if (! $this->exists ( $mls_id )) {
				return 0;
			}
		}
		if (! $this->imageCached ( $mls_id, $ordering )) {
			$this->lookupImageUsingMls_idAndOrdering ( $mls_id, $ordering );
		}
		return $this->imageIds [$mls_id] [$ordering];
	}
	private function getImagePropertyValues(ReaxmlEzrImage $image, stdClass $currentImage = null) {
		$object = new stdClass ();
		foreach ( array_keys ( $this->getEzrealtyImagesColumns () ) as $colName ) {
			if ($colName == 'id') {
				$value = isset ( $currentImage ) ? $currentImage->id : null;
			} else {
				$value = $image->getValue ( $colName );
			}
			if ($value !== null) {
				$object->$colName = $value;
			}
		}
		return $object;
	}
	private function getRowPropertyValues(ReaxmlEzrRow $row) {
		$object = new stdClass ();
		foreach ( array_keys ( $this->getEzrealtyColumns () ) as $colName ) {
			if ($colName == 'id') {
				$value = ($this->exists ( $row )) ? $this->ids [$row->mls_id] : null;
			} else {
				$value = $row->getValue ( $colName );
			}
			if ($value !== null) {
				$object->$colName = $value;
			}
		}
		return $object;
	}
	private function updateCom_ezrealtyParamsOtherFeatures($rowPropertyValues) {
		if (property_exists ( $rowPropertyValues, 'otherFeatures' )) {
			$otherFeatures = $rowPropertyValues->otherfeatures;
			if ($otherFeatures !== null) {
				$otherFeatures = explode ( ';', $otherFeatures );
				$this->updateCom_ezrealtyParamsFeatures ( 'otherfeats', $otherFeatures );
			}
		}
	}
	public function updateCom_ezrealtyParamsFeatures($featureSetName, array $featureNameSet) {
		$ezrparams = JComponentHelper::getParams ( 'com_ezrealty' );
		
		$feats = trim ( $ezrparams->get ( $featureSetName ) );
		$feats = (substr ( $feats, - 1 ) == ';') ? substr ( $feats, 0, - 1 ) : $feats;
		$feats = explode ( ';', $feats );
		foreach ( $featureNameSet as $featureName ) {
			if (! in_array ( $featureName, $feats )) {
				array_push ( $feats, $featureName );
			}
		}
		$feats = join ( ';', $feats ) . ';';
		$ezrparams->set ( $featureSetName, $feats );
		
		// Get a new database query instance
		$db = JFactory::getDBO ();
		$query = $db->getQuery ( true );
		
		// Build the query
		$query->update ( '#__extensions AS a' );
		$query->set ( 'a.params = ' . $db->quote ( ( string ) $ezrparams ) );
		$query->where ( 'a.element = "com_ezrealty"' );
		
		// Execute the query
		$db->setQuery ( $query );
		$db->query ();
	}
	private function updateIncats($rowPropertyValues) {
		if (property_exists ( $rowPropertyValues, 'cid' ) && $rowPropertyValues->cid !== null && $this->countIncatsForPropertyIdCategoryId ( $rowPropertyValues->id, $rowPropertyValues->cid ) == 0) {
			$this->insertIncats ( $rowPropertyValues->id, $rowPropertyValues->cid );
		}
	}
	private function insertIncats($id, $cid) {
		$object = new stdClass ();
		$object->property_id = $id;
		$object->category_id = $cid;
		$db = $this->getDb ();
		$db->insertObject ( '#__ezrealty_incats', $object );
	}
	private function countIncatsForPropertyIdCategoryId($id, $cid) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( 'COUNT(*)' )->from ( $db->quoteName ( '#__ezrealty_incats' ) );
		$query->where ( $db->quoteName ( 'property_id' ) . ' = ' . $id, 'AND' )->where ( $db->quoteName ( 'category_id' ) . ' = ' . $cid, 'AND' );
		$db->setQuery ( $query );
		return $db->loadResult ();
	}
	private function getEzrealtyColumns() {
		return $this->getDb ()->getTableColumns ( '#__ezrealty' );
	}
	private function getEzrealtyImagesColumns() {
		return $this->getDb ()->getTableColumns ( '#__ezrealty_images' );
	}
	public function update(ReaxmlEzrRow $row, ReaxmlEzrImages $images = null) {
		$object = $this->getRowPropertyValues ( $row );
		if ($this->getDb ()->updateObject ( '#__ezrealty', $object, 'id', false )) {
			$this->updateIncats ( $object );
			$this->updateCom_ezrealtyParamsOtherFeatures ( $object );
			if (isset ( $images )) {
				$this->updateImages ( $row, $images );
			}
		}
	}
	public function lookupEzrImageFnameUsingMls_idAndOrdering($mls_id, $ordering) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( $db->qn ( 'a.fname', 'fname' ) )->from ( $db->qn ( '#__ezrealty_images', 'a' ) )->join ( 'INNER', '#__ezrealty as b on a.propid = b.id' );
		$query->where ( 'b.' . $db->qn ( 'mls_id' ) . ' = ' . $db->q ( $mls_id ), 'AND' );
		$query->where ( 'a.' . $db->qn ( 'ordering' ) . ' = ' . $db->q ( $ordering ), 'AND' );
		$db->setQuery ( $query );
		return $db->loadResult ();
	}
	public function countEzrImagesUsingMls_id($mls_id) {
		$db = $this->getDb ();
		$query = $db->getQuery ( true );
		$query->select ( 'count(*)' )->from ( $db->qn ( '#__ezrealty_images', 'a' ) )->join ( 'INNER', '#__ezrealty as b on a.propid = b.id' );
		$query->where ( $db->qn ( 'b.mls_id' ) . ' = ' . $db->q ( $mls_id ) );
		$db->setQuery ( $query );
		return $db->loadResult ();
	}
	public function insertEzrLocality($ezcity, $stateid, $postcode) {
		$locality = new stdClass ();
		$locality->stateid = $stateid;
		$locality->ezcity = $ezcity;
		$locality->alias = $this->generateAlias ( $ezcity );
		$locality->postcode = $postcode;
		$locality->language = '*';
		$locality->ordering = 1;
		
		$db = $this->getDb ();
		$db->insertObject ( '#__ezrealty_locality', $locality );
		return $db->insertid ();
	}
	public function insertEzrState($name, $countryid) {
		$state = new stdClass ();
		$state->countid = $countryid;
		$state->name = $name;
		$state->alias = $this->generateAlias ( $name );
		$state->language = '*';
		$state->ordering = 1;
		
		$db = $this->getDb ();
		$db->insertObject ( '#__ezrealty_state', $state );
		return $db->insertid ();
	}
	public function insertEzrCountry($name) {
		$country = new stdClass ();
		$country->name = $name;
		$country->alias = $this->generateAlias ( $name );
		$country->language = '*';
		$country->ordering = 1;
		
		$db = $this->getDb ();
		$db->insertObject ( '#__ezrealty_country', $country );
		return $db->insertid ();
	}
	public function insertEzrCategory($name) {
		$category = new stdClass ();
		$category->id = 0;
		$category->name = $name;
		$category->alias = $this->generateAlias ( $name );
		$category->access = 1;
		$category->language = '*';
		$category->ordering = 1;
		$db = $this->getDb ();
		$db->insertObject ( '#__ezrealty_catg', $category, 'id' );
		return $category->id;
	}
	public function insertEzrAgent($name, $mail = null, $telephone = null) {
		$user = new stdClass ();
		$user->id = 0;
		$user->name = $name;
		$user->username = $this->generateUsername ( $name );
		$user->block = 1;
		$user->activation = 0;
		$user->sendEmail = 0;
		$user->email = $mail == null ? 'nosuchaddress@localhost' : $mail;
		
		$db = $this->getDb ();
		$db->insertObject ( '#__users', $user, 'id' );
		
		$agent = new stdClass ();
		$agent->id = 0;
		$agent->alias = $this->generateAlias ( $name );
		$agent->uid = $user->id;
		$agent->seller_name = $name;
		$agent->seller_phone = $telephone;
		$agent->seller_email = $user->email;
		$agent->show_addy = 0;
		$agent->published = 1;
		$agent->ordering = 1;
		
		$db->insertObject ( '#__ezportal', $agent, 'id' );
		return $agent->uid;
	}
	private function generateAlias($string) {
		// replace spaces with dashes
		$string = str_replace ( " ", "-", $string );
		// lowercase
		$string = strtolower ( $string );
		// keep only numbers and letters and dashes
		$string = preg_replace ( '/[^a-z0-9-]/', '', $string );
		
		return $string;
	}
	private function generateUsername($string) {
		return preg_replace ( '/[^a-z0-9-]/', '', strtolower ( $string ) );
	}
}