<?php
namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
class CrawlerService
{
    /** @var Client  */
    private $client;
    public function __construct()
    {
        $this->client = app(Client::class);
    }

    /**
     * @param string $path
     * @return Crawler
     */
    public function getOriginalData(string $path): Crawler
    {

        $content = $this->client->get($path)->getBody()->getContents();
        $crawler = new Crawler();
        $crawler->addHtmlContent($content);
        return $crawler;

    }

    /**
     * @param Crawler $crawler
     * @return array
     * @deprecated
     */



    public function getPowerNewNumberAllInformation(Crawler $crawler):array
    {
        $target = $crawler->filterXPath('//div[@class=\'contents_box02\'][1]')
            ->each(function (Crawler $node) {
                $date = $this->getNewPowerNumberDate($node);
                $eventCode = $this->getNewPowerNumberEventCode($node);
                $numberOrderTime = $this->getNewPowerNormalNumberOrderTime($node);
                $numberOrderSize = $this->getNewPowerNormalNumberOrderSize($node);
                $numberSP = $this->getNewPowerSpecialNumber($node);

                $response = [
                    'date' => $date,
                    'eventCode' => $eventCode,
                    'numberOrderTime' => $numberOrderTime,
                    'numberOrderSize' => $numberOrderSize,
                    'numberSP' => Arr::first($numberSP),
                ];
                return in_array(null, array_values($response), true) ? null : $response;
            });
        $target = array_filter($target, function ($data) {
            return null !== $data;
        });

        return $target;
    }


    public function numberHandle($array,$numberSmall,$numberLarge):array
    {

        foreach($array as $key => $value)
        {
            if($key >= $numberSmall && $key <= $numberLarge)
            {
                $array = Arr::except($array, $key);
            }
        }
//        dd($array);
        return $array;
    }



    public function dateAndEventCodeDetach($dateData,$key)
    {
//         var_dump($dateData);
        $arraysHtml    = htmlentities($dateData);
        $arraysReplace = str_replace(" ","&nbsp;",$arraysHtml);
        $arrays        = explode("&nbsp;",$arraysReplace);

//        $string = '108/11/14&nbsp;第108000091期 ';
//        $string = preg_replace("/\s|&nbsp;/", '***', $dateData);
//        list($date, $number) = explode('***', $string);
//        $number = mb_substr($number, 1, -1);
//        var_dump($date, $number);

//        dd($arrays);
        $array  =  $key == 0 ?  $arrays[$key] : $arrays[$key];
//        dd($array);
        return $array;
    }






    /**
     * @param Crawler $node
     * @return array
     */
    public function getNewPowerNormalNumberOrderTime(Crawler $node):array
    {
        $target = $node->filterXPath('//div[@class=\'contents_box02\'][1]/div[@class=\'ball_tx ball_green\']');
        $numberArray  = $target->each(function (Crawler $node) {
            $numbers  = '';
            $numbers .= $node->html();
            return $numbers;
        });
        $result = $this->numberHandle($numberArray,6,11);
        return $result;
    }

    public function getNewPowerNormalNumberOrderSize(Crawler $node):array
    {
        $target = $node->filterXPath('//div[@class=\'contents_box02\'][1]/div[@class=\'ball_tx ball_green\']');
        $numberArray  = $target->each(function (Crawler $node) {
            $numbers  = '';
            $numbers .= $node->html();
            return $numbers;
        });
        $result = $this->numberHandle($numberArray,0,5);
        return $result;
    }


    /**
     * @param Crawler $node
     * @return array
     */
    public function getNewPowerSpecialNumber(Crawler $node)
    {
//        $target = $node->filterXPath('//div[@class=\'contents_box02\'][1]/div[@class=\'ball_red\']');
        return $node->filterXPath('//div[@class=\'contents_box02\'][1]/div[@class=\'ball_red\']')
            ->each(function (Crawler $node) {
                return $node->html();
//        return $target;
            });
    }
    public function getNewPowerNumberDate(Crawler $node)
    {
        $dateData =  $node->filterXPath('//div[@class=\'contents_box02\'][1]/div[@class=\'contents_mine_tx02\']/span[@class=\'font_black15\']')
            ->each(function (Crawler $node) {
                return $node->html();
//          return $target;
            });
//        var_dump($dateData[0]);
        return $this->dateAndEventCodeDetach($dateData[0],0);
//        dd($this->dateAndEventCodeDetach($dateData[0],0));
    }

    public function getNewPowerNumberEventCode(Crawler $node)
    {
        $eventCodeData =  $node->filterXPath('//div[@class=\'contents_box02\'][1]/div[@class=\'contents_mine_tx02\']/span[@class=\'font_black15\']')
            ->each(function (Crawler $node) {
                return $node->html();
//          return $target;
            });
        return $this->dateAndEventCodeDetach($eventCodeData[0],1);
//        dd($this->dateAndEventCodeDetach($eventCodeData[0],1));
    }

}
