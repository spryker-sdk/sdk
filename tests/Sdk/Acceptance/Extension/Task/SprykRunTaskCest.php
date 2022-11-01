<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Task;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Acceptance
 * @group Extension
 * @group Task
 * @group SprykRunTaskCest
 * Add your own group annotations below this line
 */
class SprykRunTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'spryk:run';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testSprykRunRunsSuccessfully(AcceptanceTester $I): void
    {
        // Arrange
        $I->skipCliInteractiveTest();

        $jsonArray = [
            'Object' => [
                'name' => [
                    'key' => 'John',
                ],
            ],
        ];
        $expectedJson = json_encode($jsonArray);
        $I->createFileWithContent(
            $I->getPathFromProjectRoot('src/Pyz/Glue/AuthRestApi/Object.json'),
            $expectedJson,
        );

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--spryk=UpdateJson',
            '--targetModule=Pyz.AuthRestApi.Glue',
            '--option=--targetFilename=Object.json',
            '--option=--key=key',
            '--option=--value=' . $jsonArray['Object']['name']['key'],
            '--option=--targetPath=src/Pyz/Glue/AuthRestApi/',
            '--option=--target=Object.name',
            '--option=--no-interaction',
            '--no-interaction',
            '--quiet',
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
        Assert::assertJsonStringEqualsJsonString(
            $expectedJson,
            file_get_contents($I->getPathFromProjectRoot('src/Pyz/Glue/AuthRestApi/Object.json')),
        );
        $I->removeFile($I->getPathFromProjectRoot('src/Pyz/Glue/AuthRestApi/Object.json'));
    }
}
