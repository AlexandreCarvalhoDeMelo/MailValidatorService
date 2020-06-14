<?php
declare(strict_types=1);

namespace Tests\Unit\Validator;

use MailValidator\Validator\Smtp as SmtpValidator;
use MailValidator\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class SmtpTest extends TestCase
{

    /**
     * @return array
     */
    public function emailProvider(): array
    {
        return [
            'valid_a' => [
                'value' => 'system@google.com',
                'expected' => true
            ],
            'valid_b' => [
                'value' => 'system@gmail.com',
                'expected' => true
            ],
            'valid_c' => [
                'value' => 'system@facebook.com',
                'expected' => true
            ],

        ];
    }

    /**
     * @return array
     */
    public function invalidEmailProvider(): array
    {
        return [
            'invalid_a' => [
                'value' => 'too@many@aaaaaaaaaaaaa123sssssxx.com',
                'expected' => false
            ],
            'invalid_b' => [
                'value' => 'beer@vodkawhiskytequilarumcongnacwine.life',
                'expected' => false
            ],
            'invalid_c' => [
                'value' => 'ceo@mycompanyisnotaproblem-asyousee.to.me',
                'expected' => false
            ],

        ];
    }

    /**
     * @dataProvider emailProvider
     * @param string $email
     * @param bool $expected
     */
    public function test_smtp_validator_works(string $email, bool $expected)
    {
        $validator = new SmtpValidator();

        $subject = $validator->validate($email);

        self::assertEquals($subject, $expected);
    }

    /**
     *
     */
    public function test_checkdns_exists()
    {
        self::assertTrue(function_exists('checkdnsrr'));
    }

    /**
     * @dataProvider invalidEmailProvider
     * @param string $email
     * @param bool $expected
     */
    public function test_smtp_validator_blocks_non_existant(string $email)
    {
        /**
         * @var ValidatorInterface $subject
         */
        $validator = new SmtpValidator();

        $subject = $validator->validate($email);

        self::assertFalse($subject);
        self::assertEquals($validator->getError(), SmtpValidator::VALIDATION_ERROR_MSG);
    }
}
