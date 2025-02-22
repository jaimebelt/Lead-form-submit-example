<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\LeadRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LeadController
{
    private LeadRepository $leadRepository;

    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    public function getLeads(Request $request, Response $response): Response
    {
        try {
            $leads = $this->leadRepository->getAllLeads();

            if (empty($leads)) {
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            $encodedLeads = json_encode($leads);
            if ($encodedLeads === false) {
                $reason = json_last_error_msg();
                throw new \RuntimeException("Failed to encode leads to JSON: $reason");
            }

            $response->getBody()->write($encodedLeads);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $errorMessage = json_encode(['error' => 'Internal server error']);
            $response->getBody()->write($errorMessage === false ? 'Internal server error' : $errorMessage);
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
