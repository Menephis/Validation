<?php
$maskTest = array(
    'userName' => array(
        'latinName' => array(3,16),
        'required' => true
    ),
    'email' =>array(
        'email' => array(8,20),
    ),
    'age' => array(
        'intRanged' => array(14,20),
    ),
    'float' => array(
        'float' => null
    ),
    'url' => array(
        'url' => null
    ),
    'ip' => array(
        'ipV4' => array('int')
    )
);
?>
