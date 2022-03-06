<?php

namespace App\Ui\Cli\Trial;

use App\Application\Trial\Query\GuessMissingSignatureQuery;
use App\Application\Trial\Response\SignatureGuessResponse;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class GetMinimumSignatureToWinConsoleCommand extends Command
{
    use HandleTrait;

    protected static $defaultName = 'app:signature-guess';

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('incompleteContract', InputArgument::REQUIRED, 'Incomplete contract signatures')
            ->addArgument('oppositeContract', InputArgument::REQUIRED, 'Opposite contract signatures')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var string $result */
            $incompleteContract = $input->getArgument('incompleteContract');
            $oppositionContract = $input->getArgument('oppositeContract');

            /** @var SignatureGuessResponse $result */
            $result = $this->handle(new GuessMissingSignatureQuery($incompleteContract, $oppositionContract));

            $output->writeln($result->getSignatureToWin());
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
