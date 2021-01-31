<?php

namespace App\Util\ChatBot\Models;

class ConversationResponse
{
    /**
     * @var string
     */
    public const CHAT_BOT_API_NAME = 'chatbot';

    private ?string $sessionToken;

    private ?string $sessionId;

    private array $apis = [];

    /**
     * ChatBotConversation constructor.
     */
    public function __construct(array $data, array $apis = [])
    {
        $this->sessionId = $data['sessionId'] ?? null;
        $this->sessionToken = $data['sessionToken'] ?? null;
        $this->apis = $apis;
    }

    public function getSessionToken(): ?string
    {
        return $this->sessionToken;
    }

    public function setSessionToken(?string $sessionToken): ConversationResponse
    {
        $this->sessionToken = $sessionToken;

        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(?string $sessionId): ConversationResponse
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function isValid(): bool
    {
        return $this->sessionToken && $this->sessionId;
    }

    public function getChatBotApi(): ?string
    {
        return $this->apis[self::CHAT_BOT_API_NAME] ?? null;
    }
}
