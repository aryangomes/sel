<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    public $url = 'api/v1/';

    public function urlWithParameter($url, $parameter = null)
    {
        $urlWithParameter = $url;

        if (isset($parameter)) {
            $urlWithParameter .= "/{$parameter}";
        }

        return $urlWithParameter;
    }

    public function printContentResponse($response)
    {
        print_r($response->getContent());
    }
}
