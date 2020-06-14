<?php
declare(strict_types=1);

namespace Tests\Unit\Validator;

use MailValidator\Validator\Regex as RegexValidator;
use MailValidator\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class RegexTest extends TestCase
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
                'value' => 'system@gmail.nx',
                'expected' => true
            ],
            'valid_c' => [
                'value' => 'system@huxter.ca',
                'expected' => true
            ],
            'valid_d' => [
                'value' => 'system@facebook.co.uk',
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
                'value' => 'too@many@google.com.br',
                'expected' => false
            ],
            'invalid_b' => [
                'value' => '$pec-%$ial@google.com.br',
                'expected' => false
            ],
            'invalid_c' => [
                'value' => 't---o"%%%o@@google.com.br',
                'expected' => false
            ],

        ];
    }

    /**
     * @dataProvider emailProvider
     * @param string $email
     * @param bool $expected
     */
    public function test_regex_validator_works(string $email, bool $expected)
    {
        $validator = new RegexValidator();

        $subject = $validator->validate($email);

        self::assertEquals($subject, $expected);
    }

    /**
     * @dataProvider invalidEmailProvider
     * @param string $email
     * @param bool $expected
     */
    public function test_regex_validator_blocks_invalid_format(string $email, bool $expected)
    {
        /**
         * @var ValidatorInterface $subject
         */
        $validator = new RegexValidator();

        $subject = $validator->validate($email);

        self::assertFalse($subject);
        self::assertEquals($validator->getError(), RegexValidator::VALIDATION_ERROR_MSG);
    }

}