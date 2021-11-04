#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$application = new Symfony\Component\Console\Application;
$application->add(new \App\Command\SprykerSdkConsole());
$application->run();
