<?php

namespace App\Util\ChatBot;

use App\Entity\ChatBotConversationMessage;
use App\Util\ChatBot\Models\AnswerResponse;
use App\Util\ChatBot\Models\ConversationResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Class ChatBotApiClient.
 *
 * @author Toni Paricio <toniparicio@gmail.com>
 */
class ChatBotApiClient
{
    /**
     * @var string
     */
    private const BASE_URI = 'https://api.inbenta.io';
    private const ENDPOINT_AUTH = 'v1/auth';
    private const ENDPOINT_CONVERSATION = 'chatbot/v1/conversation';
    private const ENDPOINT_MESSAGE = 'chatbot/v1/conversation/message';

    private ?string $token = null;

    private ?array $apis = [];

    /**
     * @var string : API KEY
     */
    private string $key;

    /**
     * @var string : API secret
     */
    private string $secret;

    /**
     * ChatBotApiClient constructor.
     * @param string $chatBotApiKey
     * @param string $chatBotApiSecret
     */
    public function __construct(string $chatBotApiKey, string $chatBotApiSecret)
    {
        $this->key = $chatBotApiKey;
        $this->secret = $chatBotApiSecret;
    }

    /**
     * get auth token
     * TODO save token in session or memory for re-use this and avoid some request while token is valid.
     **/
    public function getAuthToken()
    {
        $client = $this->getAuthClient();
        $payload = [
            'secret' => $this->secret,
        ];
        try {
            $response = $client->post(self::ENDPOINT_AUTH, [
                RequestOptions::HEADERS => $this->getHeaders(),
                RequestOptions::JSON => $payload,
            ]);
            $body = $response->getBody();
            $data = json_decode((string) $body, true, 512, JSON_THROW_ON_ERROR);
            $this->token = $data['accessToken'] ?? null;
            $this->apis = $data['apis'] ?? null;
        } catch (GuzzleException $e) {
            return;
        } catch (\JsonException $e) {
            return;
        } catch (\Exception $exception) {
            // handle exceptions
            return;
        }
    }

    /**
     * Get auth client for get access token for other requests.
     */
    private function getAuthClient(): Client
    {
        return new Client([
            'base_uri' => self::BASE_URI,
            'headers' => $this->getHeaders(),
        ]);
    }

    /**
     * Get a client for chatbot api
     * URL is get in auth request (no need request to /apis endpoint).
     */
    private function getChatBotClient(string $uri): Client
    {
        return new Client([
            'base_uri' => $uri,
        ]);
    }

    /**
     * Create a conversation in API.
     */
    public function addConversation(): ?ConversationResponse
    {
        $this->getAuthToken();
        $uri = $this->apis[ConversationResponse::CHAT_BOT_API_NAME] ?? null;
        $client = $this->getChatBotClient($uri);
        $payload = [];
        try {
            $response = $client->post(self::ENDPOINT_CONVERSATION, [
                RequestOptions::HEADERS => $this->getAuthHeaders(),
                RequestOptions::JSON => $payload,
            ]);
            $body = $response->getBody();
            $data = json_decode((string) $body, true, 512, JSON_THROW_ON_ERROR);
            $conversation = new ConversationResponse($data, $this->apis);

            return $conversation->isValid() ? $conversation : null;
        } catch (GuzzleException $e) {
            return null;
        } catch (\JsonException $e) {
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Add message to conversation.
     *
     * @param ChatBotConversationMessage $message : message for get a response
     *
     * @return AnswerResponse[]|null
     */
    public function addMessage(ChatBotConversationMessage $message): ?array
    {
        $this->getAuthToken();
        $uri = $message->getConversation()->getUri();
        $client = $this->getChatBotClient($uri);
        $payload = [
            'message' => $message->getContent(),
        ];
        try {
            $response = $client->post(self::ENDPOINT_MESSAGE, [
                RequestOptions::HEADERS => $this->getSessionHeaders($message->getConversation()),
                RequestOptions::JSON => $payload,
            ]);
            $body = $response->getBody();
            $data = json_decode((string) $body, true, 512, JSON_THROW_ON_ERROR);
            if (isset($data['answers'])) {
                return array_map(static function (array $item) {
                    return new AnswerResponse($item);
                }, $data['answers']);
            }

            return [];
        } catch (GuzzleException $e) {
            return null;
        } catch (\JsonException $e) {
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Return custom basic headers for API request.
     *
     * @return string[]
     */
    private function getHeaders(): array
    {
        $headers = [
            'x-inbenta-key' => $this->key,
            'x-inbenta-env' => 'development',
            'Content-Type' => 'application/json',
        ];

        return $headers;
    }

    /**
     * return headers with Bearer token included for API auth.
     *
     * @array
     */
    private function getAuthHeaders(): array
    {
        $headers = $this->getHeaders();
        if ($this->token) {
            // if bearer token exists then include in headers for auth
            $headers['Authorization'] = 'Bearer '.$this->token;
        }

        return $headers;
    }

    /**
     * return headers with bearer token and session token.
     *
     * @return string[]
     */
    private function getSessionHeaders(\App\Entity\ChatBotConversation $conversation): array
    {
        $headers = $this->getAuthHeaders();
        if ($conversation->getRemoteToken()) {
            $headers['x-inbenta-session'] = 'Bearer '.$conversation->getRemoteToken();
        }

        return $headers;
    }
}
