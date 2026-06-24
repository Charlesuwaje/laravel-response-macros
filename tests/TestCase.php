<?php

namespace CharlesUwaje\ResponseMacros\Tests;

use CharlesUwaje\ResponseMacros\Providers\ResponseMacrosServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ResponseMacrosServiceProvider::class];
    }
}
