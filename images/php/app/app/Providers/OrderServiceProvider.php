<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\OrderTransformer;
use App\Contracts\OrderValidator;
use App\Services\Transformers\CurrencyTransformer;
use App\Services\Validators\OrderFormatValidator;
use App\Services\OrderService;

class OrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(OrderValidator::class, OrderFormatValidator::class);
        $this->app->bind(OrderTransformer::class, CurrencyTransformer::class);
        $this->app->bind(OrderService::class, function ($app) {
            return new OrderService(
                $app->make(OrderValidator::class),
                $app->make(OrderTransformer::class)
            );
        });
    }
}   