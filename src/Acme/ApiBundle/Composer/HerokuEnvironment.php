<?php
namespace Acme\ApiBundle\Composer;

use Composer\Script\Event;

class HerokuEnvironment
{
    /**
     * Populate Heroku environment
     *
     * @param Event $event Event
     */
    public static function populateEnvironment(Event $event)
    {
        $url = getenv('CLEARDB_DATABASE_URL');

        if ($url) {
            $url = parse_url($url);
            putenv("SYMFONY__DATABASE_DRIVER=pdo_mysql");
            putenv("SYMFONY__DATABASE_HOST={$url['host']}");
            putenv("SYMFONY__DATABASE_USER={$url['user']}");
            putenv("SYMFONY__DATABASE_PASSWORD={$url['pass']}");

            $db = substr($url['path'], 1);
            putenv("SYMFONY__DATABASE_NAME={$db}");
        }

        $io = $event->getIO();
        $io->write('CLEARDB_DATABASE_URL=' . getenv('CLEARDB_DATABASE_URL'));
    }

    /**
     * Update DB schema and load fixtures
     *
     * @param Event $event
     */
    public static function updateSchema(Event $event)
    {
        $options = self::getOptions($event);
        $consoleDir = self::getConsoleDir($event, 'build');

        if (null === $consoleDir) {
            return;
        }

        static::executeCommand($event, $consoleDir, 'doctrine:schema:update --force', $options['process-timeout']);
        static::executeCommand($event, $consoleDir, 'doctrine:fixtures:load --append', $options['process-timeout']);

    }
}