<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    SprykerSdk\Sdk\Presentation\Console\SprykerSdkConsoleBundle::class => ['all' => true],
    SprykerSdk\Sdk\Core\SprykerSdkCoreBundle::class => ['all' => true],
    SprykerSdk\Sdk\Infrastructure\SprykerSdkInfrastructureBundle::class => ['all' => true],
    SprykerSdk\Sdk\Extension\SprykerSdkExtensionBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Upgrader\UpgraderBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    SprykerSdk\Sdk\Presentation\Web\SprykerSdkWebBundle::class => ['all' => true],
];
