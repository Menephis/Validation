<?php
/**
* Mask for test
*
* @package Validation
* @subpackage Masks 
* @author Kurapov Pavel <spawnrus56@gmail.com>
*/
namespace Menephis\MaskValidator\Masks;

use Menephis\MaskValidator\Masks\AbstractMask;
/**
* Test mask for test validator's class method
*/
class TestMask extends AbstractMask
{
    /**
    * @var array $maskComposite
    */
    protected $maskComposite = array(
        'default' => array(
            'minMaxLength' => array(
                'method' => 'minMaxLength',
                'arguments' => array(8, 30),
                'required' => false,
            ),
            'int' => array(
                'method' => 'int',
                'arguments' => null,
                'required' => false,
            ),
            'intRanged' => array(
                'method' => 'intRanged',
                'arguments' => array(9, 20),
                'required' => false,
            ),
            'intPositive' => array(
                'method' => 'intPositive',
                'arguments' => null,
                'required' => false,
            ),
            'intNegative' => array(
                'method' => 'intNegative',
                'arguments' => null,
                'required' => false,
            ),
            'intFromList' => array(
                'method' => 'intFromList',
                'arguments' => null,
                'required' => false,
            ),
            'float' => array(
                'method' => 'float',
                'arguments' => null,
                'required' => false,
            ),
            'floatPositive' => array(
                'method' => 'floatPositive',
                'arguments' => null,
                'required' => false,
            ),
            'floatNegative' => array(
                'method' => 'floatNegative',
                'arguments' => null,
                'required' => false,
            ),
            'floatFromList' => array(
                'method' => 'floatFromList',
                'arguments' => null,
                'required' => false,
            ),
            'boolean' => array(
                'method' => 'boolean',
                'arguments' => null,
                'required' => false,
            ),
            'latinName' => array(
                'method' => 'latinName',
                'arguments' => array(8, 30),
                'required' => false,
            ),
            'password' => array(
                'method' => 'password',
                'arguments' => array(8, 30),
                'required' => false
            ),
            'email' =>array(
                'method' => 'email',
                'arguments' => null,
                'required' => false,
            ),
            'ip' => array(
                'method' => 'ip',
                'arguments' => null,
                'required' => false
            ),
            'ipInInt' => array(
                'method' => 'ipInInt',
                'arguments' => null,
                'required' => false
            ),
            'ipV4' => array(
                'method' => 'ipV4',
                'arguments' => null,
                'required' => false
            ),
            'ipV6' => array(
                'method' => 'ipV6',
                'arguments' => null,
                'required' => false
            ),
            'ipRanged' => array(
                'method' => 'ipRanged',
                'arguments' => array('127.0.0.1', '225.225.225.225'),
                'required' => false
            ),
            'url' => array(
                'method' => 'url',
                'arguments' => null,
                'required' => false
            ),
        )
    );
}
?>
