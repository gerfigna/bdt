<?php

namespace App\Domain\Contract\Exception;

class InvalidSignatureException extends \Exception
{
    public function __construct(string $signaturesString)
    {
        parent::__construct(sprintf("Signatures string '%s' is not valid ", $signaturesString));
    }
}
