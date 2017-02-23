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
 * Date: 2/22/2017
 * Time: 1:57 PM
 */

namespace Gossamer\Aset\Http;


use Gossamer\Aset\Casting\ParamTypeCaster;
use Gossamer\Aset\Exceptions\ParameterNotFoundException;
use Gossamer\Aset\Utils\UriParser;

abstract class AbstractParameters
{
    protected $uri;

    protected $config;

    protected $parameters = null;

    protected $postedParameters = null;

    public function __construct($uri, array $config, array $post = null)
    {
        
        $this->uri = $uri;
        $this->config = $config;
        $this->postedParameters = $post;
        $this->parameters = array_key_exists('parameters', $config) ? $config['parameters'] : array();
    }

    /**
     * @return array
     */
    protected function parseURIParameters()
    {
        $parser = new UriParser();
        if (array_key_exists('uri', $this->config)) {
            return $parser->getParameterIndexes($this->uri, $this->config['uri']);
        }

        return $parser->getParameterIndexes($this->uri, $this->config['pattern']);
    }

    protected function formatURIParameters(array $params, array $config)
    {

        $retval = array();
        $caster = new ParamTypeCaster();
        foreach ($config as $parameter) {
            $key = $parameter['key'];
            $item = array_shift($params);
            $retval[$key] = $caster->cast($parameter, $item);
        }

        return $retval;
    }

    protected function parseParameters()
    {

        $pessimistic = isset($this->parameters['pessimistic']) && $this->parameters['pessimistic'] == 'true';
        if ($pessimistic) {

        }

        $params = $this->formatPostParameters();
    }

    protected function formatPostParameters(array $params, array $config)
    {
        $retval = array();
        $caster = new ParamTypeCaster();
        $configKeys = array_column($config, 'key');

        //first go through the posted params and see what we DO have
        foreach ($params as $postedKey => $postedParameter) {

            $parameter = $this->getParameterByKey($postedKey, $config);
            if (is_null($parameter)) {
                //not in our list, but we aren't pessimistic - pass it through
                $retval[$postedKey] = $postedParameter;
            } else {
                $key = array_key_exists('keyAs', $parameter) ? $parameter['keyAs'] : $parameter['key'];
                $retval[$key] = $caster->cast($parameter, $postedParameter);
            }
        }

        //now find if there are any required ones we did not receive
        $usedKeys = array_keys($retval);
        foreach ($configKeys as $key) {
            if (!in_array($key, $usedKeys)) {
                //we found a config param that does not exist in the form - is it required?
                $parameter = $this->getParameterByKey($key, $config);
                if (array_key_exists('required', $parameter) && $parameter['required'] == 'true') {
                    throw new ParameterNotFoundException($key);
                }
            }
        }

        return $retval;
    }

    /**
     * @param array $params
     * @param array $config
     * @return array
     * @throws ParameterNotFoundException
     */
    protected function formatPostPessimisticParameters(array $params, array $config)
    {
        $retval = array();
        $caster = new ParamTypeCaster();

        foreach ($config as $parameter) {
            $key = array_key_exists('keyAs', $parameter) ? $parameter['keyAs'] : $parameter['key'];
            //legacy system used optional param
            //$required = !(array_key_exists('optional', $parameter) && $parameter['optional'] == 'true');
            $required = (array_key_exists('required', $parameter) && $parameter['required'] == 'true');

            if (!array_key_exists($parameter['key'], $params)) {
                if ($required) {
                    throw new ParameterNotFoundException($parameter['key']);
                }
            } else {
                $retval[$key] = $caster->cast($parameter, $params[$parameter['key']]);
            }

        }

        return $retval;
    }

    protected function getParameterByKey($key, $parameters)
    {

        foreach ($parameters as $parameter) {

            if (array_key_exists('key', $parameter) && $parameter['key'] == $key) {

                return $parameter;
            }
        }

        return null;
    }

//
//
//    protected function formatQueryStringPessimisticParameters(array $params, array $config)
//    {
//        $retval = array();
//        $caster = new ParamTypeCaster();
//
//        foreach ($config as $parameter) {
//                $key = array_key_exists('keyAs', $parameter) ? $parameter['keyAs'] : $parameter['key'];
//
//                $retval[$key] = $caster->cast($parameter, array_shift($params));
//        }
//
//        return $retval;
//    }
//
//    protected function formatQueryStringParameters(array $params, array $config)
//    {
//        $retval = array();
//        $caster = new ParamTypeCaster();
//        foreach($params as $paramKey => $value) {
//            $parameter = $this->getParameterByKey($postedKey, $config);
//            if (is_null($parameter)) {
//                //not in our list, but we aren't pessimistic - pass it through
//                $retval[$postedKey] = $postedParameter;
//            } else {
//                $key = array_key_exists('keyAs', $parameter) ? $parameter['keyAs'] : $parameter['key'];
//                $retval[$key] = $caster->cast($parameter, $postedParameter);
//            }
//        }
//        foreach ($config as $parameter) {
//            $key = array_key_exists('keyAs', $parameter) ? $parameter['keyAs'] : $parameter['key'];
//
//            $retval[$key] = $caster->cast($parameter, array_shift($params));
//        }
//
//        return $retval;
//    }

//    protected function formatParameters(array $params, array $config)
//    {
//        $retval = array();
//        $caster = new ParamTypeCaster();
//        $configKeys = array_column($this->parameters, 'key');
//
//        //first go through the posted params and see what we DO have
//        foreach ($this->postedParameters as $postedKey => $postedParameter) {
//
//            if (in_array($postedKey, $configKeys)) {
//
//                $parameter = $this->getParameterByKey($postedKey);
//
//                $key = array_key_exists('keyAs', $parameter) ? $parameter['keyAs'] : $parameter['key'];
//                $retval[$key] = $caster->cast($parameter, $postedParameter);
//            }
//        }
//
//        //now find if there are any required ones we did not receive
//        $usedKeys = array_keys($retval);
//        foreach ($configKeys as $key) {
//            if (!in_array($key, $usedKeys)) {
//                //we found a config param that does not exist in the form - is it required?
//                $parameter = $this->getParameterByKey($key);
//                if (array_key_exists('required', $parameter) && $parameter['required'] == 'true') {
//                    throw new ParameterNotFoundException($key);
//                }
//            }
//        }
//
//        return $retval;
//    }
}