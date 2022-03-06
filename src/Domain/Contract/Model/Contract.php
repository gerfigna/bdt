<?php

namespace App\Domain\Contract\Model;

use App\Domain\Contract\Exception\InvalidSignatureException;

class Contract
{
    public const VALID_SIGNATURES = [
        'K' => 5,
        'N' => 2,
        'V' => 1,
    ];

    public const WINS = 1;
    public const LOOSE = -1;
    public const TIE = 0;

    protected string $signatures;
    protected int $score;

    protected function __construct(string $signatures, int $score)
    {
        $this->signatures = $signatures;
        $this->score = $score;
    }

    /**
     * @throws InvalidSignatureException
     */
    public static function createFromSignaturesString(string $signatures): self
    {
        self::guard($signatures);
        $score = self::scoreFromString($signatures);

        return new self($signatures, $score);
    }

    public function getSignatures(): string
    {
        return $this->signatures;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function compare(Contract $contract): int
    {
        return $this->score > $contract->getScore() ? self::WINS : ($this->getScore() === $contract->getScore() ? self::TIE : self::LOOSE);
    }

    protected static function scoreFromString(string $signatures): int
    {
        $score = 0;
        $hasKingSignature = str_contains($signatures, 'K');

        foreach (self::VALID_SIGNATURES as $letter => $points) {
            if ($hasKingSignature && 'V' === $letter) {
                continue;
            }

            $score += $points * substr_count($signatures, $letter);
        }

        return $score;
    }

    public static function isValid(string $signatures): bool
    {
        $validCharacters = [];
        foreach (self::VALID_SIGNATURES as $letter => $points) {
            $validCharacters[] = $letter;
        }

        return !strlen($signatures) > 0 || strlen(str_replace($validCharacters, '', $signatures)) > 0;
    }

    /**
     * @throws InvalidSignatureException
     */
    public static function guard(string $signatures)
    {
        if (self::isValid($signatures)) {
            throw new InvalidSignatureException($signatures);
        }
    }
}
