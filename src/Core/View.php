<?php
//--------------------------------------------------------------//
//              View
//  Use Handlebars for render HTML templates
//  https://github.com/XaminProject/handlebars.php
//--------------------------------------------------------------//
namespace Core;

use Core\Model\Page;
use Core\Model\Category;

class View
{

	public $handlebars = null;
    public $helpers = null;

    public function __construct($template_folder, $options = array('prefix' => ''))
    {
    	$loader 			= new \Handlebars\Loader\FilesystemLoader($template_folder);
		$partials_loader 	= new \Handlebars\Loader\FilesystemLoader($template_folder, $options);

    	$this->handlebars  = new \Handlebars\Handlebars(array(
		    'loader' 			=> $loader,
		    'partials_loader' 	=> $partials_loader
		));

        $this->initHelpers();
    }


    public function initHelpers()
    {
        $this->handlebars->addHelper("ifCond", new View\Component\Handlebars\Helpers\ifCond());
        $this->handlebars->addHelper("getPagesFromCategory", new View\Component\Handlebars\Helpers\getPagesFromCategory());
        $this->handlebars->addHelper("getPages", new View\Component\Handlebars\Helpers\getPages());
    }


    /**
    *   Render template
    *   $template : template path
    *   $data : Array of data
    */
    public function render($template, $data = array())
    {
        global $app;

        $format = isset($_GET["format"]) ? $_GET["format"] : null;

        // FIXME : pas très propre :)
        $url = explode("/", $_SERVER["REQUEST_URI"]);

        if(isset($url[1])) {
            if($url[1] == "admin") {
                $data["ADMIN_SECTION"] = isset($url[2]) ? strtolower($url[2]) : null;
                $data["SESSION"] = array(
                    "USERNAME" => $app['security']->getToken()->getUsername()
                );
            }
        }

        switch($format) {
            case "json":
                return json_encode($data);
            break;

            default:
                return $this->handlebars->render($template, $data);
            break;
        }
    	
    }

}
//--------------------------------------------------------------//


//--------------------------------------------------------------//
//  HELPERS TO IMPLEMENT
//--------------------------------------------------------------//
/*

//
//  FIXME : revoir completement l'algo, utiliser les REGEX au lieu d'explode.
//  La syntaxe est la suivante : {{url route {params1: value, params2: value}}}
//  Note : essayer de trouver une syntaxe plus cool :)
//
$app["handlebars"]->addHelper('url', function($template, $context, $args, $source) {

    global $app;

    $args   = explode(' ', $args);
    $route  = isset($args[0]) ? trim($args[0]) : null;
    
    $params = null;
    foreach($args as $index=>$arg) {
        if($index > 0) {
            $params .= $arg . " "; 
        }
    }
    
    $params = explode(",", substr(trim($params), 1, strlen($params)));
    $routesParams = array();

    foreach($params as $p) {
        $values = explode(':', trim($p));
        
        $key    = isset($values[0]) ? trim($values[0]) : null;
        $value  = isset($values[1]) ? $context->get(trim($values[1])) : null;

        $routesParams[$key] = $value;
    }

    return $app['url_generator']->generate($route, $routesParams);
});
*/
?>