<?php

use vendor\core\Router;

$query = rtrim($_SERVER['QUERY_STRING'], '/');

define('DEBUG', 0);
define('WWW', __DIR__);
define('CORE', dirname(__DIR__) . '/vendor/core');
define('ROOT', dirname(__DIR__));
define('APP', dirname(__DIR__) . '/app');
define('CACHE', dirname(__DIR__) . '/tmp/cache');
define('LAYOUT', 'default');


require '../vendor/libs/functions.php';


spl_autoload_register(function($class) {
	$file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
	if(is_file($file)) {
		require_once $file;
	}
 });

new \vendor\core\App;

Router::add('^(user/confirm/(?P<token>[a-z0-9A-Z]+))$', ['controller' => 'User', 'action' => 'confirm']);

Router::add('^(user/reset/(?P<token>[a-z0-9A-Z]+))$', ['controller' => 'User', 'action' => 'reset']);
Router::add('^admin$', ['controller' => 'User', 'action' => 'index', 'prefix' => 'admin']);
Router::add('^admin/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$', ['prefix' => 'admin']);
Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

Router::dispatch($query);

?>
