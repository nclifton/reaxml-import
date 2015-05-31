<?php die(); ?>
Version 1.4.2
--------------------------------------------------------------------------------
# [HIGH] restore.php was not included in the package

Version 1.4.1
--------------------------------------------------------------------------------
~ Updated restore.php to latest version

Version 1.4.0
--------------------------------------------------------------------------------
+ dbport key to define the database server port
# [MEDIUM] Remote backup support no longer works
# [LOW] --script parameter not honoured

Version 1.3.0
--------------------------------------------------------------------------------
+ Support for JPS archives (encrypted backup backup archives)

Version 1.2.3
--------------------------------------------------------------------------------
+ Download backup archives from Amazon S3

Version 1.2.2
--------------------------------------------------------------------------------
~ Support for backups taken with Akeeba Backup 3.10.2 or later

Version 1.2.1
--------------------------------------------------------------------------------
# [HIGH] Running multiple jobs in the same run results in only the first job executing
# [MEDIUM] The mailfrom and fromname are swapped
# [LOW] MySQL connection could close while restoring, leading to failed restoration
# [LOW] Temporary files could get left behind

Version 1.2
--------------------------------------------------------------------------------
~ Updated restore.php
# [HIGH] Remote backup not working
# [HIGH] Stealth did not work
# [MEDIUM] The configuration.php file would not be read correctly for Joomla! 2.5 sites
# [LOW] Wrong count in "Total definitions failed to run"
# [LOW] Fixed some PHP notices
