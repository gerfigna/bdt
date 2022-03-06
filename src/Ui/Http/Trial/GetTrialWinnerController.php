<?php

namespace App\Ui\Http\Trial;

use App\Application\Trial\Query\GetTrialWinnerQuery;
use App\Application\Trial\Response\TrialWinnerResponse;
use App\Domain\Contract\Exception\InvalidSignatureException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class GetTrialWinnerController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/api/trial/{plaintiffSignatures}/{defendantSignatures}/winner", name="app_trial",
     *     methods={"GET"}, requirements={"plaintiffSignatures"="\w+","defendantSignatures"="\w+"}, format="json")
     */
    public function __invoke(string $plaintiffSignatures, string $defendantSignatures): Response
    {
        try {
            /** @var TrialWinnerResponse $result */
            $result = $this->handle(new GetTrialWinnerQuery($plaintiffSignatures, $defendantSignatures));

            return new JsonResponse([
                'result' => $result->getTrialResult(),
            ]);
        } catch (HandlerFailedException $e) {
            $exception = $e->getNestedExceptions()[0];
            if ($exception instanceof InvalidSignatureException) {
                throw new BadRequestHttpException($exception->getMessage());
            }

            throw $e;
        }
    }
}
