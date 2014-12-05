<?php

namespace Core\View\Component\Handlebars\Helpers;

use Core\Model\Category;

class getPages extends \Core\View\Component\Handlebars\Helper
{

    public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source)
    {

        $options = $this->parseOptions($args);

        $Pages = Page::fetchAll($options);

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