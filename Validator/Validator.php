<?php
/**
* Validator
*
* @package Validation
* @subpackage Validator 
* @author Kurapov Pavel <spawnrus56@gmail.com>
*/
namespace kurapov\kurapov_validate\Validator;

use kurapov\kurapov_validate\Masks\AbstractMask;
/**
* Main validator class
*/
class Validator
{
    /**
    * @Author Kurapov Pavel <spawnrus56@gmail.com>
    */
    /**
    * @var protected Exception $_error - Хранит ошкибки, которые вырабатываются при валидации по маске
    */
    protected $_error;
    /**
    * Возвращает исключение с ошибками
    *
    * @return Exception $_error
    */
    public function getError(){
        return $this->_error;
    }
    /**
    * Валидация по маске
    *
    * @param array $data - массив с информацией для прохождения процесса валидации
    * @param AbstractMask $mask
    *
    * @return array $answer - Возвращает массив валидированными данными в случае успешного проходжения валидации, либо false в случае неудачи
    */
    public function Validate(array $data, AbstractMask $mask)
    {
        try
        {
            $answer = array();
            foreach($mask->getMask() as $key => $maskField){
                if(isset($data[$key])){
                    $method = $maskField['method'];
                    if($maskField['arguments'] === null){
                        $answer[$key] = $this->$method($data[$key]);
                    }else{
                        $answer[$key] = $this->$method($data[$key], $maskField['arguments']);
                    }
                    // Required parametr
                    if(($maskField['required'] === true) and ($answer[$key] === false)){
                        throw new \Exception('Stoped on - ' . $key);
                    }
                }elseif($maskField['required'] === true){
                    throw new \Exception('Data is not transferred - ' . $key);
                }
            }
            return $answer;
        }catch(\Exception $e){
            $this->_error = $e;
            return false;
        }
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
                    //echo $minMaxRange[0] . ' -----' . $value . '--------' . $minMaxRange[1];
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