<?php
$maskTest = array(
    'userName' => array(
        'latinName' => array(3,16),
        'required' => null
    ),
    'email' =>array(
        'email' => array(8,20),
        'execute' => null
    ),
    'age' => array(
        'intRanged' => array(14,20),
        'execute' => null
        
    ),
    'float' => array(
        'float' => null,
        'execute' => null
    ),
    'url' => array(
        'url' => null,
        'execute' => null
    )
);
?>
