<?php
/**
* Validator
*
* @package Validation
* @subpackage ValidedField 
* @author Kurapov Pavel <spawnrus56@gmail.com>
*/
namespace Menephis\MaskValidator\Validator;
/**
* Class Valided Field points to valided data or not
*/
class ValidedField
{
    /**
    * @var string $fieldData - data for validate 
    */
    protected $fieldData;
    /**
    * @var boolean $isValid - points to valid data or not
    */
    protected $isValid = false;
    /**
    * Accept data
    *
    * @param mixed $fieldData 
    *
    * @return void
    */
    public function __construct($fieldData)
    {
        $this->fieldData = $fieldData;
    }
    /**
    * Return data on condition that they are checked
    *
    * @return mixed
    */
    public function getValidedData()
    {
        return $this->isValid ? $this->fieldData : null;
    }
    /**
    * Check validate of data
    *
    * @return boolean - return true if data correct and were tested
    */
    public function isValid()
    {
        return $this->isValid;
    }
    /**
    * set property isValid to true, that means that data is correct
    *
    * @return self
    */
    public function correct()
    {
        $this->isValid = true;
        return $this;
    }
    /**
    * Return data 
    *
    * @return string $this->fieldData;
    */
    public function getFieldData()
    {
        return $this->fieldData;
    }
}
?>