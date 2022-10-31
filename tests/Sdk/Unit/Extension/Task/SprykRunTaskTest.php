<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Extension\Task;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Extension
 * @group Task
 * @group SprykRunTaskTest
 * Add your own group annotations below this line
 */
class SprykRunTaskTest extends KernelTestCase
{
    /**
     * @var string
     */
    protected const COMMAND = 'spryk:run';

    /**
     * @return string
     */
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    /**
     * @return void
     */
    public function testSprykRunRunsSuccessfully(): void
    {
        // Arrange
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $applicationTester = new ApplicationTester($application);
        $expectedJson = [
            'Object' => [
                'name' => [
                    'key' => 'John',
                ],
            ],
        ];

        // Act
        $applicationTester->run(
            [
                'command' => static::COMMAND,
                '--spryk' => 'UpdateJson',
                '--targetModule' => 'Pyz.AuthRestApi.Glue',
                '--option' => [
                    '--targetFilename=Object.json',
                    '--key=key',
                    '--value=' . $expectedJson['Object']['name']['key'],
                    '--targetPath=tests/_project/project/src/Pyz/Glue/AuthRestApi/',
                    '--target=Object.name',
                    '--no-interaction',
                ],
                '--no-interaction',
            ],
            [
                'interactive' => false,
            ],
        );

        // Assert
        $applicationTester->assertCommandIsSuccessful();
    }
}
