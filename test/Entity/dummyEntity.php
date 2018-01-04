<?php
/**
 * Created by PhpStorm.
 * User: james.s
 * Date: 1/3/2018
 * Time: 8:14 PM
 */

class dummyEntity
{
    private $field1=null;

    public $saved=false;

    public $deleted=false;

    /**
     * @return null
     */
    public function getField1()
    {
        return $this->field1;
    }

    /**
     * @param null $field1
     */
    public function setField1($field1)
    {
        $this->field1 = $field1;
    }

    public function save()
    {
        $this->saved=true;
    }

    public function delete()
    {
        $this->deleted=true;
    }

}