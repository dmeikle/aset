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


use Gossamer\Aset\Casting\ParamTypeCaster;
use Gossamer\Aset\Exceptions\ParameterNotFoundException;
use Gossamer\Aset\Exceptions\UriMismatchException;
use Gossamer\Aset\Utils\UriParser;

/**
 * Class RequestParameters
 * @package Gossamer\Aset\Http
 */
class RequestParameters
{

    private $uri;

    private $config;

    private $parameters;

    private $postedParameters;

    public function __construct($uri, array $config, array $post = null)
    {
        $this->uri = $uri;
        $this->config = $config;
        $this->postedParameters = $post;
    }

    /**
     * @return array
     */
    public function getURIParameters()
    {
        print_r($this->config);
        $pessimistic = (array_key_exists('pessimistic', $this->config) && $this->config['pessimistic'] == 'true');
        if (array_key_exists('parameters', $this->config)) {
            $this->parameters = $this->config['parameters'];
            try {
                $params = $this->parseParameters();
                
                if($pessimistic) {
                    return $this->formatParametersPessimistic($params);
                }
                
                return $this->formatParameters($params);
            } catch (ParameterNotFoundException $e) {
                throw $e;
            } catch (\Exception $e) {
                die($e->getTrace());
                throw new UriMismatchException();
            }

        }

    }

    public function getQueryStringParameters(array $params)
    {

        if (array_key_exists('parameters', $this->config)) {

            $this->parameters = $this->config['parameters'];
            try {

                return $this->formatQueryStringParameters($params);
            } catch (ParameterNotFoundException $e) {
                throw $e;
            } catch (\Exception $e) {
                throw new UriMismatchException();
            }

        }

    }
    
    private function formatParameters(array $params) {
        $retval = array();
        $caster = new ParamTypeCaster();
        
        //first go through the posted params and see what we DO have
        foreach($this->postedParameters as $postedKey => $postedParameter) {
            if(array_key_exists($postedKey, $this->parameters)) {
                $key = array_key_exists('keyAs', $this->parameters[$postedKey]) ? $this->parameters[$postedKey]['keyAs'] : $this->parameters[$postedKey]['key'];                
                $retval[$key] = $caster->cast($this->parameters[$postedKey], $postedParameter);     
            }            
        }
        die('here');
        //now find if there are any required ones we did not receive
        $usedKeys = array_keys($retval);
        $configKeys = array_column($this->parameters, 'key');
        foreach($configKeys as $key) {
            if(!in_array($key, $usedKeys)) {
                $parameter = $this->getParameterByKey($key);
                if(array_key_exists('required', $parameter) && $parameter['required'] == 'true') {
                    throw new ParameterNotFoundException($key);
                }
            }
        }

        return $retval;
    }

    private function getParameterByKey($key) {
        foreach($this->parameters as $parameter) {
            if(array_key_exists($key, $parameter) && $parameter['key'] == $key) {
                return $parameter;
            }
        }
    }

    /**
     * @param array $params
     * @return array
     */
    private function formatParametersPessimistic(array $params)
    {
        $retval = array();
        $caster = new ParamTypeCaster();
echo "here is params\r\n";
        print_r($this->postedParameters);
        foreach ($this->parameters as $parameter) {

            $key = array_key_exists('keyAs', $parameter) ? $parameter['keyAs'] : $parameter['key'];
echo "here is key $key\r\n";
            if (array_key_exists('method', $parameter) && strtolower($parameter['method']) == 'post') {
           echo "inside post\r\n";
                //legacy system used optional param
                //$required = !(array_key_exists('optional', $parameter) && $parameter['optional'] == 'true');
                $required = (array_key_exists('required', $parameter) && $parameter['required'] == 'true');

                if (!array_key_exists($parameter['key'], $this->postedParameters)) {
                    if ($required) {
                        throw new ParameterNotFoundException($parameter['key']);
                    }
                    //$retval[$key] = '';
                } else {
                    $retval[$key] = $caster->cast($parameter, $this->postedParameters[$parameter['key']]);
                }

            } elseif (array_key_exists('method', $parameter) &&
                (strtolower($parameter['method']) == 'get' || strtolower($parameter['method']) == 'uri')) {
echo "ahift\r\n";
                $retval[$key] = $caster->cast($parameter, array_shift($params));
            } else{
                echo "unkown method\r\n";
            }
        }

        return $retval;
    }

    private function formatQueryStringParameters(array $params) {
        $retval = array();
        $caster = new ParamTypeCaster();

        foreach ($this->parameters as $parameter) {
            if (array_key_exists('method', $parameter) && strtolower($parameter['method']) == 'query') {
                $key = array_key_exists('keyAs', $parameter) ? $parameter['keyAs'] : $parameter['key'];

                $retval[$key] = $caster->cast($parameter, array_shift($params));
            }
        }

        return $retval;
    }

    /**
     * @return array
     */
    private function parseParameters()
    {
        $parser = new UriParser();
        if (array_key_exists('uri', $this->config)) {
            return $parser->getParameterIndexes($this->uri, $this->config['uri']);
        }

        return $parser->getParameterIndexes($this->uri, $this->config['pattern']);
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    public function setPostedParameters(array $params)
    {
        $this->postedParameters = $params;
    }

    public function getPostedParameter($key, $required)
    {
        if (!array_key_exists($key, $this->postedParameters)) {
            if ($required) {
                throw new ParameterNotFoundException($key . ' does not exist in posted form');
            }

            return;
        }

        return $this->postedParameters[$key];
    }

    public function getPost()
    {
        return $this->postedParameters;
    }
}