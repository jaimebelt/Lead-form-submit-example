<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class ExternalApiService
{
    private const MAX_RETRIES = 3;
    private const RETRY_DELAY = 2;
    private const RETRY_RESPONSE_CODES = [400, 500];

    private string $apiUrl;

    public function __construct(
        private Client $httpClient,
        private LoggerInterface $logger
    ) {
        $this->apiUrl = $_ENV['WEBHOOK_URL'];
    }

    /**
     * @param array<array-key, mixed> $lead
     */
    public function notifyNewLead(array $lead): void
    {
        $payload = [
            'lead_id' => $lead['id'],
            'name' => $lead['name'],
            'email' => $lead['email'],
            'source' => $lead['source']
        ];

        $attempts = 0;

        while ($attempts < self::MAX_RETRIES) {
            try {
                $attempts++;
                $this->logger->info('Notifying external system', [
                    'payload' => $payload,
                    'attempts' => $attempts,
                    'api_url' => $this->apiUrl
                ]);

                $response = $this->httpClient->post($this->apiUrl, [
                    'json' => $payload,
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                ]);

                if ($response->getStatusCode() === 200) {
                    return;
                }

                if (!in_array($response->getStatusCode(), self::RETRY_RESPONSE_CODES)) {
                    $this->logger->error('Unexpected response from external system. No retry will be attempted.', [
                        'status_code' => $response->getStatusCode(),
                        'response' => $response->getBody(),
                        'attempts' => $attempts,
                        'lead_id' => $lead['id']
                    ]);
                    return;
                }


                sleep(self::RETRY_DELAY);
            } catch (GuzzleException $e) {
                $this->logger->error('Error notifying external system.', [
                    'attempts' => $attempts,
                    'lead_id' => $lead['id'],
                    'error' => $e->getMessage()
                ]);
                return;
            }
        }

        $this->logger->error(
            'Failed to notify external system after ' . $attempts . ' attempts',
            [
                'attempts' => $attempts,
                'lead_id' => $lead['id'],
            ]
        );

        return;
    }
}
