<?php
/**
 * UNITE
 * The automated site restoration system
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   unite
 */

// Protection against direct access
defined('UNITE') or die('Restricted access');

/**
 * Parses a UNITE Site Specification XML file and returns a structure of nested hash
 * arrays, as they are far easier to handle in PHP code. Note: it uses PHP5's DOM
 * extension.
 */
class UCoreXmlparser extends UAbstractObject
{

	/**
	 * Parses an XML file and returns its contents as a structured array
	 *
	 * @param $file string The file to process
	 *
	 * @return array|bool A structured array with the file's data, or false if processing failed
	 */
	public function parseFile($file)
	{
		// Init result
		$structure = array();

		// Load the XML file
		$dom = new DOMDocument();
		$success = @$dom->load($file, LIBXML_NOBLANKS | LIBXML_NOCDATA | LIBXML_NOENT | LIBXML_NONET);

		// Die gracefully if the XML file couldn't be processed by the DOM library
		if (!$success)
		{
			$this->setError("File $file is not valid XML");

			return false;
		}

		// Get reference to <unite> root node, or fail if there are more or less than 1 nodes of this type.
		$rootNodes = $dom->getElementsByTagname('unite');

		if ($rootNodes->length != 1)
		{
			$this->setError("Error: the count of <unite> root nodes is " . $rootNodes->length);

			return false;
		}
		else
		{
			$root = $rootNodes->item(0);
		}

		// Make sure we have child nodes
		if (!$root->hasChildNodes())
		{
			$this->setError('Error: <unite> does not seem to have child nodes!');

			return false;
		}

		// Loop for all childnodes
		$childNodes = $root->childNodes;

		for ($i = 0; $i < $childNodes->length; $i++)
		{
			// Skip over non-tags (e.g. comments)
			$thisNode = $childNodes->item($i);

			if (!($thisNode->nodeType == XML_ELEMENT_NODE))
			{
				continue;
			}

			// For tags, defer parsing to one of the special methods...
			switch ($thisNode->tagName)
			{
				case 'remote':
					$result = $this->parseRemote($thisNode);
					break;

				case 's3':
					$result = $this->parseS3($thisNode);
					break;

				case 'siteInfo':
					$result = $this->parseSiteInfo($thisNode);
					break;

				case 'databaseInfo':
					$result = $this->parseDatabaseInfo($thisNode);
					break;

				case 'extrafiles':
					$result = $this->parseExtrafiles($thisNode);
					break;

				case 'extrasql':
					$result = $this->parseExtrasql($thisNode);
					break;

				case 'postrun':
					$result = $this->parsePostrun($thisNode);
					break;

				default:
					UUtilLogger::WriteLog('Warning: unknown tag <' . $thisNode->tagName . '> encountered. It will be ignored');
					$result = array();
					break;
			}

			// Let's see what the parsing result was...
			if (($result === false) || (!is_array($result)))
			{
				// Oops! An error occured.
				$this->setError('Error: parsing of <' . $thisNode->tagName . '> has failed.');

				return false;
			}
			else
			{
				// Only add if the result is a non-empty array
				if (count($result) != 0)
				{
					// Some data has to be added
					$structure[$thisNode->tagName] = $result;
				}
			}
		}

		// Return the structured array. We are done.
		return $structure;
	}

