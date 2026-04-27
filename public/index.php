<?php

declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));
define('PUBLIC_PATH', APP_ROOT . '/public');
define('PRIVATE_PATH', APP_ROOT . '/private');
define('STORAGE_PATH', APP_ROOT . '/storage');
define('DOCTOS_PATH', STORAGE_PATH . '/doctos');
define('CORE_PATH', PRIVATE_PATH . '/core');
define('CONTROLLERS_PATH', PRIVATE_PATH . '/Controllers');
define('API_PATH', PRIVATE_PATH . '/api');
define('LIBS_PATH', PRIVATE_PATH . '/libs');
define('VIEWS_PATH', PRIVATE_PATH . '/vistas');
define('ETC_PATH', PRIVATE_PATH . '/etc');
define('ENV_FILE', PRIVATE_PATH . '/.env');

require_once ETC_PATH . '/paths.php';

@mkdir(STORAGE_PATH, 0775, true);
@mkdir(DOCTOS_PATH, 0775, true);
@mkdir(LOGS_PATH, 0775, true);
@mkdir(SESSIONS_PATH, 0775, true);
@mkdir(TMP_PATH, 0775, true);

chdir(APP_ROOT);

if (is_file(PRIVATE_PATH . '/ConfigBD.php')) {
    require_once PRIVATE_PATH . '/ConfigBD.php';
}
require_once CORE_PATH . '/autoload.php';
