<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enums\LeadSource;
use App\Exceptions\ValidationException;
use App\Repository\LeadRepository;
use App\Service\ExternalApiService;
use App\Service\ResponseFormatter;
use App\Service\ValidationService;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Psr\Log\LoggerInterface;

class LeadController
{
    public function __construct(
        private LeadRepository $leadRepository,
        private ResponseFormatter $responseFormatter,
        private ValidationService $validationService,
        private ExternalApiService $externalApiService,
        private LoggerInterface $logger
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
            $this->logger->error('Error retrieving leads: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->responseFormatter->internalServerError(
                $response,
                'Internal server error'
            );
        }
    }

    public function createLead(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $this->logger->info('Creating lead', [
                'data' => $data
            ]);

            if ($data === null) {
                $data = json_decode((string) $request->getBody(), true);
            }

            $validationRules = [
                'name' => v::notEmpty()->length(3, 50),
                'email' => v::notEmpty()->email(),
                'phone' => v::optional(v::phone()),
                'source' => v::notEmpty()->in(array_column(LeadSource::cases(), 'value')),
            ];

            $validationErrors = $this->validationService->validate($data ?? [], $validationRules);
            if ($validationErrors !== null) {
                throw new ValidationException('Validation failed', $validationErrors);
            }

            $existingLead = $this->leadRepository->getLeadByEmail($data['email']);

            if ($existingLead) {
                throw new ValidationException('Validation failed', [
                    'email' => 'This email is already registered'
                ]);
            }

            $lead = $this->leadRepository->createNewLead($data);

            if ($lead !== false) {
                $this->externalApiService->notifyNewLead($lead);
            }

            return $this->responseFormatter->success(
                response: $response,
                data: $lead,
                message: 'Lead created successfully'
            );
        } catch (ValidationException $e) {
            return $this->responseFormatter->validationError($response, $e->getErrors());
        } catch (Exception $e) {
            $this->logger->error('Error creating lead: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data ?? null
            ]);
            return $this->responseFormatter->internalServerError(
                $response,
                'Internal server error: ' . $e->getMessage()
            );
        }
    }
}
