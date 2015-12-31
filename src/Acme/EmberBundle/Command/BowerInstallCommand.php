<?php

// src/Acme/EmberBundle/Command/GreetCommand.php
namespace Acme\EmberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

class BowerInstallCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ember:bower:install')
            ->setDescription('Install bower dependencies')
            ->addOption(
                'timeout',
                null,
                InputOption::VALUE_OPTIONAL,
                'Timeout',
                600
            )
            ->setHelp(<<<EOT
The <info>ember:bower:install</info> command installs bower dependencies of the EmberJS application.

  <info>php app/console ember:bower:install [--timeout[="..."]]</info>
EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $this->getContainer()->get('kernel')->locateResource('@AcmeEmberBundle/Resources/public');

        $builder = new ProcessBuilder(array('bower', 'install'));
        $builder->setTimeout($input->getOption('timeout'));
        $builder->setWorkingDirectory($path);

        $output->writeln(
            sprintf(
                'Installing bower dependencies for <comment>"%s"</comment> into <comment>"%s"</comment>',
                'AcmeEmberBundle',
                $path
            )
        );

        $process = $builder->getProcess();
        $process->run(function($type, $data) use ($output) {
            $output->write($data);
        });
    }
}