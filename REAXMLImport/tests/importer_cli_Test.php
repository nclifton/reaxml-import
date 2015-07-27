<?php

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/
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
class ReaxmlImporterCli_Test extends Reaxml_Tests_DatabaseTestCase {
	
	/**
	 * @var \Guzzle\Http\Client
	 */
	private $mailcatcher;
	
	
	/**
	 * @beforeclass
	 */
	public function classsetup() {
		$dt = new DateTime ();
		$logfile = 'REAXMLImport-' . $dt->format ( 'YmdHis' ) . '.log';
		JLog::addLogger ( array (
				'text_file' => $logfile,
				'text_file_path' => __DIR__ . '/../test_log',
				'text_file_no_php' => true,
				'text_entry_format' => '{DATE} {TIME} {PRIORITY} {MESSAGE}' 
		), JLog::ALL, array (
				REAXML_LOG_CATEGORY 
		) );


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
	 * @before
	 */
	public function setUp() {
		parent::setUp ();
		$this->cleanDirectories ();

		$this->mailcatcher = new \Guzzle\Http\Client('http://127.0.0.1:'.$GLOBALS['MAILCATCHER_HTTP_PORT']);
		
		// clean emails between tests
		$this->cleanMessages();

        $lang = JFactory::getLanguage();
        $lang->load('lib_reaxml', realpath(__DIR__.'/../../ReaXmlLibrary'), 'en-GB', true);

    }
	public function cleanDirectories() {
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . '/test_done/*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . '/test_work/*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . '/test_input/*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . '/test_error/*' );
		$this->recursiveUnlink ( __DIR__ . DIRECTORY_SEPARATOR . '/test_log/*' );
	}

	/**
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	public function getDataSet() {
		return $this->createMySQLXMLDataSet ( __DIR__ . '/files/ezrealty-seed.xml' );
	}
	
	/**
	 * @test
	 */
	public function bad_xml_file_handling(){
		// Arrange
		copy ( __DIR__ . '/files/bad.xml', __DIR__ . '/test_input/bad.xml' );

        // Act
		include __DIR__ . '/../admin/cli/reaxml-importer.php';
		
		// connect to mailcatcher
		self::assertEmailIsSent("email sent?");
	}
	
	/**
	 * @test
	 */
	public function import_commercial_pullman() {
		// Arrange
		copy ( __DIR__ . '/files/pullman_201410280550052876573.xml', __DIR__ . '/test_input/pullman_201410280550052876573.xml' );

        // Act
		include __DIR__ . '/../admin/cli/reaxml-importer.php';
		
		// Assert
		$dataSet = $this->filterDataset ( $this->getConnection ()->createDataSet () );
		$table1 = $dataSet->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty' );
		$table2 = $dataSet->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty_images' );
		
		$expectedDataset = $this->filterDataset ( $this->createMySQLXMLDataSet ( __DIR__ . '/files/expected_ezrealty_after_commercial_pullman_insert_test.xml' ) );
		$expectedTable1 = $expectedDataset->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty' );
		$expectedTable2 = $expectedDataset->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty_images' );
		
		$this->assertTablesEqual ( $expectedTable1, $table1 );
		$this->assertTablesEqual ( $expectedTable2, $table2 );
		
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in input' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in work' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 1 ), 'files in done' );
		$this->assertThat ( count ( glob_recursive ( __DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*' ) ), $this->equalTo ( 0 ), 'files in error' );

		// connect to mailcatcher
		self::assertEmailIsSent("email sent?");

	}
	private function filterDataset($dataSet) {
		$filterDataSet = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ( $dataSet );
		$filterDataSet->setExcludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty', array (
				'hits',
				'flpl1',
				'flpl2',
				'declat',
				'declong',
				'propdesc',
				'smalldesc'
		) );
		$filterDataSet->setIncludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions', array (
				'params' 
		) );
		
		$filterDataSet->setExcludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty_images', array (
				'fname' 
		) );
		
		return $filterDataSet;
	}
	public function cleanMessages()
	{
		$this->mailcatcher->delete('/messages')->send();
	}
	
	public function getLastMessage()
	{
		$messages = $this->getMessages();
		if (empty($messages)) {
			$this->fail("No messages received");
		}
		// messages are in descending order
		return reset($messages);
	}
	
	public function getMessages()
	{
		$jsonResponse = $this->mailcatcher->get('/messages')->send();
		return json_decode($jsonResponse->getBody());
	}
	public function assertEmailIsSent($description = '')
	{
		$this->assertNotEmpty($this->getMessages(), $description);
	}
	
	public function assertEmailSubjectContains($needle, $email, $description = '')
	{
		$this->assertContains($needle, $email->subject, $description);
	}
	
	public function assertEmailSubjectEquals($expected, $email, $description = '')
	{
		$this->assertContains($expected, $email->subject, $description);
	}
	public function dumpMessageHtmlBody($email,$wheredir){
		$response = $this->mailcatcher->get("/messages/{$email->id}.html")->send();
		$body = (string)$response->getBody();
		file_put_contents("{$wheredir}/email{$email->id}.html",$body) ;
	}
	public function dumpMessagePlainBody($email,$wheredir){
		$response = $this->mailcatcher->get("/messages/{$email->id}.plain")->send();
		$body = (string)$response->getBody();
		file_put_contents("{$wheredir}/email{$email->id}.txt",$body) ;
	}
	public function dumpAllMessagesHtmlBody($wheredir){
		foreach ($this->getMessages() as $email) {
			$this->dumpMessageHtmlBody($email,$wheredir);
		}
	}
	public function dumpAllMessagesPlainBody($wheredir){
		foreach ($this->getMessages() as $email) {
			$this->dumpMessagePlainBody($email,$wheredir);
		}
	}
	public function assertEmailHtmlContains($needle, $email, $description = '')
	{
		$response = $this->mailcatcher->get("/messages/{$email->id}.html")->send();
		$body = (string)$response->getBody();
		$this->assertContains($needle, $body, $description);
	}
	public function assertEmailHtmlContainsRegex($pattern, $email, $description = '')
	{
		$response = $this->mailcatcher->get("/messages/{$email->id}.html")->send();
		$body = (string)$response->getBody();
		$this->assertRegExp($pattern, $body, $description);
	}
	
	public function assertEmailTextContains($needle, $email, $description = '')
	{
		$response = $this->mailcatcher->get("/messages/{$email->id}.plain")->send();
		$this->assertContains($needle, (string)$response->getBody(), $description);
	}
	
	public function assertEmailSenderEquals($expected, $email, $description = '')
	{
		$response = $this->mailcatcher->get("/messages/{$email->id}.json")->send();
		$email = json_decode($response->getBody());
		$this->assertEquals($expected, $email->sender, $description);
	}
	public function assertEmailHasAttachments($expected, $email, $description = '')
	{
		$response = $this->mailcatcher->get("/messages/{$email->id}.json")->send();
		$email = json_decode($response->getBody());
		$this->assertEquals($expected, count($email->attachments), $description);
	}
	
	public function assertEmailRecipientsContain($needle, $email, $description = '')
	{
		$response = $this->mailcatcher->get("/messages/{$email->id}.json")->send();
		$email = json_decode($response->getBody());
		$this->assertContains($needle, $email->recipients, $description);
	}
	public function assertEmailRecipientsNotContain($needle, $email, $description = '')
	{
		$response = $this->mailcatcher->get("/messages/{$email->id}.json")->send();
		$email = json_decode($response->getBody());
		$this->assertNotContains($needle, $email->recipients, $description);
	}
}