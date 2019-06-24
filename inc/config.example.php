<?php
define('DATABASE_TYPE', 'mysql'); //mysql or sqlite3

// useless if sqlite3 is selected
define('MYSQL_HOST', 'localhost');
define('MYSQL_DATABASE', 'url');
define('MYSQL_USER', 'url');
define('MYSQL_PASSWORD', '******');

//useless if mysql is selected
define('SQLITE3_FILE', './database.sqlite3');

define('DEFAULT_URL', 'https://azlux.fr'); // omit the trailing slash!
define('URL_SIZE', 5); // The lenght of your short created links
define('WEB_THEME', 'dark'); // dark or light
define('PUBLIC_INSTANCE', 'false'); // true to allow no connected people to create short url. The admin can see no connected short URL created.
define('ALLOW_SIGNIN', 'false'); // true to allow people to signin on there own, the first user created will be an admin.

?>
