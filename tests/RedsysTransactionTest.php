<?php

namespace Ssheduardo\Redsys\Tests;

use Sermepa\Tpv\Tpv;

class RedsysTransactionTest extends TestCase
{
    private Tpv $tpv;
    private string $key;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tpv = new Tpv();
        $this->key = $this->getMerchantParams()['key'];
    }

    public function test_transaction_type_authorization(): void
    {
        $this->tpv->setTransactiontype('0');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('0', $decoded['DS_MERCHANT_TRANSACTIONTYPE']);
    }

    public function test_transaction_type_preauthorization(): void
    {
        $this->tpv->setTransactiontype('1');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('1', $decoded['DS_MERCHANT_TRANSACTIONTYPE']);
    }

    public function test_transaction_type_refund(): void
    {
        $this->tpv->setTransactiontype('3');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('3', $decoded['DS_MERCHANT_TRANSACTIONTYPE']);
    }

    public function test_currency_euro(): void
    {
        $this->tpv->setCurrency('978');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('978', $decoded['DS_MERCHANT_CURRENCY']);
    }

    public function test_currency_usd(): void
    {
        $this->tpv->setCurrency('840');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('840', $decoded['DS_MERCHANT_CURRENCY']);
    }

    public function test_currency_gbp(): void
    {
        $this->tpv->setCurrency('826');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('826', $decoded['DS_MERCHANT_CURRENCY']);
    }

    public function test_payment_method_card(): void
    {
        $this->tpv->setMethod('T');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('T', $decoded['DS_MERCHANT_PAYMETHODS']);
    }

    public function test_payment_method_bizum(): void
    {
        $this->tpv->setMethod('z');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('z', $decoded['DS_MERCHANT_PAYMETHODS']);
    }

    public function test_merchant_data(): void
    {
        $this->tpv->setMerchantData('internal_data_123');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('internal_data_123', $decoded['DS_MERCHANT_MERCHANTDATA']);
    }

    public function test_product_description(): void
    {
        $this->tpv->setProductDescription('Test product description');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('Test product description', $decoded['DS_MERCHANT_PRODUCTDESCRIPTION']);
    }

    public function test_titular(): void
    {
        $this->tpv->setTitular('John Doe');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('John Doe', $decoded['DS_MERCHANT_TITULAR']);
    }

    public function test_trade_name(): void
    {
        $this->tpv->setTradeName('My Test Shop');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('My Test Shop', $decoded['DS_MERCHANT_MERCHANTNAME']);
    }

    public function test_language(): void
    {
        $this->tpv->setLanguage('es');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('es', $decoded['DS_MERCHANT_CONSUMERLANGUAGE']);
    }

    public function test_language_english(): void
    {
        $this->tpv->setLanguage('en');
        $this->tpv->setAmount(1000);
        $this->tpv->setOrder('12345');
        $this->tpv->setMerchantcode('000000000');
        $this->tpv->setCurrency('978');
        $this->tpv->setTransactiontype('0');
        $this->tpv->setTerminal('1');

        $params = $this->tpv->generateMerchantParameters();
        $decoded = $this->tpv->getMerchantParameters($params);

        $this->assertEquals('en', $decoded['DS_MERCHANT_CONSUMERLANGUAGE']);
    }
}
