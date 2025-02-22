<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use App\Service\ResponseFormatter;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$responseFormatter = new ResponseFormatter();

// Custom error handler
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

// Override the default error handler
$errorMiddleware->setDefaultErrorHandler(
    function (Request $request, Throwable $exception, bool $displayErrorDetails) 
    use ($app, $responseFormatter) {
        $statusCode = 500;
        
        if ($exception instanceof HttpNotFoundException) {
            $statusCode = 404;
        }

        $response = $app->getResponseFactory()->createResponse();
        return $responseFormatter->asJson(
            $response,
            [
                'error' => $exception->getMessage(),
                'trace' => $displayErrorDetails ? $exception->getTraceAsString() : null
            ],
            $statusCode,
            'An error occurred'
        );
    }
);

$app->get('/api/health', function (Request $request, Response $response) use ($responseFormatter) {
    return $responseFormatter->success(
        $response,
        [],
        'System is healthy'
    );
});

$app->run();