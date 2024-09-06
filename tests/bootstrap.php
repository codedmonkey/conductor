<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

passthru(sprintf(
  'APP_ENV=%s php "%s/../bin/console" doctrine:database:create',
  $_ENV['APP_ENV'],
  __DIR__
));

passthru(sprintf(
  'APP_ENV=%s php "%s/../bin/console" doctrine:schema:create',
  $_ENV['APP_ENV'],
  __DIR__
));
