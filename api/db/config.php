<?php
#MySQL Database name:
define('DB_NAME', 'test_db');

#MySQL Database User Name:
define('DB_USER', 'root');

#MySQL Database Password:
define('DB_PASSWORD', 'password');

#MySQL Hostname:
// define('DB_HOST', '192.168.1.3:3306');
define('DB_HOST', '127.0.0.1:3306');
#Table Prefix:
define('PREFIX','');

#Session Timeout Time:
define('SESSION_TIMEOUT',360000);

#Major version:
define('VERSION','1.0');


?>
<?php
#Base Path :
#Please Do not change this absolute path.
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

?>
