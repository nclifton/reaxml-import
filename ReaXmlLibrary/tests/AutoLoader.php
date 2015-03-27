<?php

/**
 * @copyright	Copyright (C) 2014 Clifton IT Foundries Pty Ltd All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 **/
class ReaxmlTestingAutoLoader {
	private static $classNames = array ();
	
	/**
	 * store the filename (sans extensions) & full path of all ".php" files found emulating what we will have JLoader doing in joomla; prefixing class names with the parent directory names camelcase wise.
	 */
	public static function registerDirectory($dirName, $classPrefix = '') {
		$di = new DirectoryIterator ( $dirName );
		$classPrefix = $classPrefix . ucfirst ( strtolower ( basename ( $dirName ) ) );
		foreach ( $di as $file ) {
			if ($file->isDir () && ! $file->isLink () && ! $file->isDot ()) {
				// recurse into directories other than a few special ones
				self::registerDirectory ( $file->getPathname (), $classPrefix );
			} else {
				if (substr ( $file->getFilename (), - 4 ) === '.php') {
					// save the class name / path of a .php file found
					$className = $classPrefix . ucfirst ( strtolower ( substr ( $file->getFilename (), 0, - 4 ) ) );
					ReaxmlTestingAutoLoader::registerClass ( $className, $file->getRealPath () );
				}
			}
		}
	}
	public static function registerClass($className, $fileName) {
		ReaxmlTestingAutoLoader::$classNames [$className] = $fileName;
	}
	public static function loadClass($className) {
		if (isset ( ReaxmlTestingAutoLoader::$classNames [$className] )) {
			require_once (ReaxmlTestingAutoLoader::$classNames [$className]);
		} 
	}
}

spl_autoload_register ( array (
		'ReaxmlTestingAutoLoader',
		'loadClass' 
) );