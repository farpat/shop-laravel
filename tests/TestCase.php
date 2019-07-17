<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Symfony\Component\DomCrawler\Crawler;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param string $expected
     * @param string $actual
     */
    protected function assertText (string $expected, string $actual)
    {
        $this->assertSame($expected, trim($actual));
    }

    /**
     * @param TestResponse $response
     *
     * @return Crawler
     */
    protected function getCrawler (TestResponse $response)
    {
        return new Crawler($response->getContent());
    }
}
