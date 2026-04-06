<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Application\Command\MessengerWebhookCommand;
use Application\Command\MessengerWebhookHandler;
use Application\DTO\MessengerClientDTO;
use DefStudio\Telegraph\DTO\TelegramUpdate;
use DefStudio\Telegraph\Facades\Telegraph;
use Domain\MessengerClient\MessengerProviderEnum;
use Domain\MessengerClient\VO\MessengerId;
use Domain\Shared\DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

/**
 *
 */
final class TelegramWebhookController extends Controller
{
    /**
     * @param Request $request
     * @param MessengerWebhookHandler $handler
     * @return JsonResponse
     */
    public function __invoke(Request $request, MessengerWebhookHandler $handler): JsonResponse
    {
        $requestDto = TelegramUpdate::fromArray($request->all());
        try {
            $messengerId = new MessengerId(
                MessengerProviderEnum::TELEGRAM,
                (string) $requestDto->message()->from()->id(),
            );
        } catch (DomainException $e) {
            throw new RuntimeException($e->getMessage());
        }

        $chatId = $requestDto->message()?->chat()?->id();
        if (is_null($chatId)) {
            $chatId = $requestDto->chatMemberUpdate()?->chat()?->id();
        }

        $command = new MessengerWebhookCommand(
            messengerClientDTO: new MessengerClientDTO(
                messengerId: $messengerId->getValue(),
                firstName: $requestDto->message()->from()->firstName(),
                lastName: $requestDto->message()->from()->lastName(),
                isBot: $requestDto->message()->from()->isBot(),
                clientId: null,
            ),
            chatId: (int) $chatId,
        );

        Telegraph::chat($chatId)->message('Hello world!')->send();
        $handler->handle($command);
        return response()->json($chatId);
    }
}
