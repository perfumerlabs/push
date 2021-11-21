<?php

namespace Project\Model\GoRush;

use Push\Service\GoRush;

class Huawei extends Layout
{
    protected ?string $image;

    public function __construct(array $tokens, array $push)
    {
        parent::__construct($tokens, $push);
        $this->setPlatform(GoRush::PLATFORM_HUAWEI);
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
            "title" => $this->getTitle(),
            "message" => $this->getMessage(),
        ];

        $data = [
            'title' => $this->getTitle(),
            'message' => $this->getMessage()
        ];

        if ($this->getPayload()){
            $data = array_merge($data, $this->getPayload());
        }

        $array['huawei_data'] = json_encode($data);

        if ($this->getImage()){
            $array['image'] = $this->getImage();
        }

        return $array;
    }
}