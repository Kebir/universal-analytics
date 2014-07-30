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
        $file_path = $this->createFile('toto');

        $url = 'file://'.$file_path;

        $this->assertEquals('toto', $this->request->get($url, array('param1' => 1)));
    }

    private function createFile($content)
    {
        $path = sys_get_temp_dir().'/'.time().'-'.rand(0, 10).'-test_request.txt';

        file_put_contents($path, $content);

        return $path;
    }
}
