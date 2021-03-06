<?php

declare(strict_types=1);

namespace MailValidator\Validator;

/**
 * Class Smtp
 * @package MailValidator\Validator
 */
class Smtp extends ValidatorAbstract implements ValidatorInterface
{
    public const VALIDATOR_NAME = 'smtp';
    public const VALIDATION_ERROR_MSG = "UNABLE_TO_CONNECT";
    private const VALIDATION_ERROR_FUNCTION_NOT_FOUND = "SOMETHING_WENT_WRONG";

    /**
     * @param string $email
     * @return bool
     */
    public function validate(string $email): bool
    {
        if (function_exists('checkdnsrr')) {
            list(, $domain) = explode('@', $email);
            if (checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A')) {
                return true;
            }
        } else {
            $this->setValidatorError(self::VALIDATION_ERROR_FUNCTION_NOT_FOUND);
            return false;
        }

        $this->setValidatorError(self::VALIDATION_ERROR_MSG);
        return false;
    }

    /**
     * returns validator name
     */
    public function getName(): string
    {
        return self::VALIDATOR_NAME;
    }
}
