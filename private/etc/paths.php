<?php

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__, 2));
}

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', APP_ROOT . '/public');
}

if (!defined('PRIVATE_PATH')) {
    define('PRIVATE_PATH', APP_ROOT . '/private');
}

if (!defined('STORAGE_PATH')) {
    define('STORAGE_PATH', APP_ROOT . '/storage');
}

if (!defined('DOCTOS_PATH')) {
    define('DOCTOS_PATH', STORAGE_PATH . '/doctos');
}

if (!defined('CORE_PATH')) {
    define('CORE_PATH', PRIVATE_PATH . '/core');
}

if (!defined('CONTROLLERS_PATH')) {
    define('CONTROLLERS_PATH', PRIVATE_PATH . '/Controllers');
}

if (!defined('API_PATH')) {
    define('API_PATH', PRIVATE_PATH . '/api');
}

if (!defined('LIBS_PATH')) {
    define('LIBS_PATH', PRIVATE_PATH . '/libs');
}

if (!defined('VIEWS_PATH')) {
    define('VIEWS_PATH', PRIVATE_PATH . '/vistas');
}

if (!defined('ETC_PATH')) {
    define('ETC_PATH', PRIVATE_PATH . '/etc');
}

if (!defined('ENV_FILE')) {
    define('ENV_FILE', PRIVATE_PATH . '/.env');
}

if (!defined('MEDIA_PATH')) {
    define('MEDIA_PATH', PUBLIC_PATH . '/media');
}

if (!defined('MEDIA_PUBLIC_URL')) {
    define('MEDIA_PUBLIC_URL', '/media');
}

if (!defined('SESSIONS_PATH')) {
    define('SESSIONS_PATH', STORAGE_PATH . '/sessions');
}

if (!defined('TMP_PATH')) {
    define('TMP_PATH', STORAGE_PATH . '/tmp');
}

if (!defined('LOGS_PATH')) {
    define('LOGS_PATH', STORAGE_PATH . '/logs');
}
