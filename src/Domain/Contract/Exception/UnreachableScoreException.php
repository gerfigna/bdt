<?php

namespace App\Domain\Contract\Exception;

use App\Domain\Contract\Model\Contract;
use App\Domain\Contract\Model\IncompleteContract;

class UnreachableScoreException extends \Exception
{
    public function __construct(IncompleteContract $contract, Contract $opposition)
    {
        parent::__construct(sprintf("Cannot guess the minimum signature for '%s' to win to '%s'", $contract->getSignatures(), $opposition->getSignatures()));
    }
}
