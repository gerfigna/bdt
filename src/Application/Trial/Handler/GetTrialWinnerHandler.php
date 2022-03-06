<?php

namespace App\Application\Trial\Handler;

use App\Application\Trial\Query\GetTrialWinnerQuery;
use App\Application\Trial\Response\TrialWinnerResponse;
use App\Domain\Contract\Exception\InvalidSignatureException;
use App\Domain\Contract\Model\Contract;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetTrialWinnerHandler implements MessageHandlerInterface
{
    /**
     * @throws InvalidSignatureException
     */
    public function __invoke(GetTrialWinnerQuery $query): TrialWinnerResponse
    {
        $plaintiffContract = Contract::createFromSignaturesString($query->getPlaintiffSignatures());
        $defendantContract = Contract::createFromSignaturesString($query->getDefendantSignatures());

        $compareResult = $plaintiffContract->compare($defendantContract);

        switch ($compareResult) {
            case Contract::WINS: return TrialWinnerResponse::createFromContract('plaintiff', $plaintiffContract);
            case Contract::LOOSE: return TrialWinnerResponse::createFromContract('defendant', $defendantContract);
            default: return TrialWinnerResponse::createTie();
        }
    }
}
