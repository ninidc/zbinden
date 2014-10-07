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
        $metadata->addPropertyConstraint('username', new Assert\NotBlank());
        $metadata->addPropertyConstraint('password', new Assert\NotBlank());
    }


    public function encodePasswd($passwd) 
    {
        global $app;

        $encoder = new MessageDigestPasswordEncoder();

        return $encoder->encodePassword($passwd, '');
    }


    public function beforeCreate()
    {        
        $this->passwd = $this->encodePasswd($this->passwd);
    }

}
//--------------------------------------------------------------//

?>