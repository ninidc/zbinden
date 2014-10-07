<?php
//--------------------------------------------------------------//
//              MEDIA CONTROLLER
//--------------------------------------------------------------//
namespace Core\Controller;

use Core\Controller;
use Core\View;
use Core\Model\User;
use Symfony\Component\HttpFoundation\Request;

class AdminMedia extends Controller
{

    public function initialize() 
    {
        $this->View = new View($this->app["template_folder"]);
    }

    public function index()
    {
        return $this->View->render("Admin/index", array(
        	"TITLE" => "DASHBOARD"
        ));
    }

}
//--------------------------------------------------------------//
?>