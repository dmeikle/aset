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
 * Time: 1:40 PM
 */

namespace Gossamer\Aset\Utils;


use Gossamer\Aset\Exceptions\ParameterNotFoundException;

class UriParser
{
    public function getParameterIndexes($uri, $pattern)
    {

        $uriChunks = explode('/', $uri);
        if ($uriChunks[0] == '') {
            array_shift($uriChunks);
        }

        $patternChunks = explode('/', $pattern);
        $retval = array();
        $index = 0;

        foreach ($patternChunks as $chunk) {
            
            if ($chunk == '*') {
                if(!array_key_exists($index, $uriChunks)) {
                    throw new ParameterNotFoundException("Index #$index");
                }
                $retval[] = $uriChunks[$index];
            }

            $index++;
        }

        return $retval;
    }
}