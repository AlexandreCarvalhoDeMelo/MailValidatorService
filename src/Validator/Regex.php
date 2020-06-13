<?php

declare(strict_types=1);

namespace MailValidator\Validator;

/**
 * Class Regex
 * @package MailValidator\Validator
 */
class Regex extends ValidatorAbstract implements ValidatorInterface
{
    public const VALIDATOR_NAME = 'regex';
    private const VALIDATION_ERROR_MSG = 'INVALID_EMAIL_FORMAT';

    /**
     * Regex validation pattern
     */
    private const PATTERN = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix';

    /**
     * @param string $email
     * @return bool
     */
    public function validate(string $email): bool
    {
        if (!(bool)preg_match(self::PATTERN, $email)) {
            $this->setValidatorError(self::VALIDATION_ERROR_MSG);
            return false;
        }
        return true;
    }
}
