<?php

namespace App\Command;

use App\Service\ParserService;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:parse-product',
    description: 'Parse a product from chosen marketplace using its url',
)]
class ParseProductCommand extends Command
{
    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly ParserService $parser
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'marketplace',
            InputArgument::REQUIRED,
            'Marketplace from which you want to get products'
        )
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'Product\'s url'
            );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws GuzzleException
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $marketplace = $input->getArgument('marketplace');
        $url = $input->getArgument('url');
        $publicDir = $this->kernel->getProjectDir().'/public/';

        $io->note('Parsing product from marketplace: '.$marketplace);

        $this->parser->parseFromUrl($url, $marketplace, $publicDir);

        $io->success('Successfully parsed!');

        return Command::SUCCESS;
    }
}
