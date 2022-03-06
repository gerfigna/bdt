<?php

namespace App\Application\Trial\Handler;

use App\Application\Trial\Query\GuessMissingSignatureQuery;
use App\Application\Trial\Response\SignatureGuessResponse;
use App\Domain\Contract\Exception\InvalidSignatureException;
use App\Domain\Contract\Exception\UnreachableScoreException;
use App\Domain\Contract\Model\Contract;
use App\Domain\Contract\Model\IncompleteContract;
use App\Domain\Contract\Service\SignatureGuesser;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GuessMissingSignatureHandler implements MessageHandlerInterface
{
    private SignatureGuesser $signatureGuesser;

    public function __construct(SignatureGuesser $signatureGuesser)
    {
        $this->signatureGuesser = $signatureGuesser;
    }

    /**
     * @throws InvalidSignatureException
     * @throws UnreachableScoreException
     */
    public function __invoke(GuessMissingSignatureQuery $query): SignatureGuessResponse
    {
        $signatures = IncompleteContract::createFromSignaturesString($query->getIncompleteSignatures());
        $opposition = Contract::createFromSignaturesString($query->getOppositionSignatures());

        $signatureToWin = $this->signatureGuesser->guessMinimumSignatureToWin($signatures, $opposition);

        if (null === $signatureToWin) {
            return SignatureGuessResponse::createNoSignatureRequired();
        }

        return SignatureGuessResponse::createFromSignature($signatureToWin);
    }
}
