<?php

declare(strict_types=1);

use Slim\App;
use App\Controller\LeadController;
use App\Service\ResponseFormatter;

return function (App $app) {
    $app->group('/api', function ($group) {
        // Health check route
        $group->get('/health', function ($request, $response) {
            /** @phpstan-ignore-next-line */
            return $this->get(ResponseFormatter::class)->success(
                $response,
                [],
                'System is healthy'
            );
        });

        // Leads routes
        $group->group('/leads', function ($group) {
            /** @phpstan-ignore-next-line */
            $leadController = $this->get(LeadController::class);
            $group->get('', [$leadController, 'getLeads']);
            $group->post('', [$leadController, 'createLead']);
        });
    });
};
