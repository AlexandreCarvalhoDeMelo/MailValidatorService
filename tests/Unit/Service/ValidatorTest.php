<?php
declare(strict_types=1);

namespace Tests\Unit\Service;

use MailValidator\Service\Validator as ValidatorService;
use MailValidator\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @return array
     */
    public function emailProvider(): array
    {
        return [
            'email_domain' => [
                'value' => 'system@google.com.br',
                'expected' => [
                    'valid' => true,
                    'validators' => [
                        'MockValidator' => [
                            [
                                'valid' => true
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     *
     */
    public function test_validation_service_can_add_validator()
    {
        $validator = \Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')
            ->andReturn(1);

        $validator->shouldReceive('getName')
            ->andReturn('MockValidator');

        $validator->shouldReceive('getError')
            ->andReturnNull();


        $service = new ValidatorService();
        $service->addValidator($validator);

        self::assertEquals($service->getValidationStack()[0], $validator);
    }

    /**
     * @dataProvider emailProvider
     * @param string $email
     * @param array $expected
     */
    public function test_process_valid_result(string $email, array $expected)
    {
        $validator = \Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')
            ->andReturn(1);

        $validator->shouldReceive('getName')
            ->andReturn('MockValidator');

        $validator->shouldReceive('getError')
            ->andReturnNull();


        $service = new ValidatorService();
        $service->addValidator($validator);

        $subject = $service->processAndGetResult($email);

        self::assertEquals($expected, $subject);
    }
}
