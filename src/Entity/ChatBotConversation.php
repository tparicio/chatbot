<?php

namespace App\Entity;

use App\Repository\ChatBotConversationRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ChatBotConversationRepository::class)
 *
 * @author Toni Paricio <toniparicio@gmail.com>
 */
class ChatBotConversation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * Inbenta conversation remote token
     * Session token used to maintain a session between requests. This token expires after 30 minutes of inactivity.
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private ?string $remoteToken;

    /**
     * Inbenta conversation remote id
     * Unique conversation session identifier.
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private ?string $remoteId;

    /**
     * Inbenta remote URI for chatbot API
     * TODO can change? if not we can persist uri in another site as .env parameter, configuration or constant
     * @ORM\Column(type="string", length=128, nullable=true)
     * @var string|null
     */
    private ?string $uri;

    /**
     * @ORM\OneToMany(targetEntity="ChatBotConversationMessage", mappedBy="conversation")
     */
    private iterable $messages;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @Gedmo\Timestampable(on="create")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     * @Gedmo\Timestampable(on="update")
     */
    private DateTimeInterface $updatedAt;

    /**
     * ChatBotSession constructor.
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getRemoteToken(): ?string
    {
        return $this->remoteToken;
    }

    /**
     * @param string|null $remoteToken
     * @return ChatBotConversation
     */
    public function setRemoteToken(?string $remoteToken): ChatBotConversation
    {
        $this->remoteToken = $remoteToken;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRemoteId(): ?string
    {
        return $this->remoteId;
    }

    /**
     * @param string|null $remoteId
     * @return ChatBotConversation
     */
    public function setRemoteId(?string $remoteId): ChatBotConversation
    {
        $this->remoteId = $remoteId;
        return $this;
    }

    /**
     * @return ChatBotConversationMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages->toArray();
    }

    /**
     * return only yoda responses from messages
     * @param int|null $size : define the number of responses to return (from end)| all by default
     * @return ChatBotConversationMessage[]
     */
    public function getAnswers(?int $size = 0): array
    {
        // get only answers
        $answers = array_filter($this->getMessages(), static function (ChatBotConversationMessage $message) {
            return $message->getIsResponse();
        });
        if ($answers) {
            // return a subset with final answers
            return array_slice($answers, -$size, $size, true);
        }
        return $answers;
    }

    /**
     * @param ChatBotConversationMessage[] $messages
     */
    public function setMessages(iterable $messages): self
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * @return $this
     */
    public function addMessage(ChatBotConversationMessage $message): self
    {
        $this->messages->add($message);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * @param string|null $uri
     * @return ChatBotConversation
     */
    public function setUri(?string $uri): ChatBotConversation
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
