<?php
//--------------------------------------------------------------//
//              Page Model
//--------------------------------------------------------------//
namespace Core\Model;

use Core\Model;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class Page extends Model {

    public $page_id;
    public $title;
    public $slug;
    public $date;
    public $category_id;
    public $content;
    public $status = "draft";
    
    public static $table = "pages";
    public static $index = "page_id";

  	public function __construct($data = array()) 
    {
        if(!empty($data))
        {
            $this->fromArray($data);
        }
    }

    public function beforeCreate() 
    {
        $this->date = date("Y-m-d H:i:s");
    }

    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('slug', new Assert\NotBlank());
    }

}
//--------------------------------------------------------------//

?>