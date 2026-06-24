<?php

namespace CharlesUwaje\ResponseMacros\Providers;

use CharlesUwaje\ResponseMacros\Mixins\ResponseMixin;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class ResponseMacrosServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        ResponseFactory::mixin(new ResponseMixin());
    }
}
