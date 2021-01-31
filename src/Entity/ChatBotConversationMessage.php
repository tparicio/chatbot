<?php

namespace App\Entity;

use App\Repository\ChatBotConversationMessageRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ChatBotConversationMessageRepository::class)
 * @author Toni Paricio <toniparicio@gmail.com>
 */
class ChatBotConversationMessage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ChatBotConversation", inversedBy="messages")
     */
    private ChatBotConversation $conversation;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private string $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $avatar;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isResponse;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isNotFound = false;

    /**
     * @ORM\OneToOne(targetEntity="ChatBotConversationMessage")
     */
    private ?ChatBotConversationMessage $related;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private DateTimeInterface $updatedAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ChatBotConversation
     */
    public function getConversation(): ChatBotConversation
    {
        return $this->conversation;
    }

    /**
     * @return $this
     */
    public function setConversation(ChatBotConversation $conversation): ChatBotConversationMessage
    {
        $this->conversation = $conversation;
        $conversation->addMessage($this);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @return $this
     */
    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsResponse(): ?bool
    {
        return $this->isResponse;
    }

    /**
     * @return $this
     */
    public function setIsResponse(bool $isResponse): self
    {
        $this->isResponse = $isResponse;

        return $this;
    }

    /**
     * @return ChatBotConversationMessage|null
     */
    public function getRelated(): ?ChatBotConversationMessage
    {
        return $this->related;
    }

    /**
     * @param ChatBotConversationMessage|null $related
     * @return $this
     */
    public function setRelated(?ChatBotConversationMessage $related): ChatBotConversationMessage
    {
        $this->related = $related;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNotFound(): bool
    {
        return $this->isNotFound;
    }

    /**
     * @param bool $isNotFound
     * @return ChatBotConversationMessage
     */
    public function setIsNotFound(bool $isNotFound): ChatBotConversationMessage
    {
        $this->isNotFound = $isNotFound;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $created): self
    {
        $this->createdAt = $created;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $updated): ChatBotConversationMessage
    {
        $this->updatedAt = $updated;

        return $this;
    }

    /**
     * Return message as array for javascript
     * @return array
     */
    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'avatar' => $this->avatar,
            'yoda' => $this->isResponse,
            'timestamp' => $this->getCreatedAt()->getTimestamp()
        ];
    }
}
