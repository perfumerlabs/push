<?php

namespace Project\Model\GoRush;

use Push\Service\GoRush;

class Apple extends Layout
{
    protected string $topic;

    public function __construct(array $tokens, array $push)
    {
        parent::__construct($tokens, $push);
        $this->setTopic("kz.naimi.app");
        $this->setPlatform(GoRush::PLATFORM_APPLE);
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     */
    public function setTopic(string $topic): void
    {
        $this->topic = $topic;
    }

    public function toArray(): array
    {
        $array = [
            "tokens" => $this->getTokens(),
            "platform" => $this->getPlatform(),
            "topic" => $this->getTopic(),
            "alert" => [
                "title" => $this->getTitle(),
                "body" => $this->getMessage(),
            ]
        ];

        if ($this->getPayload()){
            $array['data']['payload'] = $this->getPayload();
        }

        return $array;
    }
}