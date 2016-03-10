<?php
use Cake\Routing\Router;

Router::scope('/', ['plugin' => 'Platform'], function ($routes) {
    $routes->connect('/', ['controller' => 'Basic', 'action' => 'info']);
});

/* Admin routes */
Router::prefix('admin', function($routes) {
	//FronEngine custom routes
	//$routes->connect('/menus', ['plugin' => 'FrontEngine', 'controller' => 'Menus', 'action' => 'index']);
	//$routes->connect('/settings', ['plugin' => 'RearEngine', 'controller' => 'Settings', 'action' => 'index']);
});
