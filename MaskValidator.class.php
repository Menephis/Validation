<?php
require_once 'validator.class.php';
class MaskValidator extends validator{
    public $answer = array(); 
    public $_error = null;
    /**
    * Validate on mask
    *
    * @param array $data - array with data from any form
    *
    * @return array - return validated array
    */
    final function __construct(array $data, array $mask){
        try{
            foreach($mask as $key => $field){
                if(isset($data[$key])){
                    // Произошло совпадение по маске
                    $x = new Validator($data[$key]);
                    foreach($field as $function => $arg){
                        $this->answer[$key] = $x->$function($arg);
                    }
                    unset($x);
                }elseif(isset($field['required'])){
                    throw new Exception('Data is not transfered - ' . $key);
                }
            }
        }catch(Exception $e){
            $this->_error = $e;
            $this->answer = null;
            return false;
        }
    }
}
?>
