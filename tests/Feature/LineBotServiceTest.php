<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\LineBotService;
use Tests\TestCase;

class LineBotServiceTest extends TestCase
{
    /** @var  LineBotService */
    private $lineBotService;

    public function setUp():void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->lineBotService = app(LineBotService::class);
    }

    public function tearDown():void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    public function testPushMessage()
    {
        $this->markTestSkipped('OK!');
        $response = $this->lineBotService->pushMessage('Test from laravel.');

        $this->assertEquals(200, $response->getHTTPStatus());
    }
}
