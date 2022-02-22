<?php

namespace App\Notifications;

use Illuminate\Http\Client\Factory;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class GotifyApiClient
{
    public function __construct(
        private readonly Factory $httpFactory,
        private readonly LoggerInterface $logger
    ) {
        $this->httpFactory->contentType("application/json")
            ->bodyFormat('json')
            ->withHeaders([
                'X-Gotify-Key' => env('GOTIFY_API_KEY')
            ]);
    }

    public function postMessage(string $recipient, string $body): bool
    {
        $message = $this->httpFactory->post('gotify:'.env('GOTIFY_PORT'), [
                'message' => $body,
                'title' => $recipient,
            ]);
        $this->logger->info("Create message status: {$message->status()}");

        return $message->status() === Response::HTTP_OK;
    }
}
