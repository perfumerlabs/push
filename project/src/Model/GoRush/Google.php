<?php

namespace Project\Model\GoRush;

use Push\Service\GoRush;

class Google extends Layout
{
    protected ?string $image;

    public function __construct(array $tokens, array $push)
    {
        parent::__construct($tokens, $push);
        $this->setPlatform(GoRush::PLATFORM_GOOGLE);
        $this->setImage($push['image'] ?? null);
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function toArray(): array
    {
        $array = [
            "tokens" => $this->getTokens(),
            "platform" => $this->getPlatform(),
            "data" => [
                "title" => $this->getTitle(),
                "message" => $this->getMessage()
            ]
        ];

        if ($this->getImage()){
            $array['data']['image'] = $this->getImage();
        }

        if ($this->getPayload()){
            $array['data'] = array_merge($array['data'], $this->getPayload());
        }

        return $array;
    }
}