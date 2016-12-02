<?php
/**
* Authentication Mask
*
* @package Validation
* @subpackage Masks 
* @author Kurapov Pavel <spawnrus56@gmail.com>
*/
namespace kurapov\kurapov_validate\Masks;

use kurapov\kurapov_validate\Masks\AbstractMask;
/**
* AuthenticationMask for validate authentication data
*/
class AuthenticationMask extends AbstractMask
{
    /**
    * Return rules for validator
    *
    * @return array
    */
    public function getMask()
    {
        return array(
            'email' =>array(
                'method' => 'email',
                'arguments' => null,
                'required' => true
            ),
            'password' => array(
                'method' => 'password',
                'arguments' => array(8, 30),
                'required' => true
            )
        );
    }
}
?>
