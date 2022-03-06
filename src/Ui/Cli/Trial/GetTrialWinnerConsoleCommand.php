<?php

namespace App\Ui\Cli\Trial;

use App\Application\Trial\Query\GetTrialWinnerQuery;
use App\Application\Trial\Response\TrialWinnerResponse;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class GetTrialWinnerConsoleCommand extends Command
{
    use HandleTrait;

    protected static $defaultName = 'app:trial-winner';

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('plaintiffSignatures', InputArgument::REQUIRED, 'Plaintiff contract signatures')
            ->addArgument('defendantSignatures', InputArgument::REQUIRED, 'Defendant contract signatures')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var TrialWinnerResponse $result */
            $result = $this->handle(new GetTrialWinnerQuery($input->getArgument('plaintiffSignatures'), $input->getArgument('defendantSignatures')));

            $output->writeln($result->getTrialResult());
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
