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

    static public function findBySlug($slug) 
    {

        global $app;
        
        $className = get_called_class();

        $sql = "
            SELECT 
                * 
            FROM 
                categories 
            WHERE 
                slug = '".$slug."'
        ";

        $result = $app['db']->fetchAll($sql);
        $data = array();

        if(sizeof($result) == 1) {
            return new $className($result[0]);
        } else {
            foreach($result as $r) {
                $data[] = new $className($r);
            }
        }
        
        return $data;
    }


    public function fetchPages($options  = array()) 
    {
        return Page::fetchAll(array_merge(array(
            "WHERE" => array(
                "category_id" => $this->category_id
            )
        ), $options));
    }

}
//--------------------------------------------------------------//
?>