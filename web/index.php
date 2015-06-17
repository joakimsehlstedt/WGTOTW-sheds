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

$di->set('CommentController', function() use ($di) {
    $controller = new \Anax\Comment\CommentDbController();
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

// Set routing options

$app->router->add('', function() use ($app) {

    $app->theme->setTitle("Home");

    // Set stats content of homepage
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action' => 'view-top-three',
    ]);

    // Set the text content of homepage
    $content = $app->fileContent->get('home.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $app->views->add('theme/page', [
        'content' => $content,
        ]);  
});

$app->router->add('about', function() use ($app) {

    $app->theme->setTitle("About");

    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $app->views->add('theme/page', [
        'content' => $content,
        ]);  
});

$app->router->add('questions', function() use ($app) {

    $app->theme->setTitle('Questions'); 

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action' => 'view',
    ]);
});

// Router

if (isset($_SESSION['authenticated']['valid'])) {

    $app->router->handle();

    // Set configuration for theme
    $app->theme->configure(ANAX_APP_PATH . 'config/theme.php');

    // Navigation
    $app->navbar->configure(ANAX_APP_PATH . 'config/navbar.php');

} else {

    // Set configuration for theme
    $app->theme->configure(ANAX_APP_PATH . 'config/theme_login.php');

    $app->dispatcher->forward([
        'controller' => 'users',
        'action' => 'login',
    ]);

}

// Render the response using theme engine.
$app->theme->render();
