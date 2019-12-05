<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;


class CallBacksController extends Controller
{
    private $client;
    private $bot;
//    private $channel_access_token;
//    private $channel_secret;

    public function __construct()
    {
//        $this->channel_access_token = env('LINEBOT_TOKEN');
//        $this->channel_secret       = env('LINEBOT_SECRET');

//        $httpClient   = new CurlHTTPClient($this->channel_access_token);
//        $this->bot    = new LINEBot($httpClient, ['channelSecret' => $this->channel_secret]);
//        $this->client = $httpClient;
//        dd($this->bot);
        $this->bot  = app(LINEBot::class);
//        var_dump($a);
    }

    public function callback(Request $request)
    {
        $bot       = $this->bot;
        $signature = $request->header(HTTPHeader::LINE_SIGNATURE);
        $body      = $request->getContent();
        try {
            $events = $bot->parseEventRequest($body, $signature);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        foreach ($events as $event) {
            $replyToken = $event->getReplyToken();
            if ($event instanceof MessageEvent) {
                $message_type = $event->getMessageType();
                $text = $event->getText();
                switch ($message_type) {
                    case 'text':
                        $bot->replyText($replyToken, $replyToken);
                        break;
                }
            }
        }
    }
}
