<?php

use Kebir\UniversalAnalytics\PixelRequest;

class PixelRequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * The request.
     *
     * @var Jomedia\Tracking\Google\PixelRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new PixelRequest();
    }

    /**
     * @test
     */
    public function it_calls_a_url()
    {
        $url = 'http://localhost:9615';
        $result = $this->request->get($url, array('param1' => 1));

        $this->assertEquals('Hello', $result);
    }
}
