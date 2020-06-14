<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestHandler;

/**
 * Bootstraping madness
 */

require_once __DIR__ . '/vendor/autoload.php';

define('APP_ENV', $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'DEVELOPMENT');

$settings = (require_once __DIR__ . '/config/settings.php')(APP_ENV);

$containerBuilder = new ContainerBuilder();

if ($settings['di_compilation_path']) {
    $containerBuilder->enableCompilation($settings['di_compilation_path']);
}

(require_once __DIR__ . '/config/dependencies.php')($containerBuilder, $settings);

AppFactory::setContainer($containerBuilder->build());

$app = AppFactory::create();

$app->getRouteCollector()->setDefaultInvocationStrategy(
    new RequestHandler(true)
);

(require_once __DIR__ . '/config/middleware.php')($app);

(require_once __DIR__ . '/config/routes.php')($app);

$app->run();
