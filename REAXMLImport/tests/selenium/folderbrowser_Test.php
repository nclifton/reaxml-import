<?php
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;
class folderbrowser_Test extends reaxml_selenium_TestCase {
	
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
	public function displays_folders() {
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
		
		$this->url ( 'http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.urlencode ('/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"]' ), $this->anything () );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="option"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="option"]' )->attribute('value'), $this->equalTo ('com_reaxmlimport') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="view"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="view"]' )->attribute('value'), $this->equalTo ('folderbrowser') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="tmpl"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="tmpl"]' )->attribute('value'), $this->equalTo ('component') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="controller"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="controller"]' )->attribute('value'), $this->equalTo ('config') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="inputid"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="inputid"]' )->attribute('value'), $this->equalTo ('jform_input_dir') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="urlinputid"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="urlinputid"]' )->attribute('value'), $this->equalTo ('jform_input_url') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="url"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="url"]' )->attribute('value'), $this->equalTo ('/ftp') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('type'), $this->equalTo ('text') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('value'), $this->equalTo ('/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp') );
		$buttons = $this->elements($this->using('css selector')->value('form[name="adminForm"] button'));
		$this->assertThat ( count($buttons), $this->equalTo(2));
		$this->assertThat ( $buttons[0]->attribute('onclick'), $this->equalTo('document.form.adminForm.submit(); return false;' ));
		$this->assertThat ( $buttons[1]->attribute('onclick'), $this->equalTo('reaxml_folderbrowser_useThis(\'jform_input_dir\'); return false;' ));

		$items = $this->elements($this->using('css selector')->value('ul.breadcrumb li'));
		$this->assertThat ( count($items), $this->equalTo(8));
		$this->assertThat ( $items[0]->attribute('class') , $this->equalTo('item1'));
		$this->assertThat ( $items[0]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2F' ));
		$this->assertThat ( $items[0]->byTag('a')->text(), $this->matchesRegularExpression ( '%/%i' ) );
		$this->assertThat ( $items[1]->attribute('class') , $this->equalTo('item2'));
		$this->assertThat ( $items[1]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers' ));
		$this->assertThat ( $items[1]->byTag('a')->text(), $this->matchesRegularExpression ( '%Users%i' ) );
		$this->assertThat ( $items[2]->attribute('class') , $this->equalTo('item3'));
		$this->assertThat ( $items[2]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton' ));
		$this->assertThat ( $items[2]->byTag('a')->text(), $this->matchesRegularExpression ( '%nclifton%i' ) );
		$this->assertThat ( $items[3]->attribute('class') , $this->equalTo('item4'));
		$this->assertThat ( $items[3]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments' ));
		$this->assertThat ( $items[3]->byTag('a')->text(), $this->matchesRegularExpression ( '%Documents%i' ) );
		$this->assertThat ( $items[4]->attribute('class') , $this->equalTo('item5'));
		$this->assertThat ( $items[4]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP' ));
		$this->assertThat ( $items[4]->byTag('a')->text(), $this->matchesRegularExpression ( '%MAMP%i' ) );
		$this->assertThat ( $items[5]->attribute('class') , $this->equalTo('item6'));
		$this->assertThat ( $items[5]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs' ));
		$this->assertThat ( $items[5]->byTag('a')->text(), $this->matchesRegularExpression ( '%htdocs%i' ) );
		$this->assertThat ( $items[6]->attribute('class') , $this->equalTo('item7'));
		$this->assertThat ( $items[6]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml' ));
		$this->assertThat ( $items[6]->byTag('a')->text(), $this->matchesRegularExpression ( '%reaxml%i' ) );
		$this->assertThat ( $this->byCssSelector ( 'ul.breadcrumb li.item8.active' )->text(), $this->matchesRegularExpression ( '%ftp%i' ) );
		
		$items = $this->elements($this->using('css selector')->value('ul.folders li'));
		$this->assertThat ( count($items), $this->equalTo(5));
		$this->assertThat ( $items[0]->attribute('class') , $this->matchesRegularExpression('/\bitem1\b/'));
		$this->assertThat ( $items[0]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml%2Fftp%2Fdone' ));
		$this->assertThat ( $items[0]->byTag('a')->text(), $this->matchesRegularExpression ( '/done/' ) );
		$this->assertThat ( $items[1]->attribute('class') , $this->matchesRegularExpression('/\bitem2\b/'));
		$this->assertThat ( $items[1]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml%2Fftp%2Ferror' ));
		$this->assertThat ( $items[1]->byTag('a')->text(), $this->matchesRegularExpression ( '/error/' ) );
		$this->assertThat ( $items[2]->attribute('class') , $this->matchesRegularExpression('/\bitem3\b/'));
		$this->assertThat ( $items[2]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml%2Fftp%2Finput' ));
		$this->assertThat ( $items[2]->byTag('a')->text(), $this->matchesRegularExpression ( '/input/' ) );
		$this->assertThat ( $items[3]->attribute('class') , $this->matchesRegularExpression('/\bitem4\b/'));
		$this->assertThat ( $items[3]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml%2Fftp%2Flog' ));
		$this->assertThat ( $items[3]->byTag('a')->text(), $this->matchesRegularExpression ( '/log/' ) );
		$this->assertThat ( $items[4]->attribute('class') , $this->matchesRegularExpression('/\bitem5\b/'));
		$this->assertThat ( $items[4]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml%2Fftp%2Fwork' ));
		$this->assertThat ( $items[4]->byTag('a')->text(), $this->matchesRegularExpression ( '/work/' ) );
		
	}
	
	/**
	 * @test
	 */
	public function can_use_current_folder() {
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
		$link = $this->byLinkText ( 'Components' );
		$link->click ();
		$link = $this->byLinkText ( 'REAXML Import' );
		$link->click ();
		$button = $this->byXpath ( '//button[contains(.,"Options")]' );
		$button->click ();
		
		$uidialog = $this->byId ( 'reaxmlimport-config-panel' )->byXPath('parent::*');
		$this->assertThat ( $uidialog->displayed (), $this->isFalse () );

		$this->assertThat($this->byId('jform_input_dir')->attribute('value'), $this->equalTo('input'));
		
		// click the folder browser button
		$this->byCssSelector ( 'button.folder-browser-button' )->click ();	
		
		// iframe should now be visible
		$uidialog = $this->byId ( 'reaxmlimport-config-panel' )->byXPath('parent::*');
		$this->assertThat ( $uidialog->displayed (), $this->isTrue () );
		
		$this->frame('reaxmlimport-config-frame');
		// the default input directory should be the 'base' directory
		$this->assertThat($this->byId('folder')->attribute('value'), $this->equalTo('/Users/nclifton/Documents/MAMP/htdocs/reaxml'));
		
		$this->byCssSelector('form[name="adminForm"] button.btn-success')->click();
		
		$this->frame(null);
		// the iframe should now not be visible again
		$uidialog = $this->byId ( 'reaxmlimport-config-panel' )->byXPath('parent::*');
		$this->assertThat ( $uidialog->displayed (), $this->isFalse () );

		$this->assertThat($this->byId('jform_input_dir')->attribute('value'), $this->equalTo('/Users/nclifton/Documents/MAMP/htdocs/reaxml'));
		
		
	}
	
	/**
	 * @test
	 */
	public function uses_root_folder_if_supplied_folder_does_not_exist() {
		
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
		
		$this->url ( 'http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.urlencode ('input') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('value'), $this->equalTo ('/Users/nclifton/Documents/MAMP/htdocs/reaxml') );
		
		
	}
	
	/**
	 * @test
	 */
	public function can_select_another_folder() {

		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
		
		$this->url ( 'http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.urlencode ('/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp') );
		$this->byCssSelector('li.item7 a')->click();
		
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="option"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="option"]' )->attribute('value'), $this->equalTo ('com_reaxmlimport') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="view"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="view"]' )->attribute('value'), $this->equalTo ('folderbrowser') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="tmpl"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="tmpl"]' )->attribute('value'), $this->equalTo ('component') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="controller"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="controller"]' )->attribute('value'), $this->equalTo ('config') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="inputid"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="inputid"]' )->attribute('value'), $this->equalTo ('jform_input_dir') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="urlinputid"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="urlinputid"]' )->attribute('value'), $this->equalTo ('jform_input_url') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="url"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="url"]' )->attribute('value'), $this->equalTo ('/') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('type'), $this->equalTo ('text') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('value'), $this->equalTo ('/Users/nclifton/Documents/MAMP/htdocs/reaxml') );
		$items = $this->elements($this->using('css selector')->value('ul.breadcrumb li'));
		$this->assertThat ( count($items), $this->equalTo(7));
		$this->assertThat ( $items[0]->attribute('class') , $this->equalTo('item1'));
		$this->assertThat ( $items[0]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2F' ));
		$this->assertThat ( $items[0]->byTag('a')->text(), $this->matchesRegularExpression ( '%/%i' ) );
		$this->assertThat ( $items[1]->attribute('class') , $this->equalTo('item2'));
		$this->assertThat ( $items[1]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers' ));
		$this->assertThat ( $items[1]->byTag('a')->text(), $this->matchesRegularExpression ( '%Users%i' ) );
		$this->assertThat ( $items[2]->attribute('class') , $this->equalTo('item3'));
		$this->assertThat ( $items[2]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton' ));
		$this->assertThat ( $items[2]->byTag('a')->text(), $this->matchesRegularExpression ( '%nclifton%i' ) );
		$this->assertThat ( $items[3]->attribute('class') , $this->equalTo('item4'));
		$this->assertThat ( $items[3]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments' ));
		$this->assertThat ( $items[3]->byTag('a')->text(), $this->matchesRegularExpression ( '%Documents%i' ) );
		$this->assertThat ( $items[4]->attribute('class') , $this->equalTo('item5'));
		$this->assertThat ( $items[4]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP' ));
		$this->assertThat ( $items[4]->byTag('a')->text(), $this->matchesRegularExpression ( '%MAMP%i' ) );
		$this->assertThat ( $items[5]->attribute('class') , $this->equalTo('item6'));
		$this->assertThat ( $items[5]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs' ));
		$this->assertThat ( $items[5]->byTag('a')->text(), $this->matchesRegularExpression ( '%htdocs%i' ) );
		$this->assertThat ( $this->byCssSelector ( 'ul.breadcrumb li.item7.active' )->text(), $this->matchesRegularExpression ( '%reaxml%i' ) );
		
	
	}

	/**
	 * @test
	 */
	public function can_go_to_another_folder() {
	
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
	
		$this->url ( 'http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.urlencode ('/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp') );
		$this->byCssSelector('#folder')->value('/input');
		$this->byCssSelector('button.btn-primary')->click();
	
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="option"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="option"]' )->attribute('value'), $this->equalTo ('com_reaxmlimport') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="view"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="view"]' )->attribute('value'), $this->equalTo ('folderbrowser') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="tmpl"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="tmpl"]' )->attribute('value'), $this->equalTo ('component') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="controller"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="controller"]' )->attribute('value'), $this->equalTo ('config') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="inputid"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="inputid"]' )->attribute('value'), $this->equalTo ('jform_input_dir') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="urlinputid"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="urlinputid"]' )->attribute('value'), $this->equalTo ('jform_input_url') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="url"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="url"]' )->attribute('value'), $this->equalTo ('/ftp/input') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('type'), $this->equalTo ('text') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('value'), $this->equalTo ('/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp/input') );
		$items = $this->elements($this->using('css selector')->value('ul.breadcrumb li'));
		$this->assertThat ( count($items), $this->equalTo(9));
		$this->assertThat ( $items[0]->attribute('class') , $this->equalTo('item1'));
		$this->assertThat ( $items[0]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2F' ));
		$this->assertThat ( $items[0]->byTag('a')->text(), $this->matchesRegularExpression ( '%/%i' ) );
		$this->assertThat ( $items[1]->attribute('class') , $this->equalTo('item2'));
		$this->assertThat ( $items[1]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers' ));
		$this->assertThat ( $items[1]->byTag('a')->text(), $this->matchesRegularExpression ( '%Users%i' ) );
		$this->assertThat ( $items[2]->attribute('class') , $this->equalTo('item3'));
		$this->assertThat ( $items[2]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton' ));
		$this->assertThat ( $items[2]->byTag('a')->text(), $this->matchesRegularExpression ( '%nclifton%i' ) );
		$this->assertThat ( $items[3]->attribute('class') , $this->equalTo('item4'));
		$this->assertThat ( $items[3]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments' ));
		$this->assertThat ( $items[3]->byTag('a')->text(), $this->matchesRegularExpression ( '%Documents%i' ) );
		$this->assertThat ( $items[4]->attribute('class') , $this->equalTo('item5'));
		$this->assertThat ( $items[4]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP' ));
		$this->assertThat ( $items[4]->byTag('a')->text(), $this->matchesRegularExpression ( '%MAMP%i' ) );
		$this->assertThat ( $items[5]->attribute('class') , $this->equalTo('item6'));
		$this->assertThat ( $items[5]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs' ));
		$this->assertThat ( $items[5]->byTag('a')->text(), $this->matchesRegularExpression ( '%htdocs%i' ) );
		$this->assertThat ( $items[6]->attribute('class') , $this->equalTo('item7'));
		$this->assertThat ( $items[6]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml' ));
		$this->assertThat ( $items[6]->byTag('a')->text(), $this->matchesRegularExpression ( '%reaxml%i' ) );
		$this->assertThat ( $items[7]->attribute('class') , $this->equalTo('item8'));
		$this->assertThat ( $items[7]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml%2Fftp' ));
		$this->assertThat ( $items[7]->byTag('a')->text(), $this->matchesRegularExpression ( '%ftp%i' ) );
		$this->assertThat ( $this->byCssSelector ( 'ul.breadcrumb li.item9.active' )->text(), $this->matchesRegularExpression ( '%input%i' ) );
	
	
	}
	
	/**
	 * @test
	 */
	public function can_drill_down_to_a_subfolder() {
	
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
	
		$this->url ( 'http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.urlencode ('/Users/nclifton/Documents/MAMP/htdocs/reaxml') );
		$this->byCssSelector('ul.folders li.item6 a')->click();
	
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="option"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="option"]' )->attribute('value'), $this->equalTo ('com_reaxmlimport') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="view"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="view"]' )->attribute('value'), $this->equalTo ('folderbrowser') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="tmpl"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="tmpl"]' )->attribute('value'), $this->equalTo ('component') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="controller"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="controller"]' )->attribute('value'), $this->equalTo ('config') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="inputid"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="inputid"]' )->attribute('value'), $this->equalTo ('jform_input_dir') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="urlinputid"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="urlinputid"]' )->attribute('value'), $this->equalTo ('jform_input_url') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="url"]' )->attribute('type'), $this->equalTo ('hidden') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="url"]' )->attribute('value'), $this->equalTo ('/ftp') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('type'), $this->equalTo ('text') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('value'), $this->equalTo ('/Users/nclifton/Documents/MAMP/htdocs/reaxml/ftp') );

		$items = $this->elements($this->using('css selector')->value('ul.breadcrumb li'));
		$this->assertThat ( count($items), $this->equalTo(8));
		$this->assertThat ( $items[0]->attribute('class') , $this->matchesRegularExpression('/\bitem1\b/'));
		$this->assertThat ( $items[0]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2F' ));
		$this->assertThat ( $items[0]->byTag('a')->text(), $this->matchesRegularExpression ( '%/%i' ) );
		$this->assertThat ( $items[1]->attribute('class') , $this->matchesRegularExpression('/\bitem2\b/'));
		$this->assertThat ( $items[1]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers' ));
		$this->assertThat ( $items[1]->byTag('a')->text(), $this->matchesRegularExpression ( '%Users%i' ) );
		$this->assertThat ( $items[2]->attribute('class') , $this->matchesRegularExpression('/\bitem3\b/'));
		$this->assertThat ( $items[2]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton' ));
		$this->assertThat ( $items[2]->byTag('a')->text(), $this->matchesRegularExpression ( '%nclifton%i' ) );
		$this->assertThat ( $items[3]->attribute('class') , $this->matchesRegularExpression('/\bitem4\b/'));
		$this->assertThat ( $items[3]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments' ));
		$this->assertThat ( $items[3]->byTag('a')->text(), $this->matchesRegularExpression ( '%Documents%i' ) );
		$this->assertThat ( $items[4]->attribute('class') , $this->matchesRegularExpression('/\bitem5\b/'));
		$this->assertThat ( $items[4]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP' ));
		$this->assertThat ( $items[4]->byTag('a')->text(), $this->matchesRegularExpression ( '%MAMP%i' ) );
		$this->assertThat ( $items[5]->attribute('class') , $this->matchesRegularExpression('/\bitem6\b/'));
		$this->assertThat ( $items[5]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs' ));
		$this->assertThat ( $items[5]->byTag('a')->text(), $this->matchesRegularExpression ( '%htdocs%i' ) );
		$this->assertThat ( $items[6]->attribute('class') , $this->matchesRegularExpression('/\bitem7\b/'));
		$this->assertThat ( $items[6]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml' ));
		$this->assertThat ( $items[6]->byTag('a')->text(), $this->matchesRegularExpression ( '%reaxml%i' ) );
		$this->assertThat ( $this->byCssSelector ( 'ul.breadcrumb li.item8.active' )->text(), $this->matchesRegularExpression ( '%ftp%i' ) );
	
		$items = $this->elements($this->using('css selector')->value('ul.folders li'));
		$this->assertThat ( count($items), $this->equalTo(5));
		$this->assertThat ( $items[0]->attribute('class') , $this->matchesRegularExpression('/\bitem1\b/'));
		$this->assertThat ( $items[0]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml%2Fftp%2Fdone' ));
		$this->assertThat ( $items[0]->byTag('a')->text(), $this->matchesRegularExpression ( '/done/' ) );
		$this->assertThat ( $items[1]->attribute('class') , $this->matchesRegularExpression('/\bitem2\b/'));
		$this->assertThat ( $items[1]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml%2Fftp%2Ferror' ));
		$this->assertThat ( $items[1]->byTag('a')->text(), $this->matchesRegularExpression ( '/error/' ) );
		$this->assertThat ( $items[2]->attribute('class') , $this->matchesRegularExpression('/\bitem3\b/'));
		$this->assertThat ( $items[2]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml%2Fftp%2Finput' ));
		$this->assertThat ( $items[2]->byTag('a')->text(), $this->matchesRegularExpression ( '/input/' ) );
		$this->assertThat ( $items[3]->attribute('class') , $this->matchesRegularExpression('/\bitem4\b/'));
		$this->assertThat ( $items[3]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml%2Fftp%2Flog' ));
		$this->assertThat ( $items[3]->byTag('a')->text(), $this->matchesRegularExpression ( '/log/' ) );
		$this->assertThat ( $items[4]->attribute('class') , $this->matchesRegularExpression('/\bitem5\b/'));
		$this->assertThat ( $items[4]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=%2FUsers%2Fnclifton%2FDocuments%2FMAMP%2Fhtdocs%2Freaxml%2Fftp%2Fwork' ));
		$this->assertThat ( $items[4]->byTag('a')->text(), $this->matchesRegularExpression ( '/work/' ) );
		
	}
		
	
}
?>