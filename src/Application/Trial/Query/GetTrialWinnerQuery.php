<?php

namespace App\Application\Trial\Query;

class GetTrialWinnerQuery
{
    private string $plaintiffSignatures;
    private string $defendantSignatures;

    public function __construct(string $plaintiffSignatures, string $defendantSignatures)
    {
        $this->plaintiffSignatures = $plaintiffSignatures;
        $this->defendantSignatures = $defendantSignatures;
    }

    public function getPlaintiffSignatures(): string
    {
        return $this->plaintiffSignatures;
    }

    public function getDefendantSignatures(): string
    {
        return $this->defendantSignatures;
    }
}
