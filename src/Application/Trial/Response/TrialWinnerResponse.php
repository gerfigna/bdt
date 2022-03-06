<?php

namespace App\Application\Trial\Response;

use App\Domain\Contract\Model\Contract;

class TrialWinnerResponse
{
    private string $trialResult;

    private function __construct(string $part)
    {
        $this->trialResult = $part;
    }

    public function getTrialResult(): string
    {
        return $this->trialResult;
    }

    public static function createTie(): self
    {
        return new self('Nobody wins');
    }

    public static function createFromContract(string $part, Contract $contract): self
    {
        return new self(sprintf('%s part wins with %s signatures and %d points', $part, $contract->getSignatures(), $contract->getScore()));
    }
}
