<?php
/**
* Abstract masks
*
* @package Validation
* @subpackage Masks 
* @author Kurapov Pavel <spawnrus56@gmail.com>
*/
namespace kurapov\kurapov_validate\Masks;
/**
* AbstractMask class for extending
*/
abstract class AbstractMask
{
    /**
    * GetMask will return mask array
    *
    * @return array $mask
    */
    abstract function getMask();
    /**
    * CheckMask for syntax
    *
    * @return self
    */
    public function checkMask()
    {
        try
        {
            $mask = $this->getMask();
            if(count($mask) === 0  or ! is_array($mask))
                throw new \Exception('Mask must be array and have at least one value');
            foreach($mask as $key => $value){
                // field exists
                if( ! (count($value === 3) and key_exists('method', $value) and key_exists('arguments', $value) and key_exists('required', $value)))
                    throw new \Exception('Mask must have three field: method, arguments, required');
                // check method type
                if( ! is_string($value['method']))
                    throw new \Exception('Value for method must be string type');
                // check arguments type
                if( ! (is_array($value['arguments']) or is_null($value['arguments'])))
                    throw new \Exception('Value for arguments must be array type or null');
                // check required type
                if( ! is_bool($value['required']))
                    throw new \Exception('Value for required must be boolean type');
            }
        return $this;
        }catch(\Exception $e)
        {
            throw new \Exception($e);
        }
    }
}
?>