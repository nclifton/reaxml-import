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
        $output = [];
        $exitcode = 0;
        $path1 = __DIR__ . '/unite';
        $workDir = realpath($path1);
        $path = __DIR__ . '/htdocs';
        $htdocs = realpath($path);

        exec('rm -r '.$htdocs.' && mkdir '.$htdocs,$output,$exitcode);
        foreach ($output as $line){
            echo $line."\n";
            if ($line == 'Total definitions failed to run        : 1')
                exit(1);
        }
        exec('cd ' . $workDir .' && php unite.php',$output,$exitcode);
        foreach ($output as $line){
            echo $line."\n";
            if ($line == 'Total definitions failed to run        : 1')
                exit(1);
        }
	}
    protected static function rmdir_recursive($dir) {
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
