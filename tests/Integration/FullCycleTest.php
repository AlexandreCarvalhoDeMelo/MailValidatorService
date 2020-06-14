<?php
declare(strict_types=1);

namespace Tests\Integration\Validator;

use MailValidator\Handler\ValidatorHandler;
use MailValidator\Service\Validator as ValidatorService;
use MailValidator\Validator\Domain;
use MailValidator\Validator\Regex;
use MailValidator\Validator\Smtp;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Request;

class FullCycleTest extends TestCase
{

    public function test_valid_cycle()
    {

        //up validators
        $regexValidator = new Regex();
        $domainValidator = new Domain();
        $smtpValidator = new Smtp();


        //up service
        $service = new ValidatorService();

        $service->addValidator($regexValidator)
            ->addValidator($domainValidator)
            ->addValidator($smtpValidator);

        //up "controler" handler
        $action = new ValidatorHandler($service);

        $request = \Mockery::mock(Request::class);
        $request->expects('getBody->getContents')->andReturn(
            json_encode(['email' => 'system@google.com'])
        );

        $subject = $action->handle($request);

        $expectedResult = json_decode('{
                "valid": true,
                "validators": {
                    "regex": [
                        {
                            "valid": true
                        }
                    ],
                    "domain": [
                        {
                            "valid": true
                        }
                    ],
                    "smtp": [
                        {
                            "valid": true
                        }
                    ]
                }
            }');

        self::assertEquals($expectedResult, json_decode($subject->getBody()->getContents()));


    }

    public function test_invalid_regex_cycle()
    {

        //up validators
        $regexValidator = new Regex();
        $domainValidator = new Domain();
        $smtpValidator = new Smtp();


        //up service
        $service = new ValidatorService();

        $service->addValidator($regexValidator)
            ->addValidator($domainValidator)
            ->addValidator($smtpValidator);

        //up "controler" handler
        $action = new ValidatorHandler($service);

        $request = \Mockery::mock(Request::class);
        $request->expects('getBody->getContents')->andReturn(
            json_encode(['email' => 'tes/te@show.com'])
        );

        $subject = $action->handle($request);

        $expectedResult = json_decode('{
                "valid": false,
                "validators": {
                    "regex": [
                        {
                            "valid": false,
                            "reason": "INVALID_EMAIL_FORMAT"
                        }
                    ],
                    "domain": [
                        {
                            "valid": true
                        }
                    ],
                    "smtp": [
                        {
                            "valid": true
                        }
                    ]
                }
            }');

        self::assertEquals($expectedResult, json_decode($subject->getBody()->getContents()));


    }

    public function test_invalid_domain_cycle()
    {

        //up validators
        $regexValidator = new Regex();
        $domainValidator = new Domain();
        $smtpValidator = new Smtp();


        //up service
        $service = new ValidatorService();

        $service->addValidator($regexValidator)
            ->addValidator($domainValidator)
            ->addValidator($smtpValidator);

        //up "controler" handler
        $action = new ValidatorHandler($service);

        $request = \Mockery::mock(Request::class);
        $request->expects('getBody->getContents')->andReturn(
            json_encode(['email' => 'teste@tesssssste.gfy'])
        );

        $subject = $action->handle($request);

        $expectedResult = json_decode('{
                "valid": false,
                "validators": {
                    "regex": [
                        {
                            "valid": true
                        }
                    ],
                    "domain": [
                        {
                            "valid": false,
                            "reason": "INVALID_TLD"
                        }
                    ],
                    "smtp": [
                        {
                            "valid": false,
                            "reason": "UNABLE_TO_CONNECT"
                        }
                    ]
                }
            }');


        self::assertEquals($expectedResult, json_decode($subject->getBody()->getContents()));

    }


    public function test_invalid_smtp_cycle()
    {

        //up validators
        $regexValidator = new Regex();
        $domainValidator = new Domain();
        $smtpValidator = new Smtp();


        //up service
        $service = new ValidatorService();

        $service->addValidator($regexValidator)
            ->addValidator($domainValidator)
            ->addValidator($smtpValidator);

        //up "controler" handler
        $action = new ValidatorHandler($service);

        $request = \Mockery::mock(Request::class);
        $request->expects('getBody->getContents')->andReturn(
            json_encode(['email' => 'teste@giffy123123x.com'])
        );

        $subject = $action->handle($request);

        $expectedResult = json_decode('{
                "valid": false,
                "validators": {
                    "regex": [
                        {
                            "valid": true
                        }
                    ],
                    "domain": [
                        {
                            "valid": true
                        }
                    ],
                    "smtp": [
                        {
                            "valid": false,
                            "reason": "UNABLE_TO_CONNECT"
                        }
                    ]
                }
            }');


        self::assertEquals($expectedResult, json_decode($subject->getBody()->getContents()));

    }
}