<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use \MailValidator\Service\Validator as ValidatorService;
use \MailValidator\Validator\Regex as RegexValidator;
use \MailValidator\Validator\Domain as DomainValidator;
use \MailValidator\Validator\Smtp as SmtpValidator;

//I really dig dependency containers and some auto-wiring
return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions(
        [
            ValidatorService::class => function (ContainerInterface $c) {
                return (new ValidatorService())
                    ->addValidator((new RegexValidator()))
                    ->addValidator((new DomainValidator()))
                    ->addValidator((new SmtpValidator()));
            }
        ]
    );
};
