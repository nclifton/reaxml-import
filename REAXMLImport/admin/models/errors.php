<?php
class ReaXmlImportModelsErrors extends JModelBase {
	public function getErrorFiles() {
		$params = JComponentHelper::getParams ( 'com_reaxmlimport' );
		$dir = $params->get ( 'error_dir' );
		$files = array ();
		if (isset ( $dir ) && JFolder::exists ( $dir )) {
			$files = JFolder::files ( $dir );
		}
		return $files;
	}
	public function getErrorRelUrl() {
		$params = JComponentHelper::getParams ( 'com_reaxmlimport' );
		return $params->get ( 'error_rel_url' ) . '';
	}
}