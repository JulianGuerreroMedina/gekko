<?php

declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/private/paths.php';

chdir(APP_ROOT);

require_once PRIVATE_PATH . '/ConfigBD.php';

define('APP_ROUTER_MODE', 'custom');
define('APP_ROUTER_PATH', PRIVATE_PATH . '/router.php');

require_once CORE_PATH . '/autoload.php';
