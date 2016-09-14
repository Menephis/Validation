<?php
$maskTest = array(
    'userName' => array(
        'method' => 'latinName',
        'arguments' => array(3,16),
        'required' => true
    ),
    'email' =>array(
        'method' => 'email',
        'arguments' => array(8,20)
    ),
    'age' => array(
        'method' => 'intRanged',
        'arguments' => array(14,20)
    ),
    'float' => array(
        'method' => 'float',
        'arguments' => null,
        'required' => true
    ),
    'url' => array(
        'method' => 'url',
        'arguments' => null
    ),
    'ip' => array(
        'method' => 'ipV4',
        'arguments' => array('int')
    )
);
//var_dumpend($maskTest);
?>