	/**
	 * Parses the siteInfo tags.
	 *
	 * @param DOMNode $node The siteInfo DOM node
	 *
	 * @return array|bool An array to be merged on the main structured array, or false if parsing failed
	 */
	private function parseSiteInfo(DOMNode $node)
	{
		// Initialize structure
		$structure = array(
			'package'       => '',
			'packageFrom'   => 'inbox',
			'deletePackage' => true,
			'localLog'      => '',
			'emailSysop'    => true,
			'name'          => '',
			'email'         => '',
			'absolutepath'  => '',
			'livesite'      => null,
			'ftphost'       => '',
			'ftpport'       => 21,
			'ftpuser'       => '',
			'ftppass'       => '',
			'ftpdir'        => '',
			'createdb'      => false,
			'dbhost'        => '',
			'dbuser'        => '',
			'dbpass'        => '',
			'dbname'        => '',
			'dbprefix'      => 'jos_',
			'adminID'       => 62,
			'adminUser'     => 'admin',
			'adminPassword' => '',
			'jpspassword'   => ''
		);

		// Do we have child nodes?
		if (!$node->hasChildNodes())
		{
			// No child nodes! This is a problem, we have to abort.
			UUtilLogger::WriteLog('Error: <siteInfo> doesn\'t seem to have child nodes!');

			return false;
		}

		$childNodes = $node->childNodes;
		for ($i = 0; $i < $childNodes->length; $i++)
		{
			// Loop for all element (tag) nodes
			$thisNode = $childNodes->item($i);
			if ($thisNode->nodeType == XML_ELEMENT_NODE)
			{
				switch ($thisNode->tagName)
				{
					case 'package':
						$structure[$thisNode->tagName] = $thisNode->textContent;
						if ($thisNode->hasAttributes())
						{
							$structure['packageFrom'] = $thisNode->attributes->getNamedItem('from')->nodeValue;
						}
						break;

					case 'deletePackage':
					case 'emailSysop':
					case 'createdb':
						$structure[$thisNode->tagName] = $thisNode->textContent == 1 ? true : false;
						break;

					case 'localLog':
					case 'name':
					case 'email':
					case 'absolutepath':
					case 'livesite':
					case 'ftphost':
					case 'ftpport':
					case 'ftpuser':
					case 'ftppass':
					case 'ftpdir':
					case 'dbhost':
					case 'dbuser':
					case 'dbpass':
					case 'dbname':
					case 'dbprefix':
					case 'adminUser':
					case 'adminPassword':
					case 'jpspassword':
						$structure[$thisNode->tagName] = $thisNode->textContent;
						break;

					case 'adminID':
						if (is_numeric($thisNode->textContent))
						{
							$structure[$thisNode->tagName] = (int)$thisNode->textContent;
						}
						else
						{
							UUtilLogger::WriteLog('Value of adminID is not numeric: "' . $thisNode->textContent . '"');
						}
						break;

					default:
						UUtilLogger::WriteLog('Unknown <siteInfo> tag: <' . $thisNode->tagName . '>');
						break;
				}
			}
		}

		return $structure;
	}

	/**
	 * Parses the remote tags.
	 *
	 * @param DOMNode $node The remote DOM node
	 *
	 * @return array|bool An array to be merged on the main structured array, or false if parsing failed
	 */
	private function parseRemote(DOMNode $node)
	{
		// Initialize structure
		$structure = array(
			'host'         => '',
			'secret'       => '',
			'profile'      => 1,
			'downloadmode' => 'http',
			'dlurl'        => '',
			'delete'       => 0,
		);

		// Do we have child nodes?
		if (!$node->hasChildNodes())
		{
			// No child nodes! This is a problem, we have to abort.
			UUtilLogger::WriteLog('Error: <remote> doesn\'t seem to have child nodes!');

			return false;
		}

		$childNodes = $node->childNodes;
		for ($i = 0; $i < $childNodes->length; $i++)
		{
			// Loop for all element (tag) nodes
			$thisNode = $childNodes->item($i);
			if ($thisNode->nodeType == XML_ELEMENT_NODE)
			{
				switch ($thisNode->tagName)
				{
					case 'host':
					case 'secret':
					case 'dlurl':
						$structure[$thisNode->tagName] = $thisNode->textContent;
						break;

					case 'profile':
						$structure[$thisNode->tagName] = (int)$thisNode->textContent;
						break;

					case 'downloadmode':
						$dlmode = strtolower($thisNode->textContent);
						if (!in_array($dlmode, array('http', 'chunk', 'curl')))
						{
							$dlmode = 'http';
						}
						$structure[$thisNode->tagName] = $dlmode;
						break;

					case 'delete':
						$structure[$thisNode->tagName] = $thisNode->textContent == 1 ? true : false;
						break;

					default:
						UUtilLogger::WriteLog('Unknown <remote> tag: <' . $thisNode->tagName . '>');
						break;
				}
			}
		}

		return $structure;
	}

