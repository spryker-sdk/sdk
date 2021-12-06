<?php

$bundles = [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    SprykerSdk\Sdk\Presentation\Console\SprykerSdkConsoleBundle::class => ['all' => true],
    SprykerSdk\Sdk\Core\SprykerSdkCoreBundle::class => ['all' => true],
    SprykerSdk\Sdk\Infrastructure\SprykerSdkInfrastructureBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
];

$extensionDirectory = __DIR__ . '/../extension/*/';

$autoloader = new \SprykerSdk\Sdk\Infrastructure\Service\AutoloaderService(__DIR__ . '/../');
$autoloader->loadClassesFromDirectory(
    [$extensionDirectory],
    '*Bundle.php',
    function (string $loadableClassName) use (&$bundles): void {
        $bundles[$loadableClassName] = ['all' => true];
    }
);

return $bundles;
