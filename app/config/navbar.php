<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'pure-menu pure-menu-open pure-menu-horizontal',
    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home' => [
            'text' => '<i class="fa fa-home"></i> Home',
            'url' => '',
            'title' => 'Home'
        ],
        'questions' => [
            'text' => '<i class="fa fa-stack-exchange"></i> Questions',
            'url' => 'comment/view-questions',
            'title' => 'Questions'
        ],
        'tags' => [
            'text' => '<i class="fa fa-tags"></i> Tags',
            'url' => 'comment/tags',
            'title' => 'tags'
        ],
        'users' => [
            'text' => '<i class="fa fa-users"></i> Users',
            'url' => 'users/list',
            'title' => 'users'
        ],
        'myprofile' => [
            'text' => '<i class="fa fa-user"></i> My Profile',
            'url' => 'users/id/' . $_SESSION['authenticated']['user']->id,
            'title' => 'myprofile'
        ],
        'about' => [
            'text' => '<i class="fa fa-info-circle"></i> About',
            'url' => 'about',
            'title' => 'about'
        ],
        'logout' => [
            'text' => '<i class="fa fa-sign-out"></i> Logout',
            'url' => 'users/logout',
            'title' => 'logout'
        ],
    ],

    // Callback tracing the current selected menu item base on scriptname
    'callback' => function($url) {
        if ($url == $this->di->get('request')->getRoute()) {
            return true;
        }
    },
    // Callback to create the urls
    'create_url' => function($url) {
        return $this->di->get('url')->create($url);
    },
];
