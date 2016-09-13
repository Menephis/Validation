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
            foreach($data as $dataKey => $value){
                foreach($mask as $key => $field){
                    if($dataKey === $key){
                        foreach($field as $method => $args){
                            if($method == 'required'){
                                continue;
                            }else{
                                $this->_answer[$key] = $validator($method, $value, $args);
                            }
                        }
                    }
                    if(isset($field['required']))
                        $require[] = $key;
                }
            }
            foreach($require as $reqs){
                if(!isset($this->_answer[$reqs]))
                    throw new Exception('data is not transferred - ' . $reqs);
            }
        }catch(Exception $e){
            $this->_error = $e;
            $this->_answer = null;
        }
    }
}
?>
