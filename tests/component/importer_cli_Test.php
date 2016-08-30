<?php

/**
 * @copyright    Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 **/
jimport('joomla.filesystem.file');

if (!function_exists('glob_recursive')) {
    // Does not support flag GLOB_BRACE
    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
        }
        return $files;
    }
}

class ReaxmlImporterCli_Test extends Reaxml_Tests_DatabaseTestCase
{

    use ReaxmlLanguageTrait;

    /**
     * @var \GuzzleHttp\Client
     */
    private $mailcatcher;


    /**
     * @beforeClass
     */
    public static function classSetup()
    {
        parent::setupSite();
        $dt = new DateTime ();
        $logfile = 'REAXMLImport-' . $dt->format('YmdHis') . '.log';
        JLog::addLogger(array(
            'text_file' => $logfile,
            'text_file_path' => __DIR__ . '/../test_log',
            'text_file_no_php' => true,
            'text_entry_format' => '{DATE} {TIME} {PRIORITY} {MESSAGE}'
        ), JLog::ALL, array(
            REAXML_LOG_CATEGORY
        ));

    }

    /**
     * @before
     */
    public function before()
    {

        self::cleanDirectories();
        parent::setUp();

        $this->mailcatcher = new \GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:' . $GLOBALS['MAILCATCHER_HTTP_PORT']]);

        // clean emails between tests
        $this->cleanMessages();

    }

    /**
     * @since version
     * @after
     */
    public function after()
    {
        self::cleanDirectories();
    }

    /**
     * @afterClass
     */
    public static function afterClass()
    {
        parent::setupSite();
    }

    private static function recursiveUnlink($pattern)
    {
        foreach (glob_recursive($pattern) as $file) {
            if (!is_dir($file)) {
                unlink($file);
            }
        }
        foreach (glob_recursive($pattern) as $file) {
            try {
                rmdir($file);
            } catch (Exception $e) {
            }
        }
    }

    public static function cleanDirectories()
    {
        foreach (['done', 'work', 'input', 'error', 'log'] as $dir) {
            $dirName = __DIR__ . '/test_' . $dir;
            if (!file_exists($dirName))
                mkdir($dirName);
            self::recursiveUnlink($dirName . '/*');
        }

        self::recursiveUnlink(JPATH_BASE . '/images/ezrealty/properties/*');

    }

    /**
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(__DIR__ . '/files/ezrealty-seed.xml');
    }

    /**
     * @test
     */
    public function bad_xml_file_handling()
    {
        // Arrange
        copy(__DIR__ . '/files/bad.xml', __DIR__ . '/test_input/bad.xml');

        $params = JComponentHelper::getParams('com_reaxmlimport');

        $params->set('input_dir', __DIR__ . '/test_input');
        $params->set('work_dir', __DIR__ . '/test_work');
        $params->set('done_dir', __DIR__ . '/test_done');
        $params->set('error_dir', __DIR__ . '/test_error');
        $params->set('log_dir', __DIR__ . '/test_log');
        $params->set('send_success', 1);
        $params->set('send_nofiles', 0);
        $params->set('done_mail_to', 'done@reaxml.test');
        $params->set('error_mail_to', 'error@reaxml.test');
        $params->set('mail_from_address', 'reaxml.importer@reaxml.test');
        $params->set('mail_from_name', 'REAXML Import Test');
        $params->set('subject', 'REAXML Import {status} Notification');
        $params->set('usemap', 2);
        $params->set('default_country', 'Australia');

        // Act
        include REAXML_ADMIN_COMPONENTS . '/com_reaxmlimport/cli/reaxml-importer.php';

        // connect to mailcatcher
        self::assertEmailIsSent("email sent?");
    }

