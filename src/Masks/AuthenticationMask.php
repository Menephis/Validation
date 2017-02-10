<?php
/**
* Authentication Mask
*
* @package Validation
* @subpackage Masks 
* @author Kurapov Pavel <spawnrus56@gmail.com>
*/
namespace Menephis\MaskValidator\Masks;

use Menephis\MaskValidator\Masks\AbstractMask;
use mustangostang\Spyc;
/**
* AuthenticationMask for validate authentication data
*/
class AuthenticationMask extends AbstractMask
{
    /**
    * @var array $maskComposite
    */
    protected $maskComposite = array(
        'default' => array(
            'email' => array(
                'method' => 'email', 
                'arguments' => null,
                'required' =>  false
            ),
            'password' => array(
                'method' => 'password',
                'arguments' => array( 
                    0 => 8,
                    1 => 30
                ),
                'required' => true
            )
        )
    );
}
?>
