<?php
declare(strict_types=1);

namespace Tests\Unit\Validator;

use MailValidator\Validator\Domain as DomainValidator;
use MailValidator\Validator\ValidatorInterface;
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
            'email_invalid_domain' => [
                'value' => 'system@google.co/m.b/r',
                'expected' => false
            ]
        ];
    }

    /**
     * @return array
     */
    public function invalidTldProvider(): array
    {
        return [
            'email_invalid_tld' => [
                'value' => 'system@strange.domain.from.a.crazy.galaxie',
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
        /**
         * @var ValidatorInterface $subject
         */
        $validator = new DomainValidator();

        $subject = $validator->validate($email);

        self::assertFalse($subject);
        self::assertEquals($validator->getError(), DomainValidator::VALIDATION_DOMAIN_MSG);
    }

    /**
     * @dataProvider invalidTldProvider
     * @param string $email
     * @param bool $expected
     */
    public function test_domain_validator_blocks_invalid_tld(string $email, bool $expected)
    {
        /**
         * @var ValidatorInterface $subject
         */
        $validator = new DomainValidator();

        $subject = $validator->validate($email);

        self::assertFalse($subject);
        self::assertEquals($validator->getError(), DomainValidator::VALIDATION_TLD_MSG);
    }
}