    /**
     * @test
     */
    public function import_commercial_pullman()
    {
        // Arrange
        copy(__DIR__ . '/files/pullman_smalltest.xml', __DIR__ . '/test_input/pullman_smalltest.xml');

        $params = JComponentHelper::getParams('com_reaxmlimport');

        $params->set('input_dir', __DIR__ . '/test_input');
        $params->set('work_dir', __DIR__ . '/test_work');
        $params->set('done_dir', __DIR__ . '/test_done');
        $params->set('error_dir', __DIR__ . '/test_error');
        $params->set('log_dir', __DIR__ . '/test_log');
        $params->set('send_success', 1);
        $params->set('send_nofiles', 0);
        $params->set('done_mail_to', 'done@reaxml.test');
        $params->set('error_mail_to', 'error@reaxml.test');
        $params->set('mail_from_address', 'reaxml.importer@reaxml.test');
        $params->set('mail_from_name', 'REAXML Import Test');
        $params->set('subject', 'REAXML Import {status} Notification');
        $params->set('usemap', 2);
        $params->set('default_country', 'Australia');

        // Act
        include REAXML_ADMIN_COMPONENTS . '/com_reaxmlimport/cli/reaxml-importer.php';

        // Assert

        $this->compareRelevantTables(__DIR__ . '/files/expected_ezrealty_after_small_commercial_pullman_insert_test.xml');

        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'files in input');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'files in work');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(1), 'files in done');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'files in error');

        // connect to mailcatcher
        self::assertEmailIsSent("email sent?");

    }

    /**
     * @test
     */
    public function import_comaus()
    {
        // Arrange
        copy(__DIR__ . '/files/commaus_2016-08-12_01-24-07_210.xml', __DIR__ . '/test_input/commaus_2016-08-12_01-24-07_210.xml');

        $params = JComponentHelper::getParams('com_reaxmlimport');

        $params->set('input_dir', __DIR__ . '/test_input');
        $params->set('work_dir', __DIR__ . '/test_work');
        $params->set('done_dir', __DIR__ . '/test_done');
        $params->set('error_dir', __DIR__ . '/test_error');
        $params->set('log_dir', __DIR__ . '/test_log');
        $params->set('send_success', 1);
        $params->set('send_nofiles', 0);
        $params->set('done_mail_to', 'done@reaxml.test');
        $params->set('error_mail_to', 'error@reaxml.test');
        $params->set('mail_from_address', 'reaxml.importer@reaxml.test');
        $params->set('mail_from_name', 'REAXML Import Test');
        $params->set('subject', 'REAXML Import {status} Notification');
        $params->set('usemap', 2);
        $params->set('default_country', 'Australia');

        // Act
        include REAXML_ADMIN_COMPONENTS . '/com_reaxmlimport/cli/reaxml-importer.php';

        // Assert
        $this->compareRelevantTables(__DIR__ . '/files/expected_ezrealty_after_comaus.xml');

        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'files in input');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'files in work');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(1), 'files in done');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'files in error');

        // connect to mailcatcher
        self::assertEmailIsSent("email sent?");

        // TODO: what about the image files?




    }


    private function filterDataset($dataSet)
    {
        $filterDataSet = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ($dataSet);
        $filterDataSet->setExcludeColumnsForTable($GLOBALS ['DB_TBLPREFIX'] . 'ezrealty', array(
            'hits',
            'lastupdate',
            'expdate'
        ));
        $filterDataSet->setIncludeColumnsForTable($GLOBALS ['DB_TBLPREFIX'] . 'extensions', array(
            'params'
        ));

        $filterDataSet->setExcludeColumnsForTable($GLOBALS ['DB_TBLPREFIX'] . 'ezrealty_images', array(
            'fname'
        ));

        return $filterDataSet;
    }

    public function cleanMessages()
    {
        $this->mailcatcher->delete('/messages');
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
        $jsonResponse = $this->mailcatcher->get('/messages');
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

    public function dumpMessageHtmlBody($email, $wheredir)
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.html");
        $body = (string)$response->getBody();
        file_put_contents("{$wheredir}/email{$email->id}.html", $body);
    }

    public function dumpMessagePlainBody($email, $wheredir)
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.plain");
        $body = (string)$response->getBody();
        file_put_contents("{$wheredir}/email{$email->id}.txt", $body);
    }

    public function dumpAllMessagesHtmlBody($wheredir)
    {
        foreach ($this->getMessages() as $email) {
            $this->dumpMessageHtmlBody($email, $wheredir);
        }
    }

    public function dumpAllMessagesPlainBody($wheredir)
    {
        foreach ($this->getMessages() as $email) {
            $this->dumpMessagePlainBody($email, $wheredir);
        }
    }

    public function assertEmailHtmlContains($needle, $email, $description = '')
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.html");
        $body = (string)$response->getBody();
        $this->assertContains($needle, $body, $description);
    }

    public function assertEmailHtmlContainsRegex($pattern, $email, $description = '')
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.html");
        $body = (string)$response->getBody();
        $this->assertRegExp($pattern, $body, $description);
    }

    public function assertEmailTextContains($needle, $email, $description = '')
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.plain");
        $this->assertContains($needle, (string)$response->getBody(), $description);
    }

    public function assertEmailSenderEquals($expected, $email, $description = '')
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.json");
        $email = json_decode($response->getBody());
        $this->assertEquals($expected, $email->sender, $description);
    }

    public function assertEmailHasAttachments($expected, $email, $description = '')
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.json");
        $email = json_decode($response->getBody());
        $this->assertEquals($expected, count($email->attachments), $description);
    }

    public function assertEmailRecipientsContain($needle, $email, $description = '')
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.json");
        $email = json_decode($response->getBody());
        $this->assertContains($needle, $email->recipients, $description);
    }

    public function assertEmailRecipientsNotContain($needle, $email, $description = '')
    {
        $response = $this->mailcatcher->get("/messages/{$email->id}.json");
        $email = json_decode($response->getBody());
        $this->assertNotContains($needle, $email->recipients, $description);
    }

    /**
     * @param $xmlFile
     *
     *
     * @since version
     */
    private function compareRelevantTables($xmlFile)
    {
        $dataSet = $this->getConnection()->createDataSet();
        $filteredDataSet = $this->filterDataset($dataSet);
        $propertiesTableName = $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty';
        $filteredPropertiesTable = $filteredDataSet->getTable($propertiesTableName);
        $imagesTableName = $GLOBALS ['DB_TBLPREFIX'] . 'ezrealty_images';
        $filteredImagesTable = $filteredDataSet->getTable($imagesTableName);

        $expectedDataSet = $this->createMySQLXMLDataSet($xmlFile);
        $expectedFilteredDataSet = $this->filterDataset($expectedDataSet);
        $expectedFilteredPropertiesTable = $expectedFilteredDataSet->getTable($propertiesTableName);
        $expectedFilteredImagesTable = $expectedFilteredDataSet->getTable($imagesTableName);

        $this->assertThat($filteredPropertiesTable->getRowCount(), $this->equalTo($expectedFilteredPropertiesTable->getRowCount()), 'property count');
        $this->assertThat($filteredImagesTable->getRowCount(), $this->equalTo($expectedFilteredImagesTable->getRowCount()), 'image count');

        $expectedPropertiesTable = $expectedDataSet->getTable($propertiesTableName);
        $propertiesTable = $dataSet->getTable($propertiesTableName);
        for ($i = 0; $i < $expectedPropertiesTable->getRowCount(); $i++) {
            $expectedRow = $expectedPropertiesTable->getRow($i);
            $row = $propertiesTable->getRow($i);
            $columns = $filteredPropertiesTable->getTableMetaData()->getColumns();
            foreach ($columns as $col) {
                $value = $row[$col];
                $expectedValue = $expectedRow[$col];
                $this->assertEquals($expectedValue, $value, "row $i " . $col);
            }
        }

        $this->assertTablesEqual($expectedFilteredPropertiesTable, $filteredPropertiesTable);
        $this->assertTablesEqual($expectedFilteredImagesTable, $filteredImagesTable);
    }
}