<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\ValueReceiver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliInteractionProcessor;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\ArrayQuestionFactory;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\BooleanQuestionFactory;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\QuestionFactoryInterface;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\StringQuestionFactory;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactoryRegistry;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group ValueReceiver
 * @group CliInteractionProcessorTest
 */
class CliInteractionProcessorTest extends Unit
{
    /**
     * @return void
     */
    public function testGetsDefaultAsFirstChoiceKeyWhenDefaultValueNotSet(): void
    {
        //Arrange
        $questionAssertion = $this->createQuestionAssertion(ChoiceQuestion::class, 'test1');
        $questionHelper = $this->createQuestionHelperMock($questionAssertion);
        $questionFactoriesRegistry = $this->createQuestionFactoriesRegistryMock();
        $cliValueReceiver = $this->createCliValueReceiver($questionHelper, $questionFactoriesRegistry);
        $receiverValue = new ReceiverValue('', null, 'array', ['test1' => '']);

        //Act
        $cliValueReceiver->receiveValue($receiverValue);
    }

    /**
     * @return void
     */
    public function testGetsDefaultFromChoicesWhenDefaultValueNotSet(): void
    {
        //Arrange
        $questionAssertion = $this->createQuestionAssertion(ChoiceQuestion::class, 'test1');
        $questionHelper = $this->createQuestionHelperMock($questionAssertion);
        $questionFactoriesRegistry = $this->createQuestionFactoriesRegistryMock();
        $cliValueReceiver = $this->createCliValueReceiver($questionHelper, $questionFactoriesRegistry);
        $receiverValue = new ReceiverValue('', 'test1', 'array', ['test1', 'test2']);

        //Act
        $cliValueReceiver->receiveValue($receiverValue);
    }

    /**
     * @return void
     */
    public function testReturnsConfirmationQuestionWhenBooleanTypeSet(): void
    {
        //Arrange
        $questionAssertion = $this->createQuestionAssertion(ConfirmationQuestion::class, true);
        $questionHelper = $this->createQuestionHelperMock($questionAssertion);
        $questionFactoriesRegistry = $this->createQuestionFactoriesRegistryMock();
        $cliValueReceiver = $this->createCliValueReceiver($questionHelper, $questionFactoriesRegistry);
        $receiverValue = new ReceiverValue('', true, 'boolean', []);

        //Act
        $cliValueReceiver->receiveValue($receiverValue);
    }

    /**
     * @return void
     */
    public function testReturnsChoiceQuestionWhenChoiceValuesSet(): void
    {
        //Arrange
        $questionAssertion = $this->createQuestionAssertion(ChoiceQuestion::class, 'default');
        $questionHelper = $this->createQuestionHelperMock($questionAssertion);
        $questionFactoriesRegistry = $this->createQuestionFactoriesRegistryMock();
        $cliValueReceiver = $this->createCliValueReceiver($questionHelper, $questionFactoriesRegistry);
        $receiverValue = new ReceiverValue('', 'default', 'some-type', [1, 2, 3]);

        //Act
        $cliValueReceiver->receiveValue($receiverValue);
    }

    /**
     * @return void
     */
    public function testReturnsChoiceQuestionWithMultiLineWhenArrayTypeSet(): void
    {
        //Arrange
        $questionAssertion = $this->createArrayTypeWithChoicesAssertion('default');
        $questionHelper = $this->createQuestionHelperMock($questionAssertion);
        $questionFactoriesRegistry = $this->createQuestionFactoriesRegistryMock();
        $cliValueReceiver = $this->createCliValueReceiver($questionHelper, $questionFactoriesRegistry);
        $receiverValue = new ReceiverValue('', 'default', 'array', [1, 2, 3]);

        //Act
        $cliValueReceiver->receiveValue($receiverValue);
    }

