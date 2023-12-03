<?php

namespace LensaWicara\Tests\Feature;

use LensaWicara\SnapBI\Support\Http;

class HttpTest extends \LensaWicara\Tests\TestCase
{
    /** @test */
    public function it_can_get_the_same_url_string()
    {
        $url = 'https://google.com';

        $this->assertEquals($url, Http::get($url));
    }
}