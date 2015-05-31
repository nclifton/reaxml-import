<?php
/**
 * UNITE
 * The automated site restoration system
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   unite
 */

// ensure this file is being included by a parent file
defined('UNITE') or die();

/**
 * Scans a directory for files of a sepcific extension
 */
class UUtilDirscanner
{

	/**
	 * The actual directory scanning function
	 *
	 * @return array The list of absolute paths to the files
	 */
	public function &getFiles($rootPath, $requiredExtension = 'xml', $absolute = true)
	{
		$fileList = array();

		// Try to see if we can find the inbox directory
		if (!is_dir(realpath($rootPath)))
		{
			// Try appending it to UNITE's root
			$newRoot = dirname(__FILE__) . '../../' . $rootPath;
			if (!is_dir($newRoot))
			{
				throw new Exception('The directory "' . $rootPath . '" could not be located on the file system.');
				$fileList = false;

				return $fileList;
			}
			else
			{
				$rootPath = $newRoot;
			}
		}

		$handle = @opendir($rootPath);
		while (($aFile = @readdir($handle)) !== false)
		{
			if (($aFile != '.') && ($aFile != '..'))
			{
				if (@!is_dir($aFile))
				{
					if (empty($requiredExtension) || ($this->getFileExtension($aFile) == $requiredExtension))
					{
						if ($absolute)
						{
							$fileList[] = $rootPath . '/' . $aFile;
						}
						else
						{
							$fileList[] = $aFile;
						}
					}
				}
			}
		}
		@closedir($handle);

		if (!empty($fileList))
		{
			asort($fileList);
		}

		return $fileList;
	}

	/**
	 * Returns the extension of a filename, without the dot. E.g., for "/foo/bar/readme.first.txt"
	 * it simply returns "txt".
	 *
	 * @param $filepath string The file path you want to be processed
	 *
	 * @return string The extension, without a dot
	 */
	private function getFileExtension($filepath)
	{
		$basename = basename($filepath);
		$dotpos = strrpos($basename, '.');
		if ($dotpos === false)
		{
			return '';
		}

		return strtolower(substr($basename, $dotpos + 1));
	}
}