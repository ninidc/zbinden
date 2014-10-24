<?php
//--------------------------------------------------------------//
//              Entrie Meta Model
//--------------------------------------------------------------//
namespace Core\Model;

use Core\Model;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class Meta extends Model {

    public $meta_id;
    public $mkey;
    public $data;
    public $field_name;
    public $field_id;
    
    public static $table = "metas";
    public static $index = "meta_id";

  	public function __construct($data = array()) 
    {
        if(!empty($data))
        {
            $this->fromArray($data);
        }
    }

    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('mkey', new Assert\NotBlank());
    }

    static public function findByKey()
    {

    }

    static public function deleteByNameAndId($field_name, $field_id) 
    {
        global $app;

        $className = get_called_class();

        $app['db']->delete($className::$table, array( 
            "field_name" => $field_name,
            "field_id"  => $field_id
        ));
    }


    static public function FetchAllByNameAndId($field_name, $field_id) 
    {
        global $app;
        
        $className = get_called_class();

        $sql = "
            SELECT 
                * 
            FROM 
                metas 
            WHERE 
                field_name = '".$field_name."'
            AND 
                field_id = '".$field_id."'
        ";

        $result = $app['db']->fetchAll($sql);
        $data = array();

        foreach($result as $r) {
            $data[] = new $className($r);
        }

        return $data;
    }

    

    static public function findByFieldName($name)
    {
        global $app;

        $sql = '
            SELECT 
                * 
            FROM 
                metas 
            WHERE 
                field_name = ?
        ';

        $stmt = $app["db"]->executeQuery($sql, array($name));

        if ($data = $stmt->fetch()) {
            return new Meta($data);
        }

        return null;
    }
}
//--------------------------------------------------------------//

?>