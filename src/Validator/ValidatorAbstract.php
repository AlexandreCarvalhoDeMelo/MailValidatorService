<?php

declare(strict_types=1);

namespace MailValidator\Validator;

/**
 * Class Request
 * @package MailValidator\Validator
 */
abstract class ValidatorAbstract
{
    protected $name;
    protected $error;

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error ?? null;
    }

    /**
     * @param string $name
     */
    protected function setValidatorName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $error
     */
    protected function setValidatorError(string $error): void
    {
        $this->error = $error;
    }
}
