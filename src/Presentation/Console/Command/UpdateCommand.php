<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends AbstractUpdateCommand
{
    /**
     * @var string
     */
    public const NAME = 'sdk:update:hidden-all';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface
     */
    protected LifecycleManagerInterface $lifecycleManager;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface
     */
    protected InitializerInterface $initializer;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface
     */
    protected ContextFactoryInterface $contextFactory;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface $lifecycleManager
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface $initializer
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface $contextFactory
     */
    public function __construct(
        LifecycleManagerInterface $lifecycleManager,
        InitializerInterface $initializer,
        ContextFactoryInterface $contextFactory
    ) {
        parent::__construct(static::NAME);
        $this->lifecycleManager = $lifecycleManager;
        $this->initializer = $initializer;
        $this->contextFactory = $contextFactory;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initializer->initialize([]);
        $context = $this->contextFactory->getContext();

        if ($input->getOption(static::OPTION_NO_CHECK)) {
            $this->checkForUpdate($context);
        }

        if (!$input->getOption(static::OPTION_CHECK_ONLY)) {
            $this->lifecycleManager->update();
        }

        return static::SUCCESS;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function checkForUpdate(ContextInterface $context): void
    {
        foreach ($this->lifecycleManager->checkForUpdate() as $key => $message) {
            $context->addMessage('check_updates_' . $key, $message);
        }
    }
}
