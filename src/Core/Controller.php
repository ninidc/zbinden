<?php

namespace Core;

use Silex\Application;
use Core\Controller\Component;
use Symfony\Component\Security\Core\SecurityContext;

/**
 *  Base Controller class
 */
abstract class Controller
{
    /**
     * @var \Silex\Application
     */
    protected $app;
    protected $Session;

    abstract public function initialize();

    public function __construct(Application $app)
    {

        $this->app      = $app;
        $this->Session  = new Controller\Component\Session();

        $this->initialize();
    }


    /**
    *   Validate model object
    *   return false if no errors
    */
    public function validate($object)
    {
        // Check validate data
        $errors = $this->app['validator']->validate($object);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                return $error->getMessage()." : ".$error->getPropertyPath();
            }
        }

        return false;
    }
}

?>