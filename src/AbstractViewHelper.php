<?php
/**
 * Created by PhpStorm.
 * User: james.s
 * Date: 12/21/2017
 * Time: 10:55 AM
 */

namespace JStormes\HtmlGateway;


abstract class AbstractViewHelper
{
    public abstract function execute($name, $arguments, $parent);
}