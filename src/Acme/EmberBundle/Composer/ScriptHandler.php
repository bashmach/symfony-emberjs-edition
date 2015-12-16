<?php

namespace Acme\EmberBundle\Composer;

use Composer\Script\CommandEvent;

class ScriptHandler extends \Sensio\Bundle\DistributionBundle\Composer\ScriptHandler {


    /**
     * Call the demo command of the Acme Demo Bundle.
     *
     * @param $event CommandEvent A instance
     */
    public static function build(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $consoleDir = self::getConsoleDir($event, 'build');

        if (null === $consoleDir) {
            return;
        }

        static::executeCommand($event, $consoleDir, 'ember:npm:install', $options['process-timeout']);
        static::executeCommand($event, $consoleDir, 'ember:build', $options['process-timeout']);
    }

}