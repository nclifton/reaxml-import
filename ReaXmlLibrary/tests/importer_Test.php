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

class ReaxmlImporter_Test extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Guzzle\Http\Client
     */
    private $mailcatcher;


    /**
     * @beforeClass
     */
    public static function unlinklog()
    {
        foreach (glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_log' . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
    }

    /**
     * @before
     */
    public function setup()
    {
        $this->cleanDirectories();

        $this->mailcatcher = new \Guzzle\Http\Client('http://127.0.0.1:'.$GLOBALS['MAILCATCHER_HTTP_PORT']);

        // clean emails between tests
        $this->cleanMessages();

    }

    private function cleanMessages()
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
    public function assertEmailNotSent($description = '')
    {
        $this->assertEmpty($this->getMessages(), $description);
    }

    /**
     * @test
     */
    public function sends_mail_nofiles()
    {
        sleep(3);

        // arrange
        $configuration = new ReaxmlConfiguration ();
        $configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
        $configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
        $configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
        $configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
        $configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
        $importer = new ReaxmlImporter();
        $importer->setConfiguration($configuration);
        $dbo = $this->getMock('ReaxmlEzrDbo');
        $importer->setDbo($dbo);
        $params = $this->getMock('\Joomla\Registry\Registry');
        $params->expects(
            $this->exactly(8))->method('get')
            ->withConsecutive(
                array($this->equalTo('send_success'), $this->equalTo(true)),
                array($this->equalTo('done_mail_to')),
                array($this->equalTo('send_nofiles'), $this->equalTo(false)),
                array($this->equalTo('mail_from_address'), $this->equalTo('website@reaxmltest')),
                array($this->equalTo('mail_from_name'), $this->equalTo('REAXML TEST SITE')),
                array($this->equalTo('subject'), $this->equalTo('REAXML Import Notification: {status}')),
                array($this->equalTo('log_dir'), $this->equalTo(JPATH_ROOT . '/log')),
                array($this->equalTo('done_mail_to')))
            ->will($this->onConsecutiveCalls(
                true
                , 'done@reaxmlimporter.test, done@reaxmlimporter.test'
                , true
                , 'from@reaxmlimporter.test'
                , 'From REAXML Importer'
                , 'REAXML Import Test Notification: {status}'
                , __DIR__ . '/test_log'
                , 'done@reaxmlimporter.test, done@reaxmlimporter.test'
            ));
        $importer->setParams($params);

        // act
        $importer->import();


        // assert
        $this->assertEmailIsSent();
        $email = $this->getLastMessage();
        $this->assertEmailSubjectEquals('REAXML Import Test Notification: SUCCESS', $email ,"subject");
        $this->assertEmailTextContains(
            "REAXML Import completed with status SUCCESS\n"
            ."\n"
            ."\tXML files imported: \t   0\n"
            ."\tXML files with errors: \t   0\n"
            ."\n"
            ."\tProperties: \t     0\n"
            ."\tInserted: \t\t     0\n"
            ."\tUpdated: \t\t     0"
            ,$email);
        $this->assertEmailHasAttachments(1,$email,"has 1 attachment");

    }

    /**
     * @test
     * @depends sends_mail_nofiles
     */
    public function import_fromazipfile()
    {
        // Arrange
        copy(__DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'Sample.zip', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'Sample.zip');

        $configuration = new ReaxmlConfiguration ();
        $configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
        $configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
        $configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
        $configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
        $configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
        $importer = new ReaxmlImporter ();
        $importer->setConfiguration($configuration);
        $params = $this->getMock('\Joomla\Registry\Registry');
        $params->expects(
            $this->exactly(7))->method('get')
            ->withConsecutive(
                array($this->equalTo('send_success'), $this->equalTo(true)),
                array($this->equalTo('done_mail_to')),
                array($this->equalTo('mail_from_address'), $this->equalTo('website@reaxmltest')),
                array($this->equalTo('mail_from_name'), $this->equalTo('REAXML TEST SITE')),
                array($this->equalTo('subject'), $this->equalTo('REAXML Import Notification: {status}')),
                array($this->equalTo('log_dir'), $this->equalTo(JPATH_ROOT . '/log')),
                array($this->equalTo('done_mail_to')))
            ->will($this->onConsecutiveCalls(
                true
                , 'done1@reaxmlimporter.test, done2@reaxmlimporter.test'
                , 'reaxml@reaxmlimporter.test'
                , "REAXML Importer"
                , 'REAXML Import Test Notification: {status}'
                , __DIR__ . '/test_log'
                , 'done1@reaxmlimporter.test, done2@reaxmlimporter.test'
            ));
        $importer->setParams($params);

        // Act
        $importer->import();

        // Assert

        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'input');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'work');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(6), 'done');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'error');
    }

    /**
     * @skip
     */
    public function import_updateprice()
    {
        // Arrange
        copy(__DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_updateprice_sample.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'residential_updateprice_sample.xml');

        $configuration = new ReaxmlConfiguration ();
        $configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
        $configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
        $configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
        $configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
        $configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
        $importer = new ReaxmlImporter();
        $importer->setConfiguration($configuration);
        $importer->setParams($this->getMockParams());

        // Act
        $importer->import();

        // Assert

        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'input');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'work');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(1), 'done');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'error');
    }

    /**
     * @skip
     */
    public function import_updatesold()
    {
        // Arrange
        copy(__DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'residential_updatelistingsold_sample.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'residential_updatelistingsold_sample.xml');

        $configuration = new ReaxmlConfiguration ();
        $configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
        $configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
        $configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
        $configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
        $configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
        $importer = new ReaxmlImporter();
        $importer->setConfiguration($configuration);
        $importer->setParams($this->getMockParams());

        // Act
        $importer->import();

        // Assert

        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'input');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'work');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(1), 'done');
        $this->assertThat(count(glob_recursive(__DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*')), $this->equalTo(0), 'error');
    }

    /**
     * @test
     * @depends sends_mail_nofiles
     */
    public function traverses_propertyList()
    {
        // Arrange
        copy(__DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'multipropertylist.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'multipropertylist.xml');
        $configuration = new ReaxmlConfiguration ();
        $configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
        $configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
        $configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
        $configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
        $configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
        $importer = new ReaxmlImporter();
        $importer->setConfiguration($configuration);
        $dbo = $this->getMock('ReaxmlEzrDbo');
        $dbo->expects($this->exactly(114))->method('exists')->willReturn(false);
        $importer->setDbo($dbo);
        $params = $this->getMock('\Joomla\Registry\Registry');
        $params->expects(
            $this->exactly(7))->method('get')
            ->withConsecutive(
                array($this->equalTo('send_success'), $this->equalTo(true)),
                array($this->equalTo('done_mail_to')),
                array($this->equalTo('mail_from_address'), $this->equalTo('website@reaxmltest')),
                array($this->equalTo('mail_from_name'), $this->equalTo('REAXML TEST SITE')),
                array($this->equalTo('subject'), $this->equalTo('REAXML Import Notification: {status}')),
                array($this->equalTo('log_dir'), $this->equalTo(JPATH_ROOT . '/log')),
                array($this->equalTo('done_mail_to')))
            ->will($this->onConsecutiveCalls(
                true
                , 'done1@reaxmlimporter.test, done2@reaxmlimporter.test'
                , 'reaxml@reaxmlimporter.test'
                , "REAXML Importer"
                , 'REAXML Import Test Notification: {status}'
                , __DIR__ . '/test_log'
                , 'done1@reaxmlimporter.test, done2@reaxmlimporter.test'
            ));
        $importer->setParams($params);
        // Act
        $importer->import();

        // Assert

    }


    /**
     * @test
     * @depends sends_mail_nofiles
     */
    public function doesnt_send_mail_nofiles()
    {
        // arrange
        $configuration = new ReaxmlConfiguration ();
        $configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
        $configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
        $configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
        $configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
        $configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
        $importer = new ReaxmlImporter();
        $importer->setConfiguration($configuration);
        $dbo = $this->getMock('ReaxmlEzrDbo');
        $importer->setDbo($dbo);

        $params = $this->getMock('\Joomla\Registry\Registry');
        $params->expects(
            $this->exactly(3))->method('get')
            ->withConsecutive(
                array($this->equalTo('send_success'), $this->equalTo(true)),
                array($this->equalTo('done_mail_to')),
                array($this->equalTo('send_nofiles'), $this->equalTo(false)))
            ->will($this->onConsecutiveCalls(
                true
                , 'done1@reaxmlimporter.test, done2@reaxmlimporter.test'
                , false
            ));
        $importer->setParams($params);
        // act
        $importer->import();


        // assert
        $this->assertEmailNotSent();

    }

    /**
     * @test
     * @depends sends_mail_nofiles
     */
    public function sends_mail_success()
    {
        // arrange
        copy(__DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'multipropertylist.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'multipropertylist.xml');
        $configuration = new ReaxmlConfiguration ();
        $configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
        $configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
        $configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
        $configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
        $configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
        $importer = new ReaxmlImporter();
        $importer->setConfiguration($configuration);
        $dbo = $this->getMock('ReaxmlEzrDbo');
        $dbo->expects($this->exactly(114))->method('exists')->willReturn(false);
        $importer->setDbo($dbo);
        $params = $this->getMock('\Joomla\Registry\Registry');
        $params->expects(
            $this->exactly(7))->method('get')
            ->withConsecutive(
                array($this->equalTo('send_success'), $this->equalTo(true)),
                array($this->equalTo('done_mail_to')),
                array($this->equalTo('mail_from_address'), $this->equalTo('website@reaxmltest')),
                array($this->equalTo('mail_from_name'), $this->equalTo('REAXML TEST SITE')),
                array($this->equalTo('subject'), $this->equalTo('REAXML Import Notification: {status}')),
                array($this->equalTo('log_dir'), $this->equalTo(JPATH_ROOT . '/log')),
                array($this->equalTo('done_mail_to')))
            ->will($this->onConsecutiveCalls(
                true
                , 'done1@reaxmlimporter.test, done2@reaxmlimporter.test'
                , 'reaxml@reaxmlimporter.test'
                , "REAXML Importer"
                , 'REAXML Import Test Notification: {status}'
                , __DIR__ . '/test_log'
                , 'done1@reaxmlimporter.test, done2@reaxmlimporter.test'
            ));
        $importer->setParams($params);


        // act
        $importer->import();


        // assert
        $this->assertEmailIsSent();
        $email = $this->getLastMessage();
        $this->assertEmailSubjectEquals('REAXML Import Test Notification: SUCCESS', $email ,"subject");
        $this->assertEmailTextContains(
            "REAXML Import completed with status SUCCESS\n"
            ."\n"
            ."\tXML files imported: \t   1\n"
            ."\tXML files with errors: \t   0\n"
            ."\n"
            ."\tProperties: \t   114\n"
            ."\tInserted: \t\t   114\n"
            ."\tUpdated: \t\t     0"
            ,$email);

    }

    /**
     * @test
     * @depends sends_mail_nofiles
     */
    public function doesnt_send_mail_success()
    {
        // arrange
        copy(__DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'multipropertylist.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'multipropertylist.xml');
        $configuration = new ReaxmlConfiguration ();
        $configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
        $configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
        $configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
        $configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
        $configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
        $importer = new ReaxmlImporter();
        $importer->setConfiguration($configuration);
        $dbo = $this->getMock('ReaxmlEzrDbo');
        $dbo->expects($this->exactly(114))->method('exists')->willReturn(false);
        $importer->setDbo($dbo);

        $params = $this->getMock('\Joomla\Registry\Registry');
        $params->expects(
            $this->exactly(1))->method('get')
            ->withConsecutive(
                array($this->equalTo('send_success'), $this->equalTo(true)))
            ->will($this->onConsecutiveCalls(
                false));
        $importer->setParams($params);


        // act
        $importer->import();


        // assert
        $this->assertEmailNotSent();

    }
    /**
     * @test
     * @depends sends_mail_nofiles
     */
    public function send_mail_failure()
    {
        // arrange
        copy(__DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'bad.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'bad.xml');
        $configuration = new ReaxmlConfiguration ();
        $configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
        $configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
        $configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
        $configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
        $configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
        $importer = new ReaxmlImporter();
        $importer->setConfiguration($configuration);
        $dbo = $this->getMock('ReaxmlEzrDbo');
        $importer->setDbo($dbo);
        $params = $this->getMock('\Joomla\Registry\Registry');
        $params->expects(
            $this->exactly(8))->method('get')
            ->withConsecutive(
                array($this->equalTo('done_mail_to')),
                array($this->equalTo('mail_from_address'), $this->equalTo('website@reaxmltest')),
                array($this->equalTo('mail_from_name'), $this->equalTo('REAXML TEST SITE')),
                array($this->equalTo('subject'), $this->equalTo('REAXML Import Notification: {status}')),
                array($this->equalTo('log_dir'), $this->equalTo(JPATH_ROOT . '/log')),
                array($this->equalTo('done_mail_to')),
                array($this->equalTo('error_mail_to')),
                array($this->equalTo('error_mail_to')))
            ->will($this->onConsecutiveCalls(
                'done@reaxmlimporter.test'
                , 'reaxml@reaxmlimporter.test'
                , 'REAXML Importer'
                , 'REAXML Import Test Notification: {status}'
                , __DIR__ . '/test_log'
                , 'done1@reaxmlimporter.test, done2@reaxmlimporter.test'
                , 'error1@reaxmlimporter.test, error2@reaxmlimporter.test'
                , 'error1@reaxmlimporter.test, error2@reaxmlimporter.test'
                ));

        $importer->setParams($params);

        // act
        $importer->import();


        // assert
        $this->assertEmailIsSent();
        $email = $this->getLastMessage();
        $this->assertEmailRecipientsContain('<done1@reaxmlimporter.test>',$email,'has receipient done1');
        $this->assertEmailRecipientsContain('<done2@reaxmlimporter.test>',$email,'has receipient done2');
        $this->assertEmailRecipientsContain('<error1@reaxmlimporter.test>',$email,'has receipient error1');
        $this->assertEmailRecipientsContain('<error2@reaxmlimporter.test>',$email,'has receipient error2');
        $this->assertEmailSenderEquals('<reaxml@reaxmlimporter.test>',$email,'sender');

        $this->assertEmailSubjectEquals('REAXML Import Test Notification: FAILURE', $email ,"subject");
        $this->assertEmailTextContains(
            "REAXML Import completed with status FAILURE\n"
            ."\n"
            ."\tXML files imported: \t   0\n"
            ."\tXML files with errors: \t   1\n"
            ."\n"
            ."\tProperties: \t     0\n"
            ."\tInserted: \t\t     0\n"
            ."\tUpdated: \t\t     0"
            ,$email);
    }

    /**
     * @test
     * @depends sends_mail_nofiles
     */
    public function sends_mail_error()
    {
        // arrange
        copy(__DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'multipropertylist.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'multipropertylist.xml');
        copy(__DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'bad.xml', __DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . 'bad.xml');
        $configuration = new ReaxmlConfiguration ();
        $configuration->log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_log';
        $configuration->input_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_input';
        $configuration->work_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_work';
        $configuration->done_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_done';
        $configuration->error_dir = __DIR__ . DIRECTORY_SEPARATOR . 'test_error';
        $importer = new ReaxmlImporter();
        $importer->setConfiguration($configuration);
        $dbo = $this->getMock('ReaxmlEzrDbo');
        $dbo->expects($this->exactly(114))->method('exists')->willReturn(false);
        $importer->setDbo($dbo);
        $params = $this->getMock('\Joomla\Registry\Registry');
        $params->expects(
            $this->exactly(8))->method('get')
            ->withConsecutive(
                array($this->equalTo('done_mail_to')),
                array($this->equalTo('mail_from_address'), $this->equalTo('website@reaxmltest')),
                array($this->equalTo('mail_from_name'), $this->equalTo('REAXML TEST SITE')),
                array($this->equalTo('subject'), $this->equalTo('REAXML Import Notification: {status}')),
                array($this->equalTo('log_dir'), $this->equalTo(JPATH_ROOT . '/log')),
                array($this->equalTo('done_mail_to')),
                array($this->equalTo('error_mail_to')),
                array($this->equalTo('error_mail_to')))
            ->will($this->onConsecutiveCalls(
                'done@reaxmlimporter.test'
                , 'reaxml@reaxmlimporter.test'
                , 'REAXML Importer'
                , 'REAXML Import Test Notification: {status}'
                , __DIR__ . '/test_log'
                , 'done1@reaxmlimporter.test, done2@reaxmlimporter.test'
                , 'error1@reaxmlimporter.test, error2@reaxmlimporter.test'
                , 'error1@reaxmlimporter.test, error2@reaxmlimporter.test'
            ));

        $importer->setParams($params);

        // act
        $importer->import();


        // assert
        $this->assertEmailIsSent();
        $email = $this->getLastMessage();
        $this->assertEmailSubjectEquals('REAXML Import Test Notification: ERROR', $email ,"subject");
        $this->assertEmailTextContains(
            "REAXML Import completed with status ERROR\n"
            ."\n"
            ."\tXML files imported: \t   1\n"
            ."\tXML files with errors: \t   1\n"
            ."\n"
            ."\tProperties: \t   114\n"
            ."\tInserted: \t\t   114\n"
            ."\tUpdated: \t\t     0"
            ,$email);
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
    public function adminLogin() {
        $this->url('http://'.$GLOBALS ['SITE_HOST_NAME'].'/administrator');
        $usernameInput = $this->byId('mod-login-username');
        $usernameInput->value('admin');
        $this->assertEquals('admin', $usernameInput->value());
        $passwordInput = $this->byId('mod-login-password');
        $passwordInput->value("admin\r");
        $pageTitle = $this->byCssSelector('h1.page-title');
        $this->assertRegExp('/Control Panel/', $pageTitle->text());
    }

    /**
     * @after
     */
    public function teardown()
    {
    }

    private function recursiveUnlink($pattern)
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

    public function cleanDirectories()
    {
        $this->recursiveUnlink(__DIR__ . DIRECTORY_SEPARATOR . 'test_done' . DIRECTORY_SEPARATOR . '*');
        $this->recursiveUnlink(__DIR__ . DIRECTORY_SEPARATOR . 'test_work' . DIRECTORY_SEPARATOR . '*');
        $this->recursiveUnlink(__DIR__ . DIRECTORY_SEPARATOR . 'test_input' . DIRECTORY_SEPARATOR . '*');
        $this->recursiveUnlink(__DIR__ . DIRECTORY_SEPARATOR . 'test_error' . DIRECTORY_SEPARATOR . '*');
        $this->recursiveUnlink(__DIR__ . DIRECTORY_SEPARATOR . 'test_log' . DIRECTORY_SEPARATOR . '*');
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockParams()
    {
        $params = $this->getMock('\Joomla\Registry\Registry');
        $params->expects(
            $this->exactly(7))->method('get')
            ->withConsecutive(
                array($this->equalTo('send_success'), $this->equalTo(true)),
                array($this->equalTo('done_mail_to')),
                array($this->equalTo('send_nofiles'), $this->equalTo(false)),
                array($this->equalTo('mail_from_address'), $this->equalTo('website@reaxmltest')),
                array($this->equalTo('mail_from_name'), $this->equalTo('REAXML TEST SITE')),
                array($this->equalTo('subject'), $this->equalTo('REAXML Import Notification: {status}')),
                array($this->equalTo('log_dir'), $this->equalTo(JPATH_ROOT . '/log')),
                array($this->equalTo('done_mail_to')))
            ->will($this->onConsecutiveCalls(
                true
                , 'done@reaxmlimporter.test, done@reaxmlimporter.test'
                , true
                , 'done@reaxmlimporter.test'
                , 'from@reaxmlimporter.test'
                , 'REAXML Import Test Notification: {status}'
                , __DIR__ . '/test_log'
                , 'done@reaxmlimporter.test, done@reaxmlimporter.test'
            ));
        return $params;
    }


}