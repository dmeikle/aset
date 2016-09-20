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


use Gossamer\Aset\Exceptions\UriMismatchException;
use Gossamer\Aset\Http\RequestParameters;

class RequestPostedParametersTest extends \tests\BaseTest
{

    public function testBasicRouting() {
        $params = new RequestParameters('/members/A0001/receipts/REC1234', $this->getConfig(), $this->getPost());

        $result = $params->getURIParameters();

        $this->assertTrue(array_key_exists('memberId', $result));
        $this->assertEquals($result['memberId'], 'A0001');
    }

    public function testInvalidUri() {
        $params = new RequestParameters('/members/A0001/REC1234', $this->getConfig(), $this->getPost());
        try{
            $result = $params->getURIParameters();
            $this->fail('Test for invalid Uri should have failed');
        }catch(UriMismatchException $e) {

            $this->assertTrue($e->getCode() == 425);
        }

    }

    public function testRequiredField() {
       
        $params = new RequestParameters('/members/A0001/receipts/REC1234', $this->getRequiredConfig(), $this->getPost());

        $result = $params->getURIParameters();

        $this->assertTrue(array_key_exists('memberId', $result));
        $this->assertEquals($result['memberId'], 'A0001');
    }

    private function getPost() {
        return array(
            'receipt_id' => 'W29-0085'
        );
    }

    private function getConfig() {
        return array(
            'pattern'=> 'members/*/receipts/*',
            'parameters' =>
            array(
                array('key' => 'memberId', 'type'=> 'string', 'mask'=> '~[^a-zA-Z0-9]+~'),
                array('key' => 'extraId', 'type'=> 'string', 'mask'=> '~[^a-zA-Z0-9]+~'),
                array('key' => 'receipt_id', 'type'=> 'string', 'mask'=> '~[^a-z\-A-Z0-9]+~', 'keyAs' => 'receiptId', 'method' => 'post')
            )
        );
    }

    private function getRequiredConfig() {
        return array(
            'pattern'=> 'members/*/receipts/*',
            'parameters' =>
                array(
                    array('key' => 'memberId', 'type'=> 'string', 'mask'=> '~[^a-zA-Z0-9]+~'),
                    array('key' => 'extraId', 'type'=> 'string', 'mask'=> '~[^a-zA-Z0-9]+~'),
                    array('key' => 'receipt_id', 'type'=> 'string', 'mask'=> '~[^a-z\-A-Z0-9]+~', 'keyAs' => 'receiptId', 'method' => 'post'),
                    array('key' => 'requiredItem', 'type'=> 'string', 'mask'=> '~[^a-z\-A-Z0-9]+~', 'method' => 'post', 'required'=>'true')
                )
        );
    }
}