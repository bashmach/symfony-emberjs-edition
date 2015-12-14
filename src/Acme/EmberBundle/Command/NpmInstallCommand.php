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

class NpmInstallCommand extends ContainerAwareCommand
{
    protected $bundleName = '@AcmeEmberBundle';

    protected function configure()
    {
        $this
            ->setName('ember:npm:install')
            ->setDescription('Install npm dependencies')
            ->addOption(
                'timeout',
                null,
                InputOption::VALUE_OPTIONAL,
                'Timeout',
                600
            )
            ->setHelp(<<<EOT
The <info>ember:npm:install</info> command installs npm dependencies of the EmberJS application.

  <info>php app/console ember:npm:install [--timeout[="..."]]</info>
EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $this->getContainer()->get('kernel')->locateResource('@AcmeEmberBundle/Resources/public');

        $builder = new ProcessBuilder(array('npm', 'install'));
        $builder->setTimeout($input->getOption('timeout'));
        $builder->setWorkingDirectory($path);

        $output->writeln(
            sprintf(
                'Installing npm dependencies for <comment>"%s"</comment> into <comment>"%s"</comment>',
                $this->bundleName,
                $path
            )
        );

        $process = $builder->getProcess();
        $process->run(function($type, $data) use ($output) {
            $output->write($data);
        });

//        while ($process->isRunning()) {
//            // waiting for process to finish
//            ;
//        }

//        $output->write($process->getOutput());
    }
}