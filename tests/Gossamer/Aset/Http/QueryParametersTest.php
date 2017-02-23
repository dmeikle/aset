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
 * Time: 12:03 PM
 */

namespace tests\Gossamer\Aset\Http;


use Gossamer\Aset\Exceptions\ParameterNotFoundException;
use Gossamer\Aset\Exceptions\UriMismatchException;
use Gossamer\Aset\Http\RequestParameters;

class QueryParametersTest extends \tests\BaseTest
{

    public function testBasicRouting()
    {
        $params = new RequestParameters('/members/A0001/receipts/REC1234', $this->getConfig());
        $queryString = array(
            'name' => 'Dave',
            'age' => 'infinite'
        );
        $result = $params->getQueryStringParameters($queryString);

        $this->assertTrue(array_key_exists('age', $result));
        $this->assertEquals($result['name'], 'Dave');
    }

    public function testPessimisticRouting()
    {
        $params = new RequestParameters('/members/A0001/receipts/REC1234', $this->getPessimisticConfig());
        $queryString = array(
            'name' => 'Dave',
            'age' => 'infinite'
        );
        $result = $params->getQueryStringParameters($queryString);

        $this->assertTrue(!array_key_exists('age', $result));
        $this->assertEquals($result['name'], 'Dave');
    }

    public function testBasicRoutingNoConfig() {
        $params = new RequestParameters('/members/A0001/receipts/REC1234', array());
        $queryString = array(
            'name' => 'Dave',
            'age' => 'infinite'
        );
        $result = $params->getQueryStringParameters($queryString);

        $this->assertTrue(array_key_exists('age', $result));
        $this->assertEquals($result['name'], 'Dave');
    }


    private function getConfig()
    {
        return array(
            'pattern' => 'members/*/receipts/*',
            'parameters' =>
                array(
                    'query' =>
                        array(
                            array('key' => 'name', 'type' => 'string', 'mask' => '~[^a-zA-Z ]+~'),
                        )

                )
        );
    }

    private function getPessimisticConfig()
    {
        return array(
            'pattern' => 'members/*/receipts/*',
            'pessimistic' => 'true',
            'parameters' =>
                array(
                    'query' =>
                        array(
                            array('key' => 'name', 'type' => 'string', 'mask' => '~[^a-zA-Z ]+~'),
                        )

                )
        );
    }
}