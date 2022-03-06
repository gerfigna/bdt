<?php

namespace App\Domain\Contract\Service;

use App\Domain\Contract\Exception\UnreachableScoreException;
use App\Domain\Contract\Model\Contract;
use App\Domain\Contract\Model\IncompleteContract;

class SignatureGuesser
{
    /**
     * @throws UnreachableScoreException
     */
    public function guessMinimumSignatureToWin(IncompleteContract $contract, Contract $opposition): ?string
    {
        $score = $contract->getScore();
        $oppositionScore = $opposition->getScore();

        if ($score > $oppositionScore) {
            return null;
        }

        $possibleSignatures = Contract::VALID_SIGNATURES;
        asort($possibleSignatures);

        foreach ($possibleSignatures as $letter => $points) {
            if ($score + $points > $oppositionScore) {
                return $letter;
            }
        }

        throw new UnreachableScoreException($contract, $opposition);
    }
}
