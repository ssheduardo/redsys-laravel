<?php

namespace Ssheduardo\Redsys\Tests;

use Sermepa\Tpv\Tpv;

class RedsysTest extends TestCase
{
    private Tpv $tpv;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tpv = new Tpv();
    }

    public function test_can_set_amount(): void
    {
        $this->tpv->setAmount(1000);
        $this->assertTrue(true);
    }

    public function test_can_set_order(): void
    {
        $this->tpv->setOrder('12345');
        $this->assertTrue(true);
    }

    public function test_can_set_merchant_code(): void
    {
        $this->tpv->setMerchantcode('000000000');
        $this->assertTrue(true);
    }

    public function test_can_set_currency(): void
    {
        $this->tpv->setCurrency('978');
        $this->assertTrue(true);
    }

    public function test_can_set_transaction_type(): void
    {
        $this->tpv->setTransactiontype('0');
        $this->assertTrue(true);
    }

    public function test_can_set_terminal(): void
    {
        $this->tpv->setTerminal('1');
        $this->assertTrue(true);
    }

    public function test_can_set_notification_url(): void
    {
        $this->tpv->setNotification('http://test.com/notify');
        $this->assertTrue(true);
    }

    public function test_can_set_url_ok(): void
    {
        $this->tpv->setUrlOk('http://test.com/ok');
        $this->assertTrue(true);
    }

    public function test_can_set_url_ko(): void
    {
        $this->tpv->setUrlKo('http://test.com/ko');
        $this->assertTrue(true);
    }

    public function test_can_set_environment(): void
    {
        $this->tpv->setEnviroment('test');
        $url = $this->tpv->getEnviroment();
        $this->assertStringContainsString('sis-t.redsys.es', $url);
    }

    public function test_can_generate_merchant_signature(): void
    {
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $key = $this->getMerchantParams()['key'];
        $signature = $this->tpv->generateMerchantSignature($key);

        $this->assertNotEmpty($signature);
    }

    public function test_can_create_form(): void
    {
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');
        $this->tpv->setMethod('T');
        $this->tpv->setNotification('http://test.com/notify');
        $this->tpv->setUrlOk('http://test.com/ok');
        $this->tpv->setUrlKo('http://test.com/ko');
        $this->tpv->setVersion('HMAC_SHA256_V1');
        $this->tpv->setTradeName('Test Shop');
        $this->tpv->setTitular('Test Customer');
        $this->tpv->setProductDescription('Test product');
        $this->tpv->setEnviroment('test');

        $key = $this->getMerchantParams()['key'];
        $signature = $this->tpv->generateMerchantSignature($key);
        $this->tpv->setMerchantSignature($signature);

        $form = $this->tpv->createForm();

        $this->assertStringContainsString('<form', $form);
        $this->assertStringContainsString('sis-t.redsys.es', $form);
        $this->assertStringContainsString('Ds_MerchantParameters', $form);
        $this->assertStringContainsString('Ds_Signature', $form);
    }

    public function test_can_decode_merchant_parameters(): void
    {
        $params = [
            'Ds_Amount' => '1000',
            'Ds_Order' => '12345',
            'Ds_MerchantCode' => '000000000',
            'Ds_Currency' => '978',
            'Ds_Response' => '0000',
        ];

        $encoded = base64_encode(json_encode($params));

        $decoded = $this->tpv->getMerchantParameters($encoded);

        $this->assertArrayHasKey('Ds_Amount', $decoded);
        $this->assertArrayHasKey('Ds_Order', $decoded);
    }
}
