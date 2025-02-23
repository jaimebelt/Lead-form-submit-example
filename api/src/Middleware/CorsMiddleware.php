<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    public function __construct(
        /**
         * @var array<string, mixed>
         */
        private array $corsConfig
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $origin = $request->getHeaderLine('Origin');

        if (
            in_array($origin, $this->corsConfig['allowed_origins'])
            || in_array('*', $this->corsConfig['allowed_origins'])
        ) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $origin);
        } elseif (empty($origin) && !empty($this->corsConfig['allowed_origins'])) {
            $response = $response->withHeader(
                'Access-Control-Allow-Origin',
                $this->corsConfig['allowed_origins'][0]
            );
        }

        return $response
            ->withHeader('Access-Control-Allow-Methods', implode(', ', $this->corsConfig['allowed_methods']))
            ->withHeader('Access-Control-Allow-Headers', implode(', ', $this->corsConfig['allowed_headers']));
    }
}
