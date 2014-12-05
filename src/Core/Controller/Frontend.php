<?php
//--------------------------------------------------------------//
//              FRONT CONTROLLER
//--------------------------------------------------------------//
namespace Core\Controller;

use Core\Controller;
use Core\View;
use Core\Model\Meta;
use Core\Model\Page;
use Core\Model\Category;
use Symfony\Component\HttpFoundation\Request;

class Frontend extends Controller
{

    public function initialize() 
    {
        $this->View = new View($this->app["template_folder"]);
    }

    public function index()
    {
        return $this->View->render("Front/index");
    }


    public function page($slug, $id)
    {
        return $this->View->render("Front/page", array(
        	"PAGE" => Page::find($id),
        	"MENU" => $this->buildMenu()
        ));
    }

    public function category($slug)
    {
    	return $this->View->render("Front/index", array(
        	"CATEGORY" 	=> Category::findBySlug($slug),
        	"MENU" 		=> $this->buildMenu()
        ));
    }


    public function buildMenu()
    {
    	$MenuData = array();
    	$MetaMenu = Meta::findByKey("menu");

    	// FIXME : corriger ce probleme...
    	if(sizeof($MetaMenu) == 1) {
    		$MetaMenu = $MetaMenu[0];
    	}

    	$menu = array();
    	foreach(json_decode($MetaMenu->data, true) as $item) {
    		
    		$item 	= json_decode($item, true);

    		$type 	= isset($item["type"]) ? $item["type"] : null;
    		$label 	= isset($item["label"]) ? $item["label"] : null;
    		$id 	= isset($item["id"]) ? $item["id"] : null;

    		switch($type) {
    			case "page":
    				$Page = Page::find($id);

    				$url = $this->app['url_generator']->generate('front.page', array(
    					'slug' => $Page->slug,
                        'id' => $Page->page_id
                    ));

    				$menu[] = array(
    					"url" 	=> $url,
    					"label" => $label
    				);
    			break;

    			case "category":
    				$Category = Category::find($id);

    				$url = $this->app['url_generator']->generate('front.category', array(
    					'slug' 	=> $Category->slug
                    ));

    				$menu[] = array(
    					"url" 	=> $url,
    					"label" => $label
    				);
    			break;
    		}

    	}
    	return $menu;

    }

}
//--------------------------------------------------------------//
?>