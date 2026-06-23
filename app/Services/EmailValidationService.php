<?php

namespace App\Services;

use Exception;

class EmailValidationService
{
    private array $disposableDomains = [
        // Temporary email services
        'tempmail.com', 'temp-mail.org', '10minutemail.com', 'guerrillamail.com',
        'mailinator.com', 'maildrop.cc', 'throwaway.email', 'yopmail.com',
        'temp.email', 'tempmail.org', 'fakeinbox.com', 'trashmail.com',
        'spam4.me', 'mailnesia.com', 'mailtrap.io', 'ethereal.email',
        'testmail.com', 'minutemail.com', 'sharklasers.com', 'grr.la',
        'temp-mail.io', '1secmail.com', 'temp-mail.io', 'mailnesia.com',
        'dispostable.com', 'temp-sms.com', 'throwawaymail.com', 'spam.la',
        'temp-mail.cc', '10minutemail.com', 'mytrashmail.com', 'fakeemail.net',
        'trashmail.com', 'tempail.com', 'tempmail.de', 'tempmail.info',
    ];

    /**
     * Validate email secara komprehensif
     */
    public function validate(string $email): array
    {
        $email = strtolower(trim($email));

        // 1. Format validation
        if (! $this->isValidFormat($email)) {
            return [
                'valid' => false,
                'message' => 'Format email tidak valid',
                'type' => 'format',
            ];
        }

        // 2. Check disposable domains
        if ($this->isDisposableEmail($email)) {
            return [
                'valid' => false,
                'message' => 'Email dari provider temporary/disposable tidak diperbolehkan',
                'type' => 'disposable',
            ];
        }

        // 3. DNS record check
        if (! $this->hasMXRecord($email)) {
            return [
                'valid' => false,
                'message' => 'Domain email tidak valid atau tidak dapat dijangkau',
                'type' => 'dns',
            ];
        }

        return [
            'valid' => true,
            'message' => 'Email valid',
            'type' => 'success',
        ];
    }

    /**
     * Validasi format email dengan regex ketat
     */
    private function isValidFormat(string $email): bool
    {
        // RFC 5322 simplified validation
        $pattern = '/^(?!.*\.{2})(?!\.)[a-zA-Z0-9._-]+(?<!\.)@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        if (! preg_match($pattern, $email)) {
            return false;
        }

        // Check length
        if (strlen($email) > 254) {
            return false;
        }

        [$localPart, $domain] = explode('@', $email);

        // Local part max 64 chars
        if (strlen($localPart) > 64) {
            return false;
        }

        // No consecutive dots
        if (strpos($localPart, '..') !== false || strpos($domain, '..') !== false) {
            return false;
        }

        // No special sequences
        if (preg_match('/[<>()[\]\\,;:\s@"]/', $email)) {
            return false;
        }

        return true;
    }

    /**
     * Check apakah email dari disposable/temporary provider
     */
    private function isDisposableEmail(string $email): bool
    {
        [, $domain] = explode('@', strtolower($email));

        return in_array($domain, $this->disposableDomains);
    }

    /**
     * Check MX record DNS untuk domain
     */
    private function hasMXRecord(string $email): bool
    {
        try {
            [, $domain] = explode('@', $email);

            // Check DNS MX record
            $mxHosts = [];
            if (@getmxrr($domain, $mxHosts)) {
                return ! empty($mxHosts);
            }

            // Fallback: check A/AAAA record jika MX tidak ada
            if (@gethostbyname($domain) !== $domain) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get suggestion untuk user (jika ada typo umum)
     */
    public function getSuggestion(string $email): ?string
    {
        $commonDomains = ['gmail.com', 'yahoo.com', 'outlook.com', 'mail.com'];
        [, $domain] = explode('@', $email);

        // Check untuk typo umum menggunakan levenshtein distance
        foreach ($commonDomains as $common) {
            $distance = levenshtein($domain, $common);

            if ($distance < 3 && $distance > 0) {
                return str_replace('@'.$domain, '@'.$common, $email);
            }
        }

        return null;
    }

    /**
     * Add custom disposable domain
     */
    public function addDisposableDomain(string $domain): void
    {
        $domain = strtolower(trim($domain));

        if (! in_array($domain, $this->disposableDomains)) {
            $this->disposableDomains[] = $domain;
        }
    }

    /**
     * Get all disposable domains
     */
    public function getDisposableDomains(): array
    {
        return $this->disposableDomains;
    }
}
