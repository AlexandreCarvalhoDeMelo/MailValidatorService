<?php

declare(strict_types=1);

namespace MailValidator\Validator;

/**
 * Class Domain
 * @package MailValidator\Validator
 */
class Domain extends ValidatorAbstract implements ValidatorInterface
{
    public const VALIDATOR_NAME = 'domain';
    public const VALIDATION_TLD_MSG = "INVALID_TLD";
    public const VALIDATION_DOMAIN_MSG = "INVALID_DOMAIN";
    private const TLD_LIST_PATH = 'config/tld_list_version_2020061200.txt';

    /**
     * I didnt really wanted to have whole packages just to check for TLDS
     * Also checking it from the URL can be a little slow
     * What i did is saved the file to the config folder (yes could be in a better place)
     * the file content is found on:
     *
     * @see https://data.iana.org/TLD/tlds-alpha-by-domain.txt
     * @param string $email
     * @return bool
     */
    public function validate(string $email): bool
    {
        $emailParts = explode('@', $email);
        $domain = array_pop($emailParts);

        $isDomainValid = filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);

        if (!$isDomainValid) {
            $this->setValidatorError(self::VALIDATION_DOMAIN_MSG);
            return false;
        }

        $tldList = file_get_contents(self::TLD_LIST_PATH);
        $domainParts = explode('.', $domain);
        $tld = strtoupper(array_pop($domainParts));

        if (preg_match("#\\b$tld\\b#", $tldList)) {
            return true;
        }

        $this->setValidatorError(self::VALIDATION_TLD_MSG);

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
