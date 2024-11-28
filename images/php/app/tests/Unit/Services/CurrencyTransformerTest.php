<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Transformers\CurrencyTransformer;

class CurrencyTransformerTest extends TestCase
{
    public function testTransformUSDToTWD()
    {
        $transformer = new CurrencyTransformer();
        
        $data = [
            'price' => 100,
            'currency' => 'USD'
        ];

        $result = $transformer->transform($data);

        $this->assertEquals(3100, $result['price']);
        $this->assertEquals('TWD', $result['currency']);
    }

    public function testTransformTWD()
    {
        $transformer = new CurrencyTransformer();
        
        $data = [
            'price' => 100,
            'currency' => 'TWD'
        ];

        $result = $transformer->transform($data);

        $this->assertEquals(100, $result['price']);
        $this->assertEquals('TWD', $result['currency']);
    }
}