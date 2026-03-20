<?php

namespace Ssheduardo\Redsys\Tests;

use Sermepa\Tpv\Tpv;

class RedsysFormTest extends TestCase
{
    private Tpv $tpv;
    private string $key;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tpv = new Tpv();
        $this->key = $this->getMerchantParams()['key'];
    }

    private function setupBasicPayment(): void
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
    }

    public function test_form_contains_signature_version(): void
    {
        $this->setupBasicPayment();

        $signature = $this->tpv->generateMerchantSignature($this->key);
        $this->tpv->setMerchantSignature($signature);

        $form = $this->tpv->createForm();

        $this->assertStringContainsString('HMAC_SHA256_V1', $form);
    }

    public function test_form_contains_all_required_hidden_fields(): void
    {
        $this->setupBasicPayment();

        $signature = $this->tpv->generateMerchantSignature($this->key);
        $this->tpv->setMerchantSignature($signature);

        $form = $this->tpv->createForm();

        $this->assertStringContainsString('name="Ds_MerchantParameters"', $form);
        $this->assertStringContainsString('name="Ds_Signature"', $form);
        $this->assertStringContainsString('name="Ds_SignatureVersion"', $form);
    }

    public function test_form_action_is_sandbox_url(): void
    {
        $this->setupBasicPayment();

        $signature = $this->tpv->generateMerchantSignature($this->key);
        $this->tpv->setMerchantSignature($signature);

        $form = $this->tpv->createForm();

        $this->assertStringContainsString('sis-t.redsys.es:25443/sis/realizarPago', $form);
    }

    public function test_form_has_submit_button(): void
    {
        $this->setupBasicPayment();

        $signature = $this->tpv->generateMerchantSignature($this->key);
        $this->tpv->setMerchantSignature($signature);

        $form = $this->tpv->createForm();

        $this->assertStringContainsString('type="submit"', $form);
    }

    public function test_form_uses_post_method(): void
    {
        $this->setupBasicPayment();

        $signature = $this->tpv->generateMerchantSignature($this->key);
        $this->tpv->setMerchantSignature($signature);

        $form = $this->tpv->createForm();

        $this->assertStringContainsString('method="post"', $form);
    }

    public function test_form_has_id_attribute(): void
    {
        $this->setupBasicPayment();

        $signature = $this->tpv->generateMerchantSignature($this->key);
        $this->tpv->setMerchantSignature($signature);

        $form = $this->tpv->createForm();

        $this->assertStringContainsString('id="redsys_form"', $form);
    }

    public function test_merchant_parameters_is_base64_encoded(): void
    {
        $this->setupBasicPayment();

        $signature = $this->tpv->generateMerchantSignature($this->key);
        $this->tpv->setMerchantSignature($signature);

        $form = $this->tpv->createForm();

        preg_match('/name="Ds_MerchantParameters" value="([^"]*)"/', $form, $matches);

        $this->assertNotEmpty($matches[1]);
        
        $decoded = json_decode(base64_decode($matches[1]), true);
        $this->assertNotNull($decoded);
        $this->assertIsArray($decoded);
    }

    public function test_merchant_parameters_contains_order(): void
    {
        $this->setupBasicPayment();
        $this->tpv->setOrder('999888777');

        $signature = $this->tpv->generateMerchantSignature($this->key);
        $this->tpv->setMerchantSignature($signature);

        $form = $this->tpv->createForm();

        preg_match('/name="Ds_MerchantParameters" value="([^"]*)"/', $form, $matches);
        $decoded = $this->tpv->getMerchantParameters($matches[1]);

        $this->assertEquals('999888777', $decoded['DS_MERCHANT_ORDER']);
    }

    public function test_merchant_parameters_contains_amount(): void
    {
        $this->setupBasicPayment();
        $this->tpv->setAmount(5000);

        $signature = $this->tpv->generateMerchantSignature($this->key);
        $this->tpv->setMerchantSignature($signature);

        $form = $this->tpv->createForm();

        preg_match('/name="Ds_MerchantParameters" value="([^"]*)"/', $form, $matches);
        $decoded = $this->tpv->getMerchantParameters($matches[1]);

        // Amount is multiplied by 100 internally (5000 * 100 = 500000 cents = 50.00 euros)
        $this->assertEquals('500000', $decoded['DS_MERCHANT_AMOUNT']);
    }

    public function test_merchant_parameters_contains_merchant_code(): void
    {
        $this->setupBasicPayment();
        $this->tpv->setMerchantcode('123456789');

        $signature = $this->tpv->generateMerchantSignature($this->key);
        $this->tpv->setMerchantSignature($signature);

        $form = $this->tpv->createForm();

        preg_match('/name="Ds_MerchantParameters" value="([^"]*)"/', $form, $matches);
        $decoded = $this->tpv->getMerchantParameters($matches[1]);

        $this->assertEquals('123456789', $decoded['DS_MERCHANT_MERCHANTCODE']);
    }

    public function test_signature_is_consistent_for_same_params(): void
    {
        $this->setupBasicPayment();

        $signature1 = $this->tpv->generateMerchantSignature($this->key);

        $signature2 = $this->tpv->generateMerchantSignature($this->key);

        $this->assertEquals($signature1, $signature2);
    }

    public function test_signature_is_different_after_changing_params(): void
    {
        $this->setupBasicPayment();

        $signature1 = $this->tpv->generateMerchantSignature($this->key);
        
        $this->tpv->setAmount(9999);
        $signature2 = $this->tpv->generateMerchantSignature($this->key);

        $this->assertNotEquals($signature1, $signature2);
    }

    public function test_live_environment_url(): void
    {
        $this->tpv->setEnviroment('live');
        $url = $this->tpv->getEnviroment();

        $this->assertStringContainsString('sis.redsys.es', $url);
    }
}
