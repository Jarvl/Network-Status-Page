<?php
define('ROOT_DIR', dirname(__FILE__));
define('ROOT_URL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(ROOT_DIR))));

//path to config file, recommend you place it outside of web root (Currently referencing to one directory up from web root)
$json = file_get_contents(ROOT_DIR . "/../config.json");
// Strip comments from json file
$json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $json);
$GLOBALS["config"] = json_decode($json, true);

require_once("assets/php/functions.php");
?>
