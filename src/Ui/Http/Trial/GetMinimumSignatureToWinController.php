<?php

namespace App\Ui\Http\Trial;

use App\Application\Trial\Query\GuessMissingSignatureQuery;
use App\Application\Trial\Response\SignatureGuessResponse;
use App\Domain\Contract\Exception\InvalidSignatureException;
use App\Domain\Contract\Exception\UnreachableScoreException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class GetMinimumSignatureToWinController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/api/trial/{incomplete}/{opposite}/guess-signature", name="app_guess",
     *     methods={"GET"}, requirements={"incomplete"="\w*#\w*","opposite"="\w+"})
     */
    public function __invoke(string $incomplete, string $opposite): Response
    {
        try {
            /** @var SignatureGuessResponse $result */
            $result = $this->handle(new GuessMissingSignatureQuery($incomplete, $opposite));

            return new JsonResponse([
                'result' => $result->getSignatureToWin(),
            ]);
        } catch (HandlerFailedException $e) {
            $exception = $e->getNestedExceptions()[0];
            if ($exception instanceof UnreachableScoreException) {
                throw new NotFoundHttpException($exception->getMessage());
            }
            if ($exception instanceof InvalidSignatureException) {
                throw new BadRequestHttpException($exception->getMessage());
            }

            throw $e;
        }
    }
}
