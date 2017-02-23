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

/**
 * Class RequestParameters
 * @package Gossamer\Aset\Http
 */
class RequestParameters extends AbstractParameters
{


    public function getURIParameters()
    {

        try {
            $params = $this->parseURIParameters();

            if (!is_null($this->parameters) && isset($this->parameters['uri'])) {

                return $this->formatURIParameters($params, $this->parameters['uri']);

            }

            return $params;
        } catch (ParameterNotFoundException $e) {
            throw $e;
        }
    }

    public function getPostParameters()
    {
        try {
            if (!is_null($this->parameters) && isset($this->parameters['post'])) {

                if (array_key_exists('pessimistic', $this->config) && $this->config['pessimistic'] == 'true') {

                    return $this->formatPostPessimisticParameters($this->postedParameters, $this->parameters['post']);
                }
                return $this->formatPostParameters($this->postedParameters, $this->parameters['post']);

            }
            return $this->formatPostParameters($this->postedParameters, array());
        } catch (ParameterNotFoundException $e) {
            throw $e;
        }
    }


    public function getQueryStringParameters(array $params)
    {

        try {
            if (!is_null($this->parameters) && isset($this->parameters['query'])) {

                if (array_key_exists('pessimistic', $this->config) && $this->config['pessimistic'] == 'true') {
                    return $this->formatPostPessimisticParameters($params, $this->parameters['query']);
                }
                return $this->formatPostParameters($params, $this->parameters['query']);

            }
            return $this->formatPostParameters($params, array());
        } catch (ParameterNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new UriMismatchException();
        }

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