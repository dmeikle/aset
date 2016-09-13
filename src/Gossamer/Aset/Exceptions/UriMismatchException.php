<?php
/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

/** *
 * Author: dave
 * Date: 9/13/2016
 * Time: 2:24 PM
 */

namespace Gossamer\Aset\Exceptions;


class UriMismatchException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Url mismatch in parameter type casing library', 425);
    }
}