	/**
	 * Parses the s3 tags.
	 *
	 * @param DOMNode $node The s3 DOM node
	 *
	 * @return array|bool An array to be merged on the main structured array, or false if parsing failed
	 */
	private function parseS3(DOMNode $node)
	{
		// Initialize structure
		$structure = array(
			'accesskey' => '',
			'secretkey' => '',
			'bucket'    => '',
			'ssl'       => false,
			'filename'  => '',
			'endpoint'  => '',
		);

		// Do we have child nodes?
		if (!$node->hasChildNodes())
		{
			// No child nodes! This is a problem, we have to abort.
			UUtilLogger::WriteLog('Error: <s3> doesn\'t seem to have child nodes!');

			return false;
		}

		$childNodes = $node->childNodes;

		for ($i = 0; $i < $childNodes->length; $i++)
		{
			// Loop for all element (tag) nodes
			$thisNode = $childNodes->item($i);
			if ($thisNode->nodeType == XML_ELEMENT_NODE)
			{
				switch ($thisNode->tagName)
				{
					case 'accesskey':
					case 'secretkey':
					case 'bucket':
					case 'filename':
					case 'endpoint':
						$structure[$thisNode->tagName] = $thisNode->textContent;
						break;

					case 'ssl':
						$ssl = strtolower($thisNode->textContent);
						if (!in_array($ssl, array('1', 'true', 'yes', 'on', 'enabled', 'ssl')))
						{
							$ssl = true;
						}
						$structure[$thisNode->tagName] = $ssl;
						break;

					default:
						UUtilLogger::WriteLog('Unknown <remote> tag: <' . $thisNode->tagName . '>');
						break;
				}
			}
		}

		return $structure;
	}

	/**
	 * Parses the databaseInfo tags.
	 *
	 * @param DOMNode $node The databaseInfo DOM node
	 *
	 * @return array|bool An array to be merged on the main structured array, or false if parsing failed
	 */
	private function parseDatabaseInfo(DOMNode $node)
	{
		$return = array();

		// Initialize structure
		$structure = array(
			'changecollation' => 0,
			'dbhost'          => '',
			'dbuser'          => '',
			'dbpass'          => '',
			'dbname'          => '',
			'dbprefix'        => 'jos_',
		);

		// Do we have child nodes?
		if (!$node->hasChildNodes())
		{
			// No child nodes! This is a problem, we have to abort.
			UUtilLogger::WriteLog('Error: <databaseInfo> doesn\'t seem to have child nodes!');

			return false;
		}

		$databaseNodes = $node->childNodes;

		for ($j = 0; $j < $databaseNodes->length; $j++)
		{
			$databaseNode = $databaseNodes->item($j);

			if (!$databaseNode->hasChildNodes())
			{
				// No child nodes! This is a problem, we have to abort.
				UUtilLogger::WriteLog('<database> node doesn\'t seem to have child nodes!');
				continue;
			}

			if ($databaseNode->hasAttributes())
			{
				$dbfileNode = $databaseNode->attributes->getNamedItem('name');

				if (empty($dbfileNode))
				{
					UUtilLogger::WriteLog('Error: <databaseInfo> doesn\'t seem to have a "name" attribute!');

					return false;
				}
				else
				{
					$dbfile = $dbfileNode->textContent;
				}
			}

			$thisdb = $structure;

			$childNodes = $databaseNode->childNodes;

			for ($i = 0; $i < $childNodes->length; $i++)
			{
				// Loop for all element (tag) nodes
				$thisNode = $childNodes->item($i);

				if ($thisNode->nodeType == XML_ELEMENT_NODE)
				{
					switch ($thisNode->tagName)
					{
						case 'changecollation':
						case 'dbdriver':
						case 'dbhost':
						case 'dbport':
						case 'dbuser':
						case 'dbpass':
						case 'dbname':
						case 'dbprefix':
							$thisdb[$thisNode->tagName] = $thisNode->textContent;
							break;
						default:
							UUtilLogger::WriteLog('Unknown <database> tag: <' . $thisNode->tagName . '>');
							break;
					}
				}
			}

			$return[$dbfile] = $thisdb;
		}

		return $return;
	}

