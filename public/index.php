<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

define('APPLICATION_ROOT_DIR', dirname(__DIR__, 1));


return function (array $context) {
    chdir(getenv('PROJECT_DIR'));

    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
