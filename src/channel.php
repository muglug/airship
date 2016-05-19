<?php
declare(strict_types=1);

use \Airship\Engine\Keyggdrasil;

/**
 * Automatic update processing -- either throw this in a cronjob or let it get
 * triggered every time a page loads after enough time has elapsed
 */
\ignore_user_abort(true);
\set_time_limit(0);

require_once __DIR__.'/bootstrap.php';

if (\is_readable(ROOT . '/config/databases.json')) {
    /**
     * Initialize the channel updater service
     */
    $channels = \Airship\loadJSON(ROOT . '/config/channels.json');
    $database = \Airship\get_database();

    $state->logger->info('Keyggdrasil started');
    $keyUpdater = \Airship\Engine\Gears::get(
        'KeyUpdater',
        $hail,
        $database,
        $channels
    );
    if (IDE_HACKS) {
        $keyUpdater = new Keyggdrasil();
    }

    $keyUpdater->doUpdate();
    $state->logger->info('Keyggdrasil concluded');
} else {
    // We can't update keys without a place to persist the changes
}
