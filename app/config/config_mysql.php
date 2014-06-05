<?php

define("DB_PASSWORD", "61YwaL5.");
define("DB_USER", "nise14");

return [
    'dsn'     => "mysql:host=blu-ray.student.bth.se;dbname=nise14;",
    'username'        => DB_USER,
    'password'        => DB_PASSWORD,
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'    => "phpmvc2_",
    'verbose' => true,
    //'debug_connect' => 'true',
];
