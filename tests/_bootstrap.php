<?php

use Spryker\Shared\Config\Environment;

require_once __DIR__ . '/../vendor/autoload.php';

define('APPLICATION_ENV', Environment::TESTING);
define('APPLICATION_STORE', 'UNIT');

$stream = \org\bovigo\vfs\vfsStream::setup('root');
define('APPLICATION_ROOT_DIR', $stream->url());
