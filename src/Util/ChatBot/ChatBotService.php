<?php

namespace App\Util\ChatBot;

use App\Entity\ChatBotConversation;
use App\Entity\ChatBotConversationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class ChatBotService.
 *
 * @author Toni Paricio <toniparicio@gmail.com>
 */
class ChatBotService
{
    /**
     * @var string
     */
    private const CHAT_BOT_SESSION_ID = 'chatBot_session';

    /**
     * a custom message to return when a chat is created.
     *
     * @var string
     */
    private const WELCOME_MESSAGE = 'Welcome to the Yoda chat, for you what can I do? Yes, hrrrm.';

    /**
     * Number of not found messages to trigger a special response (characters list).
     *
     * @var int
     */
    private const TRIGGER_NOT_FOUND = 2;

    /**
     * Special word to trigger a special response (film list).
     *
     * @var int
     */
    private const TRIGGER_FORCE_WORD = 'force';

    private SessionInterface $session;

    private EntityManagerInterface $manager;

    private ChatBotApiClient $client;

    /**
     * ChatBotService constructor.
     */
    public function __construct(
        ChatBotApiClient $client,
        SessionInterface $session,
        EntityManagerInterface $manager
    ) {
        $this->client = $client;
        $this->session = $session;
        $this->manager = $manager;
    }

    /**
     * get a chat bot session from session and database or create a new one.
     */
    public function getOrCreateChatBotSession(): ChatBotConversation
    {
        $id = $this->session->get(self::CHAT_BOT_SESSION_ID);
        if ($id && ($session = $this->getChatBotSession($id))) {
            return $session;
        }

        return $this->createChatBotSession();
    }

    /**
     * get chat bot session from database.
     *
     * @param int $id : internal (not remote) chat bot session id
     */
    private function getChatBotSession(int $id): ?ChatBotConversation
    {
        $repo = $this->manager->getRepository(ChatBotConversation::class);

        return $repo->find($id);
    }

    /**
     * create a new chat bot session and store id in session for recovery on load.
     */
    private function createChatBotSession(): ?ChatBotConversation
    {
        $remoteChatBot = $this->client->addConversation();
        if (!$remoteChatBot) {
            return null;
        }
        // create session
        $conversation = new ChatBotConversation();
        $conversation->setRemoteToken($remoteChatBot->getSessionToken());
        $conversation->setRemoteId($remoteChatBot->getSessionId());
        $conversation->setUri($remoteChatBot->getChatBotApi());
        $this->manager->persist($conversation);
        $this->manager->flush();

        // store session id in session for recovery
        $this->session->set(self::CHAT_BOT_SESSION_ID, $conversation->getId());

        // create a welcome message
        $this->addChatBotWelcomeMessage($conversation);

        return $conversation;
    }

    /**
     * Add a custom welcome message to a new conversation.
     *
     * @param ChatBotConversation $conversation : conversation where add welcome message
     */
    private function addChatBotWelcomeMessage(ChatBotConversation $conversation): ChatBotConversationMessage
    {
        $message = new ChatBotConversationMessage();
        $message->setConversation($conversation);
        $message->setContent(self::WELCOME_MESSAGE);
        $message->setAvatar('yoda');
        $message->setIsResponse(true);

        $this->manager->persist($message);
        $this->manager->flush();

        return $message;
    }

    /**
     * Add chat bot message to database.
     *
     * @param string $content : message
     * @param string $avatar  : user icon
     * @param bool   $isYoda  : true is yoda (response) | false is user
     *
     * @return ChatBotConversationMessage[]
     */
    public function addChatBotMessage(
        string $content,
        string $avatar = 'vader',
        bool $isYoda = false
    ): array {
        $session = $this->getOrCreateChatBotSession();
        $message = new ChatBotConversationMessage();
        $message->setConversation($session);
        $message->setContent($content);
        $message->setAvatar($avatar);
        $message->setIsResponse($isYoda);

        $this->manager->persist($message);

        $responses = $this->getChatBotResponse($message);

        // and extra response depending on some triggers
        $special = $this->handleSpecialCases($message);
        if ($special) {
            $responses[] = $special;
            $this->manager->persist($special);
        }
        $this->manager->flush();

        return $responses;
    }

    /**
     * Some question are responsed with special answers
     * When 2 consecutive not found then return a characters list
     * When force word inside message content then return a film list.
     */
    private function handleSpecialCases(ChatBotConversationMessage $message): ?ChatBotConversationMessage
    {
        if ($this->isForceWordContained($message)) {
            $client = new StarWarsApiClient();
            $films = $client->getFilms();
            if ($films) {
                $answer = new ChatBotConversationMessage();
                $answer->setConversation($message->getConversation());
                $answer->setContent(implode('<br/>', array_column($films, 'title')));
                $answer->setAvatar('yoda');
                $answer->setIsResponse(true);
                return $answer;
            }
        }
        if ($this->isTwoConsecutiveNotFound($message)) {
            $client = new StarWarsApiClient();
            $characters = $client->getCharacters();
            if ($characters) {
                // get only 10 random characters
                shuffle($characters);
                $sample = array_slice($characters, 0, min(count($characters), 10));
                $answer = new ChatBotConversationMessage();
                $answer->setConversation($message->getConversation());
                $answer->setContent(implode('<br/>', array_column($sample, 'name')));
                $answer->setAvatar('yoda');
                $answer->setIsResponse(true);
                return $answer;
            }
        }

        return null;
    }

    /**
     * Check if last two answers are a not found message
     * @param ChatBotConversationMessage $message
     * @return bool
     */
    private function isTwoConsecutiveNotFound(ChatBotConversationMessage $message): bool
    {
        $answers = $message->getConversation()->getAnswers(self::TRIGGER_NOT_FOUND);
        if (count($answers) >= self::TRIGGER_NOT_FOUND) {
            // check how many answers are not found answers
            $filtered = array_filter($answers, static function (ChatBotConversationMessage $message) {
                return $message->isNotFound();
            });
            return count($filtered) >= self::TRIGGER_NOT_FOUND;
        }

        return false;
    }

    /**
     * check if word TRIGGER_FORCE_WORD is contained in message.
     */
    private function isForceWordContained(ChatBotConversationMessage $message): bool
    {
        $haystack = strtolower($message->getContent());

        return false !== strpos($haystack, self::TRIGGER_FORCE_WORD);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return ChatBotConversationMessage[]
     */
    private function getChatBotResponse(ChatBotConversationMessage $message): array
    {
        $answers = $this->client->addMessage($message);
        $responses = [];
        if ($answers) {
            foreach ($answers as $answer) {
                $response = new ChatBotConversationMessage();
                $response->setConversation($message->getConversation());
                $response->setRelated($message);
                $response->setContent($answer->getMessage());
                $response->setAvatar('yoda');
                $response->setIsResponse(true);
                $response->setIsNotFound($answer->isNotFoundResponse());

                $this->manager->persist($response);
                $responses[] = $response;
            }
        }

        return $responses;
    }
}
