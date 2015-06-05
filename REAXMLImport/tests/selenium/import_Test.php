<?php

jimport ( 'joomla.filesystem.file' );

if (! function_exists ( 'glob_recursive' )) {
	// Does not support flag GLOB_BRACE
	function glob_recursive($pattern, $flags = 0) {
		$files = glob ( $pattern, $flags );
		foreach ( glob ( dirname ( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir ) {
			$files = array_merge ( $files, glob_recursive ( $dir . '/' . basename ( $pattern ), $flags ) );
		}
		return $files;
	}
}
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class Import_Test extends reaxml_selenium_TestCase {
	
	/**
	 * @before
	 */
	public function setUp() {
		parent::restoreJoomla ();
		parent::setup ();
		$this->cleanDirectories ();
	}
	
	public function cleanDirectories() {
		$this->recursiveUnlink ( '/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/input/*' );
		$this->recursiveUnlink ( '/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/work/*' );
		$this->recursiveUnlink ( '/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/done/*' );
		$this->recursiveUnlink ( '/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/log/*' );
		$this->recursiveUnlink ( '/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/error/*' );
	}
	private function recursiveUnlink($pattern) {
		foreach ( glob_recursive ( $pattern ) as $file ) {
			if (! is_dir ( $file )) {
				unlink ( $file );
			}
		}
		foreach ( glob_recursive ( $pattern ) as $file ) {
			try {
				rmdir ( $file );
			} catch ( Exception $e ) {
			}
		}
	}	
	
	
	/**
	 * @after
	 */
	public static function after() {
//		parent::restoreJoomla ();
	}
	
	/**
	 * @test
	 */
	public function configure_and_import() {
		
		copy ( __DIR__.'/../files/import_test.xml', '/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/input/import_Test.xml' );
		
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
		$link = $this->byLinkText ( 'Components' );
		$link->click ();
		$link = $this->byLinkText ( 'REAXML Import' );
		$link->click ();
		$button = $this->byXpath ( '//button[contains(.,"Options")]' );
		$button->click ();
		sleep(1); //wait for document ready scripts to complete

		
		$folderbrowserButtons = $this->elements($this->using('css selector')->value('button.folder-browser-button'));	
		
		// click the first folder browser button
		$folderbrowserButtons[0]->click();
		$this->frame('reaxmlimport-config-frame');
		$subfolderlinks = $this->elements($this->using('css selector')->value('ul.folders li a'));
		$subfolderlinks[5]->click();
		$subfolderlinks = $this->elements($this->using('css selector')->value('ul.folders li a'));
		$subfolderlinks[2]->click();
		$this->byCssSelector('button.btn-success')->click();
		$this->frame(null);
		
		$this->assertThat ($this->byId('jform_input_dir')->value(), $this->equalTo('/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/input'));
		$this->assertThat ($this->byId('jform_input_url')->value(), $this->equalTo('/ftp/input'));	

		// click the second folder browser button
		$folderbrowserButtons[1]->click();
		$this->frame('reaxmlimport-config-frame');
		$subfolderlinks = $this->elements($this->using('css selector')->value('ul.folders li a'));
		$subfolderlinks[5]->click();
		$subfolderlinks = $this->elements($this->using('css selector')->value('ul.folders li a'));
		$subfolderlinks[4]->click();
		$this->byCssSelector('button.btn-success')->click();
		$this->frame(null);
		
		$this->assertThat ($this->byId('jform_work_dir')->value(), $this->equalTo('/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/work'));
		$this->assertThat ($this->byId('jform_work_url')->value(), $this->equalTo('/ftp/work'));
		
		// click the third folder browser button
		$folderbrowserButtons[2]->click();
		$this->frame('reaxmlimport-config-frame');
		$subfolderlinks = $this->elements($this->using('css selector')->value('ul.folders li a'));
		$subfolderlinks[5]->click();
		$subfolderlinks = $this->elements($this->using('css selector')->value('ul.folders li a'));
		$subfolderlinks[0]->click();
		$this->byCssSelector('button.btn-success')->click();
		$this->frame(null);
		
		$this->assertThat ($this->byId('jform_done_dir')->value(), $this->equalTo('/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/done'));
		$this->assertThat ($this->byId('jform_done_url')->value(), $this->equalTo('/ftp/done'));
		
		// click the fourth folder browser button
		$folderbrowserButtons[3]->click();
		$this->frame('reaxmlimport-config-frame');
		$subfolderlinks = $this->elements($this->using('css selector')->value('ul.folders li a'));
		$subfolderlinks[5]->click();
		$subfolderlinks = $this->elements($this->using('css selector')->value('ul.folders li a'));
		$subfolderlinks[3]->click();
		$this->byCssSelector('button.btn-success')->click();
		$this->frame(null);
		
		$this->assertThat ($this->byId('jform_log_dir')->value(), $this->equalTo('/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/log'));
		$this->assertThat ($this->byId('jform_log_url')->value(), $this->equalTo('/ftp/log'));
		
		// click the fifth folder browser button
		$folderbrowserButtons[4]->click();
		$this->frame('reaxmlimport-config-frame');
		$subfolderlinks = $this->elements($this->using('css selector')->value('ul.folders li a'));
		$subfolderlinks[5]->click();
		$subfolderlinks = $this->elements($this->using('css selector')->value('ul.folders li a'));
		$subfolderlinks[1]->click();
		$this->byCssSelector('button.btn-success')->click();
		$this->frame(null);
		
		$this->assertThat ($this->byId('jform_error_dir')->value(), $this->equalTo('/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/error'));
		$this->assertThat ($this->byId('jform_error_url')->value(), $this->equalTo('/ftp/error'));
		
		$this->byLinkText ( 'Map Data' )->click();;
		$this->byId ( 'jform_usemap2' )->click();
		
		$this->byXpath ( '//button[contains(.,"Save & Close")]' )->click ();
		
		$this->byXpath ( '//button[contains(.,"Import")]' )->click ();		
		
		sleep(5);		
		//click refresh
		$this->byXpath ( '//button[contains(.,"Refresh")]' )->click ();
		
		// Assert
		// has started ...
		// loop to refresh and check the log
		$i = 0;
		$endpattern = '% INFO Ending%is';
		
		$addressPatterns = array(
			'%Looking up Latitude and Longitude for address 44 Peachtree Road, PENRITH, NSW 2750, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address Unit D, 21 Power Street, ST MARYS, NSW 2760, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address 23/541 High Street, PENRITH, NSW 2750, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address Suite 7/308 High Street, PENRITH, NSW 2750, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address 10/37-39 York Road, PENRITH, NSW 2750, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address 606 High Street, PENRITH, NSW 2750, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address Shop 2/245 Queen Street, ST MARYS, NSW 2760, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address Suite 7/2-6 Castlereagh Street, PENRITH, NSW 2750, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address 3/40 Phillip Street, ST MARYS, NSW 2760, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address Shop 3A/40 Phillip St, ST MARYS, NSW 2760, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address No 60 Argyle Street, WINDSOR, NSW 2756, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address 1A/1 Lavin Cres, WERRINGTON, NSW 2747, AUSTRALIA%is'=>false,
			'%Looking up Latitude and Longitude for address 111 Macquarie Road, SPRINGWOOD, NSW 2777, AUSTRALIA%is'=>false
		);
		
		while (100>$i++){
			sleep(5);		
			$this->assertThat ($this->byId('inputFileList')->text(), $this->equalTo(''));
			$logtext = $this->byId('logContent')->text();
			foreach ($addressPatterns as $pattern => &$found) {
				if (!$found && preg_match($pattern, $logtext)) {
					$found = true;
				}
			}
			if (preg_match($endpattern, $logtext)) {
				break;
			}
			$this->byXpath ( '//button[contains(.,"Refresh")]' )->click ();
			$i++;
		}
		foreach ($addressPatterns as $pattern => $found) {
			$this->assertThat($found, $this->isTrue() , 'message pattern match '.$pattern);
		}	

		$logtext = $this->byId('logContent')->text();
		$this->assertThat ($logtext, $this->matchesRegularExpression($pattern));
		
		// let's check we got the agent details right
		$link = $this->byLinkText ( 'Components' );
		$link->click ();
		$link = $this->byLinkText ( 'EZ Realty' );
		$link->click ();
		$link = $this->byLinkText ( 'Agents' );
		$link->click ();
		$this->assertThat ( $this->byXpath ( '//table[@id="articleList"]/tbody/tr[1]/td[4]/a ' )->text (), $this->equalTo ( 'PETER PAZIOS' ) );
		
		
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$prefix = $GLOBALS['DB_TBLPREFIX'];
		$table1 = $dataSet->getTable ( $prefix.'ezrealty' );
		$table2 = $dataSet->getTable ( $prefix.'ezportal' );
		
		$expectedDataset = $this->filterDataset ( $this->loadXMLDataset ( __DIR__ . '/../files/expected_ezrealty_after_import_test.xml' ) );		
		$expectedTable1 = $expectedDataset->getTable (  $prefix.'ezrealty' );
		$expectedTable2 = $expectedDataset->getTable (  $prefix.'ezportal' );
		
		$this->assertTablesEqual ( $expectedTable1, $table1 );
		$this->assertTablesEqual ( $expectedTable2, $table2 );
		
	}
	private function filterdataset($dataset) {
		$filtereddataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ( $dataset );
/* 		$filtereddataset->addIncludeTables ( array (
				$GLOBALS ['DB_TBLPREFIX'] . 'extensions' 
		) );
		$filtereddataset->setIncludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions', array (
				'params' 
		) ); */
		return $filtereddataset;
	}

}
?>