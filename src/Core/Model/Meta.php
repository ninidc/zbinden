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
    
    public static $table = "entrie_metas";
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

    static public function findByFieldName($name)
    {
        global $app;

        $sql = '
            SELECT 
                * 
            FROM 
                entrie_metas 
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