<?php

namespace Project\Model\GoRush;

abstract class Layout
{
    protected array $tokens;

    protected int $platform;

    protected ?string $title;

    protected ?string $message;

    protected array $payload;

    protected ?string $sound;

    public function __construct(array $tokens, array $push)
    {
        $this->setTitle($push['title'] ?? null);
        $this->setMessage($push['text'] ?? null);
        $this->setTokens($tokens);
        $this->setPayload($push['payload']);
        $this->setSound($push['sound'] ?? null);
    }

    /**
     * @return string
     */
    public function getSound(): string
    {
        return $this->sound;
    }

    /**
     * @param string $sound
     */
    public function setSound(string $sound): void
    {
        $this->sound = $sound;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     */
    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @param array $tokens
     */
    public function setTokens(array $tokens): void
    {
        $this->tokens = $tokens;
    }

    /**
     * @return int
     */
    public function getPlatform(): int
    {
        return $this->platform;
    }

    /**
     * @param int $platform
     */
    public function setPlatform(int $platform): void
    {
        $this->platform = $platform;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public abstract function toArray(): array;
}