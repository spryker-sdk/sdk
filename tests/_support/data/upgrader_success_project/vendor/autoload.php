<?php

spl_autoload_register(function (string $class) {
    if ($class === 'Pyz\Test') {
        include_once(__DIR__ . '/../src/Pyz/Test.php');
    }
});
