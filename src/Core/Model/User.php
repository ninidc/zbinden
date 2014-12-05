<?php
//--------------------------------------------------------------//
//              User Model
//--------------------------------------------------------------//
namespace Core\Model;

use Core\Model;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class User extends Model {

    public $user_id;
    public $username;
    public $password;
    public $email;
    public $firstname;
    public $lastname;
    public $roles = "USER";

    public static $table = "users";
    public static $index = "user_id";

  	public function __construct($data = array()) 
    {
        if(!empty($data))
        {
            $this->fromArray($data);
        }
    }

    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('email', new Assert\Email());
        $metadata->addPropertyConstraint('username', new Assert\NotBlank());
    }


    public function encodePasswd($passwd) 
    {
        global $app;

        $encoder = new MessageDigestPasswordEncoder();

        return $encoder->encodePassword($passwd, '');
    }



    public function beforeUpdate()
    {
        if(trim($this->password) == "" || trim($this->password) == null) {
            unset($this->password);
        } else {
            $this->password = $this->encodePasswd($this->password);
        }
    }


    public function beforeCreate()
    {       
        $this->password     = $this->encodePasswd($this->password);
    }

}
//--------------------------------------------------------------//

?>