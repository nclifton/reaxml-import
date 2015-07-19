<?php die(); // Protect from prying eyes! ?>20150706|11:26:14|Starting Akeeba UNITE v.1.4.2 (2015-01-26)
20150706|11:26:14|Scanning /Users/nclifton/Documents/marseclipse/workspace/reaxml-import/REAXMLImport/tests/unite/inbox for XML files...
20150706|11:26:14|Parsing /Users/nclifton/Documents/marseclipse/workspace/reaxml-import/REAXMLImport/tests/unite/inbox/site-reaxml.dev.xml
20150706|11:26:14|Found 1 job(s) to run
20150706|11:26:14|### Starting job #1 ###
20150706|11:26:14|Running validator
20150706|11:26:14|Running remote
20150706|11:26:14|Running s3
20150706|11:26:14|Running stealth
20150706|11:26:14|Writing a stealth .htaccess (direct access)
20150706|11:26:14|Running extract
20150706|11:26:14|Extractor tick
20150706|11:26:20|Extractor tick
20150706|11:26:20|Running dbrestore
20150706|11:26:20|Processing database site
20150706|11:26:20|Trying to retrieve installation/sql/site.sql using direct access
20150706|11:26:20|Importing site.sql to database...
20150706|11:26:20|PHP WARNING on line 231 in file /Users/nclifton/Documents/Marseclipse/workspace/reaxml-import/REAXMLImport/tests/unite/engine/driver/mysql.php:
20150706|11:26:20|mysql_query() expects parameter 2 to be resource, boolean given
20150706|11:26:20|PHP WARNING on line 235 in file /Users/nclifton/Documents/Marseclipse/workspace/reaxml-import/REAXMLImport/tests/unite/engine/driver/mysql.php:
20150706|11:26:20|mysql_errno() expects parameter 1 to be resource, boolean given
20150706|11:26:20|PHP WARNING on line 236 in file /Users/nclifton/Documents/Marseclipse/workspace/reaxml-import/REAXMLImport/tests/unite/engine/driver/mysql.php:
20150706|11:26:20|mysql_error() expects parameter 1 to be resource, boolean given
20150706|11:26:20|PHP WARNING on line 231 in file /Users/nclifton/Documents/Marseclipse/workspace/reaxml-import/REAXMLImport/tests/unite/engine/driver/mysql.php:
20150706|11:26:20|mysql_query() expects parameter 2 to be resource, boolean given
20150706|11:26:20|PHP WARNING on line 235 in file /Users/nclifton/Documents/Marseclipse/workspace/reaxml-import/REAXMLImport/tests/unite/engine/driver/mysql.php:
20150706|11:26:20|mysql_errno() expects parameter 1 to be resource, boolean given
20150706|11:26:20|PHP WARNING on line 236 in file /Users/nclifton/Documents/Marseclipse/workspace/reaxml-import/REAXMLImport/tests/unite/engine/driver/mysql.php:
20150706|11:26:20|mysql_error() expects parameter 1 to be resource, boolean given
20150706|11:26:20|SQL error dropping #__ak_profiles:  SQL=DROP TABLE IF EXISTS `jos_ak_profiles`;
20150706|11:26:20|### Finished job #1 ###
20150706|11:26:20|Running postbanner
20150706|11:26:20|===============================================================================
20150706|11:26:20|UNITE finished its run cycle
20150706|11:26:20|Total definitions found                : 1
20150706|11:26:20|Total definitions executed successfuly : 0
20150706|11:26:20|Total definitions failed to run        : 1
