<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:download-javascript-scripts',
    description: 'Add a short description for your command',
)]
class DownloadJavascriptScriptsCommand extends Command
{
    const SCRIPTS = [
        [
            'name' => 'UnoCss',
            'link' => 'https://cdn.jsdelivr.net/npm/@unocss/runtime'
        ]
    ];
    const FOLDER = '/scripts/';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ParameterBagInterface $parameterBag,
        private readonly Filesystem $filesystem
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $folder = $this->parameterBag->get('public_dir') . self::FOLDER;
        $this->filesystem->remove($folder);
        $this->filesystem->mkdir($folder);

        $io = new SymfonyStyle($input, $output);

        foreach (self::SCRIPTS as $script) {
            try {
                $response = $this->httpClient->request('GET', $script['link']);
                if (200 === $response->getStatusCode()) {
                    $content = $response->getContent();
                    file_put_contents(
                        $folder . strtolower($script['name']) . '.js', 
                        $content
                    );
                    $io->info(sprintf("%s: download", $script['name']));
                } else {
                    $io->error(sprintf("%s: %s", $script['name'], $response->getStatusCode()));
                }
            } catch (\Exception $e) {
                $io->error(sprintf("%s: %s", $script['name'], $e->getMessage()));
            }
        }
        $io->success('Done');

        return Command::SUCCESS;
    }
}
