<?php
class Validator{
    /**
    * @Author Kurapov Pavel <spawnrus56@gmail.com>
    */
    /**
    * @var array $_answer - Хранит результат после валидации по маске
    */
    public $_answer = array();
    /**
    * @var string $_error - Хранит ошкибки, которые вырабатываются при валидации по маске
    */
    public $_error;
    /**
    * Валидация по маске
    *
    * @param array $data - массив с информацией для прохождения процесса валидации
    *
    * @return array - Возвращает в переменную класса $_answer либо массив валидированными данными, либо false
    */
    final function __invoke(array $data, array $mask){
        try{
            foreach($mask as $key => $field){
                if(isset($data[$key])){
                    $this->_answer[$key] = $this->$field['method']($data[$key], $field['arguments']);
                    if(($field['required'] == true) and ($this->_answer[$key] === false)){
                        throw new Exception('Stoped on - ' . $key);
                    }
                }elseif($field['required'] == true){
                    throw new Exception('data is not transferred - ' . $key);
                }
            }
        }catch(Exception $e){
            $this->_error = $e;
            $this->_answer = false;
            return false;
        }
    }
    /**
    * Проверяет маску($mask) на корректность
    *
    * @param array $mask - Массив(он же маска), который проверяем
    *
    * @return boolean - Маска корректна или нет
    */
    final public function checkMask(array $mask){
        try{
            $example = 'Example:
            $mask = array(
                userName => array(
                    method => latinName,
                    arguments => array(3, 10),
                    required => true
                )
            );';
            if(count($mask) == 0 )
                throw new Exception('Mask must have at least one value');
            foreach($mask as $key => $value){
                if((!is_string($key)) or (!is_array($value)))
                    throw new Exception('Name of field muast be string and value field must be array');
                if((count($value) == 2) or (count($value) == 3)){
                    if(count($value) == 3){
                        if(!($value['required'] == true))
                            throw new Exception('
                            required must be true - ' . $example);
                    }
                    if(!isset($value['method']) or !key_exists('method', $value) or !key_exists('arguments', $value))
                        throw new Exception('Description of field must have fileds: method(string) and arguments(array or null) ' . $example);
                    if( ! ($value['arguments'] == null or is_array($value['arguments'])))
                        throw new Exception('Description of field must have fileds: method(string) and arguments(array or null)' . $example);
                    if(!method_exists(__CLASS__, $value['method']))
                       throw new Exception('Method - ' . $value['method'] . ' does not exists in Validator ' . __CLASS__);
                }else{
                    throw new Exception('
                    Description of field must have 2 or 3 parameters ' . $example);
                }
            }
        return true;
        }catch(Exception $e){
            $this->_error = $e;
            return false;
        }
    }
    /**
    * Проверка на длину строки
    *
    * @param string $value - проверяемое значение
    * @param array $range - минимальная и максимальная длина строки(пример: array(3,10))
    *
    * @return string $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function minMaxLength($value ,array $range){
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
    final public function int($value){
        if(!filter_var($value, FILTER_VALIDATE_INT))
            return false;
        return $value;
    }
    /**
    * Проверка integer на принадлежность диапозону значений(минимум, максимум)
    *
    * @param mixed $value - проверяемое значение
    * @param array $range - диапозон значений (min, max)
    *
    * @return integer $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function intRanged($value, array $range){
        $options = array('options' => array());  
        if(isset($range[0]))
            $options['options']['min_range'] = (int)$range[0];
        if(isset($range[1]))
            $options['options']['max_range'] = (int)$range[1];
        if(!filter_var($value, FILTER_VALIDATE_INT, $options))
            return false;
        return $value;
    }
    /**
    * Валидация положительного integer
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return integer $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function intPositive($value){
        $options = array('options' => array());  
        $options['options']['min_range'] = 1;
        if(!filter_var($value, FILTER_VALIDATE_INT, $options))
          return false;
        return $value;
    }
    /**
    * Валидация отрицательного integer
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return integer $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function intNegative($value){
        $options = array('options' => array());  
        $options['options']['max_range'] = -1;
        if(!filter_var($value, FILTER_VALIDATE_INT, $options))
            return false;
        return $value;
    }
    /**
    * Проверка массива данных на пренадлежность каждого элемента массива типу integer
    *
    * @param array $array - массив значений
    * 
    * @return array $array - массив с валидированными значениями
    */
    final public function intergerFromList(array $array){
        $intArray = array();
        foreach($array as $value){
            if(filter_var($value, FILTER_VALIDATE_INT)){
                $intArray = $value;
            }else{
                $value = false;
            }
                
        }
        return $array;
    }
    /**
    * Валидации значений типа float(RegExp - ^[\-]?[0-9]*\.[0-9]*$)
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return float $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function float($value){
        if(!preg_match( "/^[\-]?[0-9]*\.[0-9]*$/", $value))
            return false;
        return $value;
    }
    /**
    *  Валидация положительного float(RegExp - ^[0-9]*\.[0-9]*$)
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return float $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function floatPositive($value){
        if(!preg_match( "/^[0-9]*\.[0-9]*$/", $value))
             return false;
        return $value;
    }
    /**
    * Валидации отрицательного float(RegExp - ^[\-][0-9]*\.[0-9]*$)
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return float $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function floatNegative($value){
        if(!preg_match( "/^[\-][0-9]*\.[0-9]*$/", $value))
             return false;
        return $value;
    }   
    /**
    * Проверка массива данных на пренадлежность каждого элемента массива типу float (RegExp - ^[\-]?[0-9]*\.[0-9]*$)
    *
    * @param array $array - список проверяемых значений
    * 
    * @return array $array - массив с валидированными значениями
    */
    final public function floatFromList(array $array){
        foreach($array as $value){
            if(preg_match( "/^[\-]?[0-9]*\.[0-9]*$/", $value)){
                $intArray = $value;
            }else{
                $value = false;
            }
        }
        return $array;
    }
    /**
    * Валидация Булева значения(RegExp - ^(?i:true)$|^(?i:false)$|^1$|^0$)
    *
    * @param mixed $value - проверяемое значение
    *
    * @return boolean - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function bool($value){
        if(!preg_match("/^(?i:true)$|^(?i:false)$|^1$|^0$/", $value))
            return false;
        return $value;
    }
    /**
    * Проверка входного значения на наличие символов отличных от латиницы, цифр от 0 до 9, нижнего подчёркивания и тире(RegExp - ^[a-zA-Z0-9_-]{minLength, maxLength}$)
    *
    * @param mixed $value - проверяемое значение
    * @param array $array - максимальная и минимальная длина строки(min,max)
    * 
    * @return string $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function latinName($value, array $array = array(3,16)){
        if(!preg_match("/^[a-zA-Z0-9_-]{" . $array[0] . "," . $array[1] . "}$/", $value))
            return false;
        return $value;
    }
    /**
    * Валидации IP адреса(RegExp - ^[a-zA-Z0-9_-]{minLength, maxLength}$)
    *
    * @param mixed $value - проверяемое значение
    * @param array $array - максимальная и минимальная длина строки(min,max)
    * 
    * @return string $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function pass($value, array $array = array(8,20)){
        if(!preg_match("/^[a-zA-Z0-9_-]{" . $array[0] . "," . $array[1] . "}$/", $value))
            return false;
        return $value;
    }
    /**
    * Валидация email'a
    *
    * @param mixed $value - проверяемое значение
    *
    * @return string $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function email($value){
        if(!filter_var($value, FILTER_VALIDATE_EMAIL))
            return false;
        return $value;
    }
    /**
    * Валидация IP адреса
    *
    * @param mixed $value - проверяемое значение
    *
    * @return string $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function ip($value){
        if(!filter_var($value, FILTER_VALIDATE_IP))
            return false;
        return $value;
    }
    /**
    * Валидация IP адреса в форме целочисленного значения
    *
    * @param mixed $value - проверяемое значение
    * @param int $output - если указать 'string', то ip будет возвращенно в стандартном строковом значении
    *
    * @return mixed $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function ipInInt($value, array $output = array('int')){
        if((int)$value >= 0 and (int)$value <= 4294967295){
            if($output == 'string'){
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
    * @param string $output - если в output указать int, то ip будет возвращен в целочисленном значении
    * 
    * @return mixed $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function ipV4($value, array $output = array('string')){
        if(!filter_var($value, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))
            return false;
        if($output == 'int'){
            $value = sprintf('%u', ip2long($value));
        }
        return $value;
    }
    /**
    * Валидация IP адреса шестой версии(IPv6)
    *
    * @param mixed $value - проверяемое значение
    *
    * @return string $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function ipV6($value){
        if(!filter_var($value, FILTER_VALIDATE_IP,FILTER_FLAG_IPV6))
            return false;
        return $value;
    }
    /**
    * Проверка IP на принадлежность его диапозонну IP адресов
    *
    * @param mixed $value - проверяемое значение
    * @param array $array  - диапозон значений IP - min, max(пример: array(127.01.01.01, 255.127.10.10))
    * 
    * @return string $value - возвращает либо $value,если $value прошло проверку, либо false в случае неудачи
    */
    final public function ipRanged($value, array $array){
        if(filter_var($value, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))
            if(filter_var($array[0], FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))    
                if(filter_var($array[1], FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)){
                    $value = ip2long($value);
                    $array[0] = ip2long($array[0]);
                    $array[1] = ip2long($array[1]);
                    if($array[0] >= $value and $value <= $array[1]){
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
    final public function url($value){
        if(!filter_var($value, FILTER_VALIDATE_URL))
            return false;
        return $value;
    }
}
?>
