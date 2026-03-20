<?php

namespace Ssheduardo\Redsys\Tests;

use Sermepa\Tpv\Tpv;

class RedsysSignatureTest extends TestCase
{
    private Tpv $tpv;
    private string $key;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tpv = new Tpv();
        $this->key = $this->getMerchantParams()['key'];
    }

    public function test_generate_signature_with_all_required_params(): void
    {
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $signature = $this->tpv->generateMerchantSignature($this->key);

        $this->assertNotEmpty($signature);
        $this->assertIsString($signature);
    }

    public function test_signature_changes_with_different_amount(): void
    {
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $this->tpv->setAmount(1000);
        $signature1 = $this->tpv->generateMerchantSignature($this->key);

        $this->tpv->setAmount(2000);
        $signature2 = $this->tpv->generateMerchantSignature($this->key);

        $this->assertNotEquals($signature1, $signature2);
    }

    public function test_signature_changes_with_different_order(): void
    {
        $this->tpv->setAmount(1000);
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $this->tpv->setOrder('11111');
        $signature1 = $this->tpv->generateMerchantSignature($this->key);

        $this->tpv->setOrder('22222');
        $signature2 = $this->tpv->generateMerchantSignature($this->key);

        $this->assertNotEquals($signature1, $signature2);
    }

    public function test_signature_is_base64_encoded(): void
    {
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $signature = $this->tpv->generateMerchantSignature($this->key);

        $decoded = base64_decode($signature, true);
        $this->assertNotFalse($decoded);
    }

    public function test_signature_notification_generation(): void
    {
        $params = [
            'Ds_Amount' => '1000',
            'Ds_Order' => '12345',
            'Ds_MerchantCode' => '000000000',
            'Ds_Currency' => '978',
            'Ds_Response' => '0000',
        ];

        $encoded = base64_encode(json_encode($params));

        $signatureNotification = $this->tpv->generateMerchantSignatureNotification($this->key, $encoded);

        $this->assertNotEmpty($signatureNotification);
        $this->assertIsString($signatureNotification);
    }

    public function test_check_payment_notification_with_valid_signature(): void
    {
        $params = [
            'Ds_Amount' => '1000',
            'Ds_Order' => '12345',
            'Ds_MerchantCode' => '000000000',
            'Ds_Currency' => '978',
            'Ds_TransactionType' => '0',
            'Ds_Terminal' => '1',
        ];

        $encoded = base64_encode(json_encode($params));
        $signatureNotification = $this->tpv->generateMerchantSignatureNotification($this->key, $encoded);

        $result = $this->tpv->check($this->key, [
            'Ds_MerchantParameters' => $encoded,
            'Ds_Signature' => $signatureNotification,
            'Ds_SignatureVersion' => 'HMAC_SHA256_V1',
        ]);

        $this->assertTrue($result);
    }

    public function test_check_payment_notification_with_invalid_signature(): void
    {
        $params = [
            'Ds_Amount' => '1000',
            'Ds_Order' => '12345',
        ];

        $encoded = base64_encode(json_encode($params));

        $result = $this->tpv->check($this->key, [
            'Ds_MerchantParameters' => $encoded,
            'Ds_Signature' => 'invalid_signature',
            'Ds_SignatureVersion' => 'HMAC_SHA256_V1',
        ]);

        $this->assertFalse($result);
    }

    public function test_check_with_tampered_parameters(): void
    {
        $params = [
            'Ds_Amount' => '1000',
            'Ds_Order' => '12345',
            'Ds_MerchantCode' => '000000000',
            'Ds_Currency' => '978',
            'Ds_TransactionType' => '0',
            'Ds_Terminal' => '1',
        ];

        $encoded = base64_encode(json_encode($params));
        $signatureNotification = $this->tpv->generateMerchantSignatureNotification($this->key, $encoded);

        $tamperedParams = base64_encode(json_encode([
            'Ds_Amount' => '9999',
            'Ds_Order' => '12345',
        ]));

        $result = $this->tpv->check($this->key, [
            'Ds_MerchantParameters' => $tamperedParams,
            'Ds_Signature' => $signatureNotification,
            'Ds_SignatureVersion' => 'HMAC_SHA256_V1',
        ]);

        $this->assertFalse($result);
    }
}
