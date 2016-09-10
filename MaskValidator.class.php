<?php
require_once 'validator.class.php';
class MaskValidator extends validator{
    public $answer = array(); 
    /**
    * Validate on mask
    *
    * @param array $data - array with data from any form
    *
    * @return array - return validated array
    */
    final function __construct(array $data, array $mask){
        try{
            foreach($data as $dataKey => $value){
                foreach($mask as $key => $field){
                    if($dataKey == $key){
                        // Произошло совпадение по маске
                        $x = new Validator($value);
                        foreach($field as $function => $arg){
                            $this->answer[$key] = $x->$function($arg);
                        }
                        unset($x);
                    }
                }
            }
        }catch(Exception $e){
            $this->answer = null;
        }
    }
}
?>