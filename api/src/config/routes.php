<?php

declare(strict_types=1);

use Slim\App;
use App\Controller\LeadController;
use App\Repository\LeadRepository;
use App\Service\ResponseFormatter;
use App\Service\Database;

return function (App $app) {
    $container = $app->getContainer();
    $database = $container?->get(Database::class);

    if (!$database) {
        throw new \RuntimeException('Database service not found');
    }

    $app->group('/api', function ($group) use ($database) {
        // Health check route
        $group->get('/health', function ($request, $response) {
            return (new ResponseFormatter())->success(
                $response,
                [],
                'System is healthy'
            );
        });

        // Leads routes
        $group->group('/leads', function ($group) use ($database) {
            $leadController = new LeadController(
                new LeadRepository($database->getConnection()),
                new ResponseFormatter()
            );

            $group->get('', [$leadController, 'getLeads']);
        });
    });
};
