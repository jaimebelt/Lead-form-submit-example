<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use App\Service\ResponseFormatter;

require __DIR__ . '/../vendor/autoload.php';

// Load container from bootstrap
$container = require __DIR__ . '/../src/bootstrap.php';
AppFactory::setContainer($container);
$app = AppFactory::create();

// Register services
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

// Load routes
$routes = require __DIR__ . '/../src/config/routes.php';
$routes($app);

$app->run();