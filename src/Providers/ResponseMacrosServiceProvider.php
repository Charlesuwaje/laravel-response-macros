<?php

namespace CharlesUwaje\ResponseMacros\Providers;

use CharlesUwaje\ResponseMacros\Mixins\ResponseMixin;
use CharlesUwaje\ResponseMacros\Mixins\SoapResponseMixin;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class ResponseMacrosServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/response-macros.php' => config_path('response-macros.php'),
        ], 'response-macros-config');

        $format = config('response-macros.format', 'json');

        if ($format === 'soap') {
            ResponseFactory::mixin(new SoapResponseMixin());
        } else {
            ResponseFactory::mixin(new ResponseMixin());
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/response-macros.php',
            'response-macros'
        );
    }
}
