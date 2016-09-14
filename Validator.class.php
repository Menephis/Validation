<?php
class Validator{
    public $value;
    private $error = 0;
    /**
    * Get value fo validate
    * 
    * @param mixed $val - value fo vaidate
    *
    * @return void
    */
    public function __construct($val){
        $this->value = $val;
    }
    /**
    * Check validator for error 
    *
    * @return mixed  - false/$value
    */
    public function execute(){
        if($this->error > 0){
            return false;
        }else{
            return $this->value;       
        }
    } 
    /**
    * For require param - throw exception
    *
    * @return mixed  - throw Exception or return $this->value
    */
    public function required(){
        try{
            if($this->error > 0){
                throw new Exception($this->error);
            }else{
                return $this->value;       
            }  
        }catch(Exception $e){
            throw new Exception($e);
        }
        
    }
    /**
    * Check length of string
    *
    * @param array $range - min and max length of string(example: array(3,10))
    *
    * @return validator object
    */
    public function minMaxLength(array $range){
        if((strlen($this->value) < (int)$range[0]) or (strlen($this->value) > (int)$range[1]))
            $this->error++;
        return $this;
    }
    /**
    * Validation integer 
    *
    * @return validator object 
    */
    public function int(){
        if(!filter_var($this->value, FILTER_VALIDATE_INT))
            $this->error++;    
        return $this;
    }
    /**
    * Validation for a range of numbers
    *
    * @param array $range - array with range(min, max)
    *
    * @return validator object 
    */
    public function intRanged(array $range){
        $options = array('options' => array());  
        if(isset($range[0]))
            $options['options']['min_range'] = (int)$range[0];
        if(isset($range[1]))
            $options['options']['max_range'] = (int)$range[1];
        if(!filter_var($this->value, FILTER_VALIDATE_INT, $options))
            $this->error++;
        return $this;
    }
    /**
    * Positive integer validation
    * 
    * @return validator object 
    */
    public function intPositive(){
        $options = array('options' => array());  
        $options['options']['min_range'] = 1;
        if(!filter_var($this->value, FILTER_VALIDATE_INT, $options))
          $this->error++;    
        return $this;
    }
    /**
    * Negative integer validation
    *
    * @return validator object 
    */
    public function intNegative(){
        $options = array('options' => array());  
        $options['options']['max_range'] = -1;
        if(!filter_var($this->value, FILTER_VALIDATE_INT, $options))
            $this->error++;    
        return $this;
    }
    /**
    * Validation elements of array for int
    *
    * @param array $array
    * 
    * @return validator object 
    */
    public function intergerFromList(array $array){
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
    * Float validation
    * 
    * @return validator object 
    */
    public function float(){
        if(!preg_match( "/^[\-]?[0-9]*\.[0-9]*$/", $this->value))
            $this->error++;    
        return $this;
    }
    /**
    *  Positive float validation
    * 
    * @return validator object 
    */
    public function floatPositive(){
        if(!preg_match( "/^[0-9]*\.[0-9]*$/", $this->value))
             $this->error++;    
        return $this; 
    }
    /**
    * Negative float validation
    * 
    * @return validator object 
    */
    public function floatNegative(){
        if(!preg_match( "/^[\-][0-9]*\.[0-9]*$/", $this->value))
             $this->error++;    
        return $this;
    }   
    /**
    * Validation elements of array for float
    *
    * @param array $array
    * 
    * @return validator object 
    */
    public function floatFromList(array $array){
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
    * Boolean validation
    * 
    * @return validator object 
    */
    public function bool(){
        if(!preg_match("/^(?i:true)$|^(?i:false)$|^1$|^0$/", $this->value))
            $this->error++;    
        return $this; 
    }
    /**
    * Latin name validation
    *
    * @param array $array - min, max length of string
    * 
    * @return validator object 
    */
    public function latinName(array $array = array(3,16)){
        if(!preg_match("/^[a-zA-Z0-9_-]{" . $array[0] . "," . $array[1] . "}$/", $this->value))
            $this->error++;    
        return $this;
    }
    /**
    * Password validation
    *
   * @param array $array - min, max length of string
    * 
    * @return validator object 
    */
    public function pass(array $array = array(8,20)){
        if(!preg_match("/^[a-zA-Z0-9_-]{" . $array[0] . "," . $array[1] . "}$/", $this->value))
            $this->error++;    
        return $this;
    }
    /**
    * Email validation
    *
    * @return validator object 
    */
    public function email(){
        if(!filter_var($this->value, FILTER_VALIDATE_EMAIL))
            $this->error++;    
        return $this;  
    }
    /**
    * IP validation
    *
    * @return validator object 
    */
    public function ip(){
        if(!filter_var($this->value, FILTER_VALIDATE_IP)) 
            $this->error++;   
        return $this;
    }
    /**
    * IP validation in integer
    *
    * @param int $output - если указать 'string', то ip будет возвращенно в стандартном строковом значении
    *
    * @return validator object 
    */
    public function ipInInt(array $output = array('int')){
        if((int)$this->value >= 0 and (int)$this->value <= 4294967295){
            if($output == 'int'){
                $this->value = long2ip($this->value);
            }
            return $this;
        }
        $this->error++;
        return $this;
    }
    /**
    * IP version 4 validation
    *
    * @param string $output - если в output указать int, то ip будет возвращен в целочисленном значении
    * 
    * @return validator object 
    */
    public function ipV4(array $output = array('string')){
        if(!filter_var($this->value, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))
            $this->error++;
        if($output[0] == 'int'){
            $this->value = sprintf('%u', ip2long($this->value));
        }
        return $this;
    }
    /**
    * IP version 6 validation
    *
    * @return validator object 
    */
    public function ipV6(){
        if(!filter_var($this->value, FILTER_VALIDATE_IP,FILTER_FLAG_IPV6))
            $this->error++;    
        return $this;
    }
    /**
    * IP version 6 validation
    *
    * @param array $array  - min, max range of IP
    * 
    * @return validator object 
    */
    public function ipRanged(array $array){
        if(filter_var($this->value, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))
            if(filter_var($array[0], FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))    
                if(filter_var($array[1], FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)){
                    $this->value = ip2long($this->value);
                    $array[0] = ip2long($array[0]);
                    $array[1] = ip2long($array[1]);
                    if($array[0] >= $this->value and $this->value <= $array[1]){
                        $this->value = long2ip($this->value);
                        return $this;
                    }     
                }
        $this->error++;
    }
    /**
    * URL validation
    *
    * @return validator object 
    */
    public function url(){
        if(!filter_var($this->value, FILTER_VALIDATE_URL))
            $this->error++;    
        return $this;
    }
}
?>
