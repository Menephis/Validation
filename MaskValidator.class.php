<?php
require_once 'validator.class.php';
class MaskValidator extends validator{
    public $_answer = array();
    public $_error;
    /**
    * Validate on mask
    *
    * @param array $data - array with data from any form
    *
    * @return array - return validated array
    */
    final function __construct(array $data, array $mask){
        try{
            $validator = new Validator();
                foreach($mask as $key => $field){
                    if(isset($data[$key])){
                        $this->_answer[$key] = $validator($field['method'], $data[$key], $field['arguments']);
                        if(($field['required'] == true) and ($this->_answer[$key] === false)){
                             throw new Exception('Stoped on - ' . $key);
                        }
                    }elseif($field['required'] == true){
                        throw new Exception('data is not transferred - ' . $key);
                    }
        }catch(Exception $e){
            $this->_error = $e;
            $this->_answer = null;
            return false;
        }
    }
}
?>
