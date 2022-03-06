<?php

namespace App\Application\Trial\Response;

class SignatureGuessResponse
{
    private string $signatureToWin;

    private function __construct(string $signatureToWin)
    {
        $this->signatureToWin = $signatureToWin;
    }

    public function getSignatureToWin(): string
    {
        return $this->signatureToWin;
    }

    public static function createNoSignatureRequired(): self
    {
        return new self('No signature required to win the trial');
    }

    public static function createFromSignature(string $signatureToWin): self
    {
        return new self(sprintf('The minimum signature to win the trial is "%s"', $signatureToWin));
    }
}
