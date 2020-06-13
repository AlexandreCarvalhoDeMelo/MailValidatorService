<?php

declare(strict_types=1);

use MailValidator\Handler\ValidatorHandler as MainController;

return function (Slim\App $app) {
    $app->post('/email/validate', MainController::class)
        ->setName('Valid');
};
