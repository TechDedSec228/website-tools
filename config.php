<?PHP

define('SERV_ROOT','/var/www/html/');

// set Pear directory
ini_set("include_path", (SERV_ROOT."PEAR/" . ini_get("include_path")));

// database connection constants
define('DB_HOST','localhost');
define('DB_NAME','cmtools');
define('DB_USERNAME','cmtools');
define('DB_PASSWORD','cmtoolslogin');
require_once(SERV_ROOT.'classes/database.php');

// load email classes
require_once('Mail.php');
require_once('Mail/mime.php');

$illegal_sites = array(
						'booksearch.google.com',
						'books.google.com',
						'news.google.com',
						'blogsearch.google.com',
						'maps.google.com',
						'images.google.com',
						);

$goog_data_centers = array(
						   '64.233.179.104',
						   '66.102.9.99',
						   '66.102.9.147',
						   '66.102.9.104',
						   '64.233.187.99',
						   '64.233.187.104',
						   '64.233.183.99',
						   '64.233.183.104',
						   '64.233.179.99',
						   '64.233.167.99',
						   '64.233.167.147',
						   '64.233.167.104',
						   '64.233.161.99',
						   '64.233.161.147',
						   '64.233.161.104',
						   '216.239.59.99',
						   '216.239.59.147',
						   '216.239.59.104',
						   '216.239.59.103',
						   );
?>