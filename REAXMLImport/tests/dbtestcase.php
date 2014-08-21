<?php

abstract class Reaxml_Tests_DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase{
	private static $pdo = null;
	private $conn = null;
	final public function getConnection() {
	        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
		}
		return $this->conn;
	}
	
	protected function getColumns(){
		
		return self::$pdo->query('SHOW COLUMNS FROM ezrea_joomla.ezrea_ezrealty');
		
	}
}
