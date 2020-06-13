<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestHandler;

/**
 * Bootstraping madness
 */

require __DIR__ . '/vendor/autoload.php';

define('APP_ENV', $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'DEVELOPMENT');

$settings = (require __DIR__ . '/config/settings.php')(APP_ENV);

$containerBuilder = new ContainerBuilder();

if ($settings['di_compilation_path']) {
    $containerBuilder->enableCompilation($settings['di_compilation_path']);
}

(require __DIR__ . '/config/dependencies.php')($containerBuilder, $settings);

AppFactory::setContainer($containerBuilder->build());

$app = AppFactory::create();

$app->getRouteCollector()->setDefaultInvocationStrategy(
    new RequestHandler(true)
);

(require __DIR__ . '/config/middleware.php')($app);

(require __DIR__ . '/config/routes.php')($app);

$app->run();
