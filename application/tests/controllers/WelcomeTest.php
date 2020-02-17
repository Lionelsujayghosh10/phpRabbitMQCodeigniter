<?php
use PHPUnit\Framework\TestCase;

/* Install Guzzle and phpunit
composer require guzzlehttp/guzzle
composer require --dev phpunit/phpunit */

class WelcomeTest extends TestCase{


    protected $client;

    public function setUp(): void{
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost/'
        ]);
    }


    // api is http://localhost/RestAPI7.0/index.php?/Welcome/getapi
    public function test_getapi(){
        $response = $this->client->get('RestAPI7.0/index.php?/Welcome/getapi');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('/var/www/html/RestAPI7.0/application/', $response->getBody());
    }
}