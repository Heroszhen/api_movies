<?php

namespace App\Command;

use App\Service\WebsocketService;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Loop;
use React\Socket\SecureServer;
use React\Socket\SocketServer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'websocket:serveur',
    description: 'Serveur websocket',
)]
class WebsocketServerCommand extends Command
{
    private WebsocketService $websocketService;
    private KernelInterface $kernel;

    public function __construct(WebsocketService $websocketService, KernelInterface $kernel, string $name = null)
    {
        parent::__construct($name);
        $this->websocketService = $websocketService;
        $this->kernel = $kernel;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('port', InputArgument::OPTIONAL, 'Port utilisé par le websocket', 8080)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $projectRoot = $this->kernel->getProjectDir();

        $port = $input->getArgument('port');

        // $io->success('Démarrage du serveur websocket avec SSL sur le port '.$port.'.');

        // $loop =  Loop::get();
        // $webSock = new SocketServer('0.0.0.0:'.$port);

        // $secureWebSock = new SecureServer(
        //     $webSock,
        //     $loop,
        //     [
        //         'local_cert' => $projectRoot .'/path/to/ssl/certificate.crt',
        //         'local_pk' => $projectRoot .'/path/to/ssl/private.key',
        //         'allow_self_signed' => true,
        //     ]
        // );

        // $server = new IoServer(
        //     new HttpServer(
        //         new WsServer(
        //             $this->websocketService
        //         )
        //     ),
        //     $secureWebSock
        // );

        // $loop->run();

        // $io->warning('Arrèt du serveur websocket.');
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $this->websocketService
                )
            ),
            $port
        );
        $server->run();

        return Command::SUCCESS;
    }
}