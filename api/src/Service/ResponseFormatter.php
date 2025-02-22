<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Http\Message\ResponseInterface;

class ResponseFormatter
{
    public function asJson(
        ResponseInterface $response,
        mixed $data,
        int $statusCode = 200,
        ?string $message = null
    ): ResponseInterface {
        $payload = [
            'success' => $statusCode >= 200 && $statusCode < 300,
            'message' => $message,
            'data' => $data
        ];

        $json = json_encode($payload, JSON_PRETTY_PRINT);
        if ($json === false) {
            throw new \RuntimeException('Failed to encode response to JSON: ' . json_last_error_msg());
        }

        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus($statusCode);
        $response->getBody()->write($json);

        return $response;
    }

    public function success(
        ResponseInterface $response,
        mixed $data,
        ?string $message = null,
        int $statusCode = 200
    ): ResponseInterface {
        return $this->asJson($response, $data, $statusCode, $message);
    }

    public function notFound(
        ResponseInterface $response,
        ?string $message = 'Resource not found',
        int $statusCode = 404
    ): ResponseInterface {
        return $this->asJson($response, null, $statusCode, $message);
    }

    public function validationError(
        ResponseInterface $response,
        mixed $errors,
        int $statusCode = 422
    ): ResponseInterface {
        return $this->asJson($response, $errors, $statusCode, 'Validation error');
    }

    public function internalServerError(
        ResponseInterface $response,
        ?string $message = 'Internal server error',
        int $statusCode = 500
    ): ResponseInterface {
        return $this->asJson($response, null, $statusCode, $message);
    }
}
