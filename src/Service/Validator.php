<?php

declare(strict_types=1);

namespace MailValidator\Service;

use MailValidator\Validator\ValidatorInterface;

/**
 * Class ValidatorChain
 * @package MailValidator\Validator
 */
class Validator
{
    const FIELD_VALID = 'valid';
    const FIELD_RESULT = 'result';
    const FIELD_ERROR_REASON = 'reason';
    const FIELD_VALIDATORS = 'validators';

    /**
     * Class stacks, this could be a object
     */
    private $validatorStack = [];

    /**
     * @param ValidatorInterface $validator
     * @return Validator
     */
    public function addValidator(ValidatorInterface $validator): self
    {
        $this->validatorStack[] = $validator;

        return $this;
    }

    /**
     * Returns a validation matrix
     *
     * @param string $email
     * @return array
     */
    public function processAndGetResult(string $email): array
    {
        $isValid = true;
        $rows = [];

        array_map(function (ValidatorInterface $validator) use ($email, &$isValid, &$rows): void {
            $status = $validator->validate($email);
            $isValid = !$status ? $status : $isValid;

            $error = $validator->getError();
            $response = !$error ? [self::FIELD_VALID => $status] :
                [
                    self::FIELD_VALID => $status,
                    self::FIELD_ERROR_REASON => $error,
                ];

            $rows[self::FIELD_VALIDATORS][$validator::VALIDATOR_NAME] = [$response];
        }, $this->validatorStack);

        $rows[self::FIELD_VALID] = $isValid;

        return array_reverse($rows);
    }
}
