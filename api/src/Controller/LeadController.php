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
        $leads = $this->leadRepository->findAll();
        
        $response->getBody()->write(json_encode($leads));
        return $response->withHeader('Content-Type', 'application/json');
    }
} 