    /**
     * @return void
     */
    public function testMultiValuesWithMultiLineWhenArrayTypeSet(): void
    {
        //Arrange
        $questionAssertion = $this->createQuestionAssertion(Question::class, 'test1');
        $questionHelper = $this->createQuestionHelperMock($questionAssertion);
        $questionFactoriesRegistry = $this->createQuestionFactoriesRegistryMock();
        $cliValueReceiver = $this->createCliValueReceiver($questionHelper, $questionFactoriesRegistry);
        $receiverValue = new ReceiverValue('', null, 'array', ['test1' => '']);

        //Act
        $cliValueReceiver->receiveValue($receiverValue);
    }

    /**
     * @param \Symfony\Component\Console\Helper\SymfonyQuestionHelper $questionHelper
     * @param \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactoryRegistry $questionFactoriesRegistry
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliInteractionProcessor
     */
    protected function createCliValueReceiver(
        SymfonyQuestionHelper $questionHelper,
        QuestionFactoryRegistry $questionFactoriesRegistry
    ): CliInteractionProcessor {
        $cliValueReceiver = new CliInteractionProcessor($questionHelper, $questionFactoriesRegistry);
        $cliValueReceiver->setInput($this->createInputMock());
        $cliValueReceiver->setOutput($this->createOutputMock());

        return $cliValueReceiver;
    }

    /**
     * @param callable $questionAssertion
     *
     * @return \Symfony\Component\Console\Helper\SymfonyQuestionHelper
     */
    protected function createQuestionHelperMock(callable $questionAssertion): SymfonyQuestionHelper
    {
        $questionHelperMock = $this->createMock(SymfonyQuestionHelper::class);
        $questionHelperMock
            ->expects($this->once())
            ->method('ask')
            ->with(
                $this->isInstanceOf(InputInterface::class),
                $this->isInstanceOf(OutputInterface::class),
                $this->callback($questionAssertion),
            );

        return $questionHelperMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactoryRegistry
     */
    protected function createQuestionFactoriesRegistryMock(): QuestionFactoryRegistry
    {
        $questionFactoryRegistryMock = $this->createMock(
            QuestionFactoryRegistry::class,
        );

        $mockMap = [
            ValueTypeEnum::TYPE_ARRAY => ArrayQuestionFactory::class,
            ValueTypeEnum::TYPE_BOOLEAN => BooleanQuestionFactory::class,
        ];

        $questionFactoryRegistryMock->method('getQuestionFactoryByType')->willReturnCallback(
            static function (string $type) use ($mockMap): QuestionFactoryInterface {
                if (!isset($mockMap[$type])) {
                    return new StringQuestionFactory();
                }

                return new $mockMap[$type]();
            },
        );

        return $questionFactoryRegistryMock;
    }

    /**
     * @param string $expectedQuestionClass
     * @param mixed $expectedDefaultValue
     * @param bool $isMultiLine
     *
     * @return callable
     */
    protected function createQuestionAssertion(
        string $expectedQuestionClass,
        $expectedDefaultValue,
        bool $isMultiLine = false
    ): callable {
        return static function (Question $expectedQuestion) use ($expectedQuestionClass, $expectedDefaultValue, $isMultiLine): bool {
            return $expectedQuestion instanceof $expectedQuestionClass &&
                $expectedQuestion->getDefault() === $expectedDefaultValue &&
                $expectedQuestion->isMultiline() === $isMultiLine;
        };
    }

    /**
     * @param mixed $expectedDefaultValue
     *
     * @return \Closure
     */
    protected function createArrayTypeWithChoicesAssertion($expectedDefaultValue)
    {
        return static function (ChoiceQuestion $expectedQuestion) use ($expectedDefaultValue): bool {
            return $expectedQuestion->getDefault() === $expectedDefaultValue &&
                $expectedQuestion->isMultiselect() === true;
        };
    }

    /**
     * @return \Symfony\Component\Console\Input\InputInterface
     */
    protected function createInputMock(): InputInterface
    {
        return $this->createMock(InputInterface::class);
    }

    /**
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    protected function createOutputMock(): OutputInterface
    {
        return $this->createMock(OutputInterface::class);
    }
}
