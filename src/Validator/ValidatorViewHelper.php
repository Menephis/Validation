<?php
/**
* ValidatorViewHelper
*
* @package Validation
* @subpackage ViewHelper 
* @author Kurapov Pavel <spawnrus56@gmail.com>
*/
namespace Menephis\MaskValidator\Validator;
/**
* Class ValidatorViewHelper help present valided data
*/
class ValidatorViewHelper{
    /**
    * @var array $data
    */
    protected $data;
    /**
    * @param array $data
    */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }
    /**
    * @param string $fieldName
    *
    * @return mixed - or null if $fieldName doesn't exists in $this->data 
    */
    public function getField(string $fieldName)
    {
        return isset($this->data[$fieldName]) ? $this->data[$fieldName]->getFieldData() : null;
    }
    /**
    * @param string $fieldName
    *
    * @return boolean
    */
    public function isNotValid(string $fieldName)
    {
       if(isset($this->data[$fieldName]))
           return ($this->data[$fieldName]->isValid() === false ) ? true : false;  
        return null;
    }
}
?>