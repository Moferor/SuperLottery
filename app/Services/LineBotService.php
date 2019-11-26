<?php


namespace App\Services;


use LINE\LINEBot;
use LINE\LINEBot\Constant\Flex\BubleContainerSize;
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
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\Constant\Flex\ContainerDirection;
use LINE\LINEBot\MessageBuilder;
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

    public function __construct($lineUserId)
    {
        $this->lineUserId = $lineUserId;
        $this->lineBot = app(LINEBot::class);
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
     * @param array $data
     * @return FlexMessageBuilder
     */
    public function buildFlexMessageBuilder(string $altText,array $data): FlexMessageBuilder
    {
        $bubble = $this -> bubbleCreate(
            $data['size'],
            $data['direction'],
            $data['header']
        );
        return new FlexMessageBuilder($altText, $bubble);
    }
    /**
     * @param string $size
     * @param string $direction
     * @param array $header
     * @return BubbleContainerBuilder
     */
    protected function bubbleCreate(string $size,string $direction,array $header):BubbleContainerBuilder
    {
        $bubble = new BubbleContainerBuilder(
            $direction,
            $this->boxCreate($header),
            null,
            null,
            null,
            null,
            $size
        );
        return $bubble;
    }
    /**
     * @param array $header
     * @return BoxComponentBuilder
     */
    protected function boxCreate(array $header):BoxComponentBuilder
    {
        $box = new BoxComponentBuilder(
            $header['layout'],
            $header['contents']
        );
        return $box;
    }

}
