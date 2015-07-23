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

        $this->url ( 'http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.urlencode (realpath(__DIR__.'/../htdocs/ftp')) );
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
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('value'), $this->equalTo (realpath(__DIR__.'/../htdocs/ftp')) );
		$buttons = $this->elements($this->using('css selector')->value('form[name="adminForm"] button'));
		$this->assertThat ( count($buttons), $this->equalTo(2));
		$this->assertThat ( $buttons[0]->attribute('onclick'), $this->equalTo('document.form.adminForm.submit(); return false;' ));
		$this->assertThat ( $buttons[1]->attribute('onclick'), $this->equalTo('reaxml_folderbrowser_useThis(\'jform_input_dir\',\'jform_input_url\'); return false;' ));

        $path = realpath(__DIR__.'/../htdocs/ftp');
        $folders = explode('/',$path);

		$items = $this->elements($this->using('css selector')->value('ul.breadcrumb li'));
		$this->assertThat ( count($items), $this->equalTo(count($folders)));
        $tpath = '';
        for($i=0;$i<count($items)-1;$i++) {
            $tpath .= $i==1?$folders[$i]:'/'.$folders[$i];
            $this->assertThat($items[$i]->attribute('class'), $this->equalTo('item' . ($i + 1)));
            $this->assertThat($items[$i]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=' . urlencode($tpath)));
            $this->assertThat($items[$i]->byTag('a')->text(), $this->matchesRegularExpression('%' . ($i==0?'/':$folders[$i]) . '%i'));

        }

		$this->assertThat ( $this->byCssSelector ( 'ul.breadcrumb li.item'.count($items).'.active' )->text(), $this->matchesRegularExpression ( '%'.$folders[count($items)-1].'%i' ) );
		
		$items = $this->elements($this->using('css selector')->value('ul.folders li'));
		$this->assertThat ( count($items), $this->equalTo(5));
        $encodedpath = urlencode($path);
		$this->assertThat ( $items[0]->attribute('class') , $this->matchesRegularExpression('/\bitem1\b/'));
		$this->assertThat ( $items[0]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$encodedpath.'%2Fdone' ));
		$this->assertThat ( $items[0]->byTag('a')->text(), $this->matchesRegularExpression ( '/done/' ) );
		$this->assertThat ( $items[1]->attribute('class') , $this->matchesRegularExpression('/\bitem2\b/'));
		$this->assertThat ( $items[1]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$encodedpath.'%2Ferror' ));
		$this->assertThat ( $items[1]->byTag('a')->text(), $this->matchesRegularExpression ( '/error/' ) );
		$this->assertThat ( $items[2]->attribute('class') , $this->matchesRegularExpression('/\bitem3\b/'));
		$this->assertThat ( $items[2]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$encodedpath.'%2Finput' ));
		$this->assertThat ( $items[2]->byTag('a')->text(), $this->matchesRegularExpression ( '/input/' ) );
		$this->assertThat ( $items[3]->attribute('class') , $this->matchesRegularExpression('/\bitem4\b/'));
		$this->assertThat ( $items[3]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$encodedpath.'%2Flog' ));
		$this->assertThat ( $items[3]->byTag('a')->text(), $this->matchesRegularExpression ( '/log/' ) );
		$this->assertThat ( $items[4]->attribute('class') , $this->matchesRegularExpression('/\bitem5\b/'));
		$this->assertThat ( $items[4]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$encodedpath.'%2Fwork' ));
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
		$this->assertThat($this->byId('folder')->attribute('value'), $this->equalTo(realpath(__DIR__.'/../htdocs')));
		
		$this->byCssSelector('form[name="adminForm"] button.btn-success')->click();
		
		$this->frame(null);
		// the iframe should now not be visible again
		$uidialog = $this->byId ( 'reaxmlimport-config-panel' )->byXPath('parent::*');
		$this->assertThat ( $uidialog->displayed (), $this->isFalse () );

		$this->assertThat($this->byId('jform_input_dir')->attribute('value'), $this->equalTo(realpath(__DIR__.'/../htdocs')));
		
	}
	
	/**
	 * @test
	 */
	public function uses_root_folder_if_supplied_folder_does_not_exist() {
		
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
		
		$this->url ( 'http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.urlencode ('input') );
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('value'), $this->equalTo (realpath(__DIR__.'/../htdocs')) );
		
	}
	
	/**
	 * @test
	 */
	public function can_select_another_folder() {

		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
		
		$this->url ( 'http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.urlencode (realpath(__DIR__.'/../htdocs/ftp')) );
		$this->byCssSelector('li.item8 a')->click();
		
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
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('value'), $this->equalTo (realpath(__DIR__.'/../htdocs')) );

        $path = realpath(__DIR__.'/../htdocs');
        $folders = explode('/',$path);
		$items = $this->elements($this->using('css selector')->value('ul.breadcrumb li'));

        $this->assertThat ( count($items), $this->equalTo(count($folders)));
        $tpath = '';
        for($i=0;$i<count($items)-1;$i++) {
            $tpath .= $i==1?$folders[$i]:'/'.$folders[$i];
            $this->assertThat($items[$i]->attribute('class'), $this->equalTo('item' . ($i + 1)));
            $this->assertThat($items[$i]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=' . urlencode($tpath)));
            $this->assertThat($items[$i]->byTag('a')->text(), $this->matchesRegularExpression('%' . ($i==0?'/':$folders[$i]) . '%i'));

        }

        $this->assertThat ( $this->byCssSelector ( 'ul.breadcrumb li.item'.count($items).'.active' )->text(), $this->matchesRegularExpression ( '%'.$folders[count($items)-1].'%i' ) );

	}

	/**
	 * @test
	 */
	public function can_go_to_another_folder() {
	
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
	
		$this->url ( 'http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.urlencode (realpath(__DIR__.'/../htdocs/ftp')) );
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
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('value'), $this->equalTo (realpath(__DIR__.'/../htdocs/ftp').'/input') );

        $path = realpath(__DIR__.'/../htdocs/ftp/input');
        $folders = explode('/',$path);
        $items = $this->elements($this->using('css selector')->value('ul.breadcrumb li'));

        $this->assertThat ( count($items), $this->equalTo(count($folders)));
        $tpath = '';
        for($i=0;$i<count($items)-1;$i++) {
            $tpath .= $i==1?$folders[$i]:'/'.$folders[$i];
            $this->assertThat($items[$i]->attribute('class'), $this->equalTo('item' . ($i + 1)));
            $this->assertThat($items[$i]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=' . urlencode($tpath)));
            $this->assertThat($items[$i]->byTag('a')->text(), $this->matchesRegularExpression('%' . ($i==0?'/':$folders[$i]) . '%i'));

        }

        $this->assertThat ( $this->byCssSelector ( 'ul.breadcrumb li.item'.count($items).'.active' )->text(), $this->matchesRegularExpression ( '%'.$folders[count($items)-1].'%i' ) );

	}
	
	/**
	 * @test
	 */
	public function can_drill_down_to_a_subfolder() {
	
		$this->timeouts ()->implicitWait ( 10000 );
		$this->loadExtension ();
	
		$this->url ( 'http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.urlencode (realpath(__DIR__.'/../htdocs')) );
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
		$this->assertThat ( $this->byCssSelector ( 'form[name="adminForm"] input[name="folder"]' )->attribute('value'), $this->equalTo (realpath(__DIR__.'/../htdocs/ftp')) );

        $path = realpath(__DIR__.'/../htdocs/ftp');
        $folders = explode('/',$path);
        $items = $this->elements($this->using('css selector')->value('ul.breadcrumb li'));

        $this->assertThat ( count($items), $this->equalTo(count($folders)));
        $tpath = '';
        for($i=0;$i<count($items)-1;$i++) {
            $tpath .= $i==1?$folders[$i]:'/'.$folders[$i];
            $this->assertThat($items[$i]->attribute('class'), $this->equalTo('item' . ($i + 1)));
            $this->assertThat($items[$i]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder=' . urlencode($tpath)));
            $this->assertThat($items[$i]->byTag('a')->text(), $this->matchesRegularExpression('%' . ($i==0?'/':$folders[$i]) . '%i'));

        }

        $this->assertThat ( $this->byCssSelector ( 'ul.breadcrumb li.item'.count($items).'.active' )->text(), $this->matchesRegularExpression ( '%'.$folders[count($items)-1].'%i' ) );
	
		$items = $this->elements($this->using('css selector')->value('ul.folders li'));
		$this->assertThat ( count($items), $this->equalTo(5));
        $encodedpath = urlencode($path);
        $this->assertThat ( $items[0]->attribute('class') , $this->matchesRegularExpression('/\bitem1\b/'));
		$this->assertThat ( $items[0]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$encodedpath.'%2Fdone' ));
		$this->assertThat ( $items[0]->byTag('a')->text(), $this->matchesRegularExpression ( '/done/' ) );
		$this->assertThat ( $items[1]->attribute('class') , $this->matchesRegularExpression('/\bitem2\b/'));
		$this->assertThat ( $items[1]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$encodedpath.'%2Ferror' ));
		$this->assertThat ( $items[1]->byTag('a')->text(), $this->matchesRegularExpression ( '/error/' ) );
		$this->assertThat ( $items[2]->attribute('class') , $this->matchesRegularExpression('/\bitem3\b/'));
		$this->assertThat ( $items[2]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$encodedpath.'%2Finput' ));
		$this->assertThat ( $items[2]->byTag('a')->text(), $this->matchesRegularExpression ( '/input/' ) );
		$this->assertThat ( $items[3]->attribute('class') , $this->matchesRegularExpression('/\bitem4\b/'));
		$this->assertThat ( $items[3]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$encodedpath.'%2Flog' ));
		$this->assertThat ( $items[3]->byTag('a')->text(), $this->matchesRegularExpression ( '/log/' ) );
		$this->assertThat ( $items[4]->attribute('class') , $this->matchesRegularExpression('/\bitem5\b/'));
		$this->assertThat ( $items[4]->byTag('a')->attribute('href'), $this->equalTo('http://reaxml.dev/administrator/index.php?option=com_reaxmlimport&controller=config&view=folderbrowser&tmpl=component&inputid=jform_input_dir&urlinputid=jform_input_url&folder='.$encodedpath.'%2Fwork' ));
		$this->assertThat ( $items[4]->byTag('a')->text(), $this->matchesRegularExpression ( '/work/' ) );
		
	}
		
	
}
?>