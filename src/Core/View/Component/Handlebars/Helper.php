<?php

namespace Core\View\Component\Handlebars;

use Handlebars\Context;
use Handlebars\Template;

class Helper implements \Handlebars\Helper
{

	public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source) {}


	protected function parseOptions($args, $types = array("where", "order", "limit"))
    {

        $options = array();

        foreach($types as $type) {

            $regEx = '#'.$type.'\[(.*)\]#i';

            // FIXME : fonctionne seulement avec 1 seule regle
            preg_match($regEx, $args, $matches);

            if(!isset($matches[1])) {
                break;
            }

            $matches = explode(",", $matches[1]);
            
            foreach($matches as $m) {
                $exp    = explode("=", trim($m));

                $key    = isset($exp[0]) ? trim($exp[0]) : null;
                $value  = isset($exp[1]) ? substr(trim($exp[1]), 1, strlen(trim($exp[1])) - 2) : null;

                $expKey = explode(".", $key);

                if(sizeof($expKey) > 1) {
                     $options[ $expKey[0] ][strtoupper($type)] = array(
                        $expKey[1] => $value
                    );
                 } else {
                    $options[ $key ][strtoupper($type)] = $value;
                 }
            }

        }

        return $options;
    }
}
?>