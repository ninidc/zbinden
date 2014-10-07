<?php
//--------------------------------------------------------------//
//              Category Model
//--------------------------------------------------------------//
namespace Core\Model;

use Core\Model;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class Category extends Model {

    public $category_id;
    public $name;
    public $slug;
    public $count = 0;
    public $parent_id;
    
    public static $table = "categories";
    public static $index = "category_id";

  	public function __construct($data = array()) 
    {
        if(!empty($data))
        {
            $this->fromArray($data);
        }
    }

    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
    }

}
//--------------------------------------------------------------//
?>