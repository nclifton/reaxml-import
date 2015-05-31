<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @package Component REAXML Import for Joomla! 3.4
 * @version 1.3.82: folderbrowser.php 2015-05-31T05:02:03.808
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2014, 2015 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *         
 *         
 */
 
function str_starts_with($haystack, $needle)
{
	return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
}
function str_ends_with($haystack, $needle)
{
	return substr_compare($haystack, $needle, -strlen($needle))	=== 0;
}

class ReaXmlImportModelsFolderbrowser extends JModelBase {
	private $inputid = '';
	private $urlinputid = '';
	private $folder = '';
	private $currenturi = '';
	private $folderlist = array ();
	private $subfolderlist = array();
	
	function __construct($state = null) {
		parent::__construct ( $state );
		$this->currenturi = JURI::current ();
		$this->folder=JPATH_ROOT;
	}
	public function setInputid($inputid) {
		$this->inputid = $inputid;
	}
	public function setFolder($folder) {
		if ($folder !== $this->folder && file_exists($folder)) {
			$this->folderlist = array();
			$this->subsolderlist = array();
			$this->folder = $folder;
			$this->getFolderList ();
			$this->getSubFolderList();		
		}
	}
	public function getInputid() {
		return $this->inputid;
	}
	public function getFolder() {
		return $this->folder;
	}
	public function getFolderList() {
		if (count ( $this->folderlist ) == 0) {
			$chunks = explode ( DIRECTORY_SEPARATOR, $this->folder );
			foreach ( $chunks as $i => $chunk ) {
				$key = 'item' . ($i + 1);
				if ($i == count ( $chunks ) - 1) {
					$key .= ' active';
				}
				if ($chunk == '' && $i == 0) {
					$this->folderlist [$key] = '/';
				} else {
					$this->folderlist [$key] = $chunk;
				}
			}
		}
		return $this->folderlist;
	}
	public function getCurrenturi() {
		return $this->currenturi;
	}
	public function setCurrenturi($currenturi) {
		$this->currenturi = $currenturi;
		return $this;
	}
	public function getSelectFolderUrl($key = 'item1') {
		if (array_key_exists ( $key, $this->getFolderList () )) {
			return $this->currenturi . '?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=' . $this->inputid . '&urlinputid='.$this->getUrlinputid().'&folder=' . $this->getFolderPathUri ( $key );
		} else {
			return '';
		}
	}
	private function getFolderPathUri($key) {
		$uri = '';
		foreach ( $this->getFolderList () as $ckey => $folder ) {
			if ($ckey !== 'item1') {
				$uri .= DIRECTORY_SEPARATOR . $folder;
			} else if ($folder !== DIRECTORY_SEPARATOR || $key == 'item1') {
				$uri .= $folder;
			}
			if ($ckey == $key) {
				return urlencode ( $uri );
			}
		}
	}
	private function getSubFolderPathUri($key) {
		$uri = $this->folder;
		if (array_key_exists ( $key, $this->getSubFolderList () )) {
			$uri .= DIRECTORY_SEPARATOR.$this->subfolderlist[$key];
		}
		return urlencode ( $uri );
	}
	public function getSubFolderList(){
		if (count($this->subfolderlist) == 0){
			$list = scandir ($this->folder);
			$i = 1;
			foreach ($list as $item) {
				if ($item !=='.' && $item !== '..' && is_dir($this->folder.DIRECTORY_SEPARATOR.$item)){
					$key = 'item'.$i;
					$this->subfolderlist[$key] = $item;
					$i++;
				}
			}
		}
		return $this->subfolderlist;
	}
	public function getSelectSubFolderUrl($key = 'item1'){
		return $this->currenturi . '?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=' . $this->inputid . '&urlinputid='.$this->getUrlinputid().'&folder=' . $this->getSubFolderPathUri ( $key );
	}
	public function getUrl(){
		if($this->folder == JPATH_ROOT){
			return "/";
		}
		if (str_starts_with($this->folder,JPATH_ROOT)){
			return str_replace(JPATH_ROOT, '', $this->folder);
		}
		return "/";
	}
	public function getUrlinputid() {
		return $this->urlinputid;
	}
	public function setUrlinputid($urlinputid) {
		$this->urlinputid = $urlinputid;
		return $this;
	}
	

}
