<?php
//--------------------------------------------------------------//
//              MEDIA CONTROLLER
//--------------------------------------------------------------//
namespace Core\Controller;

use Core\Controller;
use Core\View;
use Core\Model\Media;
use Symfony\Component\HttpFoundation\Request;

class AdminMedia extends Controller
{

    public function initialize() 
    {
        $this->View = new View($this->app["template_folder"]);
    }

    public function index()
    {

        return $this->View->render("Admin/media_index", array(
        	"TITLE" => "Médias",
            "MEDIAS" => Media::fetchAll()
        ));
    }

    public function edit($id)
    {
        $Media = Media::find($id);

        return $this->View->render("Admin/media_edit", array(
            "TITLE" => "Médias",
            "MEDIA" => $Media
        ));
    }

    public function delete($id)
    {
        Media::delete($id);

        $url = $this->app['url_generator']->generate('admin.medias.index');

        return $this->app->redirect($url);
    }

    public function save(Request $request)
    {

        $file   = isset($_FILES["file"]) ? $_FILES["file"] : null;
        $format = isset($_GET["format"]) ? $_GET["format"] : null;

        $Media = new Media(array(
            "media_id"  => $request->get('media_id'),
            "file"      => uniqid(rand(), false) . "." . pathinfo($file["name"], PATHINFO_EXTENSION),
            "filename"  => $file["name"],
            "tmp_name"  => $file["tmp_name"],
            "title"     => $request->get('title')
        ));

         // Call validator for validate the model
        $error = $this->validate($Media);

        if(!$error)  {
            try {
                if($Media->save()) {

                    $this->Session->setNotification("Enregistrement effectué avec succès.");

                    // FIXME : essayer de gérer ça avec la vue...
                    switch($format) {
                        case "json":
                            return json_encode($Media->toArray());
                        break;

                        default:
                            $url = $this->app['url_generator']->generate('admin.medias.edit', array(
                                'id' => $Media->media_id
                            ));
                            
                            return $this->app->redirect($url);
                        break;
                    }
                    
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        return $this->View->render("Admin/media_edit", array(
            "TITLE"     => "MEDIAS",
            "MEDIA"     => $Media,
            "ERROR"     => $error
        ));
    }

}
//--------------------------------------------------------------//
?>