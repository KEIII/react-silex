<?php

namespace KEIII\ReactSilex;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * {@inheritdoc}
 */
class ReactCommand extends Command
{
    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = 1337;

    /**
     * @var ReactServer
     */
    private $server;

    /**
     * Constructor.
     *
     * @param ReactServer $server
     */
    public function __construct(ReactServer $server)
    {
        $this->server = $server;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('server:run');
        $this->setDescription('Start a reactive silex server.');

        $this->addOption(
            'host',
            null,
            InputArgument::OPTIONAL,
            'Host where server will be listening.',
            self::DEFAULT_HOST
        );

        $this->addOption(
            'port',
            'p',
            InputArgument::OPTIONAL,
            'Port where server will be listening.',
            self::DEFAULT_PORT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = (string)$input->getOption('host');
        $port = (int)$input->getOption('port');

        $output->writeln(sprintf('<info>Server running on %s:%s.</info>', $host, $port));
        $this->server->run($port, $host);
        $output->writeln('<info>Server stopped.</info>');
    }
}
