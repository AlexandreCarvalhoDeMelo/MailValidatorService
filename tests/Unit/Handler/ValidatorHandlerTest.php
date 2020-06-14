<?php
declare(strict_types=1);

namespace Tests\Unit\Validator;

use MailValidator\Handler\ValidatorHandler;
use MailValidator\Service\Validator as ValidatorService;
use MailValidator\Service\Validator;
use MailValidator\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Request;

class ValidatorHandlerTest extends TestCase
{
    public function test_controller_is_working()
    {

        //Mock validator
        $validator = \Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')
            ->andReturn(1);

        $validator->shouldReceive('getName')
            ->andReturn('MockValidator');

        $validator->shouldReceive('getError')
            ->andReturnNull();


        $service = \Mockery::mock(ValidatorService::class);
        $service->expects('getValidationStack')->andReturn([
            $validator
        ]);

        $expectedResult = [
            'valid' => true,
            'validators' => [
                'MockValidator' => [
                    [
                        'valid' => true
                    ]
                ]
            ]
        ];

        $service->expects('processAndGetResult')->andReturn(
            $expectedResult
        );

        // instantiate action
        $action = new ValidatorHandler($service);

        $request = \Mockery::mock(Request::class);

        //Mock request
        $request->expects('getBody->getContents')->andReturn(
            json_encode(['email' => 'system@google.com'])
        );

        $controller = $action->handle($request);
        $subject = json_decode($controller->getBody()->getContents(), true);

        self::assertEquals($expectedResult, $subject);
    }
}