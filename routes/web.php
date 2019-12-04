<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Services\CrawlerService;
use App\Http\Controllers\CallBacksController;
//use Illuminate\Routing\Route;




Route::get('/', function () {
    $crawler = new CrawlerService();
//    $crawler-> getOriginalData('https://www.taiwanlottery.com.tw/index_new.aspx');
    $data = $crawler->getPowerNewNumberAllInformation($crawler->getOriginalData('https://www.taiwanlottery.com.tw/index_new.aspx'));
//    dd($data);
});


Route::post('/callback','CallBacksController@callback');
//Route::post('/callback', 'CallBackController@webhook');

