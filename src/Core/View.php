<?php
//--------------------------------------------------------------//
//              View
//  Use Handlebars for render HTML templates
//  https://github.com/XaminProject/handlebars.php
//--------------------------------------------------------------//
namespace Core;

class View
{

	public $handlebars = null;

    public function __construct($template_folder, $options = array('prefix' => ''))
    {
    	$loader 			= new \Handlebars\Loader\FilesystemLoader($template_folder);
		$partials_loader 	= new \Handlebars\Loader\FilesystemLoader($template_folder, $options);

    	$this->handlebars  = new \Handlebars\Handlebars(array(
		    'loader' 			=> $loader,
		    'partials_loader' 	=> $partials_loader
		));

        // FIXME : sale ! :)
        $this->handlebars->addHelper("ifCond", function($template, $context, $args, $source) 
        {

            $args = explode(' ', $args);

            $a = $context->get($args[0]);
            $b = $context->get($args[2]);

            if(!$b) {
                $b = $args[2];
            }

            $operator = $args[1];

            $template->setStopToken('else');

            switch($operator) {
                case '==':
                    if($a == $b) {
                        $buffer = $template->render($context);
                        $template->setStopToken(false);
                        $template->discard($context);
                    } else {
                        $template->discard($context);
                        $template->setStopToken(false);
                        $buffer = $template->render($context);
                    }
                break;

                case '!=':
                    if($a != $b) {
                        $buffer = $template->render($context);
                        $template->setStopToken(false);
                        $template->discard($context);
                    } else {
                        $template->discard($context);
                        $template->setStopToken(false);
                        $buffer = $template->render($context);
                    }
                break;
            }

            return $buffer;
        });

    }


    


    /**
    *   Render template
    *   $template : template path
    *   $data : Array of data
    */
    public function render($template, $data = array())
    {
        $format = isset($_GET["format"]) ? $_GET["format"] : null;

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
// IfCond Helper
$app["handlebars"]->addHelper('ifCond', function($template, $context, $args, $source) {

    $args = explode(' ', $args);

    $a = $context->get($args[0]);
    $b = $context->get($args[2]);

    if(!$b) {
        $b = $args[2];
    }

    $operator = $args[1];

    $template->setStopToken('else');

    switch($operator) {
        case '==':
            if($a == $b) {
                $buffer = $template->render($context);
                $template->setStopToken(false);
                $template->discard($context);
            } else {
                $template->discard($context);
                $template->setStopToken(false);
                $buffer = $template->render($context);
            }
        break;

        case '!=':
            if($a != $b) {
                $buffer = $template->render($context);
                $template->setStopToken(false);
                $template->discard($context);
            } else {
                $template->discard($context);
                $template->setStopToken(false);
                $buffer = $template->render($context);
            }
        break;
    }

    return $buffer;
});


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