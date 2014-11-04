<?php
//--------------------------------------------------------------//
//              Admin menu controller
//--------------------------------------------------------------//
namespace Core\Controller;

use Core\Controller;
use Core\View;

use Core\Model\Category;
use Core\Model\Page;
use Core\Model\Meta;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class AdminMenu extends Controller
{

    public function initialize()
    {
        $this->View = new View($this->app["template_folder"]);
    }


    private function getMetaMenu()
    {
        $MetaMenu = Meta::findByKey("menu");

        if(sizeof($MetaMenu) > 0) {
            return $MetaMenu[0];
        } 

        return  new Meta(array(
            "mkey" => "menu"
        ));
    }


    private function getArrayMenu()
    {
        $MetaMenu   = $this->getMetaMenu();
        $items      = json_decode($MetaMenu->data);
        $array      = array();

        if(sizeof($items) > 0) {
            foreach($items as $index => $item) {
                $array[$index] = json_decode($item, true);
                $array[$index]["json"] = $item;
            }
        }

        return $array;
    }


    public function index()
    {

        $arrayMenu = $this->getArrayMenu();

        $menuCategoriesId   = array();
        foreach($arrayMenu as $item) {
            if($item["type"] == "category") {
                $menuCategoriesId[] = $item["id"];
            }
        }


        $menuPagesId   = array();
        foreach($arrayMenu as $item) {
            if($item["type"] == "page") {
                $menuPagesId[] = $item["id"];
            }
        }

        // Categories
        $Categories         = Category::fetchAll();
        $itemsCategories    = array();
        $i                  = 0;
        foreach($Categories as $index => $Category)
        {
            if(!in_array($Category->category_id, $menuCategoriesId)) {
                $itemsCategories[$i] = array(
                    "type"  => "category",
                    "label" => $Category->name,
                    "id"    => $Category->category_id,
                );
                $itemsCategories[$i]["json"] = json_encode($itemsCategories[$i], true);
                $i++;
            }
        }

        // Pages
        $Pages      = Page::fetchAll();
        $itemsPages = array();
        foreach($Pages as $index => $Page)
        {
            if(!in_array($Page->page_id, $menuPagesId)) {
                $itemsPages[$i] = array(
                    "type"  => "page",
                    "label" => $Page->title,
                    "id"    => $Page->page_id,
                );
                $itemsPages[$i]["json"] = json_encode($itemsPages[$i], true);
                $i++;
            }
        }


        $data = array(
            "ITEMS_CATEGORIES"  => $itemsCategories,
            "ITEMS_PAGES"       => $itemsPages,
            "ITEMS_MENU"        => $arrayMenu
        );

        return $this->View->render("Admin/menu_index", $data);
    }


    public function save(Request $request)
    {

        $Menu = Meta::findByKey("menu");

        if(sizeof($Menu) > 0) {
            $Menu = $Menu[0];
        } else {
            $Menu = new Meta(array(
                "mkey" => "menu"
            ));
        }

        // Adding catégorie
        $items = $request->get('menu_item');
        
        $Menu->data = json_encode($items);
        
        if($Menu->save()) {
            $url = $this->app['url_generator']->generate('admin.menu.index');

            return $this->app->redirect($url);
        }
        
    }

}
//--------------------------------------------------------------//
?>