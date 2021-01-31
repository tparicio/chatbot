<?php

namespace App\Controller;

use App\Entity\ChatBotConversationMessage;
use App\Util\ChatBot\ChatBotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ChatBotController.
 *
 * @author Toni Paricio <toniparicio@gmail.com>
 * @Route("/chatbot");
 */
class ChatBotController extends AbstractController
{
    /**
     * ChatBox home
     * Create a ChatBox session or recovery from session and database.
     *
     * @Route("/", name="chatbot")
     */
    public function index(): Response
    {
        return $this->render('chat_bot/index.html.twig', [
        ]);
    }

    /**
     * AJAX :: get session chatbox messages.
     *
     * @Route("/messages", name="chatbot_messages", methods={"GET"})
     * @param ChatBotService $chatBotService : main service for ChatBot
     * @return JsonResponse
     */
    public function getMessages(ChatBotService $chatBotService): JsonResponse
    {
        $session = $chatBotService->getOrCreateChatBotSession();

        return new JsonResponse([
            'result' => true,
            'messages' => array_map(static function (ChatBotConversationMessage $message) {
                return $message->toArray();
            }, $session->getMessages())
        ]);
    }

    /**
     * AJAX :: Submitted ChatBox message in ajax request.
     *
     * @Route("/submit", name="chatbot_submit")
     * @param Request $request : request for get message content
     * @param ChatBotService $chatBotService : main service for ChatBot
     * @return JsonResponse
     */
    public function submitMessage(Request $request, ChatBotService $chatBotService): JsonResponse
    {
        $responses = $chatBotService->addChatBotMessage($request->get('content'));

        return new JsonResponse([
            'result' => true,
            'answers' => array_map(static function (ChatBotConversationMessage $response) {
                return $response->toArray();
            }, $responses),
        ]);
    }
}
