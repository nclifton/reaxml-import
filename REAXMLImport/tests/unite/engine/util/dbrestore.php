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

if (!defined('DATA_CHUNK_LENGTH'))
{
	define('DATA_CHUNK_LENGTH', 65536); // How many bytes to read per step
	define('MAX_QUERY_LINES', 300); // How many lines may be considered to be one query (except text lines)
}

/**
 * Database dump file restoration class
 */
class UUtilDbrestore
{

	/**
	 * Restores a SQL dump file to a database
	 *
	 * @param $filename string The absolute path to the file to be restored
	 * @param $db       UAbstractDriver The database connection object
	 *
	 * @return bool|string True if restoration succeeded, the error message otherwise
	 */
	public static function restoreDump($filename, UAbstractDriver $db, $options = array())
	{
		// Parse options
		if (array_key_exists('changecollation', $options))
		{
			$canChangeCollation = $options['changecollation'];
		}
		else
		{
			$canChangeCollation = false;
		}

		// Try to open file
		if (!$file = @fopen($filename, "rt"))
		{
			return 'Could not open SQL dump file ' . $filename;
		}

		// Allowed comment delimiters: lines starting with these strings will be dropped
		$comment = array('#', '-- ', '---', '/*!');

		// A list of MySQL errors we can safely ignore
		// Reference: http://dev.mysql.com/doc/refman/4.1/en/error-messages-server.html
		$skipMySQLErrorNumbers = array(
			1262,
			1263,
			1264,
			1265, // "Data truncated" warning
			1266,
			1287,
			1299
			// , 1406	// "Data too long" error
		);

		// Enforce skipping foreign key checks
		$db->setQuery('SET FOREIGN_KEY_CHECKS = 0');
		$db->query();

		while (!feof($file))
		{
			// Read one line (1 line = 1 query)
			$query = "";
			while (!feof($file) && (strpos($query, "\n") === false))
			{
				$query .= fgets($file, DATA_CHUNK_LENGTH);
			}

			if (empty($query) || ($query === false))
			{
				return true;
			}

			if (substr($query, -1) != "\n")
			{
				// We read more data than we should; roll back the file
				$rollback = strlen($query) - strpos($query, "\n");
				fseek($file, -$rollback, SEEK_CUR);
				// And chop the line
				$query = substr($query, 0, $rollback);
			}

			// Handle DOS linebreaks
			$query = str_replace("\r\n", "\n", $query);
			$query = str_replace("\r", "\n", $query);

			// Skip comments and blank lines only if NOT in parents
			$skipline = false;
			reset($comment);
			foreach ($comment as $comment_value)
			{
				if (trim($query) == "" || strpos($query, $comment_value) === 0)
				{
					$skipline = true;
					break;
				}
			}
			if ($skipline)
			{
				continue;
			}

			$query = trim($query, " \n");
			$query = rtrim($query, ';');

			// CREATE TABLE query pre-processing
			// In any case, drop the table before attempting to create it.
			$replaceAll = false;
			$changeEncoding = false;
			if (substr($query, 0, 12) == 'CREATE TABLE')
			{
				// Yes, try to get the table name
				$restOfQuery = trim(substr($query, 12, strlen($query) - 12)); // Rest of query, after CREATE TABLE
				// Is there a backtick?
				if (substr($restOfQuery, 0, 1) == '`')
				{
					// There is... Good, we'll just find the matching backtick
					$pos = strpos($restOfQuery, '`', 1);
					$tableName = substr($restOfQuery, 1, $pos - 1);
				}
				else
				{
					// Nope, let's assume the table name ends in the next blank character
					$pos = strpos($restOfQuery, ' ', 1);
					$tableName = substr($restOfQuery, 1, $pos - 1);
				}
				unset($restOfQuery);

				// Try to drop the table
				$dropQuery = 'DROP TABLE IF EXISTS `' . $tableName . '`;';
				$db->setQuery(trim($dropQuery), false);
				if (!$db->query())
				{
					// Query failure
					$message = "SQL error dropping $tableName: " . $db->getErrorMsg();

					return $message;
				}

				$replaceAll = true; // When processing CREATE TABLE commands, we might have to replace SEVERAL metaprefixes.
				// Crude check: Community builder's #__comprofiler_fields includes a DEFAULT value which use a metaprefix,
				// so replaceAll must be false in that case.
				if (substr($tableName, -19) == '_comprofiler_fields')
				{
					$replaceAll = false;
				}

				$changeEncoding = true;
			}
			else
				// CREATE VIEW query pre-processing
				// In any case, drop the view before attempting to create it.
				if ((substr($query, 0, 7) == 'CREATE ') && (strpos($query, ' VIEW ') !== false))
				{
					// Yes, try to get the view name
					$view_pos = strpos($query, ' VIEW ');
					$restOfQuery = trim(substr($query, $view_pos + 6)); // Rest of query, after VIEW string
					// Is there a backtick?
					if (substr($restOfQuery, 0, 1) == '`')
					{
						// There is... Good, we'll just find the matching backtick
						$pos = strpos($restOfQuery, '`', 1);
						$tableName = substr($restOfQuery, 1, $pos - 1);
					}
					else
					{
						// Nope, let's assume the table name ends in the next blank character
						$pos = strpos($restOfQuery, ' ', 1);
						$tableName = substr($restOfQuery, 1, $pos - 1);
					}
					unset($restOfQuery);

					// Try to drop the view anyway
					$dropQuery = 'DROP VIEW IF EXISTS `' . $tableName . '`;';
					$db->setQuery(trim($dropQuery), false);
					if (!$db->query())
					{
						// Query failure
						$message = "SQL error dropping $tableName: " . $db->getErrorMsg();

						return $message;
					}

					$replaceAll = true; // When processing views, we might have to replace SEVERAL metaprefixes.
				}
				// CREATE PROCEDURE pre-processing
				elseif ((substr($query, 0, 7) == 'CREATE ') && (strpos($query, 'PROCEDURE ') !== false))
				{
					// Try to get the procedure name
					$entity_keyword = ' PROCEDURE ';
					$entity_pos = strpos($query, $entity_keyword);
					$restOfQuery = trim(substr($query, $entity_pos + strlen($entity_keyword))); // Rest of query, after entity key string
					// Is there a backtick?
					if (substr($restOfQuery, 0, 1) == '`')
					{
						// There is... Good, we'll just find the matching backtick
						$pos = strpos($restOfQuery, '`', 1);
						$entity_name = substr($restOfQuery, 1, $pos - 1);
					}
					else
					{
						// Nope, let's assume the entity name ends in the next blank character
						$pos = strpos($restOfQuery, ' ', 1);
						$entity_name = substr($restOfQuery, 1, $pos - 1);
					}
					unset($restOfQuery);

					// Try to drop the entity anyway
					$dropQuery = 'DROP' . $entity_keyword . 'IF EXISTS `' . $entity_name . '`;';
					$db->setQuery(trim($dropQuery), false);
					if (!$db->query())
					{
						$message = "SQL error dropping $entity_name: " . $db->getErrorMsg();

						return $message;
					}

					$replaceAll = true; // When processing entities, we might have to replace SEVERAL metaprefixes.
				}
				// CREATE FUNCTION pre-processing
				elseif ((substr($query, 0, 7) == 'CREATE ') && (strpos($query, 'FUNCTION ') !== false))
				{
					// Try to get the procedure name
					$entity_keyword = ' FUNCTION ';
					$entity_pos = strpos($query, $entity_keyword);
					$restOfQuery = trim(substr($query, $entity_pos + strlen($entity_keyword))); // Rest of query, after entity key string
					// Is there a backtick?
					if (substr($restOfQuery, 0, 1) == '`')
					{
						// There is... Good, we'll just find the matching backtick
						$pos = strpos($restOfQuery, '`', 1);
						$entity_name = substr($restOfQuery, 1, $pos - 1);
					}
					else
					{
						// Nope, let's assume the entity name ends in the next blank character
						$pos = strpos($restOfQuery, ' ', 1);
						$entity_name = substr($restOfQuery, 1, $pos - 1);
					}
					unset($restOfQuery);

					// Try to drop the entity anyway
					$dropQuery = 'DROP' . $entity_keyword . 'IF EXISTS `' . $entity_name . '`;';
					$db->setQuery(trim($dropQuery), false);
					if (!$db->query())
					{
						$message = "SQL error dropping $entity_name: " . $db->getErrorMsg();

						return $message;
					}

					$replaceAll = true; // When processing entities, we might have to replace SEVERAL metaprefixes.
				}
				// CREATE TRIGGER pre-processing
				elseif ((substr($query, 0, 7) == 'CREATE ') && (strpos($query, 'TRIGGER ') !== false))
				{
					// Try to get the procedure name
					$entity_keyword = ' TRIGGER ';
					$entity_pos = strpos($query, $entity_keyword);
					$restOfQuery = trim(substr($query, $entity_pos + strlen($entity_keyword))); // Rest of query, after entity key string
					// Is there a backtick?
					if (substr($restOfQuery, 0, 1) == '`')
					{
						// There is... Good, we'll just find the matching backtick
						$pos = strpos($restOfQuery, '`', 1);
						$entity_name = substr($restOfQuery, 1, $pos - 1);
					}
					else
					{
						// Nope, let's assume the entity name ends in the next blank character
						$pos = strpos($restOfQuery, ' ', 1);
						$entity_name = substr($restOfQuery, 1, $pos - 1);
					}
					unset($restOfQuery);

					// Try to drop the entity anyway
					$dropQuery = 'DROP' . $entity_keyword . 'IF EXISTS `' . $entity_name . '`;';
					$db->setQuery(trim($dropQuery), false);
					if (!$db->query())
					{
						$message = "SQL error dropping $entity_name: " . $db->getErrorMsg();

						return $message;
					}

					$replaceAll = true; // When processing entities, we might have to replace SEVERAL metaprefixes.
				}
				elseif (substr($query, 0, 6) == 'INSERT')
				{
					// Use REPLACE instead of INSERT selected
					$query = 'REPLACE ' . substr($query, 7);
					$replaceAll = false;
				}
				else
				{
					// Maybe a DROP statement from the extensions filter?
					$replaceAll = true;
				}

			if (!empty($query))
			{
				//$db->setQuery(trim($query), !$replaceAll);
				// @todo Handle prefix replacing here, please
				$db->setQuery(trim($query));
				if (!$db->query())
				{
					// Skip over errors we can safely ignore...
					if (in_array($db->getErrorNum(), $skipMySQLErrorNumbers))
					{
						continue;
					}

					$message = 'MySQL Error ' . $db->getErrorNum() . ': ' . $db->getErrorMsg();

					return $message;
				}

				// Force UTF8 encoding.
				if ($changeEncoding && $canChangeCollation)
				{
					// Get a list of columns
					$sql = 'SHOW FULL COLUMNS FROM `' . $tableName . '`';
					$db->setQuery($sql);
					$columns = $db->loadAssocList();
					$mods = array(); // array to hold individual MODIFY COLUMN commands
					if (is_array($columns))
					{
						foreach ($columns as $column)
						{
							// Make sure we are redefining only columns which do support a collation
							$col = (object)$column;
							if (empty($col->Collation))
							{
								continue;
							}

							$null = $col->Null == 'YES' ? 'NULL' : 'NOT NULL';
							$default = is_null($col->Default) ? '' : "DEFAULT '" . $db->getEscaped($col->Default) . "'";
							$mods[] = "MODIFY COLUMN `{$col->Field}` {$col->Type} $null $default COLLATE utf8_general_ci";
						}
					}

					// Begin the modification statement
					$sql = "ALTER TABLE `$tableName` ";

					// Add commands to modify columns
					if (!empty($mods))
					{
						$sql .= implode(', ', $mods) . ', ';
					}

					// Add commands to modify the table collation
					$sql .= 'DEFAULT CHARACTER SET UTF8 COLLATE utf8_general_ci;';
					$db->setQuery($sql);
					$db->query();
					$db->resetErrors();
				}
			}
		}

		return true;
	}
}