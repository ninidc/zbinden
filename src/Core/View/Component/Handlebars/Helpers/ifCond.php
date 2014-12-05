<?php
//----------------------------------------------------------//
//          Handlebars ifCond Helpers
//  Usage : 
//      {{#ifCond value/var == value/var}} {{/ifCond}}
//----------------------------------------------------------//

//namespace Handlebars\Helper;
namespace Core\View\Component\Handlebars\Helpers;

use Handlebars\Context;
use Handlebars\Helper;
use Handlebars\Template;

class ifCond implements Helper
{
    public function execute(Template $template, Context $context, $args, $source)
    {
        $args = explode(' ', $args);

        $a = $context->get($args[0]);
        $b = $context->get($args[2]);

        if(!$b) {
            $b = $args[2];
        }

        $operator = $args[1];

        $template->setStopToken('else');

        // FIXME : Must implement >, <, >= and <= operators.
        switch($operator) {
            case '==':
                if($a == $b) {
                    $buffer = $template->render($context);
                    $template->setStopToken(false);
                    $template->discard($context);
                } else {
                    $template->discard($context);
                    $template->setStopToken(false);
                    $buffer = $template->render($context);
                }
            break;

            case '!=':
                if($a != $b) {
                    $buffer = $template->render($context);
                    $template->setStopToken(false);
                    $template->discard($context);
                } else {
                    $template->discard($context);
                    $template->setStopToken(false);
                    $buffer = $template->render($context);
                }
            break;
        }

        return $buffer;
    }
}