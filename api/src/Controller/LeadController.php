<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\LeadRepository;
use App\Service\ResponseFormatter;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LeadController
{
    public function __construct(
        private LeadRepository $leadRepository,
        private ResponseFormatter $responseFormatter
    ) {
    }

    public function getLeads(Request $request, Response $response): Response
    {
        try {
            $leads = $this->leadRepository->getAllLeads();

            return $this->responseFormatter->success(
                $response,
                $leads,
                empty($leads) ? 'No leads found' : 'Leads retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->responseFormatter->internalServerError(
                $response,
                'Internal server error'
            );
        }
    }
}
