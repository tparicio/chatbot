<?php

namespace App\Util\ChatBot\Models;

/**
 * Class ChatBotMessage.
 *
 * @author Toni Paricio <toniparicio@gmail.com>
 */
class AnswerResponse
{
    private const NOT_FOUND_RESPONSE = 'no-results';

    private ?string $message;

    private array $messageList = [];

    private ?string $type;

    private ?array $options = [];

    private string $suggestedAnswer;

    private ?array $parameters;

    /**
     * @var
     */
    private ?array $actions;

    private ?array $flags;

    private ?array $attributes;

    private ?array $source;

    private ?array $actionField;

    private ?array $actionFieldEvents;

    private ?array $intent;

    /**
     * ChatBotConversation constructor.
     */
    public function __construct(array $data)
    {
        // TODO Map object and types
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): AnswerResponse
    {
        $this->message = $message;

        return $this;
    }

    public function getMessageList(): array
    {
        return $this->messageList;
    }

    public function setMessageList(array $messageList): AnswerResponse
    {
        $this->messageList = $messageList;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): AnswerResponse
    {
        $this->type = $type;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): AnswerResponse
    {
        $this->options = $options;

        return $this;
    }

    public function getSuggestedAnswer(): string
    {
        return $this->suggestedAnswer;
    }

    public function setSuggestedAnswer(string $suggestedAnswer): AnswerResponse
    {
        $this->suggestedAnswer = $suggestedAnswer;

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): AnswerResponse
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param mixed $actions
     */
    public function setActions(array $actions): AnswerResponse
    {
        $this->actions = $actions;

        return $this;
    }

    public function getFlags(): array
    {
        return $this->flags;
    }

    public function setFlags(array $flags): AnswerResponse
    {
        $this->flags = $flags;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): AnswerResponse
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getSource(): array
    {
        return $this->source;
    }

    public function setSource(array $source): AnswerResponse
    {
        $this->source = $source;

        return $this;
    }

    public function getActionField(): array
    {
        return $this->actionField;
    }

    public function setActionField(array $actionField): AnswerResponse
    {
        $this->actionField = $actionField;

        return $this;
    }

    public function getActionFieldEvents(): array
    {
        return $this->actionFieldEvents;
    }

    public function setActionFieldEvents(array $actionFieldEvents): AnswerResponse
    {
        $this->actionFieldEvents = $actionFieldEvents;

        return $this;
    }

    public function getIntent(): array
    {
        return $this->intent;
    }

    public function setIntent(array $intent): AnswerResponse
    {
        $this->intent = $intent;

        return $this;
    }

    public function isValid(): bool
    {
        return $this->suggestedAnswer;
    }

    /**
     * Check if answer is a not found response by search not found flag
     * @return bool
     */
    public function isNotFoundResponse(): bool
    {
        return $this->hasFlag(self::NOT_FOUND_RESPONSE);
    }

    /**
     * Search some flag in flags array by name.
     *
     * @param string $flag : flag name to find
     * @return bool : true when flag exists, otherwise return false
     */
    public function hasFlag(string $flag): bool
    {
        return false !== in_array($flag, $this->flags, true);
    }
}
