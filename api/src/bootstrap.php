<?php

declare(strict_types=1);

use App\Service\Database;
use App\Controller\LeadController;
use App\Repository\LeadRepository;
use App\Service\ExternalApiService;
use App\Service\ResponseFormatter;
use App\Service\ValidationService;
use DI\ContainerBuilder;
use Doctrine\DBAL\Driver\Mysqli\Connection;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger as MonologLogger;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    Database::class => function (ContainerInterface $container) {
        $config = require __DIR__ . '/config/database.php';
        return new Database($config['database']);
    },

    Connection::class => function (ContainerInterface $container) {
        return $container->get(Database::class)->getConnection();
    },

    LoggerInterface::class => function (ContainerInterface $container) {
        $logger = new MonologLogger('app');

        $config = require __DIR__ . '/config/logging.php';
        $logsDir = $config['logs']['directory'];

        $logger->pushHandler(new StreamHandler(
            $logsDir . '/' . $config['logs']['error_log'],
            $config['logs']['level']
        ));

        return $logger;
    },

    ResponseFormatter::class => function (ContainerInterface $container) {
        return new ResponseFormatter();
    },

    LeadRepository::class => function (ContainerInterface $container) {
        return new LeadRepository($container->get(Connection::class));
    },

    ValidationService::class => function (ContainerInterface $container) {
        return new ValidationService();
    },

    Client::class => function () {
        return new Client();
    },

    ExternalApiService::class => function (ContainerInterface $container) {
        return new ExternalApiService(
            $container->get(Client::class),
            $container->get(LoggerInterface::class)
        );
    },

    LeadController::class => function (ContainerInterface $container) {
        return new LeadController(
            $container->get(LeadRepository::class),
            $container->get(ResponseFormatter::class),
            $container->get(ValidationService::class),
            $container->get(ExternalApiService::class),
            $container->get(LoggerInterface::class)
        );
    },
]);

return $containerBuilder->build();
