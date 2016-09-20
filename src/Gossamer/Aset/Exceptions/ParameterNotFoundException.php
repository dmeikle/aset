<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 9/13/2016
 * Time: 9:55 PM
 */

namespace Gossamer\Aset\Exceptions;


class ParameterNotFoundException extends \Exception
{
    public function __construct($key)
    {
        parent::__construct($key . ' does not exist in the posted form', 426);
    }
}