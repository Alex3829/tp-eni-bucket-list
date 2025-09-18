<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\WishRepository;
use DateTimeImmutable;

#[AsCommand(
    name: 'app:course:clear-wish',
    description: 'Delete unpublished wishes in the wish table older than 6 months.',
)]
class ClearWishTableCommand extends Command
{

    private WishRepository $wishRepository;

    public function __construct(WishRepository $wishRepository)
    {
        parent::__construct();
        $this->wishRepository = $wishRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'age_limit',
                InputArgument::OPTIONAL,
                'Age limit of the wish in months',
                6 // valeur par défaut
            );
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $ageLimit = (int) $input->getArgument('age_limit');
        $dateLimit = new \DateTimeImmutable("-$ageLimit months");

        $count = $this->wishRepository->clearWishes($dateLimit);

        $io->success("Command success - $count wishes have been deleted");

        return Command::SUCCESS;
    }
}
