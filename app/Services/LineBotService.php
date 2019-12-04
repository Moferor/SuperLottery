<?php


namespace App\Services;

//use App\Services\CrawlerService;
use LINE\LINEBot;
use LINE\LINEBot\Constant\Flex\BubleContainerSize;
use LINE\LINEBot\Constant\Flex\ComponentBorderWidth;
use LINE\LINEBot\Constant\Flex\ComponentButtonHeight;
use LINE\LINEBot\Constant\Flex\ComponentButtonStyle;
use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentFontWeight;
use LINE\LINEBot\Constant\Flex\ComponentIconSize;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectMode;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectRatio;
use LINE\LINEBot\Constant\Flex\ComponentImageSize;
use LINE\LINEBot\Constant\Flex\ComponentLayout;
use LINE\LINEBot\Constant\Flex\ComponentMargin;
use LINE\LINEBot\Constant\Flex\ComponentPosition;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\Constant\Flex\ContainerDirection;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\BlockStyleBuilder;
use LINE\LINEBot\MessageBuilder\Flex\BubbleStylesBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SpacerComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder;
use LINE\LINEBot\Response;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;



class LineBotService
{
    /** @var LINEBot */
    private $lineBot;
    private $lineUserId;
    private $crawlerService;


    public function __construct($lineUserId)
    {
        $this->lineUserId = $lineUserId;

        $this->lineBot = app(LINEBot::class);
//        dd($this->lineBot);
//        $this->crawlerService = new CrawlerService();
        $this->crawlerService = app(CrawlerService::class);
    }

    public function fake()
    {
    }







    /**
     * @param MessageBuilder|string $content
     * @return Response
     */
    public function pushMessage($content): Response
    {

        if (is_string($content)) {
            $content = new TextMessageBuilder($content);
        }

        return $this->lineBot->pushMessage($this->lineUserId,  $content);

    }


    /**
     *
     * @param string $altText
     * @return FlexMessageBuilder
     */
    public function buildFlexMessageBuilder(string $altText): FlexMessageBuilder
    {
//        $crawler = $this->crawlerService->getOriginalData('https://www.taiwanlottery.com.tw/index_new.aspx');
        $crawler = $this->crawlerService->getOriginalData('https://www.taiwanlottery.com.tw/index_new.aspx');
        $target = $this->crawlerService->getPowerNewNumberAllInformation($crawler);
        //date
        //eventCode
        //numberOrderTime[]
        //numberOrderSize[]
        //numberSP
        $headerBox = $this->createHeardBoxStyle('SuperBall');



        $builder = BubbleContainerBuilder::builder()
            ->setDirection(ContainerDirection::LTR)
            ->setSize(BubleContainerSize::GIGA)
            ->setHeader($headerBox)
            ->setHero(new BoxComponentBuilder(ComponentLayout::VERTICAL,
                [
                    new TextComponentBuilder($target[0]['date']),
                    new TextComponentBuilder($target[0]['date'])
                ]))
            ->setBody(new BoxComponentBuilder(ComponentLayout::VERTICAL, [new TextComponentBuilder('body')]))
            ->setFooter(new BoxComponentBuilder(ComponentLayout::VERTICAL, [new TextComponentBuilder('footer')]))
            ->setStyles(BubbleStylesBuilder::builder()->setBody(new BlockStyleBuilder('#ffffff', true, '#020200')));
        $builder->setAction(new UriTemplateActionBuilder('OK', 'https://www.taiwanlottery.com.tw/index_new.aspx'));
        return $flexMessage = new FlexMessageBuilder($altText,$builder);
    }

    /**
     * @param string $heardTitle
     * @return BoxComponentBuilder
     */
    private function createHeardBoxStyle(string $heardTitle)
    {
        $headerBoxStyle = new BoxComponentBuilder(
            ComponentLayout::VERTICAL,
            [
                new TextComponentBuilder(
                    $heardTitle,
                    4,
                    null,
                    ComponentFontSize::XL,
                    null,
                    null,
                    null,
                    null,
                    ComponentFontWeight::BOLD,
                    '#ffffff',
                    null
                )
            ]);

        $headerBoxStyle->setPaddingAll('20px')
            ->setPaddingTop('22px')
//            ->setPaddingBottom('5px')
//            ->setPaddingStart(ComponentSpacing::LG)
//            ->setPaddingEnd(ComponentSpacing::XL)
            ->setBackgroundColor('#0367D3')
            ->setSpacing(ComponentSpacing::XXL)
            ->setBorderColor('#000000')
//            ->setBorderWidth(ComponentBorderWidth::SEMI_BOLD)
//            ->setCornerRadius(ComponentSpacing::XXL)
//            ->setPosition(ComponentPosition::RELATIVE)
//            ->setOffsetTop('4px')
//            ->setOffsetBottom('4%')
//            ->setOffsetStart(ComponentSpacing::NONE)
//            ->setOffsetEnd(ComponentSpacing::SM)
//            ->setWidth('5px')
            ->setHeight('80px');
        return $headerBoxStyle;
    }
}
