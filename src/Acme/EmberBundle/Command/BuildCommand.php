<?php

// src/Acme/EmberBundle/Command/BuildCommand.php
namespace Acme\EmberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Event\ConsoleExceptionEvent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\VarDumper\VarDumper;

class BuildCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ember:build')
            ->setDescription('Build EmberJS application')
            ->addOption(
                'api_host',
                null,
                InputOption::VALUE_OPTIONAL,
                'API application host'
            )
            ->setHelp(<<<EOT
The <info>ember:build</info> command builds EmberJS application.

  <info>php app/console ember:build [--api_host[="..."]]</info>
EOT
            );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $this->getContainer()->get('kernel')->locateResource('@AcmeEmberBundle/Resources/public');

        $output->writeln(
            sprintf(
                'Build EmberJS application in <comment>"%s"</comment>',
                $path
            )
        );

        $appHost = $input->getOption('api_host');

        // value from command input override parameter value
        if (is_null($appHost) && $this->getContainer()->hasParameter('acme_ember.api_host')) {
            $appHost = $this->getContainer()->getParameter('acme_ember.api_host');
        }

        if (!$appHost) {
            throw new \InvalidArgumentException('`api_host` should be defined as configuration parameter in parameters.yml or passed as command option.');
        }

        $builder = new ProcessBuilder(array('./node_modules/.bin/ember', 'build'));
        $builder->setTimeout(600);
        $builder->setWorkingDirectory($path);
        $builder->setEnv('APP_HOST', $appHost);

        $process = $builder->getProcess();
        $process->run(function($type, $data) use ($output) {
            $output->writeln($data);
        });
    }
}