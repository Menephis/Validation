<?php
require_once 'Validator.class.php';
//require_once 'MaskValidator.class.php';
require_once 'masks.php';

// Простая валидация 
$z = '4294967193';
$x = new Validator();
//var_dump($x->ipInInt()->execute());

// Валидация по маске    
$arr = array(
    'userName' => 'awdwa', 
    'email' => 'awd',
    'age' => '19',
    'float' => '2.3',
    'url' => 'http://localhost.ru',
    'ip' => '127.56.1.1'
    
);
$y = new Validator();
$y($arr, $maskTest);

?>
<pre><?php var_dump($y->_answer);?></pre>
<pre><?php var_dump($arr);?></pre>
<pre><?php echo($y->_error);?></pre>
