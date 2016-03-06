<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 *
 * @copyright Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 *         
 */


abstract class Reaxml_Tests_DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase {
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
	protected static function restoreJoomla(){
        self::rmdir_recursive(realpath(__DIR__.'/htdocs'));
		shell_exec('php '.__DIR__.'/../unite/unite.php');
		sleep(2); // wait for clean up to complete
	}
    protected static function rmdir_recursive($dir) {
        foreach(scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) continue;
            if (is_dir("$dir/$file")) self::rmdir_recursive("$dir/$file");
            else unlink("$dir/$file");
        }
        rmdir($dir);
    }

}
