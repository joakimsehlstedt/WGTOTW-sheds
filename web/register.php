<?php

/**
 * This is a Anax pagecontroller.
 *
 */
// Get environment & autoloader and the $app-object.
require __DIR__ . '/config.php';

// Create services.
$di = new \Anax\DI\CDIFactoryDefault();

$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/config_sqlite.php');
    $db->connect();
    return $db;
});

$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});

$di->set('form', '\Mos\HTMLForm\CForm');

// Inject services into the app. 
$app = new \Anax\Kernel\CAnax($di);

// Start session
$app->session;

// Set default page title
$app->theme->setTitle("default pageview");

// Set configuration for theme
$app->theme->configure(ANAX_APP_PATH . 'config/theme_login.php');

$app->dispatcher->forward([
    'controller' => 'users',
    'action' => 'add',
]);

// Render the response using theme engine.
$app->theme->render();