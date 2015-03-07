<?php

/*
Plugin Name: WP Symfony Services
Plugin URI: https://github.com/edgji/wp-symfony-services/
Description: A simple Symfony Proxy.
Author: Jose Eduardo Garcia
Version: 1.0.0
Author URI: https://github.com/edgji/
*/

$symfony_dir = 'symfony';
$service_uri = 'service';


define('WP_SYMFONY_ROOT', dirname(__FILE__));
require_once WP_SYMFONY_ROOT . '/symfony-loader.php';

define('SYMFONY_SERVICE_PATH', $service_uri);
$symfony_path = SymfonyLoader::determineAbsolutePath($symfony_dir ?: false);

define('SYMFONY_ABSPATH', $symfony_path);

$GLOBALS['symfony'] = SymfonyLoader::load();