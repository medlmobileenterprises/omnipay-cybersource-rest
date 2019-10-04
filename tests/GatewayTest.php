<?php

namespace Omnipay\CybersourceRest;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    public $gateway;

    /** @var array */
    public $options = array();

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setTestMode(true);
        $this->gateway->setMerchantId('testrest');
        $this->gateway->setMerchantKey('08c94330-f618-42a3-b09d-e1e43be5efda');
        $this->gateway->setMerchantSecret('yBJxy6LjM2TmcPGu+GaJrHtkke25fPpUX+UY6/L/1tE=');
    }

    public function testAuthorize()
    {
        $response = $this->gateway->authorize($this->options)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getMessage());
    }
}