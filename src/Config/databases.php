<?php
// DB
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'     => 'localhost',
        'dbname'   => 'zbinden',
        'user'     => 'root',
        'password' => 'spud83',
        'charset'   => 'utf8',
        'driverOptions' => array(
                1002 =>'SET NAMES utf8'
        )
    )
));
?>