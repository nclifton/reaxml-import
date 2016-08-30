<?php

use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class Install_update_Test extends reaxml_selenium_TestCase
{

	/**
	 * @before
	 */
	public function before(){
        $this->restoreJoomla ();
        $this->createDirectories();
        $this->installExtensionUnderTest();
        $this->linkExtensionUnderTest();
	}
	/**
	 * @after
	 */
	public static function after(){

	}
	
	
	
	/**
	 * @skip
	 */
	public function update_information_available() {
        $this->adminLogin ();
		$link = $this->byLinkText('Extensions');
		$link->click();
		$link = $this->byLinkText('Extension Manager');
		$link->click();
		$pageTitle = $this->byCssSelector('h1.page-title');
		$this->assertRegExp('/Extension Manager: Install/', $pageTitle->text());
		$link = $this->byLinkText('Update Sites');
		$link->click();
		sleep(2);
		$updateSiteListing = $this->byXpath('//table[@class="table table-striped"]/tbody/tr[6]/td[3]');
		$this->assertRegExp('/REAXML Package/', $updateSiteListing->text());
		
	}

}
?>