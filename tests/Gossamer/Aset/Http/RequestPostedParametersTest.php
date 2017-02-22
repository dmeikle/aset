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

//    public function testBasicRouting()
//    {
//        $params = new RequestParameters('/members/A0001/receipts/REC1234', $this->getConfig(), $this->getPost());
//
//        $result = $params->getURIParameters();
//
//        $this->assertTrue(array_key_exists('memberId', $result));
//        $this->assertEquals($result['memberId'], 'A0001');
//    }
//
//    public function testInvalidUri()
//    {
//        $params = new RequestParameters('/members/A0001/REC1234', $this->getConfig(), $this->getPost());
//
//        try {
//            $result = $params->getURIParameters();
//            $this->fail('Test for invalid Uri should have failed');
//        } catch (UriMismatchException $e) {
//            $this->assertTrue($e->getCode() == 425);
//        }
//    }
//
//    public function testMissingRequiredField()
//    {
//
//        $params = new RequestParameters('/members/A0001/receipts/REC1234', $this->getRequiredConfig(), $this->getPost());
//        try {
//            $result = $params->getURIParameters();
//        } catch (\Exception $e) {
//            $this->assertEquals($e->getCode(), '426');
//        }
//
//    }
//
//    public function testMissingOptionalField()
//    {
//        $params = new RequestParameters('/members/A0001/receipts/REC1234', $this->getOptionalConfig(), $this->getPost());
//        $result = array();
//        try {
//            $result = $params->getURIParameters();
//
//        } catch (\Exception $e) {
//            echo $e->getMessage();
//            $this->fail('Optional field should have been acceptable');
//        }
//
//        $this->assertTrue(array_key_exists('memberId', $result));
//
//    }

    public function testOptionalFields()
    {
        $params = new RequestParameters('/shoppingcart/basket/add', $this->getBasketConfig(), $this->getBasketPost());
        $result = array();
        try {
            $result = $params->getURIParameters();

        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->fail('Optional field should have been acceptable');
        }

       print_r($result);

    }


    private function getPost()
    {
        return array(
            'receipt_id' => 'W29-0085'
        );
    }

    private function getConfig()
    {
        return array(
            'pattern' => 'members/*/receipts/*',
            'parameters' =>
                array(
                    array('key' => 'memberId', 'type' => 'string', 'mask' => '~[^a-zA-Z0-9]+~', 'method' => 'uri'),
                    array('key' => 'extraId', 'type' => 'string', 'mask' => '~[^a-zA-Z0-9]+~', 'method' => 'uri'),
                    array('key' => 'receipt_id', 'type' => 'string', 'mask' => '~[^a-z\-A-Z0-9]+~', 'keyAs' => 'receiptId', 'method' => 'post')
                )
        );
    }

    private function getRequiredConfig()
    {
        return array(
            'pattern' => 'members/*/receipts/*',
            'parameters' =>
                array(
                    array('key' => 'memberId', 'type' => 'string', 'mask' => '~[^a-zA-Z0-9]+~', 'method' => 'uri'),
                    array('key' => 'extraId', 'type' => 'string', 'mask' => '~[^a-zA-Z0-9]+~', 'method' => 'uri'),
                    array('key' => 'receipt_id', 'type' => 'string', 'mask' => '~[^a-z\-A-Z0-9]+~', 'keyAs' => 'receiptId', 'method' => 'post'),
                    array('key' => 'requiredItem', 'type' => 'string', 'mask' => '~[^a-z\-A-Z0-9]+~', 'method' => 'post', 'required' => 'true')
                )
        );
    }

    private function getOptionalConfig()
    {
        return array(
            'pattern' => 'members/*/receipts/*',
            'parameters' =>
                array(
                    array('key' => 'memberId', 'type' => 'string', 'mask' => '~[^a-zA-Z0-9]+~', 'method' => 'uri'),
                    array('key' => 'extraId', 'type' => 'string', 'mask' => '~[^a-zA-Z0-9]+~', 'method' => 'uri'),
                    array('key' => 'receipt_id', 'type' => 'string', 'mask' => '~[^a-z\-A-Z0-9]+~', 'keyAs' => 'receiptId', 'method' => 'post'),
                    array('key' => 'optionalItem', 'type' => 'string', 'mask' => '~[^a-z\-A-Z0-9]+~', 'method' => 'post', 'required' => 'false')
                )
        );
    }

    private function getBasketConfig()
    {
        return array(
            'pattern' => 'shoppingcart/basket/add',
            'pessimistic' => false,
            'parameters' =>
                array(
                    array('key' => 'productNumber', 'type' => 'string', 'mask' => '~[^a-zA-Z0-9]+~', 'method' => 'post'),
                    array('key' => 'quantity', 'type' => 'int', 'method' => 'post'),
                    array('key' => 'memberID', 'type' => 'string', 'mask' => '~[^a-z\-A-Z0-9]+~', 'method' => 'post')
                )
        );
    }

    private function getBasketPost() {
        return array(
            'productNumber' => 'test123',
            'quantity' => '23',
            'memberID' => 'A0023',
            'extra1' => 'a1',
            'extra2' => 'a2'
        );
    }
//CreditCard[cardNumber]:4111111111111111
//CreditCard[cvv]:123
//CreditCard[year]:2021
//CreditCard[month]:10
//Client[firstname]:test
//Client[lastname]:test
//Client[company]:test company
//Client[address2]:1234 test street
//Client[city]:test city
//Client[state]:BC
//Client[zip]:V3R 4R4
//Client[country]:canada
//Client[email]:dave@binghan.net
//Client[mobile]:123-123-1233
//Client[memberID]:BB0033
//CreditCard[holderName]:test test
}