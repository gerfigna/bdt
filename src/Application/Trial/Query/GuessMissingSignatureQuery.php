<?php

namespace App\Application\Trial\Query;

class GuessMissingSignatureQuery
{
    private string $incompleteSignatures;
    private string $oppositionSignatures;

    public function __construct(string $incompleteSignatures, string $oppositionSignatures)
    {
        $this->incompleteSignatures = $incompleteSignatures;
        $this->oppositionSignatures = $oppositionSignatures;
    }

    public function getIncompleteSignatures(): string
    {
        return $this->incompleteSignatures;
    }

    public function getOppositionSignatures(): string
    {
        return $this->oppositionSignatures;
    }
}
