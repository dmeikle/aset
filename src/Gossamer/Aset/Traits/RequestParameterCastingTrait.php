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
 * Date: 10/3/2016
 * Time: 2:58 PM
 */

namespace Gossamer\Aset\Traits;


trait RequestParameterCastingTrait
{

    protected function castParameters($uri, array $nodeConfig, array $params) {
        $requestParameters = new \Gossamer\Aset\Http\RequestParameters($uri, $nodeConfig, $this->container->get('HTTPRequest')->getPost());

        return $requestParameters->getURIParameters();
    }

}