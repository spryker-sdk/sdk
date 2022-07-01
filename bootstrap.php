<?php

if (
    (
        str_replace(
        [
            'vendor/bin/phpmd'
        ],
        '',
        $_SERVER['SCRIPT_FILENAME']
        ) !== $_SERVER['SCRIPT_FILENAME']
    ) &&
    file_exists(getcwd() . '/vendor/autoload.php')
) {
    require_once getcwd() . '/vendor/autoload.php';
}
