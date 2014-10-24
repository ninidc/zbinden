<?php
//--------------------------------------------------------------//
//              ADMIN CONTROLLER
//--------------------------------------------------------------//
namespace Core\Controller;

use Core\Controller;
use Core\View;
use Core\Model\User;
use Core\Model\Page;
use Core\Model\Meta;
use Core\Model\Category;
use Symfony\Component\HttpFoundation\Request;

class AdminPage extends Controller
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
            "ORDER" => "date DESC"
        );

        $Paginator = new Controller\Component\Paginator(array(
            "total_rows"    => Page::count($options),
            "per_page"      => $per_page,
            "page_current"  => $page_current,
            "url_type"      => "param"
        ));

        $Pages = Page::fetchAll($options);

        foreach($Pages as $index => $Page) {
            $Pages[$index]->date = date("d/m/Y", strtotime($Page->date));
        }

        $data = array(
            "PAGES"             => $Pages,
            "PAGINATOR"         => $Paginator->toArray(),
            "TITLE"             => "Pages"
        );

        return $this->View->render("Admin/page_index", $data);
    }


    public function edit($id = null)
    {
        $Page = Page::find($id);

        $data = array(
            "PAGE"      => $Page,
            "CATEGORIES"    => Category::fetchAll(),
            "MESSAGE"   => $this->Session->getNotification(),
            "TITLE"     => isset($Page->title) ? $Page->title : "Nouvelle page"
        );

        return $this->View->render("Admin/page_edit", $data);
    }

    public function saveMedias(Request $request, $Page) 
    {
        $keys = $request->get('meta-key');
        $data = $request->get('meta-data');

        foreach($keys as $index=>$key) {
            $Meta = new Meta(array(
                "mkey"          => $key,
                "data"          => $data[$index],
                "field_name"    => "page_id",
                "field_id"      => $Page->page_id
            ));

            $Meta->save();
        }
        
    }


    public function save(Request $request)
    {

        $Page = new Page(array(
            "page_id"       => $request->get('page_id'),
            "title"         => $request->get('title'),
            "slug"          => $request->get('slug'),
            "content"       => $request->get('content'),
            "date"          => $request->get('date'),
            "status"        => $request->get('status'),
            "category_id"   => $request->get('category_id')
        ));


        if(trim($Page->slug) == "") {
            $Page->slug = Entrie::slugify($Page->title);
        }

         // Call validator for validate the model
        $error = $this->validate($Page);

        if(!$error)  {
            try {
                if($Page->save()) {

                    $this->saveMedias($request, $Page);

                    $this->Session->setNotification("Enregistrement effectué avec succès.");

                    $url = $this->app['url_generator']->generate('admin.page.edit', array(
                        'id' => $Page->page_id
                    ));
                    
                    return $this->app->redirect($url);
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        return $this->View->render("Admin/page_edit", array(
            "CATEGORIES"    => Category::fetchAll(),
            "TITLE"     => $Page->title,
            "PAGE"      => $Page,
            "ERROR"     => $error
        ));
    }

}
//--------------------------------------------------------------//
?>