	/**
	 * Parses the extrafiles tags.
	 *
	 * @param DOMNode $node The extrafiles DOM node
	 *
	 * @return array|bool An array to be merged on the main structured array, or false if parsing failed
	 */
	private function parseExtrafiles(DOMNode $node)
	{
		$structure = array();

		// Do we have child nodes?
		if (!$node->hasChildNodes())
		{
			// No child nodes. An empty structure is returned
			UUtilLogger::WriteLog('Notice: <extrafiles> is empty. You shouldn\'t have added it in the XML file.');

			return $structure;
		}

		$childNodes = $node->childNodes;

		for ($i = 0; $i < $childNodes->length; $i++)
		{
			// Loop all child nodes
			$thisNode = $childNodes->item($i);

			if ($thisNode->nodeType == XML_ELEMENT_NODE)
			{
				switch ($thisNode->tagName)
				{
					case 'extract':
						$substruc = array(
							'to'   => '',
							'file' => ''
						);

						// If there is the to="..." attribute, update the substructure array
						if ($thisNode->hasAttributes())
						{
							$to = $thisNode->attributes->getNamedItem('to');

							if (!empty($to))
							{
								$substruc['to'] = $thisNode->attributes->getNamedItem('to')->textContent;
							}
						}

						$substruc['file'] = $thisNode->textContent;
						$structure[] = $substruc;
						break;

					default:
						UUtilLogger::WriteLog('Warning: Unknown <extrafiles> tag: <' . $thisNode->tagName . '>');
						break;
				}
			}
		}

		return $structure;
	}

	/**
	 * Parses the extrasql tags.
	 *
	 * @param DOMNode $node The extrasql DOM node
	 *
	 * @return array|bool An array to be merged on the main structured array, or false if parsing failed
	 */
	private function parseExtrasql(DOMNode $node)
	{
		$structure = array();

		// Do we have child nodes?
		if (!$node->hasChildNodes())
		{
			// No child nodes. An empty structure is returned
			UUtilLogger::WriteLog('Notice: <extrasql> is empty. You shouldn\'t have added it in the XML file.');

			return $structure;
		}

		$childNodes = $node->childNodes;

		for ($i = 0; $i < $childNodes->length; $i++)
		{
			// Loop all child nodes
			$thisNode = $childNodes->item($i);

			if ($thisNode->nodeType == XML_ELEMENT_NODE)
			{
				switch ($thisNode->tagName)
				{
					case 'execute':
						$ret = array('db' => '', 'file' => '');

						if ($thisNode->hasAttributes())
						{
							$db = $thisNode->attributes->getNamedItem('db');

							if (!empty($db))
							{
								$ret['db'] = $thisNode->attributes->getNamedItem('db')->textContent;
							}
							else
							{
								//$ret['db'] = 'joomla';
							}
						}
						else
						{
							//$ret['db'] = 'joomla';
						}

						$ret['file'] = $thisNode->textContent;

						if (!empty($ret['file']))
						{
							$structure[] = $ret;
						}

						break;

					default:
						UUtilLogger::WriteLog('Warning: Unknown <extrasql> tag: <' . $thisNode->tagName . '>');

						break;
				}
			}
		}

		return $structure;
	}

	/**
	 * Parses the postrun tags.
	 *
	 * @param DOMNode $node The postrun DOM node
	 *
	 * @return array|bool An array to be merged on the main structured array, or false if parsing failed
	 */
	private function parsePostrun(DOMNode $node)
	{
		$structure = array(
			'emailto'      => '',
			'emailsubject' => '',
			'emailbody'    => ''
		);

		// Do we have child nodes?
		if (!$node->hasChildNodes())
		{
			// No child nodes. An empty structure is returned
			UUtilLogger::WriteLog('Error: <postrun> is empty.');

			return false;
		}

		$childNodes = $node->childNodes;

		for ($i = 0; $i < $childNodes->length; $i++)
		{
			// Loop all child nodes
			$thisNode = $childNodes->item($i);

			if ($thisNode->nodeType == XML_ELEMENT_NODE)
			{
				switch ($thisNode->tagName)
				{
					case 'emailto':
						$structure[$thisNode->tagName] = $thisNode->textContent;
						break;
					case 'emailsubject':
						$structure[$thisNode->tagName] = $thisNode->textContent;
						break;
					case 'emailbody':
						$structure[$thisNode->tagName] = $thisNode->textContent;
						break;
					default:
						UUtilLogger::WriteLog('Warning: Unknown <postrun> tag: <' . $thisNode->tagName . '>');
						break;
				}
			}
		}

		return $structure;
	}
}