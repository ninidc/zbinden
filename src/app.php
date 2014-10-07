<?php
//--------------------------------------------------------------//
//              ZBINDEN 
//      BY NDELCAST (ndelcast.dev@gmail.com)
//--------------------------------------------------------------//


//--------------------------------------------------------------//
//              DEPENDENCIES
//--------------------------------------------------------------//
use Silex\Application;
use Core\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File;
use Handlebars\Handlebars;
use Core\Model\Site;
use Core\Controller\Website;
//--------------------------------------------------------------//


//--------------------------------------------------------------//
//              SILEX APP
//--------------------------------------------------------------//
$app = new Application();
//--------------------------------------------------------------//


//--------------------------------------------------------------//
//              CONFIG
//--------------------------------------------------------------//
date_default_timezone_set("Europe/Paris"); 

$app['debug'] = true; // Debug mode

// Database(s) configuration
require("Config/databases.php");

$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
/*
$app->register(new Silex\Provider\RememberMeServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'default' => array(
            'pattern' => '^/play/',
            'form' => array(
                'login_path' => '/inscription', 
                'check_path' => '/play/signin'
            ),
            'logout' => array(
                'logout_path' => '/play/logout'
            ),
            'users' => $app->share(function() use ($app) {
                return new Core\Model\UserProvider($app['db']);
            }),
        ),
    ),
    'security.access_rules' => array(
        array('^/play$', 'USER'),
    )
));
*/
$app['upload_folder']       = __DIR__ . '/../web/uploads';
$app['sites_folder']        = __DIR__ . '/../web/sites/';
$app['template_folder']     = __DIR__ . '/Templates/';

$app->boot();
//--------------------------------------------------------------//


//--------------------------------------------------------------//
//              LOADING ROUTES AND INIT THEM
//--------------------------------------------------------------//
$Dispatcher = new Core\Dispatcher($app);

// Coudy routes are loaded by default.
require("Config/routes.php");  

$Dispatcher->setRoutes($routes);
//--------------------------------------------------------------//


//--------------------------------------------------------------//
//              Accepting a JSON Request Body
// (http://silex.sensiolabs.org/doc/cookbook/json_request_body.html)
//--------------------------------------------------------------//
$app->before(function(Request $request) {
    if(0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});
//--------------------------------------------------------------//


//--------------------------------------------------------------//
//              ERRORS
//--------------------------------------------------------------//
/*
$app->error(function (\Exception $e, $code) {

    global $app;

    switch ($code) {
        case 404:
            //return $app["handlebars"]->render("/Front/404");
        break;
    }
});
*/
//--------------------------------------------------------------//


//--------------------------------------------------------------//
//              RETURN APP...
//--------------------------------------------------------------//
return $app;
//--------------------------------------------------------------//

?>