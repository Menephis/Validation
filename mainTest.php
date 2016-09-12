<?php
require_once 'Validator.class.php';
require_once 'MaskValidator.class.php';
require_once 'masks.php';

// Простая валидация 
$z = '4294967193';
$x = new Validator($z);
var_dump($x->ipInInt()->execute());

// Валидация по маске    
$arr = array(
    'userName' => 'awdwa', 
    'email' => 'awd',
    'age' => '19',
    'float'
    
);
$y = new MaskValidator($arr, $maskTest);
?>
<pre><?php //var_dump($y->answer);?></pre>
<pre><?php var_dump($arr);?></pre>
