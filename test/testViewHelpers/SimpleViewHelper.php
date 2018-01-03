<?php
/**
 * Created by PhpStorm.
 * User: james.s
 * Date: 1/3/2018
 * Time: 1:10 PM
 */

namespace testViewHelpers;

class SimpleViewHelper extends \JStormes\HtmlGateway\AbstractViewHelper
{

    function execute($name, $arguments, $parent)
    {
        // TODO: Implement execute() method.
        return "Simple";
    }
}