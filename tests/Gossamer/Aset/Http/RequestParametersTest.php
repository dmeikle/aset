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
use Gossamer\Aset\Http\RequestParameters;

class RequestParametersTest extends \tests\BaseTest
{

    public function testBasicRouting() {
        $params = new RequestParameters('/members/A0001/receipts/REC1234', $this->getConfig());

        $result = $params->getURIParameters();

        $this->assertTrue(array_key_exists('memberId', $result));
        $this->assertEquals($result['memberId'], 'A0001');
    }

    public function testBasicRoutingNoConfig() {
        $params = new RequestParameters('/members/A0001/receipts/REC1234', $this->getConfigNoPatterns());

        $result = $params->getURIParameters();

        $this->assertEquals($result[0], 'A0001');
    }

    public function testInvalidUri() {
        $params = new RequestParameters('/members/A0001/REC1234', $this->getConfig());
        try{
            $result = $params->getURIParameters();
            $this->fail('Test for invalid Uri should have failed');
        }catch(ParameterNotFoundException $e) {
          
            $this->assertTrue($e->getCode() == 426);
        }

    }

    private function getConfig() {
        return array(
            'pattern'=> 'members/*/receipts/*',
            'parameters' =>
            array(
                'uri' =>
                array(
                    array('index'=>'0','key' => 'memberId', 'type'=> 'string', 'mask'=> '~[^a-zA-Z0-9]+~'),
                    array('index'=>'1','key' => 'receiptId', 'type'=> 'string', 'mask'=> '~[^a-zA-Z0-9]+~')
                )

            )
        );
    }
    private function getConfigNoPatterns() {
        return array(
            'pattern'=> 'members/*/receipts/*'
        );
    }
}