<?php
define('ROOT_DIR', dirname(__FILE__));
define('ROOT_URL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(ROOT_DIR))));

//path to config file, recommend you place it outside of web root (Currently referencing to one directory up from web root)
$config_path = '../config.ini';
// Import config file into globals
$GLOBALS['config'] = parse_ini_file($config_path);

include_once("assets/php/functions.php");
?>