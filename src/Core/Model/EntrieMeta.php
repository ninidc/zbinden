<?php
//--------------------------------------------------------------//
//              Entrie Meta Model
//--------------------------------------------------------------//
namespace Core\Model;

use Core\Model;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class EntrieMeta extends Model {

    public $meta_id;
    public $entrie_id;
    public $key;
    public $value;
    
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
        $metadata->addPropertyConstraint('key', new Assert\NotBlank());
    }

}
//--------------------------------------------------------------//

?>