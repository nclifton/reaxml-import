<?php
class SeleniumTestListener extends PHPUnit_Framework_BaseTestListener{
	
	protected $filepattern = 'screenshot_%s_%s.png';
	
	function __construct($filepattern=null){
		if (!empty($filepattern)) {
			$this->filepattern = $filepattern;
		} else {
			$this->filepattern = realpath( __DIR__.'/../test-results').'/'.$this->filepattern;
		}
	}
	
	protected function saveScreenshot($test){
		sleep(2);
		$filedata = $test->currentScreenshot();
		$file= sprintf($this->filepattern,$test->getName(),time());

		file_put_contents($file, $filedata);

		print("Screen shot saved to ".$file."\n");
	}
	
	public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
	{
		if (is_a($test,'PHPUnit_Extensions_Selenium2TestCase')){
			$this->saveScreenshot($test);
		}
	}
	
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time){
    	if (is_a($test,'PHPUnit_Extensions_Selenium2TestCase')){
			$this->saveScreenshot($test);
		}
    }
	
	
}