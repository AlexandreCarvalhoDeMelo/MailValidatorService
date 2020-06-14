<?php
declare(strict_types=1);

namespace Tests\Unit\Validator;

use MailValidator\Validator\Domain as DomainValidator;
use PHPUnit\Framework\TestCase;

class DomainTest extends TestCase
{

    /**
     * @return array
     */
    public function domainProvider(): array
    {
        return [
            'email_domain' => [
                'value' => 'system@google.com.br',
                'expected' => true
            ]
        ];
    }

    /**
     * @return array
     */
    public function invalidDomainProvider(): array
    {
        return [
            'email_domain' => [
                'value' => 'system@google.co/m.b/r',
                'expected' => false
            ]
        ];
    }

    /**
     * @dataProvider domainProvider
     * @param string $email
     * @param bool $expected
     */
    public function test_domain_validator_works(string $email, bool $expected)
    {
        $validator = new DomainValidator();

        $subject = $validator->validate($email);

        self::assertEquals($subject, $expected);
    }


    /**
     * @dataProvider invalidDomainProvider
     * @param string $email
     * @param bool $expected
     */
    public function test_domain_validator_blocks_invalid_format(string $email, bool $expected)
    {
        $validator = new DomainValidator();

        $subject = $validator->validate($email);

        self::assertEquals($subject, $expected);
    }
}