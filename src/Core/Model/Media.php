<?php
//--------------------------------------------------------------//
//              MEDIA Model
//--------------------------------------------------------------//
namespace Core\Model;

use Core\Model;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class Media extends Model {

    public $media_id;
    public $type;
    public $mime_type;
    public $date;
    public $title;
    public $file;       // Nom de fichier stocké
    public $filename;   // Nom fictif du fichier
    public $width;
    public $height;
    public $tmp_name;

    public static $table = "medias";
    public static $index = "media_id";

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

    public function beforeSave()
    {
        if(!$this->data) {
            $this->date = date("Y-m-d H:i:s");
        }
        
        if($this->filename) {
            $this->mime_type = Media::getMimeTypes($this->filename);
        }   
    }   

    public static function delete($id)
    {
        global $app;

        $Media = Media::find($id);

        if(unlink($app["upload_folder"] . "medias/" . $Media->file)) {
            parent::delete($id);

            return true;
        }
        
        return false;
    }

    public function save()
    {
        global $app;

        $this->beforeSave();

        if($this->filename != null) {

            $filePath = $app["upload_folder"] . "medias/" . $this->file;

            if (move_uploaded_file($this->tmp_name, $filePath)) {

                if($this->mime_type) {
                    // We take the first string of the mime type
                    // ex.: image/jpeg => image
                    $type           = explode("/", $this->mime_type);
                    $this->type     = isset($type[0]) ? $type[0] : null;

                    if($this->type == "image") {
                        list($width, $height) = getimagesize($filePath);
                        $this->width  = $width;
                        $this->height = $height;
                    }
                }

                unset($this->tmp_name);

                return parent::save();
            } else {
                throw new \Exception("Erreur lors de l'upload du fichier");
            }
        } else {
            unset($this->filename);
            unset($this->file);
            unset($this->tmp_name);
            unset($this->type);
            unset($this->mime_type);
            unset($this->date);

            return parent::save();
        }
    }


    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('file', new Assert\NotBlank());
    }

    static public function getMimeTypes($filename) 
    {

        $mime_types = array(
            "avi"   => "video/x-msvideo",
            "flv"   => "video/x-flv",
            "f4v"   => "video/video-mp4",
            "f4a"   => "video/video-mp4",
            "qt"    => "video/quicktime",
            "mpeg"  => "video/mpeg",
            "mpg"   => "video/mpeg",
            "ogv"   => "video/ogg",
            "mp4"   => "video/mp4",
            "oga"   => "audio/ogg",
            "mp3"   => "audio/mpeg",
            "wav"   => "audio/x-wav",
            "png"   => "image/png",
            "jpg"   => "image/jpeg",
            "jpeg"  => "image/jpeg",
            "tiff"  => "image/tiff",
            "bmp"   => "image/bmp",
            "pdf"   => "application/pdf",
            "zip"   => "application/zip",
            "doc"   => "application/msword",
            "dtd"   => "application/xml-dtd",
            "xls"   => "application/vnd.ms-excel",
            "xlsb"  => "application/vnd.ms-excel.sheet.binary.macroEnabled.12",
            "ppt"   => "application/vnd.ms-powerpoint",
            "xlsx"  => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "xltx"  => "application/vnd.openxmlformats-officedocument.spreadsheetml.template",
            "potx"  => "application/vnd.openxmlformats-officedocument.presentationml.template",
            "ppsx"  => "application/vnd.openxmlformats-officedocument.presentationml.slideshow",
            "pptx"  => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            "sldx"  => "application/vnd.openxmlformats-officedocument.presentationml.slide",
            "docx"  => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "dotx"  => "application/vnd.openxmlformats-officedocument.wordprocessingml.template",
            "xlam"  => "application/vnd.ms-excel.addin.macroEnabled.12",
            "xlsb"  => "application/vnd.ms-excel.sheet.binary.macroEnabled.12",
        );

        $ext    = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return isset($mime_types[$ext]) ? $mime_types[$ext] : null;

    }

}
//--------------------------------------------------------------//

?>