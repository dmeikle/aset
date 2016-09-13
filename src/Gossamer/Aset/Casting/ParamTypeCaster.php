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
 * Time: 1:47 PM
 */

namespace Gossamer\Aset\Casting;


class ParamTypeCaster
{
    private $castTypes = array();

    public function cast(array $parameter, $value) {
        $mask = array_key_exists('mask', $parameter) ? $parameter['mask'] : null;

        return $this->getType($parameter['type'], $value, $mask);
    }

    private function getType($type, $value, $mask = null) {
        switch($type) {
            case 'float':
                return floatval($value);
            case 'int':
                return intval($value);
            case 'bool':
            case 'boolean':
                if($value == '1' || $value =='0') {
                    return $value;
                }
                if(strtolower($value) =='true' || strtolower($value) == 'false') {
                    return $value;
                }

            return boolval($value);
            case 'string':
                if(!is_null($mask)) {
                    return preg_replace($mask, '', $value);
                }

            return $value;
        }

    }
}