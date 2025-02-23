<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use App\Service\ResponseFormatter;
use App\Middleware\CorsMiddleware;
use Psr\Log\LoggerInterface;

require __DIR__ . '/../vendor/autoload.php';

// Load container from bootstrap
$container = require __DIR__ . '/../src/bootstrap.php';
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add CORS middleware
$app->add($container->get(CorsMiddleware::class));

// Handle preflight OPTIONS requests
$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

$responseFormatter = $container->get(ResponseFormatter::class);
$logger = $container->get(LoggerInterface::class);

// Override the default error handler to return a JSON response
$errorMiddleware->setDefaultErrorHandler(
    function (Request $request, Throwable $exception, bool $displayErrorDetails) 
    use ($app, $responseFormatter, $logger) {
        $statusCode = 500;
        
        if ($exception instanceof HttpNotFoundException) {
            $statusCode = 404;
        }

        $response = $app->getResponseFactory()->createResponse();

        $error= [
            'error' => $exception->getMessage(),
            'trace' => $displayErrorDetails ? $exception->getTraceAsString() : null
        ];

        $logger->error('Unhandled exception: ' . $exception->getMessage(), $error);

        return $responseFormatter->internalServerError(
            response: $response,
            statusCode: $statusCode,
        );
    }
);

// Load routes
$routes = require __DIR__ . '/../src/config/routes.php';
$routes($app);

$app->run();