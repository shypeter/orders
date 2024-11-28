<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Validators\OrderFormatValidator;
use App\Exceptions\OrderValidationException;

class OrderFormatValidatorTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new OrderFormatValidator();
    }

    /**
     * @test
     */
    public function valid_order_data_passes_validation()
    {
        $data = [
            'name' => 'Holiday Inn',
            'price' => 1500,
            'currency' => 'TWD',
            'id' => 'A0000001',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ]
        ];

        $result = $this->validator->validate($data);
        $this->assertEquals($data, $result);
    }

    /**
     * @test
     */
    public function invalid_name_with_non_english_chars_throws_exception()
    {
        $this->expectException(OrderValidationException::class);

        $data = [
            'name' => '假日酒店',
            'price' => 1500,
            'currency' => 'TWD',
            'id' => 'A0000001',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ]
        ];

        $this->validator->validate($data);
    }

    /**
     * @test
     */
    public function invalid_price_over_2000_throws_exception()
    {
        $this->expectException(OrderValidationException::class);

        $data = [
            'name' => 'Holiday Inn',
            'price' => 2500,
            'currency' => 'TWD',
            'id' => 'A0000001',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ]
        ];

        $this->validator->validate($data);
    }
}