<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */


abstract class Reaxml_Tests_DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase {


    protected static $siteHelper;
    private static $pdo = null;
	private $conn = null;
	final public function getConnection() {
		if ($this->conn === null) {
			if (self::$pdo == null) {
				self::$pdo = new PDO ( $GLOBALS ['DB_DSN'], $GLOBALS ['DB_USER'], $GLOBALS ['DB_PASSWD'] );
			}
			$this->conn = $this->createDefaultDBConnection ( self::$pdo, $GLOBALS ['DB_DBNAME'] );
		}
		return $this->conn;
	}
	protected function getColumns() {
		return self::$pdo->query ( 'SHOW COLUMNS FROM '.$GLOBALS ['DB_DBNAME'].'.'.$GLOBALS['DB_TBLPREFIX'].'ezrealty' );
	}

	protected static function fixEzRealtyTable(){
        $db = JFactory::getDbo ();
        $sql = "SELECT substring_index(column_type,'(',1) as type, substring_index(substring_index(column_type,')',1),'(',-1) as size FROM information_schema.columns where table_name = '" . $db->getPrefix () . "ezrealty' and column_name = 'mls_id'";
        $db->setQuery ( $sql );
        $row = $db->loadAssoc ();
        if (($row ['type'] == 'varchar') && ($row ['size'] < 36)) {
            echo '<p>' . JText::_ ( 'COM_REAXMLIMPORT_ADJUSTING_EZREALTY_MLS_ID' ) . '</p>';
            $db->setQuery ( "alter table #__ezrealty modify column mls_id varchar(36) NOT NULL DEFAULT ''" );
            $db->execute ();
        }
    }

    protected static function setupSite(){

        if (is_null(static::$siteHelper))
            static::$siteHelper =  new JoomlaSiteHelper();

        echo static::$siteHelper->deleteSite('reaxml');
        echo static::$siteHelper->createSite('reaxml');
        echo static::$siteHelper->installExtension('reaxml',EZREALTY_INSTALL_FILE);
        echo static::$siteHelper->installExtension('reaxml', LIBRARY_INSTALL_FILE);
        echo static::$siteHelper->installExtension('reaxml', COMPONENT_INSTALL_FILE);
        echo static::$siteHelper->extensionSymLink('reaxml', 'com_reaxmlimport');
        echo static::$siteHelper->extensionSymLink('reaxml', 'lib_reaxml');
    }

    protected function rmdir_recursive($dir) {
        if (!empty($dir)) {
            foreach(scandir($dir) as $file) {
                if ('.' === $file || '..' === $file) continue;
                if (is_dir("$dir/$file")) self::rmdir_recursive("$dir/$file");
                else unlink("$dir/$file");
            }
            rmdir($dir);
        }

    }

}
