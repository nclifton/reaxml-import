<?php
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;
class configuration_Test extends reaxml_selenium_TestCase {
	
	/**
	 * @before
	 */
	public function setUp() {
		parent::restoreJoomla ();
		parent::setup ();
	}
	/**
	 * @after
	 */
	public static function after() {
		parent::restoreJoomla ();
	}
	
	/**
	 * @test
	 */
	public function can_do_folder_configuration() {
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
		$link = $this->byLinkText ( 'Components' );
		$link->click ();
		$link = $this->byLinkText ( 'REAXML Import' );
		$link->click ();
		$button = $this->byXpath ( '//button[contains(.,"Options")]' );
		$button->click ();
		sleep(1); //wait for document ready scripts to complete
		$this->assertThat ( $this->byCssSelector ( 'h1.page-title' )->text (), $this->matchesRegularExpression ( '/REAXML Import Component Configuration Options/' ) );
		$this->assertThat ( $this->byCssSelector ( '#configTabs li.active a' )->text (), $this->matchesRegularExpression ( '/Folders/' ) );
		$this->assertThat ( $this->byId ( 'folders' )->displayed (), $this->isTrue () );
		$this->assertThat ( $this->byCssSelector ( '#folders p.tab-description' )->text (), $this->matchesRegularExpression ( '/Specify the folders used by the REAXML Import component./' ) );
		$this->assertFieldLabelText ( 'input_dir', 'Absolute directory specification for the directory where the importer will find REAXML files containing property information to be used to update the site\'s property database.', 'Input directory' );
		$this->assertFieldLabelText ( 'input_url', 'The URL for the input directory on the web server.', 'Input URL' );
		$this->assertFieldLabelText ( 'work_dir', 'Absolute directory specification for the directory where the importer will move the REAXML files while it is processing them.', 'Work directory' );
		$this->assertFieldLabelText ( 'work_url', 'The URL for the work directory on the web server.', 'Work URL' );
		$this->assertFieldLabelText ( 'done_dir', 'Absolute directory specification for the directory where the importer will move the REAXML files when it has completed processing them.', 'Done directory' );
		$this->assertFieldLabelText ( 'done_url', 'The URL for the done directory on the web server.', 'Done URL' );
		$this->assertFieldLabelText ( 'log_dir', 'Absolute directory specification for the directory where the importer will create files containing information about how the processing of the REAXML files went.', 'Log directory' );
		$this->assertFieldLabelText ( 'log_url', 'The URL for the log directory on the web server.', 'Log URL' );
		$this->assertFieldLabelText ( 'error_dir', 'Absolute directory specification for the directory where the importer will leave REAXML files it could not process for some reason \(See the logs\).', 'Error directory' );
		$this->assertFieldLabelText ( 'error_url', 'The URL for the error directory on the web server.', 'Error URL' );
		$this->assertThat ( $this->byCssSelector ( 'button.folder-browser-button' ), $this->anything () );
		$this->assertThat ( $this->byXpath ( '//head/script[@type="text/javascript" and @src="/administrator/components/com_reaxmlimport/assets/js/fields.js"]' ), $this->anything () );
		$this->assertThat ( $this->byCssSelector ( 'body div#reaxmlimport-config-panel iframe#reaxmlimport-config-frame[src=""]' ), $this->anything () );
		$uidialog = $this->byId ( 'reaxmlimport-config-panel' )->byXPath('parent::*');
		$this->assertThat ( $uidialog->css ( 'position' ), $this->equalTo ( 'absolute' ) );
		$this->assertThat ( $uidialog->displayed (), $this->isFalse () );
		
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
		
		$this->assertThat ($this->byId('jform_input_dir')->value(), $this->equalTo(realpath(__DIR__.'/../htdocs').'/ftp/input'));
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
		
		$this->assertThat ($this->byId('jform_work_dir')->value(), $this->equalTo(realpath(__DIR__.'/../htdocs').'/ftp/work'));
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
		
		$this->assertThat ($this->byId('jform_done_dir')->value(), $this->equalTo(realpath(__DIR__.'/../htdocs').'/ftp/done'));
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
		
		$this->assertThat ($this->byId('jform_log_dir')->value(), $this->equalTo(realpath(__DIR__.'/../htdocs').'/ftp/log'));
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
		
		$this->assertThat ($this->byId('jform_error_dir')->value(), $this->equalTo(realpath(__DIR__.'/../htdocs').'/ftp/error'));
		$this->assertThat ($this->byId('jform_error_url')->value(), $this->equalTo('/ftp/error'));
		
		$this->byXpath ( '//button[contains(.,"Save & Close")]' )->click ();
		
		$dataSet = new PHPUnit_Extensions_Database_DataSet_QueryDataSet ( $this->getConnection () );
		$dataSet->addTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions', 'SELECT params FROM ' . $GLOBALS ['DB_TBLPREFIX'] . 'extensions WHERE element=\'com_reaxmlimport\'' );
		
