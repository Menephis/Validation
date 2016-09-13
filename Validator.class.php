<?php
class Validator{
    /**
    * Вызываем функции и передаём туда значения
    *
    * @param string $function - функция валидации переданых значений
    * @param string $value - значение для валидирования
    * @param array $args - аргументы передающиеся в функцию
    *
    * @return mixed - возвратятся либо проверенные данные, либо false
    */
    final public function __invoke($function, $value, $args = null){
         if(isset($args)){
            return $this->$function($value, $args);
        }else{
            //echo $function . $value . $args . "</br>";
            return $this->$function($value);
        }
    }
    /**
    * Проверка на длину строки
    *
    * @param mixed $value - проверяемое значение
    * @param array $range - минимальная и максимальная длина строки(пример: array(3,10))
    *
    * @return validator object
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
    * @return mixed - false/$value
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
    * @return mixed - false/$value
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
    * @return mixed - false/$value
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
    * @return mixed - false/$value
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
    * Валидации значений типа float
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return mixed - false/$value
    */
    final public function float($value){
        if(!preg_match( "/^[\-]?[0-9]*\.[0-9]*$/", $value))
            return false;
        return $value;
    }
    /**
    *  Валидация положительного float
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return mixed - false/$value
    */
    final public function floatPositive($value){
        if(!preg_match( "/^[0-9]*\.[0-9]*$/", $value))
             return false;
        return $value;
    }
    /**
    * Валидации отрицательного float
    *
    * @param mixed $value - проверяемое значение
    * 
    * @return mixed - false/$value
    */
    final public function floatNegative($value){
        if(!preg_match( "/^[\-][0-9]*\.[0-9]*$/", $value))
             return false;
        return $value;
    }   
    /**
    * Проверка массива данных на пренадлежность каждого элемента массива типу float
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
    * Валидация Булева значения
    *
    * @param mixed $value - проверяемое значение
    *
    * @return mixed - false/$value
    */
    final public function bool($value){
        if(!preg_match("/^(?i:true)$|^(?i:false)$|^1$|^0$/", $value))
            return false;
        return $value;
    }
    /**
    * Проверка входного значения на наличие символов отличных от латиницы, цифр от 0 до 9, нижнего подчёркивания и тире
    *
    * @param mixed $value - проверяемое значение
    * @param array $array - максимальная и минимальная длина строки(min,max)
    * 
    * @return mixed - false/$value
    */
    final public function latinName($value, array $array = array(3,16)){
        if(!preg_match("/^[a-zA-Z0-9_-]{" . $array[0] . "," . $array[1] . "}$/", $value))
            return false;
        return $value;
    }
    /**
    * Валидации IP адреса
    *
    * @param mixed $value - проверяемое значение
    * @param array $array - максимальная и минимальная длина строки(min,max)
    * 
    * @return mixed - false/$value
    */
    final public function pass($value, array $array = array(8,20)){
        if(!preg_match("/^[a-zA-Z0-9_-]{" . $array[0] . "," . $array[1] . "}$/", $value))
            return false;
        return $value;
    }
    /**
    * Email validation
    *
    * @param mixed $value - проверяемое значение
    *
    * @return mixed - false/$value
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
    * @return mixed - false/$value
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
    * @return mixed - false/$value
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
    * @return mixed - false/$value
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
    * @return mixed - false/$value
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
    * @return mixed - false/$value
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
    * @return mixed - false/$value
    */
    final public function url($value){
        if(!filter_var($value, FILTER_VALIDATE_URL))
            return false;
        return $value;
    }
}
?>
