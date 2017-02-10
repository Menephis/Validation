<?php
/**
* Validator
*
* @package Validation
* @subpackage Validator 
* @author Kurapov Pavel <spawnrus56@gmail.com>
*/
namespace Menephis\MaskValidator\Validator;

use Menephis\MaskValidator\Validator\ValidedField;
use Menephis\MaskValidator\Masks\AbstractMask;
use Menephis\MaskValidator\Exception\InvalidMaskException;

/**
* Main validator class
*/
class Validator
{
    /**
    * @Author Kurapov Pavel <spawnrus56@gmail.com>
    */ 
    /**
    * @var boolean - points to valid of data
    */
    protected $isValid;
    /**
    * @var array $data - processed data(Data after validate)
    */
    protected $data = array();
    /**
    * @var array $mask;
    */
    protected $mask;
    /**
    * Construct
    *
    * @param array $mask
    */
    public function __construct(AbstractMask $mask)
    {
        try
        {
            if($this->mask)
                return null;
            $this->mask = $mask->getMask();
            if( ! $this->mask)
                throw new InvalidMaskException('Mask was not passed');
            $mask->checkMask($this->mask);
        }catch(InvalidMaskException $e)
        {
            throw $e;
        }
    }
    /**
    * Validate 
    *
    * @param array $data - array to search and validate data into it
    *
    * @return array - validated data
    */
    public function validate(array $data)
    {
            $this->isValid = true;
            foreach($this->mask as $key => $field){
                if(isset($data[$key])){
                    $method = $field['method'];
                    if( $field['arguments'] ){
                        $x = $this->$method($data[$key], $field['arguments']);
                    }else{
                        $x = $this->$method($data[$key]);
                    }
                    if( $x === false ){
                        if($field['required'] === true)
                            $this->isValid = false;
                        $this->data[$key] = new ValidedField($data[$key]); 
                    }else{
                        $this->data[$key] = (new ValidedField($data[$key]))->correct();
                    }
                }elseif($field['required'] === true){
                    $this->isValid = false;
                }
            }
            return $this->data;
    }
    /**
    * Check current data to correct. Should be call only after called $this->validate() method
    *
    * @return boolean
    */
    public function isValid()
    {
        return $this->isValid;
    }
    /**
    * Return valided date as assoc array without objects
    *
    * @retun array
    */
    public function getDataAsArray()
    {
        $array = array();
        foreach($this->data as $key => $value)
            $array[$key] = $value->getValidedData();
        return $array;
    }
    /**
    * Erase current data and $this->isValid
    *
    * @return void
    */
    public function EraseValidator()
    {
        $this->isValid = null;
        $this->data = array();
    }
    /**
    * Проверка на длину строки
    *
    * @param mixed $value - проверяемое значение
    * @param array $range - минимальная и максимальная длина строки(пример: array(3,10))
    *
    * @return string $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    public function minMaxLength($value, array $range){
        if((strlen($value) < (int)$range[0]) or (strlen($value) > (int)$range[1]))
            return false;
        return $value;
    }
    /**
    * Валидация integer
    *
    * @param mixed $value - проверяемое значение
    *
    * @return integer $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    public function int($value){
        if( ! filter_var($value, FILTER_VALIDATE_INT))
            return false;
        return $value;
    }
    /**
    * Проверка integer на принадлежность диапозону значений(минимум, максимум)
    *
    * @param mixed $value - проверяемое значение
    * @param array $range - диапозон значений (min, max)
    *
    * @return integer $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function intRanged($value, array $range){
        $options = array('options' => array());  
        if(isset($range[0]))
            $options['options']['min_range'] = (int)$range[0];
        if(isset($range[1]))
            $options['options']['max_range'] = (int)$range[1];
        if( ! filter_var($value, FILTER_VALIDATE_INT, $options))
            return false;
        return $value;
    }
    /**
    * Валидация положительного integer
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return integer $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function intPositive($value){
        $options = array('options' => array());  
        $options['options']['min_range'] = 1;
        if( ! filter_var($value, FILTER_VALIDATE_INT, $options))
            return false;
        return $value;
    }
    /**
    * Валидация отрицательного integer
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return integer $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function intNegative($value){
        $options = array('options' => array());  
        $options['options']['max_range'] = -1;
        if( ! filter_var($value, FILTER_VALIDATE_INT, $options))
            return false;
        return $value;
    }
    /**
    * Проверка массива данных на пренадлежность каждого элемента массива типу integer
    *
    * @param array $valueArray - массив значений
    * 
    * @return array $valueArray - массив с валидированными значениями
    */
    public function intFromList(array $valueArray){
        foreach($valueArray as & $value){
            if(filter_var($value, FILTER_VALIDATE_INT)){
                continue;
            }else{
                $value = false;
            }
                
        }
        return $valueArray;
    }
    /**
    * Валидации значений типа float(RegExp - ^[\-]?[0-9]*\.[0-9]*$)
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return float $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function float($value){
        if( ! preg_match( "/^[\-]?[0-9]*\.[0-9]*$/", $value))
            return false;
        return $value;
    }
    /**
    *  Валидация положительного float(RegExp - ^[0-9]*\.[0-9]*$)
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return float $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function floatPositive($value){
        if( ! preg_match( "/^[0-9]*\.[0-9]*$/", $value))
             return false;
        return $value;
    }
    /**
    * Валидации отрицательного float(RegExp - ^[\-][0-9]*\.[0-9]*$)
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return float $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function floatNegative($value){
        if( ! preg_match( "/^[\-][0-9]*\.[0-9]*$/", $value))
             return false;
        return $value;
    }   
    /**
    * Проверка массива данных на пренадлежность каждого элемента массива типу float (RegExp - ^[\-]?[0-9]*\.[0-9]*$)
    *
    * @param array $valueArray - список проверяемых значений
    * 
    * @return array $valueArray - массив с валидированными значениями
    */
    public function floatFromList(array $valueArray){
        foreach($valueArray as & $value){
            if(preg_match( "/^[\-]?[0-9]*\.[0-9]*$/", $value)){
                continue;
            }else{
                $value = false;
            }
        }
        return $valueArray;
    }
    /**
    * Валидация Булева значения(RegExp - ^(?i:true)$|^(?i:false)$|^1$|^0$)
    *
    * @param mixed $value - проверяемое значение
    *
    * @return boolean - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function boolean($value){
        if(!preg_match("/^(?i:true)$|^(?i:false)$|^1$|^0$/", $value))
            return false;
        return $value;
    }
    /**
    * Проверка входного значения на наличие символов отличных от латиницы, цифр от 0 до 9, нижнего подчёркивания и тире(RegExp - ^[a-zA-Z0-9_-]{minLength, maxLength}$)
    *
    * @param mixed $value - проверяемое значение
    * @param array $range - максимальная и минимальная длина строки(min,max)
    * 
    * @return string $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function latinName($value, array $range = array(8, 20)){
        if(!preg_match("/^[a-zA-Z0-9_-]{" . $range[0] . "," . $range[1] . "}$/", $value))
            return false;
        return $value;
    }
    /**
    * Валидации IP адреса(RegExp - ^[a-zA-Z0-9_-]{minLength, maxLength}$)
    *
    * @param mixed $value - проверяемое значение
    * @param array $range - максимальная и минимальная длина строки(min,max)
    * 
    * @return string $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function password($value, array $range = array(8, 20)){
        if(!preg_match("/^[a-zA-Z0-9_-]{" . $range[0] . "," . $range[1] . "}$/", $value))
            return false;
        return $value;
    }
    /**
    * Валидация email'a
    *
    * @param mixed $value - проверяемое значение
    *
    * @return string $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    function email($value){
        if( ! filter_var($value, FILTER_VALIDATE_EMAIL))
            return false;
        return $value;
    }
    /**
    * Валидация IP адреса
    *
    * @param mixed $value - проверяемое значение
    *
    * @return string $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function ip($value){
        if( ! filter_var($value, FILTER_VALIDATE_IP))
            return false;
        return $value;
    }
    /**
    * Валидация IP адреса в форме целочисленного значения
    *
    * @param mixed $value - проверяемое значение
    * @param array $output - если указать 'string', то ip будет возвращенно в стандартном строковом виде
    *
    * @return mixed $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function ipInInt($value, array $output = array('integer')){
        if((int)$value >= 0 and (int)$value <= 4294967295){
            if($output[0] == 'string'){
                $value = long2ip($value);
            }
            return $value;
        }
        return false;
    }
    /**
    * Валидация IP адреса четвёртой версии(IPv4)
    *
    * @param mixed $value - проверяемое значение
    * @param array $output - если в output указать int, то ip будет возвращен в целочисленном значении
    * 
    * @return mixed $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function ipV4($value, array $output = array('string')){
        if(!filter_var($value, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))
            return false;
        if($output[0] == 'integer'){
            $value = sprintf('%u', ip2long($value));
        }
        return $value;
    }
    /**
    * Валидация IP адреса шестой версии(IPv6)
    *
    * @param mixed $value - проверяемое значение
    *
    * @return string $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function ipV6($value){
        if(!filter_var($value, FILTER_VALIDATE_IP,FILTER_FLAG_IPV6))
            return false;
        return $value;
    }
    /**
    * Проверка IP на принадлежность его диапозонну IP адресов
    *
    * @param mixed $value - проверяемое значение
    * @param array $minMaxRange  - диапозон значений IP - min, max(пример: array(127.01.01.01, 255.127.10.10))
    * 
    * @return string $value - возвращает либо $value, если $value прошло проверку, либо false в случае неудачи
    */
    public function ipRanged($value, array $minMaxRange){
        if(filter_var($value, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))
            if(filter_var($minMaxRange[0], FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))    
                if(filter_var($minMaxRange[1], FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)){
                    $value = ip2long($value);
                    $minMaxRange[0] = ip2long($minMaxRange[0]);
                    $minMaxRange[1] = ip2long($minMaxRange[1]);
                    if($minMaxRange[0] <= $value and $value <= $minMaxRange[1]){
                        $value = long2ip($value);
                        return $value;
                    }     
                }
        return false;
    }
    /**
    * Валидация URL адреса
    *
    * @param mixed $value - проверяемое значение
    *
    * @return mixed $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    public function url($value){
        if( ! filter_var($value, FILTER_VALIDATE_URL))
            return false;
        return $value;
    }
}
?>
