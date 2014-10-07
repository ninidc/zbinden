<?php
//--------------------------------------------------------------//
//              Admin users controller
//--------------------------------------------------------------//
namespace Core\Controller;

use Core\Controller;
use Core\Model\User;
use Core\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class AdminUser extends Controller
{

    public function initialize()
    {
        $this->View = new View($this->app["template_folder"]);
    }

    public function index()
    {
        $data = array(
            "USERS" => User::fetchAll()
        );

        return $this->View->render("Admin/user_index", $data);
    }

    public function edit($id = null)
    {
        $data = array(
            "USER" => User::find($id),
            "MESSAGE" => $this->Session->getNotification()
        );

        return $this->View->render("Admin/user_edit", $data);
    }


    public function delete($id = null)
    {
        User::delete($id);

        return $this->app->redirect($this->app['url_generator']->generate('admin.users.index'));
    }


    public function save(Request $request)
    {
        global $app;

        $encoder = new MessageDigestPasswordEncoder();

        $User = new User(array(
            "user_id"   => $request->get('user_id'),
            "username"  => $request->get('username'),
            "password"  => $encoder->encodePassword($request->get('passwd'), ''),
        ));

        // Call validator for validate the model
        $error = $this->validate($User);

        if(!$error) {
            try {
                if($User->save()) {
                    $this->Session->setNotification("Enregistrement effectué avec succès.");

                    $url = $this->app['url_generator']->generate('admin.users.edit', array(
                        'id' => $User->user_id
                    ));
                    
                    return $this->app->redirect($url);
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        } 

        return $this->View->render("Admin/user_edit", $data = array(
            "USER"  => $User,
            "ERROR" => $error
        ));
    }

}
//--------------------------------------------------------------//
?>