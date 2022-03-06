<?php

namespace App\Domain\Contract\Model;

use App\Domain\Contract\Exception\InvalidSignatureException;

class IncompleteContract extends Contract
{
    /**
     * @throws InvalidSignatureException
     */
    public static function createFromSignaturesString(string $signatures): self
    {
        self::guard($signatures);

        $score = self::scoreFromString($signatures);

        return new self($signatures, $score);
    }

    /**
     * @throws InvalidSignatureException
     */
    public static function guard(string $signatures)
    {
        if (!preg_match('/^[^#]*#[^#]*$/', $signatures)) {
            throw new InvalidSignatureException($signatures);
        }

        $completedSignatures = str_replace('#', '', $signatures);
        parent::guard($completedSignatures);
    }
}
