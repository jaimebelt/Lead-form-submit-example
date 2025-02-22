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
    private LeadRepository $leadRepository;
    private ResponseFormatter $responseFormatter;

    public function __construct(
        LeadRepository $leadRepository,
        ResponseFormatter $responseFormatter
    ) {
        $this->leadRepository = $leadRepository;
        $this->responseFormatter = $responseFormatter;
    }

    public function getLeads(Request $request, Response $response): Response
    {
        try {
            $leads = $this->leadRepository->getAllLeads();

            if (empty($leads)) {
                return $this->responseFormatter->notFound(
                    $response,
                    'No leads found'
                );
            }

            return $this->responseFormatter->success(
                $response,
                $leads,
                'Leads retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->responseFormatter->internalServerError(
                $response,
                'Internal server error'
            );
        }
    }
}
