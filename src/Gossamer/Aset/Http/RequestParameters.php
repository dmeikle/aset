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
 * Time: 11:20 AM
 */

namespace Gossamer\Aset\Http;


class RequestParameters
{

    private $uri;

    private $config;

    private $parameters;

    public function __construct($uri, array $config)
    {
        $this->uri = $uri;
        $this->config = $config;
    }

    /**
     *
     */
    public function getURIParameters()
    {

    }

    private function getParametersFromConfig()
    {
        if (!array_key_exists('parameters', $this->config)) {
            $this->parameters = $this->config['parameters'];
            $this->formatParameters();
        }
    }

    private function formatParameters()
    {
        print_r($this->parameters);
    }
}