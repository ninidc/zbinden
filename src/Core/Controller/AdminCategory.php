<?php
//--------------------------------------------------------------//
//              ADMIN CONTROLLER
//--------------------------------------------------------------//
namespace Core\Controller;

use Core\Controller;
use Core\View;
use Core\Model\Category;
use Core\Model\Meta;
use Core\Model\Page;
use Symfony\Component\HttpFoundation\Request;

class AdminCategory extends Controller
{

    public function initialize() 
    {
        $this->View = new View($this->app["template_folder"]);
    }


    public function index()
    {
        $page_current   = isset($_GET["page"]) ? $_GET["page"] : 1;        
        $per_page       = 50;
        $row            = ($page_current * $per_page) - $per_page;

        $options = array(
            "LIMIT" => "$row, 50",
            "ORDER" => "category_id DESC"
        );

        $Paginator = new Controller\Component\Paginator(array(
            "total_rows"    => Category::count($options),
            "per_page"      => $per_page,
            "page_current"  => $page_current,
            "url_type"      => "param"
        ));

        $Categories = array();

        foreach(Category::fetchAll($options) as $index => $Category) {
            $Categories[$index]             = $Category->toArray();
            $Categories[$index]["parent"]   = Category::find($Category->parent_id);
            $Categories[$index]["count"]    = Page::count(array(
                "WHERE" => array(
                    "category_id" => $Category->category_id
                )
            ));
        }

        $data = array(
            "CATEGORIES"        => $Categories,
            "PAGINATOR"         => $Paginator->toArray(),
            "TITLE"             => "Catégories"
        );

        return $this->View->render("Admin/category_index", $data);
    }


    public function saveMetas(Request $request, $Category) 
    {
        // Deleting all metas before new save...
        Meta::deleteByNameAndId("category_id", $Category->category_id);

        $keys = $request->get('meta-key');
        $data = $request->get('meta-data');

        foreach($keys as $index=>$key) {
            if($key != null && $data[$index] != null) {
                
                $Meta = new Meta(array(
                    "mkey"          => $key,
                    "data"          => $data[$index],
                    "field_name"    => "category_id",
                    "field_id"      => $Category->category_id
                ));

                $Meta->save();
            }
        }
    }


    public function edit($id = null)
    {
        $Category = Category::find($id);

        $data = array(
            "CATEGORIES"    => Category::fetchAll(),
            "CATEGORY"      => $Category,
            "METAS"         => $Category->getParsedMetas(),
            "MESSAGE"       => $this->Session->getNotification(),
            "TITLE"         => isset($Category->name) ? $Category->name : "Nouvelle catégorie"
        );

        return $this->View->render("Admin/category_edit", $data);
    }



    public function save(Request $request)
    {

        $Category = new Category(array(
            "category_id"   => $request->get('category_id'),
            "name"          => $request->get('name'),
            "slug"          => $request->get('slug'),
            "parent_id"     => $request->get('parent_id')
        ));

        if(trim($Category->slug) == "") {
            $Category->slug = Category::slugify($Category->name);
        }

         // Call validator for validate the model
        $error = $this->validate($Category);

        // FIXME : Better to put it into the model ;)
        if($Category->category_id == $Category->parent_id) {
            $error = "une catégorie ne peut pas avoir elle même comme parent";
        }

        if(!$error)  {
            try {
                if($Category->save()) {

                    $this->saveMetas($request, $Category);

                    $this->Session->setNotification("Enregistrement effectué avec succès.");

                    $url = $this->app['url_generator']->generate('admin.categories.edit', array(
                        'id' => $Category->category_id
                    ));
                    
                    return $this->app->redirect($url);
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        return $this->View->render("Admin/category_edit", array(
            "CATEGORIES"    => Category::fetchAll(),
            "TITLE"         => $Category->name,
            "CATEGORY"      => $Category,
            "ERROR"         => $error
        ));
    }

}
//--------------------------------------------------------------//
?>