		$table = $this->filterdataset ( $dataSet )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );
		$expectedDataset = $this->loadXMLDataSet ( __DIR__ . '/../files/expected-extensions-afteroptionssave.xml' );
		$expectedTable = $this->filterdataset ( $expectedDataset )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );
        $this->assertThat($table->getRowCount() , $this->equalTo($expectedTable->getRowCount()));
        for ($i=0; $i < $table->getRowCount(); $i++) {
            $cols = $table->getTableMetaData()->getColumns();
            foreach ($cols as $name) {
                $value = $table->getValue($i, $name);
                $expected = $expectedTable->getValue($i, $name);
                $this->assertThat($value, $this->equalTo($expected), $name);
            }
        }
	}
	private function assertFieldLabelText($field, $description, $label) {
		$this->assertThat ( $this->getTitle($field), $this->matchesRegularExpression (
            '/<strong>' . $label . '<\/strong><br \/>' . $description . '/') );
		$this->assertThat ( $this->byId ( 'jform_' . $field . '-lbl' )->text (), $this->matchesRegularExpression ( '/' . $label . '/' ) );
	}
	private function getTitle($field){
		$title = $this->byId ( 'jform_' . $field . '-lbl' )->attribute ( 'data-original-title' );
		if ($title == null){
			return $this->byId ( 'jform_' . $field . '-lbl' )->attribute ( 'title' );
		}
		return $title;
	}
	private function filterdataset($dataset) {
		$filtereddataset = new PHPUnit_Extensions_Database_DataSet_DataSetFilter ( $dataset );
		$filtereddataset->addIncludeTables ( array (
				$GLOBALS ['DB_TBLPREFIX'] . 'extensions' 
		) );
		$filtereddataset->setIncludeColumnsForTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions', array (
				'params' 
		) );
		return $filtereddataset;
	}
	/**
	 * @test
	 */
	public function can_set_no_usemap(){
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
		$link = $this->byLinkText ( 'Components' );
		$link->click ();
		$link = $this->byLinkText ( 'REAXML Import' );
		$link->click ();
		$button = $this->byXpath ( '//button[contains(.,"Options")]' );
		$button->click ();
		sleep(1); //wait for document ready scripts to complete
		$this->byLinkText ( 'Map Data' )->click();
        sleep(2);
		$this->byCssSelector ( 'label[for="jform_usemap0"]' )->click();

		$this->byXpath ( '//button[contains(.,"Save & Close")]' )->click ();		
		
		$dataSet = new PHPUnit_Extensions_Database_DataSet_QueryDataSet ( $this->getConnection () );
		$dataSet->addTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions', 'SELECT params FROM ' . $GLOBALS ['DB_TBLPREFIX'] . 'extensions WHERE element=\'com_reaxmlimport\'' );
		
		$table = $this->filterdataset ( $dataSet )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );
		$expectedDataset = $this->loadXMLDataSet ( __DIR__ . '/../files/expected-extensions-afteroptionssave_nousemap.xml' );
		$expectedTable = $this->filterdataset ( $expectedDataset )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );
        $this->assertThat($table->getRowCount() , $this->equalTo($expectedTable->getRowCount()));
        for ($i=0; $i < $table->getRowCount(); $i++) {
            $cols = $table->getTableMetaData()->getColumns();
            foreach ($cols as $name) {
                $value = $table->getValue($i, $name);
                $expected = $expectedTable->getValue($i, $name);
                $this->assertThat($value, $this->equalTo($expected), $name);
            }
        }
	}
	/**
	 * @test
	 */
	public function can_set_usemap_ifnew(){
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
		$link = $this->byLinkText ( 'Components' );
		$link->click ();
		$link = $this->byLinkText ( 'REAXML Import' );
		$link->click ();
		$button = $this->byXpath ( '//button[contains(.,"Options")]' );
		$button->click ();
		sleep(1); //wait for document ready scripts to complete
		$this->byLinkText ( 'Map Data' )->click();
        sleep(2);
		$this->byCssSelector ( 'label[for="jform_usemap1"]' )->click();

		$this->byXpath ( '//button[contains(.,"Save & Close")]' )->click ();
		
		$dataSet = new PHPUnit_Extensions_Database_DataSet_QueryDataSet ( $this->getConnection () );
		$dataSet->addTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions', 'SELECT params FROM ' . $GLOBALS ['DB_TBLPREFIX'] . 'extensions WHERE element=\'com_reaxmlimport\'' );
		
		$table = $this->filterdataset ( $dataSet )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );
		$expectedDataset = $this->loadXMLDataSet ( __DIR__ . '/../files/expected-extensions-afteroptionssave-usemapifnew.xml' );
		$expectedTable = $this->filterdataset ( $expectedDataset )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );
        $this->assertThat($table->getRowCount() , $this->equalTo($expectedTable->getRowCount()));
        for ($i=0; $i < $table->getRowCount(); $i++) {
            $cols = $table->getTableMetaData()->getColumns();
            foreach ($cols as $name) {
                $value = $table->getValue($i, $name);
                $expected = $expectedTable->getValue($i, $name);
                $this->assertThat($value, $this->equalTo($expected), $name);
            }
        }
		
	}
	/**
	 * @test
	 */
	public function can_set_usemap_always(){
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
		$link = $this->byLinkText ( 'Components' );
		$link->click ();
		$link = $this->byLinkText ( 'REAXML Import' );
		$link->click ();
		$button = $this->byXpath ( '//button[contains(.,"Options")]' );
		$button->click ();
		sleep(1); //wait for document ready scripts to complete
		$this->byLinkText ( 'Map Data' )->click();
        sleep(2);
		$this->byCssSelector ( 'label[for="jform_usemap2"]' )->click();

		$this->byXpath ( '//button[contains(.,"Save & Close")]' )->click ();
		
		$dataSet = new PHPUnit_Extensions_Database_DataSet_QueryDataSet ( $this->getConnection () );
		$dataSet->addTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions', 'SELECT params FROM ' . $GLOBALS ['DB_TBLPREFIX'] . 'extensions WHERE element=\'com_reaxmlimport\'' );
		
		$table = $this->filterdataset ( $dataSet )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );
		$expectedDataset = $this->loadXMLDataSet ( __DIR__ . '/../files/expected-extensions-afteroptionssave-usemapalways.xml' );
		$expectedTable = $this->filterdataset ( $expectedDataset )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );
        $this->assertThat($table->getRowCount() , $this->equalTo($expectedTable->getRowCount()));
        for ($i=0; $i < $table->getRowCount(); $i++) {
            $cols = $table->getTableMetaData()->getColumns();
            foreach ($cols as $name) {
                $value = $table->getValue($i, $name);
                $expected = $expectedTable->getValue($i, $name);
                $this->assertThat($value, $this->equalTo($expected), $name);
            }
        }

	}

	/**
	 * @test
	 */
	public function can_set_mail_configuration(){
		$this->loadExtension();
		$link = $this->byLinkText ( 'Components' );
		$link->click ();
		$link = $this->byLinkText ( 'REAXML Import' );
		$link->click ();
        sleep(2);
		$button = $this->byXpath ( '//button[contains(.,"Options")]' );
		$button->click ();
		sleep(1); //wait for document ready scripts to complete
        $this->assertThat ( $this->byCssSelector ( '#configTabs li.active a' )->text (), $this->matchesRegularExpression ( '/Folders/' ) );
        $this->assertThat ( $this->byLinkText ( 'Notifications' )->displayed(), $this->isTrue ());
        $this->byLinkText ( 'Notifications' )->click();
        $this->assertThat ( $this->byId ( 'notifications' )->displayed () , $this->isTrue ());
		$this->assertThat ( $this->byCssSelector ( '#notifications p.tab-description' )->text (), $this->stringContains ( 'Specify how REAXML Import component will provide notifications.' ) );
 		$this->assertFieldLabelText ( 'send_success', 'Do you want REAXML Import to send notifications when an import run is successful\?', 'Send on success' );
        $this->assertFieldLabelText ( 'send_nofiles', 'Do you want REAXML Import to send notifications when an import ran but no XML files were found in the input directory\?', 'Send when no files' );
        $this->assertFieldLabelText ( 'done_mail_to', 'Where do you want to the notifications sent to\? Enter the email address here.', 'Send to Address' );
        $this->assertFieldLabelText ( 'error_mail_to', 'When an error is detected you can have an additional recipient for the notification\? Enter the addtional email address here.', 'Send errors to Address' );
        $this->assertFieldLabelText ( 'mail_from_address', 'Where do you want to the notifications to come from\? Default is the global configuration Mail Settings from email. Add the email address here.', 'Send from Address' );
        $this->assertFieldLabelText ( 'mail_from_name', 'Where do you want to the notifications to come from\? Default is the global configuration Mail Settings from email. Add the name here.', 'Send from Name' );
        $this->assertFieldLabelText ( 'subject', 'What do you want in the subject text of the email\? Keep the {status} place marker in there to get the status in the subject.', 'Subject text' );

        $this->assertThat($this->byId ( 'jform_send_success0' )->attribute('checked'), $this->equalTo('true'));
        $this->assertThat($this->byId ( 'jform_send_success1' )->attribute('checked'), $this->isNull());
        $this->byCssSelector ( 'label.btn[for="jform_send_success1"]' )->click();
        $this->assertThat($this->byId ( 'jform_send_success0' )->attribute('checked'), $this->isNull());
        $this->assertThat($this->byId ( 'jform_send_success1' )->attribute('checked'), $this->equalTo('true'));
        $this->byCssSelector ( 'label.btn[for="jform_send_success0"]' )->click();
        $this->assertThat($this->byId ( 'jform_send_success0' )->attribute('checked'), $this->equalTo('true'));
        $this->assertThat($this->byId ( 'jform_send_success1' )->attribute('checked'), $this->isNull());

        $this->assertThat($this->byId ( 'jform_send_nofiles0' )->attribute('checked'), $this->isNull());
        $this->assertThat($this->byId ( 'jform_send_nofiles1' )->attribute('checked'), $this->equalTo('true'));
        $this->byCssSelector ( 'label.btn[for="jform_send_nofiles0"]' )->click();
        $this->assertThat($this->byId ( 'jform_send_nofiles0' )->attribute('checked'), $this->equalTo('true'));
        $this->assertThat($this->byId ( 'jform_send_nofiles1' )->attribute('checked'), $this->isNull());
        $this->byCssSelector ( 'label.btn[for="jform_send_nofiles1"]' )->click();
        $this->assertThat($this->byId ( 'jform_send_nofiles0' )->attribute('checked'), $this->isNull());
        $this->assertThat($this->byId ( 'jform_send_nofiles1' )->attribute('checked'), $this->equalTo('true'));

        $this->byId('jform_done_mail_to')->clear();
        $this->byId('jform_done_mail_to')->value('done@reaxml.test');
        $this->byId('jform_error_mail_to')->clear();
        $this->byId('jform_error_mail_to')->value('error@reaxml.test');
        $this->byId('jform_mail_from_address')->clear();
        $this->byId('jform_mail_from_address')->value('reaxml.importer@reaxml.test');
        $this->byId('jform_mail_from_name')->clear();
        $this->byId('jform_mail_from_name')->value('REAXML Import Test');
        $this->assertThat($this->byId('jform_subject')->value(), $this->equalTo('REAXML Import Notification: {status}'));
        $this->byId('jform_subject')->clear();
        $this->byId('jform_subject')->value('REAXML Import {status} Notification');

        $this->byXpath ( '//button[contains(.,"Save & Close")]' )->click ();

        $dataSet = new PHPUnit_Extensions_Database_DataSet_QueryDataSet ( $this->getConnection () );
        $dataSet->addTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions', 'SELECT params FROM ' . $GLOBALS ['DB_TBLPREFIX'] . 'extensions WHERE element=\'com_reaxmlimport\'' );

        $table = $this->filterdataset ( $dataSet )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );
        $expectedDataset = $this->loadXMLDataSet ( __DIR__ . '/../files/expected-extensions-afteroptionssave-notifications.xml' );
        $expectedTable = $this->filterdataset ( $expectedDataset )->getTable ( $GLOBALS ['DB_TBLPREFIX'] . 'extensions' );

        $this->assertThat($table->getRowCount() , $this->equalTo($expectedTable->getRowCount()));
        for ($i=0; $i < $table->getRowCount(); $i++) {
            $cols = $table->getTableMetaData()->getColumns();
            foreach ($cols as $name) {
                $value = $table->getValue($i, $name);
                $expected = $expectedTable->getValue($i, $name);
                $this->assertThat($value, $this->equalTo($expected), $name);
            }
        }
    }

}
?>