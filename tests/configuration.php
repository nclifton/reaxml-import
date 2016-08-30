<?php
/**
 * @package    Joomla.Installation
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * -------------------------------------------------------------------------
 * THIS SHOULD ONLY BE USED AS A LAST RESORT WHEN THE WEB INSTALLER FAILS
 *
 * If you are installing Joomla! manually i.e. not using the web browser installer
 * then rename this file to configuration.php e.g.
 *
 * UNIX -> mv configuration.php-dist configuration.php
 * Windows -> rename configuration.php-dist configuration.php
 *
 * Now edit this file and configure the parameters for your site and
 * database.
 */
class JConfig
{
	/* Site Settings */
	public $offline = '0';
	public $offline_message = 'This site is down for maintenance.<br /> Please check back again soon.';
	public $display_offline_message = '1';
	public $offline_image = '';
	public $sitename = 'reaxml';            // Name of Joomla site
	public $editor = 'tinymce';
	public $captcha = '0';
	public $list_limit = '20';
	public $access = '1';

	/* Database Settings */
	public $dbtype = 'mysqli';               // Normally mysqli
	public $host = 'joomla.box:3306';              // This is normally set to localhost
	public $user = 'root';                       // DB username
	public $password = 'root';                   // DB password
	public $db = 'sites_reaxml';                         // DB database name
	public $dbprefix = 'j_';               // Do not change unless you need to!

	/* Server Settings */
	public $secret = 'x3KklRRisy4JrrVH';     // Change this to something more secure
	public $gzip = '0';
	public $error_reporting = 'default';
	public $helpurl = 'https://help.joomla.org/proxy/index.php?keyref=Help{major}{minor}:{keyref}';
	public $ftp_host = 'localhost:3306';
	public $ftp_port = '';
	public $ftp_user = 'root';
	public $ftp_pass = '';
	public $ftp_root = '';
	public $ftp_enable = '';
	public $tmp_path = '/var/www/reaxml/tmp/';                // Please check with your host that this is the correct path to the temp directory. This path needs to be writable by Joomla!
	public $log_path = '/var/www/reaxml/logs/';           // Please check with your host that this is the correct path to the logs directory. This path needs to be writable by Joomla!
	public $live_site = '';                   // Optional, full url to Joomla install.
	public $force_ssl = 0;                    // Force areas of the site to be SSL ONLY.  0 = None, 1 = Administrator, 2 = Both Site and Administrator

	/* Locale Settings */
	public $offset = 'UTC';

	/* Session settings */
	public $lifetime = '600';                  // Session time
	public $session_handler = 'database';

	/* Mail Settings */
	public $mailer = 'smtp';
	public $mailfrom = 'website@reaxmltest';
	public $fromname = 'REAXML TEST SITE';
	public $sendmail = '/usr/bin/env catchmail';
	public $smtpauth = '0';
	public $smtpuser = '';
	public $smtppass = '';
	public $smtphost = 'localhost';

	/* Cache Settings */
	public $caching = '0';
	public $cachetime = '15';
	public $cache_handler = 'file';
	public $cache_platformprefix = '0';

	/* Debug Settings */
	public $debug = '1';
	public $debug_lang = '0';

	/* Meta Settings */
	public $MetaDesc = 'Joomla! - the dynamic portal engine and content management system';
	public $MetaKeys = 'joomla, Joomla';
	public $MetaTitle = '1';
	public $MetaAuthor = '1';
	public $MetaVersion = '0';
	public $robots = '';

	/* SEO Settings */
	public $sef = '1';
	public $sef_rewrite = '1';
	public $sef_suffix = '0';
	public $unicodeslugs = '1';

	/* Feed Settings */
	public $feed_limit = 10;
	public $feed_email = 'none';
	public $smtpsecure = 'none';
	public $smtpport = '1025';
}