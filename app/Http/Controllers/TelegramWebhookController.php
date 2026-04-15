<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Application\Command\MessengerWebhookCommand;
use Application\DTO\MessengerClientDTO;
use Application\Port\Bus\QueueBusPortInterface;
use DefStudio\Telegraph\DTO\TelegramUpdate;
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
     * @param QueueBusPortInterface $queueBus
     * @return JsonResponse
     */
    public function __invoke(Request $request, QueueBusPortInterface $queueBus): JsonResponse
    {
        $requestDto = TelegramUpdate::fromArray($request->all());
        $message = $requestDto->message();

        if ($message === null || $message->from() === null) {
            return response()->json(['status' => 'ignored']);
        }

        try {
            $messengerId = new MessengerId(
                MessengerProviderEnum::TELEGRAM,
                (string) $message->from()->id(),
            );
        } catch (DomainException $e) {
            throw new RuntimeException($e->getMessage());
        }

        $chatId = $message->chat()?->id();
        if (is_null($chatId)) {
            $chatId = $requestDto->chatMemberUpdate()?->chat()?->id();
        }

        $command = new MessengerWebhookCommand(
            messengerClientDTO: new MessengerClientDTO(
                username: $message->from()->username(),
                firstName: $message->from()->firstName(),
                lastName: $message->from()->lastName(),
                isBot: $message->from()->isBot(),
                messengerId: $messengerId->getValue(),
                clientId: null,
            ),
            chatId: (int) $chatId,
            messageText: $message->text(),
        );

        $queueBus->dispatch($command);

        return response()->json($chatId);
    }
}
