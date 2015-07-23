<?php
class UConfig
{
	// Should I log everything to the (per run) Global Log?
	const globalLogging	= '1';
	
	// Should I print everything to terminal?
	const verboseOutput = '1';

	// Temporary directory
	const tempDir = '/Users/nclifton/git/reaxml-import/REAXMLImport/tests/unite/tmp';
	
	// Logs directory
	const logDir = '/Users/nclifton/git/reaxml-import/REAXMLImport/tests/unite/log';	
	
	// Inbox directory
	const inboxDir = '/Users/nclifton/git/reaxml-import/REAXMLImport/tests/unite/inbox';	
	
	// When true, XML files are not erased.
	const keepxml = true;
	
	// Email of system operator
	const sysop_email = 'neil.clifton@cliftonwebfoundry.com.au';
}