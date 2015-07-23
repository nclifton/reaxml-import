<?php

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *
 */
include_once __DIR__ . '/../admin/models/folderbrowser.php';

class FolderbrowserModel_Test extends PHPUnit_Framework_TestCase {
	/**
	 * @before
	 */
	public function setUp() {
	}

	
	/**
	 * @test
	 */
	public function suppliesCorrectUrl(){
		//Arrange
		$model = new ReaXmlImportModelsFolderbrowser();
		$model->setFolder(realpath(__DIR__.'/htdocs/ftp'));
		$model->setInputid('jform_input_dir');
		$model->setCurrenturi('http://reaxml.dev/administrator/index.php');
		
		// Act
		$url = $model->getUrl();
		
		//Assert
		$this->assertThat($url, $this->equalTo('/ftp'));
	}
	/**
	 * @test
	 */
	public function suppliesRootUrlWhenFolderNotUnderSitePath(){
		//Arrange
		$model = new ReaXmlImportModelsFolderbrowser();
		$model->setFolder(realpath(__DIR__.'/../'));
		$model->setInputid('jform_input_dir');
		$model->setCurrenturi('http://reaxml.dev/administrator/index.php');
	
		// Act
		$url = $model->getUrl();
	
		//Assert
		$this->assertThat($url, $this->equalTo('/'));
	}
	/**
	 * @test
	 */
	public function suppliesRootUrlWhenFolderIsSitePath(){
		//Arrange
		$model = new ReaXmlImportModelsFolderbrowser();
		$model->setFolder(realpath(__DIR__.'/htdocs'));
		$model->setInputid('jform_input_dir');
		$model->setCurrenturi('http://reaxml.dev/administrator/index.php');
	
		// Act
		$url = $model->getUrl();
	
		//Assert
		$this->assertThat($url, $this->equalTo('/'));
	}	
	/**
	 * @test
	 */
	public function suppliesUsableFolderList(){
		//Arrange
		$model = new ReaXmlImportModelsFolderbrowser();
		$model->setFolder(realpath(__DIR__.'/htdocs/ftp'));
		$model->setInputid('jform_input_dir');
		$model->setUrlinputid('jform_input_url');
		$model->setCurrenturi('http://reaxml.dev/administrator/index.php');

		$path = realpath(__DIR__.'/htdocs/ftp');
		$folders = explode('/',$path);

		//Act
		$list = $model->getFolderList();

		//Assert

		$this->assertThat ( count($list), $this->equalTo(count($folders)));

		$tpath = '';
		for($i=0;$i<count($list)-1;$i++) {
			$tpath .= $i==1?$folders[$i]:'/'.$folders[$i];
			$this->assertThat($list['item'.($i+1)], $this->equalTo(($i==0?'/':$folders[$i])));
			$this->assertThat($model->getSelectFolderUrl('item'.($i+1)) , $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=' . urlencode($tpath)));
		}


	}

	/**
	 * @test
	 */
	public function switches_to_root_folder_if_supplied_folder_does_not_exist(){
		//Arrange
		$model = new ReaXmlImportModelsFolderbrowser();
		$model->setFolder('input');
		$model->setInputid('jform_input_dir');
		$model->setCurrenturi('http://reaxml.dev/administrator/index.php');

		$this->assertThat($model->getFolder(), $this->equalTo(realpath(__DIR__.'/htdocs')));
	}
	/**
	 * @test
	 */
	public function suppliesUsableSubFolderList(){
	
		//Arrange
		$model = new ReaXmlImportModelsFolderbrowser();
		$model->setFolder(realpath(__DIR__.'/htdocs/ftp'));
		$model->setInputid('jform_input_dir');
		$model->setUrlinputid('jform_input_url');
		$model->setCurrenturi('http://reaxml.dev/administrator/index.php');

        $path = realpath(__DIR__.'/htdocs/ftp');
        $folders = explode('/',$path);

        //Act
		$list = $model->getSubFolderList();
		
		//Assert
		$this->assertThat(count($list), $this->equalTo(5));
        $root = urlencode(realpath(__DIR__.'/htdocs'));
		$this->assertThat($list['item1'], $this->equalTo('done'));
		$this->assertThat($model->getSelectSubFolderUrl('item1') , $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$root.'%2Fftp%2Fdone'));
		$this->assertThat($list['item2'], $this->equalTo('error'));
		$this->assertThat($model->getSelectSubFolderUrl('item2') , $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$root.'%2Fftp%2Ferror'));
		$this->assertThat($list['item3'], $this->equalTo('input'));
		$this->assertThat($model->getSelectSubFolderUrl('item3') , $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$root.'%2Fftp%2Finput'));
		$this->assertThat($list['item4'], $this->equalTo('log'));
		$this->assertThat($model->getSelectSubFolderUrl('item4') , $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$root.'%2Fftp%2Flog'));
		$this->assertThat($list['item5'], $this->equalTo('work'));
		$this->assertThat($model->getSelectSubFolderUrl('item5') , $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$root.'%2Fftp%2Fwork'));
		
		$this->assertThat($model->getSelectSubFolderUrl('itemX') , $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$root.'%2Fftp'));
		
	
	}
}

?>