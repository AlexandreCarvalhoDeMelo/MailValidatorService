<?php

declare(strict_types=1);

namespace MailValidator\Validator;

/**
 * Interface Validator
 * @package MailValidator\Validator
 */
interface ValidatorInterface
{

    /**
     * Validates input
     *
     * @param string $email
     * @return bool
     */
    public function validate(string $email): bool;
    public function getError(): ?string;
    public function getName(): string;
}
