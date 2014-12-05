<?php
//----------------------------------------------------------//
//          Handlebars getPagesFromCategory Helpers
//  Usage : 
//      {{#getPagesFromCategory where(field=value) order(field=value)}} 
//          (HTML)
//      {{/getPagesFromCategory}}
//
//  Ex : where[slug="guitare-folk]
//----------------------------------------------------------//

//namespace Handlebars\Helper;
namespace Core\View\Component\Handlebars\Helpers;

use Core\Model\Category;

class getPagesFromCategory extends \Core\View\Component\Handlebars\Helper
{

    public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source)
    {

        $options = $this->parseOptions($args);        

        $Category = Category::fetchAll($options["category"]);

        if(!sizeof($Category) > 0) {
           return false;
        } 

        $Pages = $Category[0]->fetchPages($options["page"]);

        if(!empty($Pages)) {
            $Handlebars = new \Handlebars\Handlebars;

            // FIXME : we need to optimize that.
            $data = array();
            foreach($Pages as $index => $Page) {
                $data[$index]           = $Page->toArray();
                $data[$index]["METAS"]  = $Page->getParsedMetas();                    
            }

            return $Handlebars->render('{{#each PAGES}}' . $source . '{{/each}}', array(
                "PAGES" => $data
            ));
        }
    

        return $template->render($context);
    }
}