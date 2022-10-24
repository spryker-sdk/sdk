<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Initializer\Plugin;

use SprykerSdk\Sdk\Core\Application\Dto\TaskInit\AfterTaskInitDto;
use SprykerSdk\Sdk\Core\Application\Lifecycle\Event\InitializedEvent;
use SprykerSdk\Sdk\Core\Domain\Enum\CallSource;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ConsoleBasedEventDispatcherAfterTaskInitPlugin implements AfterTaskInitPluginInterface
{
    /**
     * @var \Symfony\Contracts\EventDispatcher\EventDispatcherInterface
     */
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * @param \Symfony\Contracts\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskInit\AfterTaskInitDto $afterTaskInitDto
     *
     * @return void
     */
    public function execute(AfterTaskInitDto $afterTaskInitDto): void
    {
//        if ($afterTaskInitDto->getCallSource() !== CallSource::SOURCE_TYPE_CLI) {
//            return;
//        }

        // fix input-output

        // do the call

        $this->eventDispatcher->dispatch(new InitializedEvent($afterTaskInitDto->getTask()), InitializedEvent::NAME);
    }
}
