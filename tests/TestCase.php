<?php

namespace Ssheduardo\Redsys\Tests;

use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class TestCase extends PhpUnitTestCase
{
    protected function getMerchantParams(): array
    {
        return [
            'key' => 'sq7HjrUOBfKmC576ILgskDsrU870gJ7',
            'merchantcode' => '000000000',
            'terminal' => '1',
            'enviroment' => 'test',
        ];
    }
}
